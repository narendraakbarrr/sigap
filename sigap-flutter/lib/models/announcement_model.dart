// ======================================================
// Model pengumuman SIGAP
// Menyimpan data pengumuman yang ditampilkan di dashboard.
// Digunakan oleh `AnnouncementService` dan tampilan dashboard.
// ======================================================
class AnnouncementModel {
  final int id;
  final String title;
  final String content;
  final bool isPinned;
  final String? createdBy;
  final String? createdAt;

  AnnouncementModel({
    required this.id,
    required this.title,
    required this.content,
    required this.isPinned,
    this.createdBy,
    this.createdAt,
  });

  /// Membuat `AnnouncementModel` dari respons JSON API.
  factory AnnouncementModel.fromJson(Map<String, dynamic> json) {
    return AnnouncementModel(
      id: json['id'],
      title: json['title'],
      content: json['content'],
      isPinned: json['is_pinned'] ?? false,
      createdBy: json['created_by'],
      createdAt: json['created_at'],
    );
  }
}
