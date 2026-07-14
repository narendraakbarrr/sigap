import 'package:flutter/material.dart';
import '../models/user_model.dart';
import '../services/api_service.dart';

// ======================================================
// Kontrol autentikasi pengguna
// Mengelola login, logout, dan pengecekan sesi.
// Digunakan oleh halaman login, dashboard, dan navigasi awal.
// Dependency penting: `ApiService`, `UserModel`.
// ======================================================
class AuthController extends ChangeNotifier {
  final _api = ApiService();

  UserModel? currentUser;
  bool isLoading    = false;
  String? errorMessage;

  /// Mengirimkan request login ke API.
  ///
  /// Parameter:
  /// - `email`: alamat email pengguna.
  /// - `password`: kata sandi pengguna.
  ///
  /// Mengembalikan `true` jika login berhasil dan token disimpan,
  /// `false` jika gagal.
  /// Efek samping: menyimpan token ke SharedPreferences,
  /// memperbarui state loading, dan memicu notifikasi UI.
  Future<bool> login(String email, String password) async {
    isLoading = true; errorMessage = null; notifyListeners();
    try {
      final data = await _api.login(email, password);
      if (data['token'] != null) {
        await _api.saveToken(data['token']);
        currentUser = UserModel.fromJson(data['user']);
        isLoading = false; notifyListeners();
        return true;
      }
      errorMessage = data['message'] ?? 'Login gagal';
    } catch (_) {
      errorMessage = 'Tidak dapat terhubung ke server';
    }
    isLoading = false; notifyListeners();
    return false;
  }

  Future<void> logout() async {
    await _api.logout();
    currentUser = null;
    notifyListeners();
  }

  Future<bool> checkSession() async {
    final token = await _api.getToken();
    if (token == null) return false;
    try {
      final data = await _api.getMe();
      if (data['id'] != null) {
        currentUser = UserModel.fromJson(data);
        notifyListeners();
        return true;
      }
    } catch (_) {}
    return false;
  }
}