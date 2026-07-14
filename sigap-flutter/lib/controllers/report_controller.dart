import 'package:flutter/material.dart';
import '../models/report_model.dart';
import '../models/category_model.dart';
import '../services/report_service.dart';
import 'dart:io';

// ======================================================
// Kontrol data laporan
// Mengelola pengambilan, pembuatan, pembaruan, dan penghapusan laporan.
// Digunakan oleh tampilan daftar, detail, form, dan filter kategori.
// Dependency penting: `ReportService`, `ReportModel`, `CategoryModel`.
// ======================================================
class ReportController extends ChangeNotifier {
  final _service = ReportService();

  List<ReportModel> reports = [];
  List<CategoryModel> categories = [];
  ReportModel? selectedReport;
  bool isLoading = false;
  String? errorMessage;

  /// Mengambil daftar laporan dari API.
  ///
  /// Mengatur state loading sebelum dan sesudah panggilan API,
  /// serta mengisi daftar `reports` dengan model yang diparsing.
  Future<void> fetchReports() async {
    isLoading = true;
    notifyListeners();
    try {
      final data = await _service.getReports();
      reports = data.map((j) => ReportModel.fromJson(j)).toList();
    } catch (e) {
      errorMessage = 'Gagal memuat laporan';
    }
    isLoading = false;
    notifyListeners();
  }

  /// Mengambil detail satu laporan berdasarkan `id`.
  ///
  /// Memuat data ke `selectedReport` untuk digunakan oleh layar detail.
  Future<void> fetchReportDetail(int id) async {
    isLoading = true;
    selectedReport = null;
    notifyListeners();
    try {
      final data = await _service.getReport(id);
      selectedReport = ReportModel.fromJson(data['data'] ?? data);
    } catch (e) {
      errorMessage = 'Gagal memuat detail laporan';
    }
    isLoading = false;
    notifyListeners();
  }

  /// Membuat laporan baru melalui API.
  ///
  /// Parameter mencakup judul, deskripsi, kategori, lokasi, urgensi, dan foto opsional.
  /// Mengembalikan `true` jika respons API menunjukkan laporan berhasil dibuat.
  /// Efek samping: memperbarui state loading dan error untuk UI.
  Future<bool> createReport({
    required String title,
    required String description,
    required int categoryId,
    required String locationAddress,
    required String urgency,
    File? photo,
  }) async {
    isLoading = true;
    errorMessage = null;
    notifyListeners();
    try {
      final data = await _service.createReport(
        title: title,
        description: description,
        categoryId: categoryId,
        locationAddress: locationAddress,
        urgency: urgency,
        photo: photo,
      );
      isLoading = false;
      notifyListeners();
      return data['id'] != null || data['data'] != null;
    } catch (e) {
      errorMessage = 'Gagal mengirim laporan';
      isLoading = false;
      notifyListeners();
      return false;
    }
  }

  /// Memperbarui laporan berdasarkan `id`.
  ///
  /// Parameter mencakup data laporan yang diubah. Jika update berhasil,
  /// daftar `reports` dan `selectedReport` diperbarui bila perlu.
  /// Mengembalikan `true` apabila API mengonfirmasi perubahan.
  Future<bool> updateReport({
    required int id,
    required String title,
    required String description,
    required int categoryId,
    required String locationAddress,
    required String urgency,
  }) async {
    isLoading = true;
    errorMessage = null;
    notifyListeners();
    try {
      final data = await _service.updateReport(
        id: id,
        title: title,
        description: description,
        categoryId: categoryId,
        locationAddress: locationAddress,
        urgency: urgency,
      );

      final report = data['data'] ?? data;

      if (report is Map<String, dynamic> && report['id'] != null) {
        final idx = reports.indexWhere((r) => r.id == id);
        if (idx != -1) {
          reports[idx] = ReportModel.fromJson(report);
        }
        if (selectedReport?.id == id) {
          selectedReport = ReportModel.fromJson(report);
        }
        isLoading = false;
        notifyListeners();
        return true;
      }

      errorMessage = data['message'] ?? 'Gagal memperbarui laporan';
      isLoading = false;
      notifyListeners();
      return false;
    } catch (e) {
      errorMessage = 'Tidak dapat terhubung ke server';
      isLoading = false;
      notifyListeners();
      return false;
    }
  }

  /// Menghapus laporan berdasarkan `id`.
  ///
  /// Efek samping: memanggil API delete dan menghapus item dari daftar lokal.
  Future<bool> deleteReport(int id) async {
    isLoading = true;
    notifyListeners();
    try {
      await _service.deleteReport(id);
      reports.removeWhere((r) => r.id == id);
      isLoading = false;
      notifyListeners();
      return true;
    } catch (e) {
      errorMessage = 'Gagal menghapus laporan';
      isLoading = false;
      notifyListeners();
      return false;
    }
  }

  bool isLoadingCategories = false;
  String? categoryError;

  /// Mengambil daftar kategori laporan dari API.
  ///
  /// Memperbarui state khusus kategori dan menyimpan pesan error jika gagal.
  Future<void> fetchCategories() async {
    isLoadingCategories = true;
    categoryError = null;
    notifyListeners();
    try {
      final data = await _service.getCategories();
      categories = data.map((j) => CategoryModel.fromJson(j)).toList();
    } catch (e, st) {
      // ignore: avoid_print
      print('[fetchCategories] ERROR: $e');
      // ignore: avoid_print
      print(st);
      categoryError = 'Gagal memuat kategori';
      errorMessage = categoryError;
    }
    isLoadingCategories = false;
    notifyListeners();
  }
}
