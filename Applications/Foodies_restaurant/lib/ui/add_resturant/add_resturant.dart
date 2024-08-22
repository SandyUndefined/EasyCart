import 'dart:io';
import 'package:foodie_restaurant/services/show_toast_dialog.dart';
import 'package:foodie_restaurant/widget/permission_dialog.dart';
import 'package:geolocator/geolocator.dart';
import 'package:google_maps_place_picker_mb/google_maps_place_picker.dart';

import 'package:barcode_image/barcode_image.dart';
import 'package:cloud_firestore/cloud_firestore.dart';
import 'package:easy_localization/easy_localization.dart';
import 'package:firebase_storage/firebase_storage.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter/widgets.dart';
import 'package:foodie_restaurant/main.dart';
import 'package:foodie_restaurant/model/DeliveryChargeModel.dart';
import 'package:foodie_restaurant/model/VendorModel.dart';
import 'package:foodie_restaurant/model/categoryModel.dart';
import 'package:foodie_restaurant/model/zone_model.dart';
import 'package:foodie_restaurant/services/FirebaseHelper.dart';
import 'package:foodie_restaurant/services/helper.dart';
import 'package:foodie_restaurant/ui/QrCodeGenerator/QrCodeGenerator.dart';
import 'package:foodie_restaurant/ui/fullScreenImageViewer/FullScreenImageViewer.dart';
import 'package:geoflutterfire2/geoflutterfire2.dart';
import 'package:image/image.dart' as ImageVar;
import 'package:image_picker/image_picker.dart';
import 'package:multi_select_flutter/dialog/multi_select_dialog_field.dart';
import 'package:multi_select_flutter/util/multi_select_item.dart';
import 'package:multi_select_flutter/util/multi_select_list_type.dart';
import 'package:path_provider/path_provider.dart';
import 'package:uuid/uuid.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import '../../constants.dart';

class AddRestaurantScreen extends StatefulWidget {
  AddRestaurantScreen({Key? key}) : super(key: key);

  @override
  _AddRestaurantScreenState createState() => _AddRestaurantScreenState();
}

class _AddRestaurantScreenState extends State<AddRestaurantScreen> {
  final restaurantName = TextEditingController();
  final description = TextEditingController();
  final phonenumber = TextEditingController();
  final address = TextEditingController();
  final deliverChargeKm = TextEditingController();
  final minDeliveryCharge = TextEditingController();
  final minDeliveryChargewkm = TextEditingController();
  List<VendorCategoryModel> categoryLst = [];
  List<ZoneModel> zoneList = [];
  VendorCategoryModel? selectedCategory;
  ZoneModel? selectedZone;
  DeliveryChargeModel? deliveryChargeModel;

  LatLng? selectedLocation;

  @override
  void dispose() {
    restaurantName.dispose();
    description.dispose();
    phonenumber.dispose();
    super.dispose();
  }

  @override
  void initState() {
    super.initState();

    getVendorData();
  }

  final ImagePicker _imagePicker = ImagePicker();
  String? image;
  List<String> selected = [];

  Map<String, dynamic> filters = {};
  var yes = "Yes";

  filter() {
    if (selected.contains('Good for Breakfast')) {
      filters['Good for Breakfast'] = 'Yes';
    } else {
      filters['Good for Breakfast'] = 'No';
    }
    if (selected.contains('Good for Lunch')) {
      filters['Good for Lunch'] = 'Yes';
    } else {
      filters['Good for Lunch'] = 'No';
    }

    if (selected.contains('Good for Dinner')) {
      filters['Good for Dinner'] = 'Yes';
    } else {
      filters['Good for Dinner'] = 'No';
    }

    if (selected.contains('Takes Reservations')) {
      filters['Takes Reservations'] = 'Yes';
    } else {
      filters['Takes Reservations'] = 'No';
    }

    if (selected.contains('Vegetarian Friendly')) {
      filters['Vegetarian Friendly'] = 'Yes';
    } else {
      filters['Vegetarian Friendly'] = 'No';
    }

    if (selected.contains('Live Music')) {
      filters['Live Music'] = 'Yes';
    } else {
      filters['Live Music'] = 'No';
    }

    if (selected.contains('Outdoor Seating')) {
      filters['Outdoor Seating'] = 'Yes';
    } else {
      filters['Outdoor Seating'] = 'No';
    }

    if (selected.contains('Free Wi-Fi')) {
      filters['Free Wi-Fi'] = 'Yes';
    } else {
      filters['Free Wi-Fi'] = 'No';
    }
  }

  VendorModel? vendorModel;
  bool isLoading = true;

