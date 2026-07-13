import 'package:flutter/material.dart';
import '../utils/app_colors.dart';

/// Level urgensi laporan sesuai kolom `urgency` dari Laravel:
/// normal, penting, darurat.
enum ReportUrgency { normal, penting, darurat }

extension ReportUrgencyX on ReportUrgency {
  static ReportUrgency fromApiValue(String value) {
    switch (value.toLowerCase()) {
      case 'penting':
        return ReportUrgency.penting;
      case 'darurat':
        return ReportUrgency.darurat;
      case 'normal':
      default:
        return ReportUrgency.normal;
    }
  }

  String get label {
    switch (this) {
      case ReportUrgency.normal:
        return 'Normal';
      case ReportUrgency.penting:
        return 'Penting';
      case ReportUrgency.darurat:
        return 'Darurat';
    }
  }

  Color get color {
    switch (this) {
      case ReportUrgency.normal:
        return AppColors.urgencyNormal;
      case ReportUrgency.penting:
        return AppColors.urgencyPenting;
      case ReportUrgency.darurat:
        return AppColors.urgencyDarurat;
    }
  }

  IconData get icon {
    switch (this) {
      case ReportUrgency.normal:
        return Icons.info_outline_rounded;
      case ReportUrgency.penting:
        return Icons.priority_high_rounded;
      case ReportUrgency.darurat:
        return Icons.warning_rounded;
    }
  }
}

/// Badge urgensi — dipakai berdampingan dengan [StatusBadge].
/// Untuk urgensi 'normal', badge sengaja dibuat lebih senyap (tanpa isi
/// warna solid) supaya perhatian pengguna tetap ke laporan yang genting.
class UrgencyBadge extends StatelessWidget {
  final ReportUrgency urgency;
  final bool compact;

  const UrgencyBadge({
    super.key,
    required this.urgency,
    this.compact = false,
  });

  @override
  Widget build(BuildContext context) {
    final color = urgency.color;
    final isQuiet = urgency == ReportUrgency.normal;

    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: compact ? 8 : 10,
        vertical: compact ? 3 : 5,
      ),
      decoration: BoxDecoration(
        color: isQuiet ? AppColors.primaryBlueLight : color.withValues(alpha: 0.12),
        borderRadius: BorderRadius.circular(999),
        border: Border.all(
          color: isQuiet
              ? AppColors.primaryBlue.withValues(alpha: 0.25)
              : color.withValues(alpha: 0.3),
        ),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(urgency.icon, size: compact ? 11 : 13, color: color),
          const SizedBox(width: 4),
          Text(
            urgency.label.toUpperCase(),
            style: TextStyle(
              fontSize: compact ? 10 : 11,
              fontWeight: FontWeight.w700,
              letterSpacing: 0.3,
              color: color,
            ),
          ),
        ],
      ),
    );
  }
}