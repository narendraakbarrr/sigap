import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../controllers/report_controller.dart';
import '../models/report_model.dart';
import 'report_detail_screen.dart';
import 'report_form_screen.dart';

class ReportListScreen extends StatefulWidget {
  const ReportListScreen({super.key});
  @override
  State<ReportListScreen> createState() => _ReportListScreenState();
}

class _ReportListScreenState extends State<ReportListScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() =>
        context.read<ReportController>().fetchReports());
  }

  Color _statusColor(String status) {
    switch (status) {
      case 'diterima': return Colors.blue;
      case 'diproses': return Colors.orange;
      case 'selesai':  return Colors.green;
      case 'ditolak':  return Colors.red;
      default:         return Colors.grey;
    }
  }

  @override
  Widget build(BuildContext context) {
    final ctrl = context.watch<ReportController>();

    return Scaffold(
      appBar: AppBar(
        title: const Text('Laporan Saya'),
        backgroundColor: Colors.deepOrange,
        foregroundColor: Colors.white,
      ),
      floatingActionButton: FloatingActionButton(
        backgroundColor: Colors.deepOrange,
        onPressed: () async {
          await Navigator.push(context,
              MaterialPageRoute(builder: (_) => const ReportFormScreen()));
          if (context.mounted) {
            context.read<ReportController>().fetchReports();
          }
        },
        child: const Icon(Icons.add, color: Colors.white),
      ),
      body: ctrl.isLoading
          ? const Center(child: CircularProgressIndicator())
          : ctrl.reports.isEmpty
              ? const Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Icons.inbox_outlined,
                          size: 64, color: Colors.grey),
                      SizedBox(height: 12),
                      Text('Belum ada laporan',
                          style: TextStyle(color: Colors.grey)),
                      Text('Tekan + untuk membuat laporan baru',
                          style: TextStyle(color: Colors.grey, fontSize: 12)),
                    ],
                  ),
                )
              : RefreshIndicator(
                  onRefresh: () => ctrl.fetchReports(),
                  child: ListView.builder(
                    padding: const EdgeInsets.all(16),
                    itemCount: ctrl.reports.length,
                    itemBuilder: (ctx, i) {
                      final r = ctrl.reports[i];
                      return Card(
                        margin: const EdgeInsets.only(bottom: 12),
                        child: ListTile(
                          contentPadding: const EdgeInsets.all(12),
                          title: Text(r.title,
                              style: const TextStyle(
                                  fontWeight: FontWeight.bold)),
                          subtitle: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const SizedBox(height: 4),
                              Text(r.categoryName,
                                  style: const TextStyle(
                                      fontSize: 12, color: Colors.grey)),
                              const SizedBox(height: 4),
                              Text(r.locationAddress,
                                  style: const TextStyle(fontSize: 12),
                                  maxLines: 1,
                                  overflow: TextOverflow.ellipsis),
                            ],
                          ),
                          trailing: Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Container(
                                padding: const EdgeInsets.symmetric(
                                    horizontal: 8, vertical: 4),
                                decoration: BoxDecoration(
                                  color: _statusColor(r.status)
                                      .withOpacity(0.1),
                                  borderRadius: BorderRadius.circular(12),
                                  border: Border.all(
                                      color: _statusColor(r.status)
                                          .withOpacity(0.5)),
                                ),
                                child: Text(
                                  r.status.toUpperCase(),
                                  style: TextStyle(
                                      fontSize: 10,
                                      fontWeight: FontWeight.bold,
                                      color: _statusColor(r.status)),
                                ),
                              ),
                              const SizedBox(height: 4),
                              Text(r.createdAt,
                                  style: const TextStyle(
                                      fontSize: 10, color: Colors.grey)),
                            ],
                          ),
                          onTap: () => Navigator.push(
                              context,
                              MaterialPageRoute(
                                  builder: (_) =>
                                      ReportDetailScreen(reportId: r.id))),
                        ),
                      );
                    },
                  ),
                ),
    );
  }
}