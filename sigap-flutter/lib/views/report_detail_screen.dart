import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../controllers/report_controller.dart';
import '../utils/app_colors.dart';
import 'report_edit_screen.dart';

// ======================================================
// Halaman detail laporan
// Menampilkan status, informasi lengkap, dan histori perubahan laporan.
// Mendukung aksi edit dan hapus untuk laporan dengan status `diterima`.
// Dependency penting: `ReportController`, `ReportEditScreen`.
// ======================================================
class ReportDetailScreen extends StatefulWidget {
  final int reportId;
  const ReportDetailScreen({super.key, required this.reportId});
  @override
  State<ReportDetailScreen> createState() => _ReportDetailScreenState();
}

class _ReportDetailScreenState extends State<ReportDetailScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(
      () => context.read<ReportController>().fetchReportDetail(widget.reportId),
    );
  }

  Future<void> _deleteReport() async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Hapus Laporan'),
        content: const Text('Apakah Anda yakin ingin menghapus laporan ini?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx, false),
            child: const Text('Batal'),
          ),
          TextButton(
            onPressed: () => Navigator.pop(ctx, true),
            child: const Text('Hapus'),
          ),
        ],
      ),
    );
    if (confirm != true) return;

    final controller = context.read<ReportController>();
    final success = await controller.deleteReport(widget.reportId);
    if (!mounted) return;

    if (success) {
      final messenger = ScaffoldMessenger.of(context);
      Navigator.pop(context);
      messenger.showSnackBar(
        const SnackBar(content: Text('Laporan berhasil dihapus')),
      );
    } else {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(const SnackBar(content: Text('Gagal menghapus laporan')));
    }
  }

  Color _statusColor(String status) {
    switch (status) {
      case 'diterima':
        return AppColors.primaryBlue;
      case 'diproses':
        return AppColors.urgentOrange;
      case 'selesai':
        return AppColors.successGreen;
      case 'ditolak':
        return AppColors.dangerRed;
      default:
        return AppColors.slate400;
    }
  }

  @override
  Widget build(BuildContext context) {
    final ctrl = context.watch<ReportController>();
    final report = ctrl.selectedReport;

    return Scaffold(
      backgroundColor: AppColors.slate100,
      appBar: AppBar(
        title: const Text('Detail Laporan'),
        backgroundColor: AppColors.primaryBlue,
        foregroundColor: AppColors.white,
        elevation: 0,
        actions: [
          // Tambahkan di actions AppBar, sebelum tombol delete
          if (report != null && report.status == 'diterima')
            IconButton(
              icon: const Icon(Icons.edit_outlined),
              tooltip: 'Edit Laporan',
              onPressed: () async {
                final changed = await Navigator.push<bool>(
                  context,
                  MaterialPageRoute(
                    builder: (_) => ReportEditScreen(report: report),
                  ),
                );
                // Jika ada perubahan, refresh detail
                if (changed == true && context.mounted) {
                  context.read<ReportController>().fetchReportDetail(
                    widget.reportId,
                  );
                }
              },
            ),
          if (report != null && report.status == 'diterima')
            IconButton(
              icon: const Icon(Icons.delete_outline),
              onPressed: _deleteReport,
            ),
        ],
      ),
      body: ctrl.isLoading
          ? const Center(child: CircularProgressIndicator())
          : report == null
          ? const Center(child: Text('Laporan tidak ditemukan'))
          : SingleChildScrollView(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Status badge
                  Container(
                    padding: const EdgeInsets.symmetric(
                      horizontal: 12,
                      vertical: 6,
                    ),
                    decoration: BoxDecoration(
                      color: _statusColor(report.status).withOpacity(0.12),
                      borderRadius: BorderRadius.circular(20),
                      border: Border.all(
                        color: _statusColor(report.status).withOpacity(0.6),
                      ),
                    ),
                    child: Text(
                      report.status.toUpperCase(),
                      style: TextStyle(
                        fontWeight: FontWeight.bold,
                        color: _statusColor(report.status),
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),

                  Text(
                    report.title,
                    style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                      fontWeight: FontWeight.bold,
                      color: AppColors.ink900,
                    ),
                  ),
                  const SizedBox(height: 8),

                  // Foto
                  if (report.photoUrl != null)
                    ClipRRect(
                      borderRadius: BorderRadius.circular(8),
                      child: Image.network(
                        report.photoUrl!,
                        height: 200,
                        width: double.infinity,
                        fit: BoxFit.cover,
                      ),
                    ),
                  const SizedBox(height: 12),

                  _infoRow('Kategori', report.categoryName),
                  _infoRow('Urgensi', report.urgency.toUpperCase()),
                  _infoRow('Lokasi', report.locationAddress),
                  _infoRow('Tanggal', report.createdAt),
                  const SizedBox(height: 14),
                  Text(
                    'Deskripsi',
                    style: Theme.of(context).textTheme.titleSmall?.copyWith(
                      fontWeight: FontWeight.w700,
                      color: AppColors.ink900,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    report.description,
                    style: const TextStyle(color: Colors.black87),
                  ),

                  // Track Record
                  if (report.statusLogs.isNotEmpty) ...[
                    const SizedBox(height: 20),
                    Text(
                      'Track Record',
                      style: Theme.of(context).textTheme.titleSmall?.copyWith(
                        fontWeight: FontWeight.w700,
                        color: AppColors.ink900,
                      ),
                    ),
                    const SizedBox(height: 12),

                    ...report.statusLogs.asMap().entries.map((entry) {
                      final index = entry.key;
                      final log = entry.value;
                      final isLast = index == report.statusLogs.length - 1;

                      return Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          // Timeline indicator
                          Column(
                            children: [
                              Container(
                                width: 28,
                                height: 28,
                                decoration: BoxDecoration(
                                  color: AppColors.primaryBlue,
                                  shape: BoxShape.circle,
                                ),
                                child: Center(
                                  child: Text(
                                    '${index + 1}',
                                    style: const TextStyle(
                                      color: Colors.white,
                                      fontSize: 12,
                                      fontWeight: FontWeight.bold,
                                    ),
                                  ),
                                ),
                              ),
                              if (!isLast)
                                Container(
                                  width: 2,
                                  height: 40,
                                  color: AppColors.slate200,
                                ),
                            ],
                          ),
                          const SizedBox(width: 12),

                          // Konten log
                          Expanded(
                            child: Container(
                              margin: const EdgeInsets.only(bottom: 8),
                              padding: const EdgeInsets.all(12),
                              decoration: BoxDecoration(
                                color: AppColors.slate100,
                                borderRadius: BorderRadius.circular(8),
                                border: Border.all(color: AppColors.slate200),
                              ),
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Row(
                                    mainAxisAlignment:
                                        MainAxisAlignment.spaceBetween,
                                    children: [
                                      Text(
                                        _statusLabel(log.status),
                                        style: const TextStyle(
                                          fontWeight: FontWeight.bold,
                                          fontSize: 13,
                                        ),
                                      ),
                                      if (log.changedAt != null)
                                        Text(
                                          log.changedAt!,
                                          style: Theme.of(context)
                                              .textTheme
                                              .bodySmall
                                              ?.copyWith(
                                                fontSize: 10,
                                                color: AppColors.slate400,
                                              ),
                                        ),
                                    ],
                                  ),
                                  if (log.changedBy != null)
                                    Text(
                                      'Oleh: ${log.changedBy}',
                                      style: Theme.of(context)
                                          .textTheme
                                          .bodySmall
                                          ?.copyWith(
                                            fontSize: 11,
                                            color: AppColors.slate400,
                                          ),
                                    ),
                                  if (log.taskDescription != null) ...[
                                    const SizedBox(height: 6),
                                    Container(
                                      padding: const EdgeInsets.all(8),
                                      decoration: BoxDecoration(
                                        color: AppColors.primaryBlueLight,
                                        borderRadius: BorderRadius.circular(6),
                                        border: Border(
                                          left: BorderSide(
                                            color: AppColors.primaryBlue,
                                            width: 3,
                                          ),
                                        ),
                                      ),
                                      child: Text(
                                        'Tindakan: ${log.taskDescription}',
                                        style: TextStyle(
                                          fontSize: 12,
                                          color: AppColors.primaryBlueDark,
                                        ),
                                      ),
                                    ),
                                  ],
                                  if (log.notes != null) ...[
                                    const SizedBox(height: 4),
                                    Text(
                                      'Catatan: ${log.notes}',
                                      style: Theme.of(context)
                                          .textTheme
                                          .bodySmall
                                          ?.copyWith(
                                            fontSize: 12,
                                            color: AppColors.slate600,
                                          ),
                                    ),
                                  ],
                                ],
                              ),
                            ),
                          ),
                        ],
                      );
                    }).toList(),
                  ],
                ],
              ),
            ),
    );
  }

  Widget _infoRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 6),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 80,
            child: Text(
              label,
              style: const TextStyle(color: Colors.grey, fontSize: 13),
            ),
          ),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(fontWeight: FontWeight.w500, fontSize: 13),
            ),
          ),
        ],
      ),
    );
  }

  // Helper method di dalam ReportDetailScreenState
  String _statusLabel(String status) {
    const labels = {
      'diterima': 'Diterima',
      'ditinjau': 'Ditinjau',
      'in_progress': 'In Progress',
      'selesai': 'Selesai',
      'ditolak': 'Ditolak',
    };
    return labels[status] ?? status;
  }
}
