import 'package:easy_localization/easy_localization.dart';
import 'package:flutter/material.dart';
import 'package:foodie_customer/constants.dart';
import 'package:foodie_customer/services/helper.dart';
import 'package:foodie_customer/ui/location_permission_screen.dart';

class ZoneNotAvailable extends StatefulWidget {
  const ZoneNotAvailable({super.key});

  @override
  State<ZoneNotAvailable> createState() => _ZoneNotAvailableState();
}

class _ZoneNotAvailableState extends State<ZoneNotAvailable> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 16),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              child: Image.asset("assets/images/ic_zone_image.png"),
            ),
            Padding(
              padding: const EdgeInsets.only(left: 16, top: 32, right: 16, bottom: 8),
              child: Text(
                "Zone is not Available".tr(),
                textAlign: TextAlign.center,
                style: TextStyle(color: Colors.black, fontSize: 22.0, fontWeight: FontWeight.bold),
              ),
            ),
            SizedBox(
              height: 5,
            ),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Text(
                "Sorry, The Zone is not available in your area. change the other location first.",
                style: TextStyle(fontSize: 14),
                textAlign: TextAlign.center,
              ).tr(),
            ),
            SizedBox(
              height: 40,
            ),
            ConstrainedBox(
              constraints: const BoxConstraints(minWidth: double.infinity),
              child: ElevatedButton(
                style: ElevatedButton.styleFrom(
                  backgroundColor: Color(COLOR_PRIMARY),
                  padding: EdgeInsets.only(top: 12, bottom: 12),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(25.0),
                    side: BorderSide(
                      color: Color(COLOR_PRIMARY),
                    ),
                  ),
                ),
                child: Text(
                  'Change Location'.tr(),
                  style: TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.bold,
                    color: isDarkMode(context) ? Colors.black : Colors.white,
                  ),
                ),
                onPressed: () {
                  pushAndRemoveUntil(context, LocationPermissionScreen(), false);
                },
              ),
            )
          ],
        ),
      ),
    );
  }
}
