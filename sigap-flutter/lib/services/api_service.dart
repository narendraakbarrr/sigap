import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../config.dart';

class ApiService {
  static const String _tokenKey = 'auth_token';

  Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_tokenKey, token);
  }

  Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(_tokenKey);
  }

  Future<void> removeToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_tokenKey);
  }

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

  Future<Map<String, dynamic>> login(String email, String password) async {
    final res = await http.post(
      Uri.parse('${AppConfig.baseUrl}/login'),
      headers: _publicHeaders,
      body: jsonEncode({'email': email, 'password': password}),
    );
    return jsonDecode(res.body);
  }

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

  Future<void> logout() async {
    final headers = await authHeaders();
    await http.post(Uri.parse('${AppConfig.baseUrl}/logout'), headers: headers);
    await removeToken();
  }

  Future<Map<String, dynamic>> getMe() async {
    final headers = await authHeaders();
    final res = await http.get(
      Uri.parse('${AppConfig.baseUrl}/me'),
      headers: headers,
    );
    return jsonDecode(res.body);
  }

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
