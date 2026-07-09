import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import '../config.dart';
import 'api_service.dart';

class ReportService {
  final _api = ApiService();

  Future<Map<String, String>> _headers() async {
    final token = await _api.getToken();
    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': 'Bearer $token',
    };
  }

  // Ambil semua laporan
  Future<List<dynamic>> getReports() async {
    final headers = await _headers();
    final res = await http.get(
      Uri.parse('${AppConfig.baseUrl}/reports'),
      headers: headers,
    );
    final data = jsonDecode(res.body);
    return data['data'] ?? [];
  }

  // Detail satu laporan
  Future<Map<String, dynamic>> getReport(int id) async {
    final headers = await _headers();
    final res = await http.get(
      Uri.parse('${AppConfig.baseUrl}/reports/$id'),
      headers: headers,
    );
    return jsonDecode(res.body);
  }

  // Buat laporan baru (dengan foto opsional)
  Future<Map<String, dynamic>> createReport({
    required String title,
    required String description,
    required int categoryId,
    required String locationAddress,
    double? latitude,
    double? longitude,
    File? photo,
  }) async {
    final token = await _api.getToken();
    final request = http.MultipartRequest(
      'POST',
      Uri.parse('${AppConfig.baseUrl}/reports'),
    );
    request.headers.addAll({
      'Accept': 'application/json',
      'Authorization': 'Bearer $token',
    });
    request.fields['title'] = title;
    request.fields['description'] = description;
    request.fields['category_id'] = categoryId.toString();
    request.fields['location_address'] = locationAddress;
    if (latitude != null) request.fields['latitude'] = latitude.toString();
    if (longitude != null) request.fields['longitude'] = longitude.toString();

    if (photo != null) {
      request.files.add(await http.MultipartFile.fromPath('photo', photo.path));
    }

    final streamed = await request.send();
    final res = await http.Response.fromStream(streamed);
    return jsonDecode(res.body);
  }

  Future<Map<String, dynamic>> updateReport({
    required int id,
    required String title,
    required String description,
    required int categoryId,
    required String locationAddress,
  }) async {
    final headers = await _headers();
    final res = await http.put(
      Uri.parse('${AppConfig.baseUrl}/reports/$id'),
      headers: headers,
      body: jsonEncode({
        'title': title,
        'description': description,
        'category_id': categoryId,
        'location_address': locationAddress,
      }),
    );
    return jsonDecode(res.body);
  }

  // Hapus laporan
  Future<Map<String, dynamic>> deleteReport(int id) async {
    final headers = await _headers();
    final res = await http.delete(
      Uri.parse('${AppConfig.baseUrl}/reports/$id'),
      headers: headers,
    );
    return jsonDecode(res.body);
  }

  // Ambil kategori
  Future<List<dynamic>> getCategories() async {
    final headers = await _headers();
    final res = await http.get(
      Uri.parse('${AppConfig.baseUrl}/categories'),
      headers: headers,
    );
    final data = jsonDecode(res.body);
    return data['data'] ?? [];
  }
}
