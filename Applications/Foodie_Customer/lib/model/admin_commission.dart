class AdminCommission {
  String? adminCommission;
  String? adminCommissionType;
  bool? isEnabled;

  AdminCommission({this.adminCommission, this.adminCommissionType, this.isEnabled});

  AdminCommission.fromJson(Map<String, dynamic> json) {
    adminCommission = json['fix_commission'].toString();
    adminCommissionType = json['commissionType'];
    isEnabled = json['isEnabled'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['fix_commission'] = this.adminCommission;
    data['adminCommissionType'] = this.adminCommissionType;
    data['isEnabled'] = this.isEnabled;
    return data;
  }

}
