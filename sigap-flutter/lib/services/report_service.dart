import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import '../config.dart';
import 'api_service.dart';

// ======================================================
// Layanan API untuk operasi laporan
// Mengelola pemanggilan endpoint laporan, kategori, dan operasi file foto.
// Digunakan oleh `ReportController` untuk CRUD laporan.
// Dependency penting: `ApiService`, `AppConfig`, package `http`.
// ======================================================
class ReportService {
  final _api = ApiService();

  /// Menyusun header autentikasi untuk request laporan.
  Future<Map<String, String>> _headers() async {
    final token = await _api.getToken();
    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': 'Bearer $token',
    };
  }

  // Ambil semua laporan
  /// Mengambil semua laporan pengguna dari API.
  ///
  /// Mengembalikan list data mentah yang akan diparsing oleh controller.
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
  /// Mengambil detail laporan tertentu berdasarkan `id`.
  ///
  /// Mengembalikan respons JSON lengkap dari endpoint laporan.
  Future<Map<String, dynamic>> getReport(int id) async {
    final headers = await _headers();
    final res = await http.get(
      Uri.parse('${AppConfig.baseUrl}/reports/$id'),
      headers: headers,
    );
    return jsonDecode(res.body);
  }

  // Buat laporan baru (dengan foto opsional)
  /// Membuat laporan baru dengan opsi unggahan foto.
  ///
  /// Parameter mencakup judul, deskripsi, kategori, lokasi, urgensi,
  /// dan koordinat opsional.
  /// Efek samping: mengirim request multipart ke endpoint laporan.
  Future<Map<String, dynamic>> createReport({
    required String title,
    required String description,
    required int categoryId,
    required String locationAddress,
    required String urgency,
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
    request.fields['urgency'] = urgency;
    if (latitude != null) request.fields['latitude'] = latitude.toString();
    if (longitude != null) request.fields['longitude'] = longitude.toString();

    if (photo != null) {
      request.files.add(await http.MultipartFile.fromPath('photo', photo.path));
    }

    final streamed = await request.send();
    final res = await http.Response.fromStream(streamed);
    return jsonDecode(res.body);
  }

  /// Memperbarui data laporan yang sudah ada.
  ///
  /// Mengirim request PUT ke endpoint laporan dengan data terformat JSON.
  Future<Map<String, dynamic>> updateReport({
    required int id,
    required String title,
    required String description,
    required int categoryId,
    required String locationAddress,
    required String urgency,
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
        'urgency': urgency,
      }),
    );
    return jsonDecode(res.body);
  }

  // Hapus laporan
  /// Menghapus laporan berdasarkan `id` melalui API.
  ///
  /// Mengembalikan respons JSON dari operasi hapus.
  Future<Map<String, dynamic>> deleteReport(int id) async {
    final headers = await _headers();
    final res = await http.delete(
      Uri.parse('${AppConfig.baseUrl}/reports/$id'),
      headers: headers,
    );
    return jsonDecode(res.body);
  }

  /// Mengambil daftar kategori laporan untuk opsi form.
  ///
  /// Mendukung respons API dalam bentuk list langsung atau objek dengan key `data`.
  Future<List<dynamic>> getCategories() async {
    final headers = await _headers();
    final url = Uri.parse('${AppConfig.baseUrl}/categories');
    // ignore: avoid_print
    print('[getCategories] GET $url');
    // ignore: avoid_print
    print('[getCategories] headers: $headers');
    final res = await http.get(url, headers: headers);
    // ignore: avoid_print
    print('[getCategories] status: ${res.statusCode}');
    // ignore: avoid_print
    print('[getCategories] body: ${res.body}');
    final data = jsonDecode(res.body);
    if (data is Map<String, dynamic>) {
      return data['data'] ?? [];
    }
    if (data is List) {
      return data;
    }
    return [];
  }
}
