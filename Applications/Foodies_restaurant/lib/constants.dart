import 'dart:async';
import 'dart:io';

import 'package:cloud_firestore/cloud_firestore.dart';
import 'package:firebase_storage/firebase_storage.dart';
import 'package:foodie_restaurant/model/CurrencyModel.dart';
import 'package:foodie_restaurant/model/TaxModel.dart';
import 'package:foodie_restaurant/model/admin_commission.dart';
import 'package:foodie_restaurant/model/mail_setting.dart';
import 'package:mailer/mailer.dart';
import 'package:mailer/smtp_server.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';

const FINISHED_ON_BOARDING = 'finishedOnBoarding';
const COLOR_ACCENT = 0xFF8fd468;
const COLOR_PRIMARY_DARK = 0xFF2c7305;
const COLOR_DARK = 0xFF191A1C;
const DARK_COLOR = 0xff191A1C;
const DARK_VIEWBG_COLOR = 0xff191A1C;
const DARK_CARD_BG_COLOR = 0xff242528;
// ignore: non_constant_identifier_names
var COLOR_PRIMARY = 0xFFFF683A;
const FACEBOOK_BUTTON_COLOR = 0xFF415893;
const COUPON_BG_COLOR = 0xFFFCF8F3;
const COUPON_DASH_COLOR = 0xFFCACFDA;
const GREY_TEXT_COLOR = 0xff5E5C5C;
const documents = 'documents';
const documentsVerify = 'documents_verify';
const withdrawMethod = 'withdraw_method';
const USERS = 'users';
const ZONE = 'zone';
const dynamicNotification = 'dynamic_notification';

const REPORTS = 'reports';
const FOOD_REVIEW = 'foods_review';
const VENDORS_CATEGORIES = 'vendor_categories';
const VENDORS = 'vendors';
const CREATETABLE = 'tables';
const Order_Rating = 'foods_review';
const REVIEW_ATTRIBUTES = "review_attributes";

const emailTemplates = 'email_templates';

const VENDOR_ATTRIBUTES = "vendor_attributes";
const FavouriteItem = "favorite_item";
const PRODUCTS = 'vendor_products';
const ORDERS = 'restaurant_orders';
const COUPONS = "coupons";
const ORDERS_TABLE = 'booked_table';
const CONTACT_US = 'ContactUs';
const SECOND_MILLIS = 1000;
const MINUTE_MILLIS = 60 * SECOND_MILLIS;
const HOUR_MILLIS = 60 * MINUTE_MILLIS;
String senderId = '';
String jsonNotificationFileURL = '';
String GOOGLE_API_KEY = '';
bool isRestaurantVerification = false;

const ORDER_STATUS_PLACED = 'Order Placed';
const ORDER_STATUS_ACCEPTED = 'Order Accepted';
const ORDER_STATUS_REJECTED = 'Order Rejected';
const ORDER_STATUS_DRIVER_PENDING = 'Driver Pending';
const ORDER_STATUS_DRIVER_ACCEPTED = 'Driver Accepted';
const ORDER_STATUS_DRIVER_REJECTED = 'Driver Rejected';
const ORDER_STATUS_SHIPPED = 'Order Shipped';
const ORDER_STATUS_IN_TRANSIT = 'In Transit';
const ORDER_STATUS_COMPLETED = 'Order Completed';

const USER_ROLE_VENDOR = 'vendor';
const STORY = 'story';

const scheduleOrder = "schedule_order";
const dineInPlaced = "dinein_placed";
const dineInCanceled = "dinein_canceled";
const dineInAccepted = "dinein_accepted";
const driverAccepted = "driver_accepted";
const restaurantRejected = "restaurant_rejected";
const driverCompleted = "driver_completed";
const restaurantAccepted = "restaurant_accepted";
const takeawayCompleted = "takeaway_completed";
const orderPlaced = "order_placed";

const Currency = 'currencies';
bool isDineInEnable = false;

const walletTopup = "wallet_topup";
const newVendorSignup = "new_vendor_signup";
const payoutRequestStatus = "payout_request_status";
const payoutRequest = "payout_request";
const newOrderPlaced = "new_order_placed";

CurrencyModel? currencyModel;
AdminCommission? adminCommissionModel;

const Setting = 'settings';
String placeholderImage = '';
const Wallet = "wallet";
const Payouts = "payouts";