  getVendorData() async {
    await FireStoreUtils.getDelivery().then((value) {
      setState(() {
        deliveryChargeModel = value;
        if (deliveryChargeModel != null && !deliveryChargeModel!.vendorCanModify) {
          deliverChargeKm.text = deliveryChargeModel!.deliveryChargesPerKm.toString();
          minDeliveryCharge.text = deliveryChargeModel!.minimumDeliveryCharges.toString();
          minDeliveryChargewkm.text = deliveryChargeModel!.minimumDeliveryChargesWithinKm.toString();
        }
      });
    });

    await FireStoreUtils.getVendorCategoryById().then((value) {
      categoryLst.addAll(value);
    });

    await FireStoreUtils.getZone().then((value) {
      if (value != null) {
        zoneList = value;
      }
    });

    if (MyAppState.currentUser!.vendorID != '') {
      await FireStoreUtils.getVendor(MyAppState.currentUser!.vendorID).then((value) async {
        vendorModel = value;

        for (VendorCategoryModel vendorCategoryModel in categoryLst) {
          if (vendorCategoryModel.id == vendorModel!.categoryID) {
            selectedCategory = vendorCategoryModel;
          }
        }

        for (ZoneModel zoneList in zoneList) {
          if (zoneList.id == vendorModel!.zoneId) {
            selectedZone = zoneList;
          }
        }

        if (deliveryChargeModel != null && deliveryChargeModel!.vendorCanModify && vendorModel!.deliveryCharge != null) {
          deliverChargeKm.text = vendorModel!.deliveryCharge!.deliveryChargesPerKm.toString();
          minDeliveryCharge.text = vendorModel!.deliveryCharge!.minimumDeliveryCharges.toString();
          minDeliveryChargewkm.text = vendorModel!.deliveryCharge!.minimumDeliveryChargesWithinKm.toString();
        }

        restaurantName.text = vendorModel!.title;
        description.text = vendorModel!.description;
        phonenumber.text = vendorModel!.phonenumber;
        address.text = vendorModel!.location;
        selectedLocation = LatLng(vendorModel!.latitude, vendorModel!.longitude);
        print("---->${vendorModel!.filters}");
        vendorModel!.filters.forEach((key, value) {
          if (value.contains("Yes")) {
            selected.add(key);
          }
        });
      });
    }
    isLoading = false;
    setState(() {});
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: isDarkMode(context) ? Color(COLOR_DARK) : null,
      body: SingleChildScrollView(
        child: Padding(
            padding: EdgeInsets.only(left: 20, right: 20, top: 20),
            child: isLoading == true
                ? Container(
                    alignment: Alignment.center,
                    child: CircularProgressIndicator.adaptive(
                      valueColor: AlwaysStoppedAnimation(Color(COLOR_PRIMARY)),
                    ),
                  )
                : Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Padding(
                        padding: const EdgeInsets.symmetric(vertical: 6),
                        child: Text(
                          "Restaurant Name".tr(),
                          style: TextStyle(fontWeight: FontWeight.bold, fontFamily: "Poppinsl", color: isDarkMode(context) ? Colors.white : Colors.black),
                        ),
                      ),
                      TextFormField(
                          controller: restaurantName,
                          textAlignVertical: TextAlignVertical.center,
                          textInputAction: TextInputAction.next,
                          validator: validateEmptyField,
                          // onSaved: (text) => line1 = text,
                          style: TextStyle(fontSize: 18.0),
                          keyboardType: TextInputType.streetAddress,
                          cursorColor: Color(COLOR_PRIMARY),
                          // initialValue: MyAppState.currentUser!.shippingAddress.line1,
                          decoration: InputDecoration(
                            contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 5),
                            hintText: 'Restaurant Name'.tr(),
                            hintStyle: TextStyle(color: isDarkMode(context) ? Colors.white : Color(0Xff333333), fontFamily: "Poppinsm", fontSize: 16),
                            focusedBorder: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
                            ),
                            border: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(0XFFCCD6E2)),
                              // borderRadius: BorderRadius.circular(8.0),
                            ),
                            enabledBorder: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(0XFFCCD6E2)),
                              // borderRadius: BorderRadius.circular(8.0),
                            ),
                          )),
                      Container(
                          padding: EdgeInsets.symmetric(vertical: 10),
                          alignment: Alignment.centerLeft,
                          child: Text(
                            "Category".tr(),
                            style: TextStyle(fontWeight: FontWeight.bold, fontFamily: "Poppinsl", color: isDarkMode(context) ? Colors.white : Colors.black),
                          )),
                      DropdownButtonFormField<VendorCategoryModel>(
                          decoration: InputDecoration(
                            contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 5),
                            focusedBorder: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
                            ),
                            border: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(0XFFCCD6E2)),
                              // borderRadius: BorderRadius.circular(8.0),
                            ),
                            enabledBorder: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(0XFFCCD6E2)),
                              // borderRadius: BorderRadius.circular(8.0),
                            ),

                            // filled: true,
                            //fillColor: Colors.blueAccent,
                          ),
                          //dropdownColor: Colors.blueAccent,
                          value: selectedCategory,
                          onChanged: (value) {
                            setState(() {
                              selectedCategory = value;
                            });
                          },
                          hint: Text('Select Category'.tr()),
                          items: categoryLst.map((VendorCategoryModel item) {
                            return DropdownMenuItem<VendorCategoryModel>(
                              child: Text(item.title.toString()),
                              value: item,
                            );
                          }).toList()),
                      Container(
                          padding: EdgeInsets.symmetric(vertical: 10),
                          alignment: AlignmentDirectional.centerStart,
                          child: Text(
                            "Zone".tr(),
                            style: TextStyle(fontWeight: FontWeight.bold, fontFamily: "Poppinsl", color: isDarkMode(context) ? Colors.white : Colors.black),
                          )),
                      DropdownButtonFormField<ZoneModel>(
                          decoration: InputDecoration(
                            contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 5),
                            focusedBorder: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
                            ),
                            border: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
                            ),
                            enabledBorder: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(0XFFB1BCCA)),
                              // borderRadius: BorderRadius.circular(8.0),
                            ),
                          ),
                          //dropdownColor: Colors.blueAccent,
                          value: selectedZone,
                          onChanged: (value) {
                            setState(() {
                              selectedZone = value;
                            });
                          },
                          hint: Text('Select Zone'.tr()),
                          items: zoneList.map((ZoneModel item) {
                            return DropdownMenuItem<ZoneModel>(
                              child: Text(item.name.toString()),
                              value: item,
                            );
                          }).toList()),
                      Container(
                          padding: EdgeInsets.symmetric(vertical: 10),
                          alignment: Alignment.centerLeft,
                          child: Text(
                            "Description".tr(),
                            style: TextStyle(fontWeight: FontWeight.bold, fontFamily: "Poppinsl", color: isDarkMode(context) ? Colors.white : Colors.black),
                          )),
                      TextFormField(
                          controller: description,
                          textAlignVertical: TextAlignVertical.center,
                          textInputAction: TextInputAction.next,
                          validator: validateEmptyField,
                          maxLines: 4,
                          // onSaved: (text) => line1 = text,
                          style: TextStyle(fontSize: 18.0),
                          keyboardType: TextInputType.streetAddress,
                          cursorColor: Color(COLOR_PRIMARY),
                          // initialValue: MyAppState.currentUser!.shippingAddress.line1,
                          decoration: InputDecoration(
                            contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 5),
                            hintText: 'Description'.tr(),
                            hintStyle: TextStyle(color: isDarkMode(context) ? Colors.white : Color(0Xff333333), fontFamily: "Poppinsm", fontSize: 16),
                            focusedBorder: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
                            ),
                            border: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
                            ),
                            enabledBorder: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(0XFFB1BCCA)),
                              // borderRadius: BorderRadius.circular(8.0),
                            ),
                          )),
                      Container(
                          padding: EdgeInsets.symmetric(vertical: 10),
                          alignment: Alignment.centerLeft,
                          child: Text(
                            "Phone Number".tr(),
                            style: TextStyle(fontWeight: FontWeight.bold, fontFamily: "Poppinsl", color: isDarkMode(context) ? Colors.white : Colors.black),
                          )),
                      TextFormField(
                          controller: phonenumber,
                          inputFormatters: <TextInputFormatter>[
                            FilteringTextInputFormatter.allow(RegExp(r'\d')),
                          ],
                          textAlignVertical: TextAlignVertical.center,
                          textInputAction: TextInputAction.next,
                          validator: validateEmptyField,
                          // onSaved: (text) => line1 = text,
                          style: TextStyle(fontSize: 18.0),
                          keyboardType: TextInputType.number,
                          cursorColor: Color(COLOR_PRIMARY),
                          // initialValue: MyAppState.currentUser!.shippingAddress.line1,
                          decoration: InputDecoration(
                            contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 5),
                            hintText: 'Phone Number'.tr(),
                            hintStyle: TextStyle(color: isDarkMode(context) ? Colors.white : Color(0Xff333333), fontFamily: "Poppinsm", fontSize: 16),
                            focusedBorder: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
                            ),
                            border: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
                            ),
                            enabledBorder: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(0XFFB1BCCA)),
                              // borderRadius: BorderRadius.circular(8.0),
                            ),
                          )),
                      SizedBox(
                        height: 10,
                      ),
                      Container(
                          padding: EdgeInsets.symmetric(vertical: 10),
                          alignment: AlignmentDirectional.centerStart,
                          child: Row(
                            children: [
                              Expanded(
                                child: Text(
                                  "Address".tr(),
                                  style: TextStyle(fontWeight: FontWeight.bold, fontFamily: "Poppinsl", color: isDarkMode(context) ? Colors.white : Colors.black),
                                ),
                              ),
                              InkWell(
                                onTap: () async {
                                  checkPermission(
                                    () async {
                                      await showProgress("Please wait...".tr(), false);
                                      try {
                                        await Geolocator.requestPermission();
                                        await Geolocator.getCurrentPosition(desiredAccuracy: LocationAccuracy.high);
                                        await hideProgress();
                                        Navigator.push(
                                          context,
                                          MaterialPageRoute(
                                            builder: (context) => PlacePicker(
                                              apiKey: GOOGLE_API_KEY,
                                              onPlacePicked: (result) {
                                                selectedLocation = LatLng(result.geometry!.location.lat, result.geometry!.location.lng);
                                                address.text = result.formattedAddress.toString();
                                                setState(() {});
                                                Navigator.of(context).pop();
                                              },
                                              initialPosition: LatLng(-33.8567844, 151.213108),
                                              useCurrentLocation: true,
                                              selectInitialPosition: true,
                                              usePinPointingSearch: true,
                                              usePlaceDetailSearch: true,
                                              zoomGesturesEnabled: true,
                                              zoomControlsEnabled: true,
                                              resizeToAvoidBottomInset: false, // only works in page mode, less flickery, remove if wrong offsets
                                            ),
                                          ),
                                        );
                                      } catch (e) {
                                        print(e.toString());
                                      }
                                    },
                                  );
                                },
                                child: Text(
                                  "Change".tr(),
                                  style: TextStyle(fontWeight: FontWeight.bold, fontFamily: "Poppinsl", color: Color(COLOR_PRIMARY)),
                                ),
                              ),
                            ],
                          )),
                      InkWell(
                        onTap: () {
                          if (selectedLocation == null) {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (context) => PlacePicker(
                                  apiKey: GOOGLE_API_KEY,
                                  onPlacePicked: (result) async {
                                    selectedLocation = LatLng(result.geometry!.location.lat, result.geometry!.location.lng);
                                    address.text = result.formattedAddress.toString();
                                    setState(() {});
                                    Navigator.of(context).pop();
                                  },
                                  initialPosition: LatLng(-33.8567844, 151.213108),
                                  useCurrentLocation: true,
                                  selectInitialPosition: true,
                                  usePinPointingSearch: true,
                                  usePlaceDetailSearch: true,
                                  zoomGesturesEnabled: true,
                                  zoomControlsEnabled: true,
                                  resizeToAvoidBottomInset: false, // only works in page mode, less flickery, remove if wrong offsets
                                ),
                              ),
                            );
                          }
                        },
                        child: TextFormField(
                            controller: address,
                            textAlignVertical: TextAlignVertical.center,
                            textInputAction: TextInputAction.next,
                            onSaved: (text) => address.text = text!,
                            style: TextStyle(fontSize: 18.0),
                            enabled: selectedLocation == null ? false : true,
                            cursorColor: Color(COLOR_PRIMARY),
                            // initialValue: vendor.phonenumber,
                            decoration: InputDecoration(
                              contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 5),
                              hintText: 'Address'.tr(),
                              hintStyle: TextStyle(color: isDarkMode(context) ? Colors.white : Color(0Xff333333), fontFamily: "Poppinsm", fontSize: 16),
                              focusedBorder: OutlineInputBorder(
                                borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
                              ),
                              border: OutlineInputBorder(
                                borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
                              ),
                              enabledBorder: OutlineInputBorder(
                                borderSide: BorderSide(color: Color(0XFFB1BCCA)),
                                // borderRadius: BorderRadius.circular(8.0),
                              ),
                            )),
                      ),
                      SizedBox(
                        height: 10,
                      ),
                      Container(
                          padding: EdgeInsets.symmetric(vertical: 10),
                          alignment: Alignment.centerLeft,
                          child: Text(
                            "Services".tr(),
                            style: TextStyle(fontWeight: FontWeight.bold, fontFamily: "Poppinsl", color: isDarkMode(context) ? Colors.white : Colors.black),
                          )),
                      MultiSelectDialogField(
                        items: [
                          'Good for Breakfast',
                          'Good for Lunch',
                          'Good for Dinner',
                          'Takes Reservations',
                          'Vegetarian Friendly',
                          'Live Music',
                          'Outdoor Seating',
                          'Free Wi-Fi'
                        ].map((e) => MultiSelectItem(e, e)).toList(),
                        listType: MultiSelectListType.CHIP,
                        initialValue: selected,
                        decoration: BoxDecoration(borderRadius: BorderRadius.all(Radius.circular(6)), border: Border.all(color: Color(0XFFB1BCCA))),
                        onConfirm: (values) {
                          selected = values;
                        },
                      ),
                      SizedBox(
                        height: 10,
                      ),
                      SwitchListTile.adaptive(
                        dense: true,
                        activeColor: Color(COLOR_ACCENT),
                        title: Text(
                          'Delivery Settings'.tr(),
                          style: TextStyle(color: isDarkMode(context) ? Colors.white : Colors.black, fontFamily: "Poppinsm"),
                        ),
                        value: deliveryChargeModel != null ? deliveryChargeModel!.vendorCanModify : false,
                        onChanged: (value) {},
                      ),
                      Container(
                          alignment: Alignment.centerLeft,
                          child: Text(
                            "Delivery Charge Per km".tr(),
                            style: TextStyle(fontSize: 15, fontFamily: "Poppinsl", color: isDarkMode(context) ? Colors.white : Colors.black),
                          )),
                      TextFormField(
                          controller: deliverChargeKm,
                          textAlignVertical: TextAlignVertical.center,
                          textInputAction: TextInputAction.next,
                          validator: (value) {
                            print("value os $value");
                            if (value == null || value.isEmpty) {
                              return "Invalid value".tr();
                            }
                            return null;
                          },
                          enabled: deliveryChargeModel != null ? deliveryChargeModel!.vendorCanModify : false,
                          onSaved: (text) => deliverChargeKm.text = text!,
                          style: TextStyle(fontSize: 18.0),
                          keyboardType: TextInputType.number,
                          cursorColor: Color(COLOR_PRIMARY),
                          // initialValue: vendor.phonenumber,
                          decoration: InputDecoration(
                            contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 5),
                            hintText: 'Delivery Charge Per km'.tr(),
                            hintStyle: TextStyle(color: isDarkMode(context) ? Colors.white : Color(0Xff333333), fontFamily: "Poppinsm", fontSize: 16),
                            focusedBorder: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
                            ),
                            border: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
                            ),
                            enabledBorder: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(0XFFB1BCCA)),
                              // borderRadius: BorderRadius.circular(8.0),
                            ),
                          )),
                      Container(
                          alignment: Alignment.centerLeft,
                          child: Text(
                            "Min Delivery Charge".tr(),
                            style: TextStyle(fontSize: 15, fontFamily: "Poppinsl", color: isDarkMode(context) ? Colors.white : Colors.black),
                          )),
                      TextFormField(
                          enabled: deliveryChargeModel != null ? deliveryChargeModel!.vendorCanModify : false,
                          controller: minDeliveryCharge,
                          textAlignVertical: TextAlignVertical.center,
                          textInputAction: TextInputAction.next,
                          validator: (value) {
                            if (value == null || value.isEmpty) {
                              return "Invalid value".tr();
                            }
                            return null;
                          },
                          onSaved: (text) => minDeliveryCharge.text = text!,
                          style: TextStyle(fontSize: 18.0),
                          keyboardType: TextInputType.number,
                          cursorColor: Color(COLOR_PRIMARY),
                          // initialValue: vendor.phonenumber,
                          decoration: InputDecoration(
                            contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 5),
                            hintText: 'Min Delivery Charge'.tr(),
                            hintStyle: TextStyle(color: isDarkMode(context) ? Colors.white : Color(0Xff333333), fontFamily: "Poppinsm", fontSize: 16),
                            focusedBorder: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
                            ),
                            border: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
                            ),
                            enabledBorder: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(0XFFB1BCCA)),
                              // borderRadius: BorderRadius.circular(8.0),
                            ),
                          )),
                      Container(
                          alignment: Alignment.centerLeft,
                          child: Text(
                            "Min Delivery Charge within km".tr(),
                            style: TextStyle(fontSize: 15, fontFamily: "Poppinsl", color: isDarkMode(context) ? Colors.white : Colors.black),
                          )),
                      TextFormField(
                          controller: minDeliveryChargewkm,
                          enabled: deliveryChargeModel != null ? deliveryChargeModel!.vendorCanModify : false,
                          textAlignVertical: TextAlignVertical.center,
                          textInputAction: TextInputAction.next,
                          validator: (value) {
                            if (value == null || value.isEmpty) {
                              return "Invalid value".tr();
                            }
                            return null;
                          },
                          onSaved: (text) => minDeliveryChargewkm.text = text!,
                          style: TextStyle(fontSize: 18.0),
                          keyboardType: TextInputType.number,
                          cursorColor: Color(COLOR_PRIMARY),
                          // initialValue: vendor.phonenumber,
                          decoration: InputDecoration(
                            contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 5),
                            hintText: 'Min Delivery Charge within km'.tr(),
                            hintStyle: TextStyle(color: isDarkMode(context) ? Colors.white : Color(0Xff333333), fontFamily: "Poppinsm", fontSize: 16),
                            focusedBorder: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
                            ),
                            border: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
                            ),
                            enabledBorder: OutlineInputBorder(
                              borderSide: BorderSide(color: Color(0XFFB1BCCA)),
                              // borderRadius: BorderRadius.circular(8.0),
                            ),
                          )),
                      SizedBox(
                        height: 10,
                      ),
                      vendorModel == null
                          ? image == null || image!.isEmpty
                              ? InkWell(
                                  onTap: () {
                                    _pickImage();
                                  },
                                  child: Image(
                                    image: AssetImage("assets/images/add_img.png"),
                                    width: MediaQuery.of(context).size.width * 1,
                                    height: MediaQuery.of(context).size.height * 0.2,
                                  ))
                              : _imageBuilder(File(image.toString()))
                          : image == null ||  image!.isEmpty
                              ? vendorModel!.photo.isEmpty
                                  ? InkWell(
                                      onTap: () {
                                        _pickImage();
                                      },
                                      child: Image(
                                        image: AssetImage("assets/images/add_img.png"),
                                        width: MediaQuery.of(context).size.width * 1,
                                        height: MediaQuery.of(context).size.height * 0.2,
                                      ))
                                  : InkWell(
                                      onTap: () {
                                        changeimg();
                                      },
                                      child: ClipRRect(
                                        borderRadius: BorderRadius.circular(12),
                                        child: Image(
                                          image: NetworkImage(vendorModel!.photo),
                                          width: 100,
                                          height: 100,
                                          fit: BoxFit.cover,
                                        ),
                                      ),
                                    )
                              : _imageBuilder(File(image.toString()))
                    ],
                  )),
      ),
      bottomNavigationBar: Padding(
        padding: const EdgeInsets.all(20.0),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            ElevatedButton(
              style: ElevatedButton.styleFrom(
                padding: EdgeInsets.only(top: 12, bottom: 12),
                backgroundColor: Color(COLOR_PRIMARY),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(10.0),
                  side: BorderSide(
                    color: Color(COLOR_PRIMARY),
                  ),
                ),
              ),
              onPressed: () {
                validate();
              },
              child: Text(
                'SAVE'.tr(),
                style: TextStyle(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                  color: isDarkMode(context) ? Colors.black : Colors.white,
                ),
              ),
            ),
            Visibility(
              visible: MyAppState.currentUser!.vendorID != '',
              child: Padding(
                padding: const EdgeInsets.only(top: 8.0),
                child: ElevatedButton(
                  style: ElevatedButton.styleFrom(
                    padding: EdgeInsets.only(top: 12, bottom: 12),
                    backgroundColor: Color(COLOR_PRIMARY),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(10.0),
                      side: BorderSide(
                        color: Color(COLOR_PRIMARY),
                      ),
                    ),
                  ),
                  onPressed: () async {
                    final image = ImageVar.Image(600, 600);
                    ImageVar.fill(image, ImageVar.getColor(255, 255, 255));
                    drawBarcode(image, Barcode.qrCode(), '{"vendorid":"${MyAppState.currentUser!.vendorID}","vendorname":"${vendorModel!.title}"}', font: ImageVar.arial_24);
                    // Save the image
                    Directory appDocDir = await getApplicationDocumentsDirectory();
                    String appDocPath = appDocDir.path;
                    print("path $appDocPath");
                    File file = File('$appDocPath/barcode${MyAppState.currentUser!.vendorID}.png');
                    if (!await file.exists()) {
                      await file.create();
                    } else {
                      await file.delete();
                      await file.create();
                    }
                    file.writeAsBytesSync(ImageVar.encodePng(image));
                    push(context, QrCodeGenerator(vendorModel: vendorModel!));
                  },
                  child: Text(
                    'Generate QR Code'.tr(),
                    style: TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: isDarkMode(context) ? Colors.black : Colors.white,
                    ),
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  // buildrow() {
  //   return Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
  //     Padding(
  //         padding: const EdgeInsets.symmetric(vertical: 6),
  //         child: Text(
  //           "Restaurant Name".tr(),
  //           style: TextStyle(fontWeight: FontWeight.bold, fontFamily: "Poppinsl", color: isDarkMode(context) ? Colors.white : Colors.black),
  //         )),
  //     TextFormField(
  //         controller: restaurantName,
  //         textAlignVertical: TextAlignVertical.center,
  //         textInputAction: TextInputAction.next,
  //         validator: validateEmptyField,
  //         // initialValue: vendor.title,
  //         onSaved: (text) => restaurantName.text = text!,
  //         style: TextStyle(fontSize: 18.0),
  //         keyboardType: TextInputType.streetAddress,
  //         cursorColor: Color(COLOR_PRIMARY),
  //         // initialValue: MyAppState.currentUser!.shippingAddress.line1,
  //         decoration: InputDecoration(
  //           contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 5),
  //           hintText: 'Restaurant Name'.tr(),
  //           hintStyle: TextStyle(color: isDarkMode(context) ? Colors.white : Colors.black, fontWeight: FontWeight.bold, fontFamily: "Poppinsm",fontSize: 16),
  //           focusedBorder: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
  //           ),
  //           border: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
  //           ),
  //           enabledBorder: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(0XFFB1BCCA)),
  //             // borderRadius: BorderRadius.circular(8.0),
  //           ),
  //         )),
  //     Container(
  //         padding: EdgeInsets.symmetric(vertical: 10),
  //         alignment: AlignmentDirectional.centerStart,
  //         child: Text(
  //           "Category".tr(),
  //           style: TextStyle(fontWeight: FontWeight.bold, fontFamily: "Poppinsl", color: isDarkMode(context) ? Colors.white : Colors.black),
  //         )),
  //     DropdownButtonFormField<VendorCategoryModel>(
  //         decoration: InputDecoration(
  //           contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 5),
  //           focusedBorder: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
  //           ),
  //           border: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
  //           ),
  //           enabledBorder: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(0XFFB1BCCA)),
  //             // borderRadius: BorderRadius.circular(8.0),
  //           ),
  //
  //           // filled: true,
  //           //fillColor: Colors.blueAccent,
  //         ),
  //         //dropdownColor: Colors.blueAccent,
  //         value: selectedCategory,
  //         onChanged: (value) {
  //           setState(() {
  //             selectedCategory = value;
  //           });
  //         },
  //         hint: Text('Select Category'.tr()),
  //         items: categoryLst.map((VendorCategoryModel item) {
  //           return DropdownMenuItem<VendorCategoryModel>(
  //             child: Text(item.title.toString()),
  //             value: item,
  //           );
  //         }).toList()),
  //
  //     Container(
  //         padding: EdgeInsets.symmetric(vertical: 10),
  //         alignment: AlignmentDirectional.centerStart,
  //         child: Text(
  //           "Zone".tr(),
  //           style: TextStyle(fontWeight: FontWeight.bold, fontFamily: "Poppinsl", color: isDarkMode(context) ? Colors.white : Colors.black),
  //         )),
  //     DropdownButtonFormField<ZoneModel>(
  //         decoration: InputDecoration(
  //           contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 5),
  //           focusedBorder: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
  //           ),
  //           border: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
  //           ),
  //           enabledBorder: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(0XFFB1BCCA)),
  //             // borderRadius: BorderRadius.circular(8.0),
  //           ),
  //         ),
  //         //dropdownColor: Colors.blueAccent,
  //         value: selectedZone,
  //         onChanged: (value) {
  //           setState(() {
  //             selectedZone = value;
  //           });
  //         },
  //         hint: Text('Select Zone'.tr()),
  //         items: zoneList.map((ZoneModel item) {
  //           return DropdownMenuItem<ZoneModel>(
  //             child: Text(item.name.toString()),
  //             value: item,
  //           );
  //         }).toList()),
  //
  //     Container(
  //         padding: EdgeInsets.symmetric(vertical: 10),
  //         alignment: AlignmentDirectional.centerStart,
  //         child: Text(
  //           "Services".tr(),
  //           style: TextStyle(fontWeight: FontWeight.bold, fontFamily: "Poppinsl", color: isDarkMode(context) ? Colors.white : Colors.black),
  //         )),
  //     MultiSelectDialogField(
  //       items: ['Good for Breakfast', 'Good for Lunch', 'Good for Dinner', 'Takes Reservations', 'Vegetarian Friendly', 'Live Music', 'Outdoor Seating', 'Free Wi-Fi']
  //           .map((e) => MultiSelectItem(e, e))
  //           .toList(),
  //       listType: MultiSelectListType.CHIP,
  //       initialValue: selected,
  //       decoration: BoxDecoration(borderRadius: BorderRadius.all(Radius.circular(6)), border: Border.all(color: Color(0XFFB1BCCA))),
  //       onConfirm: (values) {
  //         selected = values;
  //       },
  //     ),
  //     // Container(
  //     //   height: 48,
  //     //   child: DropDownMultiSelect(
  //     //     onChanged: (List<String> x) {
  //     //       x = selected;
  //     //
  //     //       //  vendor.filters.keys.toList()= x;
  //     //     },
  //     //     options: ['Good for Breakfast', 'Good for Lunch', 'Good for Dinner', 'Takes Reservations', 'Vegetarian Friendly', 'Live Music', 'Outdoor Seating', 'Free Wi-Fi'],
  //     //     selectedValues: selected,
  //     //
  //     //     // childBuilder: selected.first,
  //     //     // whenEmpty: 'Select Something'.tr(),
  //     //   ),
  //     // ),
  //     Container(
  //         padding: EdgeInsets.symmetric(vertical: 10),
  //         alignment: AlignmentDirectional.centerStart,
  //         child: Text(
  //           "Description".tr(),
  //           style: TextStyle(fontWeight: FontWeight.bold, fontFamily: "Poppinsl", color: isDarkMode(context) ? Colors.white : Colors.black),
  //         )),
  //     TextFormField(
  //         controller: description,
  //         textAlignVertical: TextAlignVertical.center,
  //         textInputAction: TextInputAction.next,
  //         validator: validateEmptyField,
  //         onSaved: (text) => description.text = text!,
  //         style: TextStyle(fontSize: 18.0),
  //         keyboardType: TextInputType.streetAddress,
  //         cursorColor: Color(COLOR_PRIMARY),
  //         maxLines: 4,
  //         // initialValue: vendor.description,
  //         decoration: InputDecoration(
  //           contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 5),
  //           hintText: 'Description'.tr(),
  //           hintStyle: TextStyle(color: isDarkMode(context) ? Colors.white : Color(0Xff333333), fontWeight: FontWeight.bold, fontFamily: "Poppinsm",fontSize: 16),
  //           focusedBorder: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
  //           ),
  //           border: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
  //           ),
  //           enabledBorder: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(0XFFB1BCCA)),
  //             // borderRadius: BorderRadius.circular(8.0),
  //           ),
  //         )),
  //     SizedBox(
  //       height: 10,
  //     ),
  //     Container(
  //         padding: EdgeInsets.symmetric(vertical: 10),
  //         alignment: AlignmentDirectional.centerStart,
  //         child: Text(
  //           "Phone Number".tr(),
  //           style: TextStyle(fontWeight: FontWeight.bold, fontFamily: "Poppinsl", color: isDarkMode(context) ? Colors.white : Colors.black),
  //         )),
  //     TextFormField(
  //         controller: phonenumber,
  //         textAlignVertical: TextAlignVertical.center,
  //         textInputAction: TextInputAction.next,
  //         validator: validateMobile,
  //         inputFormatters: <TextInputFormatter>[
  //           FilteringTextInputFormatter.allow(RegExp(r'\d')),
  //         ],
  //         onSaved: (text) => phonenumber.text = text!,
  //         style: TextStyle(fontSize: 18.0),
  //         keyboardType: TextInputType.streetAddress,
  //         cursorColor: Color(COLOR_PRIMARY),
  //         // initialValue: vendor.phonenumber,
  //         decoration: InputDecoration(
  //           contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 5),
  //           hintText: 'Phone Number'.tr(),
  //           hintStyle: TextStyle(color: isDarkMode(context) ? Colors.white : Color(0Xff333333), fontWeight: FontWeight.bold, fontFamily: "Poppinsm",fontSize: 16),
  //           focusedBorder: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
  //           ),
  //           border: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
  //           ),
  //           enabledBorder: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(0XFFB1BCCA)),
  //             // borderRadius: BorderRadius.circular(8.0),
  //           ),
  //         )),
  //     SizedBox(
  //       height: 10,
  //     ),

  //     SizedBox(
  //       height: 10,
  //     ),
  //     SwitchListTile.adaptive(
  //       contentPadding: EdgeInsets.all(0),
  //       activeColor: Color(COLOR_ACCENT),
  //       title: Text(
  //         'Delivery Settings'.tr(),
  //         style: TextStyle(fontWeight: FontWeight.bold, color: isDarkMode(context) ? Colors.white : Colors.black, fontFamily: "Poppinsl"),
  //       ),
  //       value: deliveryChargeModel!.vendorCanModify,
  //       onChanged: null,
  //     ),
  //     Container(
  //         alignment: AlignmentDirectional.centerStart,
  //         child: Text(
  //           "Delivery Charge Per km".tr(),
  //           style: TextStyle(fontSize: 15, fontFamily: "Poppinsl", color: isDarkMode(context) ? Colors.white : Colors.black),
  //         )),
  //     TextFormField(
  //         controller: deliverChargeKm,
  //         textAlignVertical: TextAlignVertical.center,
  //         textInputAction: TextInputAction.next,
  //         validator: (value) {
  //           if (value == null || value.isEmpty) {
  //             return "Invalid value".tr();
  //           }
  //           return null;
  //         },
  //         enabled: deliveryChargeModel!.vendorCanModify,
  //         onSaved: (text) => deliverChargeKm.text = text!,
  //         style: TextStyle(fontSize: 18.0),
  //         keyboardType: TextInputType.number,
  //         cursorColor: Color(COLOR_PRIMARY),
  //         // initialValue: vendor.phonenumber,
  //         decoration: InputDecoration(
  //           contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 5),
  //           hintText: 'Delivery Charge Per km'.tr(),
  //           hintStyle: TextStyle(color: isDarkMode(context) ? Colors.white : Color(0Xff333333), fontWeight: FontWeight.bold, fontFamily: "Poppinsm",fontSize: 16),
  //           focusedBorder: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
  //           ),
  //           border: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
  //           ),
  //           enabledBorder: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(0XFFB1BCCA)),
  //             // borderRadius: BorderRadius.circular(8.0),
  //           ),
  //         )),
  //     SizedBox(
  //       height: 10,
  //     ),
  //     Container(
  //         alignment: AlignmentDirectional.centerStart,
  //         child: Text(
  //           "Min Delivery Charge".tr(),
  //           style: TextStyle(fontSize: 15, fontFamily: "Poppinsl", color: isDarkMode(context) ? Colors.white : Colors.black),
  //         )),
  //     TextFormField(
  //         enabled: deliveryChargeModel!.vendorCanModify,
  //         controller: minDeliveryCharge,
  //         textAlignVertical: TextAlignVertical.center,
  //         textInputAction: TextInputAction.next,
  //         validator: (value) {
  //           if (value == null || value.isEmpty) {
  //             return "Invalid value".tr();
  //           }
  //           return null;
  //         },
  //         onSaved: (text) => minDeliveryCharge.text = text!,
  //         style: TextStyle(fontSize: 18.0),
  //         keyboardType: TextInputType.number,
  //         cursorColor: Color(COLOR_PRIMARY),
  //         // initialValue: vendor.phonenumber,
  //         decoration: InputDecoration(
  //           contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 5),
  //           hintText: 'Min Delivery Charge'.tr(),
  //           hintStyle: TextStyle(color: isDarkMode(context) ? Colors.white : Color(0Xff333333), fontWeight: FontWeight.bold, fontFamily: "Poppinsm",fontSize: 16),
  //           focusedBorder: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
  //           ),
  //           border: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
  //           ),
  //           enabledBorder: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(0XFFB1BCCA)),
  //             // borderRadius: BorderRadius.circular(8.0),
  //           ),
  //         )),
  //     SizedBox(
  //       height: 10,
  //     ),
  //     Container(
  //         alignment: AlignmentDirectional.centerStart,
  //         child: Text(
  //           "Min Delivery Charge within km".tr(),
  //           style: TextStyle(fontSize: 15, fontFamily: "Poppinsl", color: isDarkMode(context) ? Colors.white : Colors.black),
  //         )),
  //     TextFormField(
  //         controller: minDeliveryChargewkm,
  //         enabled: deliveryChargeModel!.vendorCanModify,
  //         textAlignVertical: TextAlignVertical.center,
  //         textInputAction: TextInputAction.next,
  //         validator: (value) {
  //           if (value == null || value.isEmpty) {
  //             return "Invalid value".tr();
  //           }
  //           return null;
  //         },
  //         onSaved: (text) => minDeliveryChargewkm.text = text!,
  //         style: TextStyle(fontSize: 18.0),
  //         keyboardType: TextInputType.number,
  //         cursorColor: Color(COLOR_PRIMARY),
  //         // initialValue: vendor.phonenumber,
  //         decoration: InputDecoration(
  //           contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 5),
  //           hintText: 'Min Delivery Charge within km'.tr(),
  //           hintStyle: TextStyle(color: isDarkMode(context) ? Colors.white : Color(0Xff333333), fontWeight: FontWeight.bold, fontFamily: "Poppinsm",fontSize: 16),
  //           focusedBorder: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
  //           ),
  //           border: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(COLOR_PRIMARY)),
  //           ),
  //           enabledBorder: OutlineInputBorder(
  //             borderSide: BorderSide(color: Color(0XFFB1BCCA)),
  //             // borderRadius: BorderRadius.circular(8.0),
  //           ),
  //         )),
  //     SizedBox(
  //       height: 10,
  //     ),
  //     _mediaFiles.isEmpty == true
  //         ? InkWell(
  //             onTap: () {
  //               changeimg();
  //             },
  //             child: Image(
  //               image: NetworkImage(vendorModel!.photo),
  //               width: 150,
  //             ))
  //         : _imageBuilder(_mediaFiles.first)
  //   ]);
  // }

  changeimg() {
    final action = CupertinoActionSheet(
      message: Text(
        'Change Picture'.tr(),
        style: TextStyle(fontSize: 15.0),
      ),
      actions: [
        CupertinoActionSheetAction(
          child: Text('Choose image from gallery'.tr()),
          isDefaultAction: false,
          onPressed: () async {
            Navigator.pop(context);
            XFile? xImage = await _imagePicker.pickImage(source: ImageSource.gallery);
            if (xImage != null) {
              setState(() {
                image = xImage.path.toString();
              });
            }
          },
        ),
        CupertinoActionSheetAction(
          child: Text('Take a picture'.tr()),
          isDestructiveAction: false,
          onPressed: () async {
            Navigator.pop(context);
            XFile? xImage = await _imagePicker.pickImage(source: ImageSource.camera);
            if (xImage != null) {
              setState(() {
                image = xImage.path.toString();
              });

            }
          },
        ),
      ],
      cancelButton: CupertinoActionSheetAction(
        child: Text('Cancel'.tr()),
        onPressed: () {
          Navigator.pop(context);
        },
      ),
    );
    showCupertinoModalPopup(context: context, builder: (context) => action);
  }

  showAlertDialog(BuildContext context) {
    // set up the button
    Widget okButton = TextButton(
      child: Text("OK".tr()),
      onPressed: () {
        Navigator.pop(context);
      },
    );

    // set up the AlertDialog
    AlertDialog alert = AlertDialog(
      title: Text("Restaurant Field".tr()),
      content: Text("Please Select Image to Continue.".tr()),
      actions: [
        okButton,
      ],
    );

    // show the dialog
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return alert;
      },
    );
  }

  validate() async {
    if (restaurantName.text.isEmpty) {
      ShowToastDialog.showToast("Please enter restaurant name");
    } else if (description.text.isEmpty) {
      ShowToastDialog.showToast("Please enter Description");
    } else if (phonenumber.text.isEmpty) {
      ShowToastDialog.showToast("Please enter phone number");
    } else if (address.text.isEmpty) {
      ShowToastDialog.showToast("Please enter address");
    } else if (selectedZone == null) {
      ShowToastDialog.showToast("Please select zone");
    } else if (selectedCategory == null) {
      ShowToastDialog.showToast("Please select category");
    } else {
      if (isPointInPolygon(selectedLocation!, selectedZone!.area!)) {
        ShowToastDialog.showLoader("Please wait");
        filter();
        DeliveryChargeModel deliveryChargeModel = DeliveryChargeModel(
            vendorCanModify: true,
            deliveryChargesPerKm: num.parse(deliverChargeKm.text),
            minimumDeliveryCharges: num.parse(minDeliveryCharge.text),
            minimumDeliveryChargesWithinKm: num.parse(minDeliveryChargewkm.text));
        print("---->$filters");

        if (vendorModel == null) {
          vendorModel = VendorModel();
          vendorModel!.createdAt = Timestamp.now();
        }
        if (image != null && image!.isNotEmpty) {
          var uniqueID = Uuid().v4();
          Reference upload = FirebaseStorage.instance.ref().child('flutter/uberEats/productImages/$uniqueID'
              '.png');
          UploadTask uploadTask = upload.putFile(File(image.toString()));
          var storageRef = (await uploadTask.whenComplete(() {})).ref;
          String downloadUrl = await storageRef.getDownloadURL();
          vendorModel!.photo = downloadUrl;
        }

        vendorModel!.id = MyAppState.currentUser!.vendorID;
        vendorModel!.author = MyAppState.currentUser!.userID;
        vendorModel!.authorName = MyAppState.currentUser!.firstName;
        vendorModel!.authorProfilePic = MyAppState.currentUser!.photos.isEmpty ? ' ' : MyAppState.currentUser!.photos.first;

        vendorModel!.categoryID = selectedCategory!.id.toString();
        vendorModel!.categoryTitle = selectedCategory!.title.toString();
        vendorModel!.geoFireData = GeoFireData(
            geohash: GeoFlutterFire().point(latitude: selectedLocation!.latitude, longitude: selectedLocation!.longitude).hash,
            geoPoint: GeoPoint(selectedLocation!.latitude, selectedLocation!.longitude));
        vendorModel!.description = description.text;
        vendorModel!.phonenumber = phonenumber.text;
        vendorModel!.filters = filters;
        vendorModel!.location = address.text;
        vendorModel!.latitude = selectedLocation!.latitude;
        vendorModel!.longitude = selectedLocation!.longitude;

        vendorModel!.deliveryCharge = deliveryChargeModel;
        vendorModel!.title = restaurantName.text;
        vendorModel!.zoneId = selectedZone!.id;

        if (MyAppState.currentUser!.vendorID.isNotEmpty) {
          await FireStoreUtils.updateVendor(vendorModel!).then((value) {
            ShowToastDialog.closeLoader();
            ShowToastDialog.showToast("Restaurant details save successfully");
          });
        } else {
          await FireStoreUtils.firebaseCreateNewVendor(vendorModel!).then((value) {
            ShowToastDialog.closeLoader();
            ShowToastDialog.showToast("Restaurant details save successfully");
          });
        }
      } else {
        ShowToastDialog.showToast("The chosen area is outside the selected zone.");
      }
    }

    // if (vendorModel == null) {
    //   vendorModel = VendorModel(
    //       id: MyAppState.currentUser!.vendorID,
    //       author: MyAppState.currentUser!.userID,
    //       authorName: MyAppState.currentUser!.firstName,
    //       authorProfilePic: MyAppState.currentUser!.photos.isEmpty ? ' ' : MyAppState.currentUser!.photos.first,
    //       categoryID: selectedCategory!.id.toString(),
    //       categoryTitle: selectedCategory!.title.toString(),
    //       createdAt: Timestamp.now(),
    //       geoFireData: GeoFireData(
    //           geohash: GeoFlutterFire()
    //               .point(latitude: selectedLocation!.latitude, longitude: selectedLocation!.longitude)
    //               .hash,
    //           geoPoint: GeoPoint(selectedLocation!.latitude, selectedLocation!.longitude)),
    //       description: description.text,
    //       phonenumber: phonenumber.text,
    //       filters: filters,
    //       location: address.text,
    //       latitude: selectedLocation!.latitude,
    //       longitude: selectedLocation!.longitude,
    //       photo: _mediaFiles.isNotEmpty ? downloadUrl : vendorModel!.photo,
    //       deliveryCharge: deliveryChargeModel,
    //       title: restaurantName.text,
    //       zoneId: selectedZone!.id,
    //       workingHours: vendorModel != null ? vendorModel!.workingHours : [],
    //       specialDiscount: vendorModel != null ? vendorModel!.specialDiscount : [],
    //       specialDiscountEnable: vendorModel != null ? vendorModel!.specialDiscountEnable : false,
    //       fcmToken: MyAppState.currentUser!.fcmToken);
    // } else {
    //
    // }
  }

  _pickImage() {
    final action = CupertinoActionSheet(
      message: Text(
        'Add Picture'.tr(),
        style: TextStyle(fontSize: 15.0),
      ),
      actions: <Widget>[
        CupertinoActionSheetAction(
          child: Text('Choose image from gallery'.tr()),
          isDefaultAction: false,
          onPressed: () async {
            Navigator.pop(context);
            XFile? xImage = await _imagePicker.pickImage(source: ImageSource.gallery);
            if (xImage != null) {
              image = xImage.path;
              setState(() {});
            }
          },
        ),
        CupertinoActionSheetAction(
          child: Text('Take a picture'.tr()),
          isDestructiveAction: false,
          onPressed: () async {
            Navigator.pop(context);
            XFile? xImage = await _imagePicker.pickImage(source: ImageSource.camera);
            if (xImage != null) {
              image = xImage.path;
              setState(() {});
            }
          },
        ),
      ],
      cancelButton: CupertinoActionSheetAction(
        child: Text('Cancel'.tr()),
        onPressed: () {
          Navigator.pop(context);
        },
      ),
    );
    showCupertinoModalPopup(context: context, builder: (context) => action);
  }

  _imageBuilder(dynamic image) {
    return GestureDetector(
      onTap: () {
        _viewOrDeleteImage();
      },
      child: Container(
        width: 100,
        height: 100,
        child: Card(
          shape: RoundedRectangleBorder(
            side: BorderSide.none,
            borderRadius: BorderRadius.circular(12),
          ),
          color: isDarkMode(context) ? Colors.black : Colors.white,
          child: ClipRRect(
            borderRadius: BorderRadius.circular(12),
            child: image is File
                ? Image.file(
                    image,
                    fit: BoxFit.cover,
                  )
                : displayImage(image),
          ),
        ),
      ),
    );
  }

  _viewOrDeleteImage() {
    final action = CupertinoActionSheet(
      actions: <Widget>[
        CupertinoActionSheetAction(
          onPressed: () async {
            Navigator.pop(context);
            image = null;
            setState(() {});
          },
          child: Text('Remove picture'.tr()),
          isDestructiveAction: true,
        ),
        CupertinoActionSheetAction(
          onPressed: () {
            Navigator.pop(context);
            push(context, image is File ? FullScreenImageViewer(imageFile: File(image.toString())) : FullScreenImageViewer(imageUrl: image));
          },
          isDefaultAction: true,
          child: Text('View picture'.tr()),
        ),
      ],
      cancelButton: CupertinoActionSheetAction(
        child: Text('Cancel'.tr()),
        onPressed: () {
          Navigator.pop(context);
        },
      ),
    );
    showCupertinoModalPopup(context: context, builder: (context) => action);
  }

  bool isPhoneNoValid(String? phoneNo) {
    if (phoneNo == null) return false;
    final regExp = RegExp(r'(^(?:[+0]9)?\d{10,12}$)');
    return regExp.hasMatch(phoneNo);
  }

  showimgAlertDialog(BuildContext context, String title, String content, bool addOkButton) {
    Widget? okButton;
    if (addOkButton) {
      okButton = TextButton(
        child: Text('OK'.tr()),
        onPressed: () {
          Navigator.pop(context);
        },
      );
    }

    if (Platform.isIOS) {
      CupertinoAlertDialog alert = CupertinoAlertDialog(
        title: Text(title),
        content: Text(content),
        actions: [if (okButton != null) okButton],
      );
      showCupertinoDialog(
          context: context,
          builder: (context) {
            return alert;
          });
    } else {
      AlertDialog alert = AlertDialog(title: Text(title), content: Text(content), actions: [if (okButton != null) okButton]);

      showDialog(
        context: context,
        builder: (BuildContext context) {
          return alert;
        },
      );
    }
  }

  showAlertDialog1(BuildContext context) {
    // set up the button
    Widget okButton = TextButton(
      child: Text("OK".tr()),
      onPressed: () {
        Navigator.pop(context);
      },
    );

    // set up the AlertDialog
    AlertDialog alert = AlertDialog(
      title: Text("My title".tr()),
      content: Text("This is my message.".tr()),
      actions: [
        okButton,
      ],
    );

    // show the dialog
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return alert;
      },
    );
  }

  void checkPermission(Function() onTap) async {
    LocationPermission permission = await Geolocator.checkPermission();
    if (permission == LocationPermission.denied) {
      permission = await Geolocator.requestPermission();
    }
    if (permission == LocationPermission.denied) {
      SnackBar snack = SnackBar(
        content: const Text(
          'You have to allow location permission to use your location',
          style: TextStyle(color: Colors.white),
        ).tr(),
        duration: const Duration(seconds: 2),
        backgroundColor: Colors.black,
      );
      ScaffoldMessenger.of(context).showSnackBar(snack);
    } else if (permission == LocationPermission.deniedForever) {
      showDialog(
        context: context,
        builder: (BuildContext context) {
          return PermissionDialog();
        },
      );
    } else {
      onTap();
    }
  }
}
