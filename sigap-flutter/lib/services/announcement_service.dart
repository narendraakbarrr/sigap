import 'dart:convert';
import 'package:http/http.dart' as http;
import '../config.dart';
import '../models/announcement_model.dart';
import 'api_service.dart';

class AnnouncementService {
  Future<List<AnnouncementModel>> fetchAnnouncements() async {
    final api = ApiService();
    final headers = await api.authHeaders();
    final response = await http.get(
      Uri.parse('${AppConfig.baseUrl}/announcements'),
      headers: headers,
    );

    if (response.statusCode == 200) {
      final body = jsonDecode(response.body);
      final List data = body['data'] ?? body;
      return data.map((e) => AnnouncementModel.fromJson(e)).toList();
    }

    throw Exception('Gagal memuat pengumuman (${response.statusCode})');
  }
}
