import 'package:flutter/material.dart';

class AppColors {
  AppColors._();

  static const Color primaryBlue = Color(0xFF2563EB);
  static const Color primaryBlueDark = Color(0xFF1E40AF);
  static const Color primaryBlueLight = Color(0xFFDBEAFE);

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

  static const Color white = Color(0xFFFFFFFF);
  static const Color ink900 = Color(0xFF0F172A); // teks utama
  static const Color slate600 = Color(0xFF475569); // teks sekunder
  static const Color slate400 = Color(0xFF94A3B8); // ikon nonaktif, hint
  static const Color slate200 = Color(0xFFE2E8F0); // border/divider
  static const Color slate100 = Color(0xFFF1F5F9); // surface/field background

  static const Color statusReceived = primaryBlue; // diterima
  static const Color statusReviewed = reviewedIndigo; // ditinjau
  static const Color statusInProgress = urgentOrange; // in_progress
  static const Color statusResolved = successGreen; // selesai
  static const Color statusRejected = dangerRed; // ditolak

  static const Color urgencyNormal = primaryBlue; // normal
  static const Color urgencyPenting = urgentOrange; // penting
  static const Color urgencyDarurat = dangerRed; // darurat
}