import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../controllers/auth_controller.dart';
import '../models/announcement_model.dart';
import '../services/announcement_service.dart';
import '../utils/app_colors.dart';
import 'login_screen.dart';
import 'profile_screen.dart';
import 'report_list_screen.dart';

// ======================================================
// Halaman dashboard utama SIGAP
// Menampilkan ringkasan profil pengguna, menu laporan, dan pengumuman terbaru.
// Dependency penting: `AuthController`, `AnnouncementService`, `ReportListScreen`.
// ======================================================
class DashboardScreen extends StatelessWidget {
  const DashboardScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthController>();
    final user = auth.currentUser;
    final textTheme = Theme.of(context).textTheme;

    return Scaffold(
      backgroundColor: AppColors.slate100,
      appBar: AppBar(
        title: const Text('Dashboard'),
        centerTitle: true,
        backgroundColor: AppColors.primaryBlue,
        foregroundColor: AppColors.white,
        elevation: 0,
        actions: [
          IconButton(
            icon: const Icon(Icons.person_outline),
            tooltip: 'Profil',
            onPressed: () => Navigator.push(
              context,
              MaterialPageRoute(builder: (_) => const ProfileScreen()),
            ),
          ),
          IconButton(
            icon: const Icon(Icons.logout_outlined),
            tooltip: 'Keluar',
            onPressed: () async {
              await auth.logout();
              if (!context.mounted) return;
              Navigator.pushAndRemoveUntil(
                context,
                MaterialPageRoute(builder: (_) => const LoginScreen()),
                (route) => false,
              );
            },
          ),
        ],
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.fromLTRB(20, 16, 20, 24),
          child: Center(
            child: ConstrainedBox(
              constraints: const BoxConstraints(maxWidth: 520),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Card(
                    elevation: 0,
                    color: AppColors.white,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(20),
                      side: const BorderSide(color: AppColors.slate200),
                    ),
                    child: Padding(
                      padding: const EdgeInsets.all(18),
                      child: Row(
                        children: [
                          Container(
                            padding: const EdgeInsets.all(12),
                            decoration: BoxDecoration(
                              color: AppColors.primaryBlueLight,
                              borderRadius: BorderRadius.circular(16),
                            ),
                            child: const Icon(
                              Icons.shield_outlined,
                              color: AppColors.primaryBlue,
                              size: 30,
                            ),
                          ),
                          const SizedBox(width: 14),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  'Halo, ${user?.name ?? '-'}',
                                  style: textTheme.titleMedium?.copyWith(
                                    fontWeight: FontWeight.w700,
                                    color: AppColors.ink900,
                                  ),
                                ),
                                const SizedBox(height: 4),
                                Text(
                                  'Peran: ${user?.role ?? '-'}',
                                  style: textTheme.bodyMedium?.copyWith(
                                    color: AppColors.slate600,
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                  const SizedBox(height: 16),
                  Card(
                    elevation: 0,
                    color: AppColors.white,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(20),
                      side: const BorderSide(color: AppColors.slate200),
                    ),
                    child: Padding(
                      padding: const EdgeInsets.all(16),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Layanan SIGAP',
                            style: textTheme.titleSmall?.copyWith(
                              fontWeight: FontWeight.w700,
                              color: AppColors.ink900,
                            ),
                          ),
                          const SizedBox(height: 8),
                          Text(
                            'Laporkan kerusakan infrastruktur publik dengan cepat dan pantau statusnya dari satu tempat.',
                            style: textTheme.bodyMedium?.copyWith(
                              color: AppColors.slate600,
                            ),
                          ),
                          const SizedBox(height: 14),
                          SizedBox(
                            width: double.infinity,
                            child: ElevatedButton.icon(
                              onPressed: () => Navigator.push(
                                context,
                                MaterialPageRoute(
                                  builder: (_) => const ReportListScreen(),
                                ),
                              ),
                              icon: const Icon(Icons.list_alt_rounded),
                              label: const Text('Lihat laporan saya'),
                              style: ElevatedButton.styleFrom(
                                backgroundColor: AppColors.primaryBlue,
                                foregroundColor: AppColors.white,
                                shape: RoundedRectangleBorder(
                                  borderRadius: BorderRadius.circular(14),
                                ),
                                padding: const EdgeInsets.symmetric(
                                  vertical: 14,
                                ),
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                  const SizedBox(height: 16),
                  FutureBuilder<List<AnnouncementModel>>(
                    future: AnnouncementService().fetchAnnouncements(),
                    builder: (context, snapshot) {
                      if (snapshot.connectionState == ConnectionState.waiting) {
                        return const Padding(
                          padding: EdgeInsets.symmetric(vertical: 8),
                          child: LinearProgressIndicator(),
                        );
                      }

                      if (snapshot.hasError) {
                        return Card(
                          elevation: 0,
                          color: AppColors.white,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(20),
                            side: const BorderSide(color: AppColors.slate200),
                          ),
                          child: Padding(
                            padding: const EdgeInsets.all(16),
                            child: Text(
                              'Gagal memuat pengumuman. ${snapshot.error}',
                              style: const TextStyle(
                                color: AppColors.dangerRed,
                              ),
                            ),
                          ),
                        );
                      }

                      if (!snapshot.hasData || snapshot.data!.isEmpty) {
                        return Card(
                          elevation: 0,
                          color: AppColors.white,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(20),
                            side: const BorderSide(color: AppColors.slate200),
                          ),
                          child: Padding(
                            padding: const EdgeInsets.all(16),
                            child: Text(
                              'Belum ada pengumuman saat ini.',
                              style: textTheme.bodyMedium?.copyWith(
                                color: AppColors.slate600,
                              ),
                            ),
                          ),
                        );
                      }

                      final announcements = snapshot.data!;
                      return Card(
                        elevation: 0,
                        color: AppColors.white,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(20),
                          side: const BorderSide(color: AppColors.slate200),
                        ),
                        child: Padding(
                          padding: const EdgeInsets.all(16),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                children: [
                                  Expanded(
                                    child: Text(
                                      'Pengumuman terbaru',
                                      style: textTheme.titleSmall?.copyWith(
                                        fontWeight: FontWeight.w700,
                                        color: AppColors.ink900,
                                      ),
                                    ),
                                  ),
                                  if (announcements.length > 3)
                                    Text(
                                      'Lihat semua',
                                      style: textTheme.bodySmall?.copyWith(
                                        color: AppColors.primaryBlue,
                                        fontWeight: FontWeight.w600,
                                      ),
                                    ),
                                ],
                              ),
                              const SizedBox(height: 16),
                              ...announcements.take(3).map((announcement) {
                                final createdAt = announcement.createdAt;
                                String formattedDate = 'Tanggal tidak tersedia';
                                if (createdAt != null && createdAt.isNotEmpty) {
                                  try {
                                    final dt = DateTime.parse(createdAt);
                                    const months = [
                                      '',
                                      'Jan',
                                      'Feb',
                                      'Mar',
                                      'Apr',
                                      'Mei',
                                      'Jun',
                                      'Jul',
                                      'Agu',
                                      'Sep',
                                      'Okt',
                                      'Nov',
                                      'Des',
                                    ];
                                    formattedDate =
                                        '${dt.day} ${months[dt.month]} ${dt.year}';
                                  } catch (_) {
                                    formattedDate = createdAt;
                                  }
                                }

                                return Padding(
                                  padding: const EdgeInsets.only(bottom: 16),
                                  child: Container(
                                    decoration: BoxDecoration(
                                      color: AppColors.slate100,
                                      borderRadius: BorderRadius.circular(16),
                                    ),
                                    padding: const EdgeInsets.all(14),
                                    child: Column(
                                      crossAxisAlignment:
                                          CrossAxisAlignment.start,
                                      children: [
                                        Row(
                                          crossAxisAlignment:
                                              CrossAxisAlignment.start,
                                          children: [
                                            Icon(
                                              announcement.isPinned
                                                  ? Icons.push_pin_rounded
                                                  : Icons.campaign_rounded,
                                              size: 18,
                                              color: announcement.isPinned
                                                  ? AppColors.urgentOrange
                                                  : AppColors.primaryBlue,
                                            ),
                                            const SizedBox(width: 10),
                                            Expanded(
                                              child: Text(
                                                announcement.title,
                                                style: textTheme.titleMedium
                                                    ?.copyWith(
                                                      fontWeight:
                                                          FontWeight.w700,
                                                      color: AppColors.ink900,
                                                    ),
                                              ),
                                            ),
                                          ],
                                        ),
                                        const SizedBox(height: 10),
                                        Text(
                                          announcement.content,
                                          style: textTheme.bodyMedium?.copyWith(
                                            color: AppColors.slate600,
                                            height: 1.6,
                                          ),
                                        ),
                                        const SizedBox(height: 12),
                                        Text(
                                          'Oleh ${announcement.createdBy ?? 'Admin SIGAP'} • $formattedDate',
                                          style: textTheme.bodySmall?.copyWith(
                                            color: AppColors.slate400,
                                          ),
                                        ),
                                      ],
                                    ),
                                  ),
                                );
                              }).toList(),
                            ],
                          ),
                        ),
                      );
                    },
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
