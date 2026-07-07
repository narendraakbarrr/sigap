class ReportModel {
  final int id;
  final String title;
  final String description;
  final String status;
  final String categoryName;
  final String locationAddress;
  final String? photoUrl;
  final double? latitude;
  final double? longitude;
  final String createdAt;
  final String userName;

  ReportModel({
    required this.id,
    required this.title,
    required this.description,
    required this.status,
    required this.categoryName,
    required this.locationAddress,
    this.photoUrl,
    this.latitude,
    this.longitude,
    required this.createdAt,
    required this.userName,
  });

  factory ReportModel.fromJson(Map<String, dynamic> json) => ReportModel(
    id:              json['id'],
    title:           json['title'],
    description:     json['description'],
    status:          json['status'],
    categoryName:    json['category'] ?? '',
    locationAddress: json['location'] ?? '',
    photoUrl:        json['photo_url'],
    latitude:        json['latitude'] != null
                       ? double.tryParse(json['latitude'].toString())
                       : null,
    longitude:       json['longitude'] != null
                       ? double.tryParse(json['longitude'].toString())
                       : null,
    createdAt:       json['created_at'] ?? '',
    userName:        json['user']?['name'] ?? '',
  );
}