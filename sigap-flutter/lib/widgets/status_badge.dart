import 'package:flutter/material.dart';
import '../utils/app_colors.dart';

/// Status siklus hidup sebuah laporan di SIGAP, sesuai nilai kolom `status`
/// yang dikirim Laravel: diterima, ditinjau, in_progress, selesai, ditolak.
enum ReportStatus { received, reviewed, inProgress, resolved, rejected }

extension ReportStatusX on ReportStatus {
  static ReportStatus fromApiValue(String value) {
    switch (value.toLowerCase()) {
      case 'ditinjau':
        return ReportStatus.reviewed;
      case 'in_progress':
        return ReportStatus.inProgress;
      case 'selesai':
        return ReportStatus.resolved;
      case 'ditolak':
        return ReportStatus.rejected;
      case 'diterima':
      default:
        return ReportStatus.received;
    }
  }

  String get label {
    switch (this) {
      case ReportStatus.received:
        return 'Diterima';
      case ReportStatus.reviewed:
        return 'Ditinjau';
      case ReportStatus.inProgress:
        return 'Diproses';
      case ReportStatus.resolved:
        return 'Selesai';
      case ReportStatus.rejected:
        return 'Ditolak';
    }
  }

  Color get color {
    switch (this) {
      case ReportStatus.received:
        return AppColors.statusReceived;
      case ReportStatus.reviewed:
        return AppColors.statusReviewed;
      case ReportStatus.inProgress:
        return AppColors.statusInProgress;
      case ReportStatus.resolved:
        return AppColors.statusResolved;
      case ReportStatus.rejected:
        return AppColors.statusRejected;
    }
  }

  /// Ikon selaras dengan elemen visual SIGAP:
  /// - Diterima  -> pin lokasi (laporan baru masuk & tercatat titik lokasinya)
  /// - Ditinjau  -> kaca pembesar (sedang diverifikasi petugas)
  /// - Diproses  -> petir (respons cepat sedang berjalan)
  /// - Selesai   -> perisai + centang (kepercayaan & hasil terverifikasi)
  /// - Ditolak   -> perisai + silang (transparansi atas keputusan)
  IconData get icon {
    switch (this) {
      case ReportStatus.received:
        return Icons.pin_drop_rounded;
      case ReportStatus.reviewed:
        return Icons.search_rounded;
      case ReportStatus.inProgress:
        return Icons.bolt_rounded;
      case ReportStatus.resolved:
        return Icons.gpp_good_rounded;
      case ReportStatus.rejected:
        return Icons.gpp_bad_rounded;
    }
  }
}

/// Badge status laporan — elemen visual signature SIGAP.
///
/// Contoh:
///   StatusBadge(status: ReportStatusX.fromApiValue(report.status))
class StatusBadge extends StatelessWidget {
  final ReportStatus status;
  final bool compact;

  const StatusBadge({
    super.key,
    required this.status,
    this.compact = false,
  });

  @override
  Widget build(BuildContext context) {
    final color = status.color;

    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: compact ? 8 : 12,
        vertical: compact ? 4 : 6,
      ),
      decoration: BoxDecoration(
        color: color.withOpacity(0.12),
        borderRadius: BorderRadius.circular(999),
        border: Border.all(color: color.withOpacity(0.35)),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(status.icon, size: compact ? 12 : 14, color: color),
          const SizedBox(width: 5),
          Text(
            status.label,
            style: Theme.of(context).textTheme.labelMedium?.copyWith(
                  color: color,
                  fontWeight: FontWeight.w600,
                  fontSize: compact ? 11 : 12,
                ),
          ),
        ],
      ),
    );
  }
}