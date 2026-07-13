import 'package:flutter/material.dart';
import '../models/report_model.dart';
import '../utils/app_colors.dart';
import 'status_badge.dart';
import 'urgency_badge.dart';

class ReportCard extends StatelessWidget {
  final ReportModel report;
  final VoidCallback onTap;

  const ReportCard({
    super.key,
    required this.report,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    final status = ReportStatusX.fromApiValue(report.status);
    final urgency = ReportUrgencyX.fromApiValue(report.urgency);
    final textTheme = Theme.of(context).textTheme;

    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(16),
        child: Padding(
          padding: const EdgeInsets.all(12),
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _Thumbnail(photoUrl: report.photoUrl),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Kategori + urgensi (kalau bukan normal)
                    Row(
                      children: [
                        Expanded(
                          child: Text(
                            report.categoryName,
                            style: textTheme.labelMedium?.copyWith(
                              color: AppColors.slate600,
                            ),
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                          ),
                        ),
                        const SizedBox(width: 6),
                        UrgencyBadge(urgency: urgency, compact: true),
                      ],
                    ),
                    const SizedBox(height: 4),

                    // Judul laporan
                    Text(
                      report.title,
                      style: textTheme.titleSmall?.copyWith(
                        color: AppColors.ink900,
                        fontWeight: FontWeight.w700,
                      ),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    const SizedBox(height: 6),

                    // Lokasi pakai ikon pin sesuai elemen visual SIGAP
                    Row(
                      children: [
                        const Icon(
                          Icons.location_on_rounded,
                          size: 14,
                          color: AppColors.slate400,
                        ),
                        const SizedBox(width: 4),
                        Expanded(
                          child: Text(
                            report.locationAddress,
                            style: textTheme.bodySmall,
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 10),

                    // Status + waktu dilaporkan
                    Row(
                      children: [
                        StatusBadge(status: status, compact: true),
                        const Spacer(),
                        const Icon(
                          Icons.schedule_rounded,
                          size: 12,
                          color: AppColors.slate400,
                        ),
                        const SizedBox(width: 3),
                        Text(
                          report.createdAt,
                          style: textTheme.bodySmall?.copyWith(fontSize: 11),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _Thumbnail extends StatelessWidget {
  final String? photoUrl;
  const _Thumbnail({required this.photoUrl});

  @override
  Widget build(BuildContext context) {
    const size = 64.0;

    if (photoUrl == null || photoUrl!.isEmpty) {
      return Container(
        width: size,
        height: size,
        decoration: BoxDecoration(
          color: AppColors.primaryBlueLight,
          borderRadius: BorderRadius.circular(12),
        ),
        child: const Icon(
          Icons.location_city_rounded,
          color: AppColors.primaryBlueDark,
          size: 28,
        ),
      );
    }

    return ClipRRect(
      borderRadius: BorderRadius.circular(12),
      child: Image.network(
        photoUrl!,
        width: size,
        height: size,
        fit: BoxFit.cover,
        errorBuilder: (context, error, stackTrace) => Container(
          width: size,
          height: size,
          color: AppColors.slate100,
          child: const Icon(
            Icons.broken_image_rounded,
            color: AppColors.slate400,
          ),
        ),
        loadingBuilder: (context, child, progress) {
          if (progress == null) return child;
          return Container(
            width: size,
            height: size,
            color: AppColors.slate100,
            child: const Center(
              child: SizedBox(
                width: 18,
                height: 18,
                child: CircularProgressIndicator(strokeWidth: 2),
              ),
            ),
          );
        },
      ),
    );
  }
}