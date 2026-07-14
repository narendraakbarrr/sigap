import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../controllers/report_controller.dart';
import '../utils/app_colors.dart';
import '../widgets/report_card.dart';
import 'report_detail_screen.dart';
import 'report_form_screen.dart';

// ======================================================
// Halaman daftar laporan pengguna
// Menampilkan daftar laporan yang diambil dari backend dan
// menyediakan akses pembuatan laporan baru serta refresh data.
// Dependency penting: `ReportController`, `ReportCard`, `ReportDetailScreen`.
// ======================================================
class ReportListScreen extends StatefulWidget {
  const ReportListScreen({super.key});
  @override
  State<ReportListScreen> createState() => _ReportListScreenState();
}

class _ReportListScreenState extends State<ReportListScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() => context.read<ReportController>().fetchReports());
  }

  @override
  Widget build(BuildContext context) {
    final ctrl = context.watch<ReportController>();

    return Scaffold(
      appBar: AppBar(
        title: const Text('Laporan Saya'),
        // Warna, elevasi, dan gaya teks sudah diatur oleh AppTheme.light
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () async {
          await Navigator.push(
            context,
            MaterialPageRoute(builder: (_) => const ReportFormScreen()),
          );
          if (context.mounted) {
            context.read<ReportController>().fetchReports();
          }
        },
        child: const Icon(Icons.add_location_alt_rounded),
      ),
      body: ctrl.isLoading
          ? const Center(child: CircularProgressIndicator())
          : ctrl.reports.isEmpty
              ? const _EmptyState()
              : RefreshIndicator(
                  onRefresh: () => ctrl.fetchReports(),
                  child: ListView.builder(
                    padding: const EdgeInsets.all(16),
                    itemCount: ctrl.reports.length,
                    itemBuilder: (ctx, i) {
                      final r = ctrl.reports[i];
                      return ReportCard(
                        report: r,
                        onTap: () => Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (_) => ReportDetailScreen(reportId: r.id),
                          ),
                        ),
                      );
                    },
                  ),
                ),
    );
  }
}

class _EmptyState extends StatelessWidget {
  const _EmptyState();

  /// Tampilan keadaan kosong saat belum ada laporan.
  ///
  /// Memberikan instruksi kepada pengguna untuk membuat laporan baru.
  @override
  Widget build(BuildContext context) {
    final textTheme = Theme.of(context).textTheme;

    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            padding: const EdgeInsets.all(20),
            decoration: const BoxDecoration(
              color: AppColors.primaryBlueLight,
              shape: BoxShape.circle,
            ),
            child: const Icon(
              Icons.location_city_rounded,
              size: 40,
              color: AppColors.primaryBlueDark,
            ),
          ),
          const SizedBox(height: 16),
          Text(
            'Belum ada laporan',
            style: textTheme.titleMedium?.copyWith(color: AppColors.ink900),
          ),
          const SizedBox(height: 4),
          Text(
            'Tekan tombol pin di kanan bawah untuk membuat laporan baru',
            textAlign: TextAlign.center,
            style: textTheme.bodySmall,
          ),
        ],
      ),
    );
  }
}