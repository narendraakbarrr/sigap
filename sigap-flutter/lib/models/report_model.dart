class StatusLog {
  final String status;
  final String? notes;
  final String? taskDescription;
  final String? changedBy;
  final String? changedAt;

  StatusLog({
    required this.status,
    this.notes,
    this.taskDescription,
    this.changedBy,
    this.changedAt,
  });

  factory StatusLog.fromJson(Map<String, dynamic> json) => StatusLog(
    status:          json['status'] ?? '',
    notes:           json['notes'],
    taskDescription: json['task_description'],
    changedBy:       json['changed_by'],
    changedAt:       json['changed_at'],
  );
}

// ======================================================
// Model laporan SIGAP
// Mewakili entitas laporan di seluruh aplikasi.
// Digunakan oleh controller, daftar laporan, detail, dan form.
// Dependency penting: `StatusLog` untuk riwayat status.
// ======================================================
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
  final String urgency;
  final List<StatusLog> statusLogs;

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
    this.urgency = 'normal',
    this.statusLogs = const [],
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
    urgency:         json['urgency'] ?? 'normal',
    statusLogs:      (json['status_logs'] as List<dynamic>? ?? [])
                         .map((l) => StatusLog.fromJson(l))
                         .toList(),
  );
}