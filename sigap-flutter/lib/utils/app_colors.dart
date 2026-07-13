import 'package:flutter/material.dart';

/// Palet warna resmi SIGAP (Sistem Informasi Gangguan dan Aspirasi Publik).
///
/// Filosofi warna:
/// - Biru  -> pelayanan publik & kepercayaan (warna utama, dominan di UI)
/// - Hijau -> laporan selesai / transparansi hasil yang positif
/// - Oranye -> laporan sedang diproses / urgensi
/// - Merah -> laporan ditolak / peringatan (pelengkap siklus status)
/// - Slate/Ink -> netral, menjaga keterbacaan teks tetap tinggi
class AppColors {
  AppColors._();

  // ---------------------------------------------------------------------
  // Brand utama
  // ---------------------------------------------------------------------
  static const Color primaryBlue = Color(0xFF2563EB);
  static const Color primaryBlueDark = Color(0xFF1E40AF);
  static const Color primaryBlueLight = Color(0xFFDBEAFE);

  /// Warna pendukung untuk status "ditinjau" — tetap dalam keluarga biru
  /// (masih tahap pelayanan awal) tapi cukup beda dari "diterima".
  static const Color reviewedIndigo = Color(0xFF6366F1);
  static const Color reviewedIndigoLight = Color(0xFFE0E7FF);

  static const Color successGreen = Color(0xFF22C55E);
  static const Color successGreenDark = Color(0xFF15803D);
  static const Color successGreenLight = Color(0xFFDCFCE7);

  static const Color urgentOrange = Color(0xFFF59E0B);
  static const Color urgentOrangeDark = Color(0xFFB45309);
  static const Color urgentOrangeLight = Color(0xFFFEF3C7);

  static const Color dangerRed = Color(0xFFEF4444);
  static const Color dangerRedDark = Color(0xFFB91C1C);
  static const Color dangerRedLight = Color(0xFFFEE2E2);

  // ---------------------------------------------------------------------
  // Netral
  // ---------------------------------------------------------------------
  static const Color white = Color(0xFFFFFFFF);
  static const Color ink900 = Color(0xFF0F172A); // teks utama
  static const Color slate600 = Color(0xFF475569); // teks sekunder
  static const Color slate400 = Color(0xFF94A3B8); // ikon nonaktif, hint
  static const Color slate200 = Color(0xFFE2E8F0); // border/divider
  static const Color slate100 = Color(0xFFF1F5F9); // surface/field background

  // ---------------------------------------------------------------------
  // Semantik siklus laporan — 5 tahap sesuai status dari Laravel
  // (dipakai di StatusBadge & daftar laporan)
  // ---------------------------------------------------------------------
  static const Color statusReceived = primaryBlue; // diterima
  static const Color statusReviewed = reviewedIndigo; // ditinjau
  static const Color statusInProgress = urgentOrange; // in_progress
  static const Color statusResolved = successGreen; // selesai
  static const Color statusRejected = dangerRed; // ditolak

  // ---------------------------------------------------------------------
  // Semantik urgensi laporan (dipakai di UrgencyBadge)
  // ---------------------------------------------------------------------
  static const Color urgencyNormal = primaryBlue; // normal
  static const Color urgencyPenting = urgentOrange; // penting
  static const Color urgencyDarurat = dangerRed; // darurat
}