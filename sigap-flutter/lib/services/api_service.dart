import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../config.dart';

// ======================================================
// Layanan API umum untuk autentikasi dan profil pengguna
// Mengelola token otentikasi dan permintaan HTTP ke endpoint user.
// Digunakan oleh `AuthController` dan layar profil.
// Dependency penting: `AppConfig`, `SharedPreferences`, package `http`.
// ======================================================
class ApiService {
  static const String _tokenKey = 'auth_token';

  /// Menyimpan token autentikasi lokal ke SharedPreferences.
  ///
  /// Parameter:
  /// - `token`: token akses yang diterima dari API.
  Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_tokenKey, token);
  }

  /// Mengambil token autentikasi yang tersimpan.
  ///
  /// Mengembalikan `null` jika token tidak ditemukan.
  Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(_tokenKey);
  }

  /// Menghapus token autentikasi dari storage lokal.
  Future<void> removeToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_tokenKey);
  }

  /// Menyusun header HTTP dengan token Bearer untuk request yang memerlukan autentikasi.
  Future<Map<String, String>> authHeaders() async {
    final token = await getToken();
    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': 'Bearer $token',
    };
  }

  Map<String, String> get _publicHeaders => {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  };

  /// Melakukan login pengguna ke backend SIGAP.
  ///
  /// Parameter:
  /// - `email`: alamat email pengguna.
  /// - `password`: kata sandi pengguna.
  ///
  /// Mengembalikan respons JSON API sebagai peta.
  Future<Map<String, dynamic>> login(String email, String password) async {
    final res = await http.post(
      Uri.parse('${AppConfig.baseUrl}/login'),
      headers: _publicHeaders,
      body: jsonEncode({'email': email, 'password': password}),
    );
    return jsonDecode(res.body);
  }

  /// Mendaftarkan pengguna baru di backend.
  ///
  /// Parameter:
  /// - `name`: nama lengkap pengguna.
  /// - `email`: alamat email.
  /// - `password`: kata sandi.
  ///
  /// Mengembalikan respons JSON yang berisi token dan data user bila berhasil.
  Future<Map<String, dynamic>> register(
    String name,
    String email,
    String password,
  ) async {
    final res = await http.post(
      Uri.parse('${AppConfig.baseUrl}/register'),
      headers: _publicHeaders,
      body: jsonEncode({
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': password,
      }),
    );
    return jsonDecode(res.body);
  }

  /// Mengirim permintaan logout ke backend dan menghapus token lokal.
  ///
  /// Efek samping: membersihkan kredensial dari SharedPreferences.
  Future<void> logout() async {
    final headers = await authHeaders();
    await http.post(Uri.parse('${AppConfig.baseUrl}/logout'), headers: headers);
    await removeToken();
  }

  /// Mendapatkan data profil pengguna yang sedang login.
  ///
  /// Menggunakan header autentikasi yang tersimpan.
  Future<Map<String, dynamic>> getMe() async {
    final headers = await authHeaders();
    final res = await http.get(
      Uri.parse('${AppConfig.baseUrl}/me'),
      headers: headers,
    );
    return jsonDecode(res.body);
  }

  /// Memperbarui nama profil pengguna melalui endpoint backend.
  ///
  /// Parameter:
  /// - `name`: nama pengguna baru.
  ///
  /// Mengembalikan respons JSON dari API.
  Future<Map<String, dynamic>> updateProfile({required String name}) async {
    final headers = await authHeaders();
    final res = await http.put(
      Uri.parse('${AppConfig.baseUrl}/profile'),
      headers: headers,
      body: jsonEncode({'name': name}),
    );
    return jsonDecode(res.body);
  }
}