String commissionType() {
  String type = "0";
  if (adminCommissionModel != null) {
    if (adminCommissionModel!.adminCommissionType == "Percent" || adminCommissionModel!.adminCommissionType == "Percentage") {
      type = "${adminCommissionModel!.adminCommission.toString()} %";
    } else {
      type = amountShow(amount: adminCommissionModel!.adminCommission.toString());
    }
  }
  return type;
}

bool hasValidUrl(String value) {
  String pattern = r'(http|https)://[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:/~+#-]*[\w@?^=%&amp;/~+#-])?';
  RegExp regExp = RegExp(pattern);
  if (value.isEmpty) {
    return false;
  } else if (!regExp.hasMatch(value)) {
    return false;
  }
  return true;
}

Future<String> uploadUserImageToFireStorage(File image, String filePath, String fileName) async {
  Reference upload = FirebaseStorage.instance.ref().child('$filePath/$fileName');
  UploadTask uploadTask = upload.putFile(image);
  var downloadUrl = await (await uploadTask.whenComplete(() {})).ref.getDownloadURL();
  return downloadUrl.toString();
}


String productCommissionPrice(String Price) {
  String commission = "0";
  if (adminCommissionModel!.adminCommissionType == "Percent" || adminCommissionModel!.adminCommissionType == "Percentage") {
    commission = (double.parse(Price) * double.parse(adminCommissionModel!.adminCommission.toString()) / 100).toString();
  } else {
    commission = (double.parse(commission) + double.parse(adminCommissionModel!.adminCommission.toString())).toString();
  }
  return commission;
}

bool isPointInPolygon(LatLng point, List<GeoPoint> polygon) {
  int crossings = 0;
  for (int i = 0; i < polygon.length; i++) {
    int next = (i + 1) % polygon.length;
    if (polygon[i].latitude <= point.latitude && polygon[next].latitude > point.latitude || polygon[i].latitude > point.latitude && polygon[next].latitude <= point.latitude) {
      double edgeLong = polygon[next].longitude - polygon[i].longitude;
      double edgeLat = polygon[next].latitude - polygon[i].latitude;
      double interpol = (point.latitude - polygon[i].latitude) / edgeLat;
      if (point.longitude < polygon[i].longitude + interpol * edgeLong) {
        crossings++;
      }
    }
  }
  return (crossings % 2 != 0);
}

String amountShow({required String? amount}) {
  if (currencyModel!.symbolatright == true) {
    return "${double.parse(amount.toString()).toStringAsFixed(currencyModel!.decimal)} ${currencyModel!.symbol.toString()}";
  } else {
    return "${currencyModel!.symbol.toString()} ${double.parse(amount.toString()).toStringAsFixed(currencyModel!.decimal)}";
  }
}

double calculateTax({String? amount, TaxModel? taxModel}) {
  double taxAmount = 0.0;
  if (taxModel != null && taxModel.enable == true) {
    if (taxModel.type == "fix") {
      taxAmount = double.parse(taxModel.tax.toString());
    } else {
      taxAmount = (double.parse(amount.toString()) * double.parse(taxModel.tax!.toString())) / 100;
    }
  }
  return taxAmount;
}

MailSettings? mailSettings;
// logs.log(newString);
// String username = 'foodie@siswebapp.com';
// String password = "8#bb\$1)E@#f3";

final smtpServer = SmtpServer(mailSettings!.host.toString(),
    username: mailSettings!.userName.toString(), password: mailSettings!.password.toString(), port: 465, ignoreBadCertificate: false, ssl: true, allowInsecure: true);

sendMail({String? subject, String? body, bool? isAdmin = false, List<dynamic>? recipients}) async {
  // Create our message.
  if (isAdmin == true) {
    recipients!.add(mailSettings!.userName.toString());
  }
  final message = Message()
    ..from = Address(mailSettings!.userName.toString(), mailSettings!.fromName.toString())
    ..recipients = recipients!
    ..subject = subject
    ..text = body
    ..html = body;

  try {
    final sendReport = await send(message, smtpServer);
    print('Message sent: ' + sendReport.toString());
  } on MailerException catch (e) {
    print(e);
    print('Message not sent.');
    for (var p in e.problems) {
      print('Problem: ${p.code}: ${p.msg}');
    }
  }
}
