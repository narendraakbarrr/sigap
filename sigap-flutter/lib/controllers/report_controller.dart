import 'package:flutter/material.dart';
import '../models/report_model.dart';
import '../models/category_model.dart';
import '../services/report_service.dart';
import 'dart:io';

class ReportController extends ChangeNotifier {
  final _service = ReportService();

  List<ReportModel>    reports    = [];
  List<CategoryModel>  categories = [];
  ReportModel?         selectedReport;
  bool                 isLoading  = false;
  String?              errorMessage;

  // ── Fetch semua laporan ───────────────────────────────────────
  Future<void> fetchReports() async {
    isLoading = true; notifyListeners();
    try {
      final data = await _service.getReports();
      reports = data.map((j) => ReportModel.fromJson(j)).toList();
    } catch (e) {
      errorMessage = 'Gagal memuat laporan';
    }
    isLoading = false; notifyListeners();
  }

  // ── Fetch detail laporan ──────────────────────────────────────
  Future<void> fetchReportDetail(int id) async {
    isLoading = true; selectedReport = null; notifyListeners();
    try {
      final data = await _service.getReport(id);
      selectedReport = ReportModel.fromJson(data['data'] ?? data);
    } catch (e) {
      errorMessage = 'Gagal memuat detail laporan';
    }
    isLoading = false; notifyListeners();
  }

  // ── Buat laporan baru ─────────────────────────────────────────
  Future<bool> createReport({
    required String title,
    required String description,
    required int    categoryId,
    required String locationAddress,
    File?           photo,
  }) async {
    isLoading = true; errorMessage = null; notifyListeners();
    try {
      final data = await _service.createReport(
        title:           title,
        description:     description,
        categoryId:      categoryId,
        locationAddress: locationAddress,
        photo:           photo,
      );
      isLoading = false; notifyListeners();
      return data['id'] != null || data['data'] != null;
    } catch (e) {
      errorMessage = 'Gagal mengirim laporan';
      isLoading = false; notifyListeners();
      return false;
    }
  }

  // ── Hapus laporan ─────────────────────────────────────────────
  Future<bool> deleteReport(int id) async {
    isLoading = true; notifyListeners();
    try {
      await _service.deleteReport(id);
      reports.removeWhere((r) => r.id == id);
      isLoading = false; notifyListeners();
      return true;
    } catch (e) {
      errorMessage = 'Gagal menghapus laporan';
      isLoading = false; notifyListeners();
      return false;
    }
  }

  // ── Fetch kategori ────────────────────────────────────────────
  Future<void> fetchCategories() async {
    try {
      final data  = await _service.getCategories();
      categories  = data.map((j) => CategoryModel.fromJson(j)).toList();
      notifyListeners();
    } catch (e) {
      errorMessage = 'Gagal memuat kategori';
    }
  }
}