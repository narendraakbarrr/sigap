import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'controllers/auth_controller.dart';
import 'views/login_screen.dart';
import 'views/dashboard_screen.dart';
import 'controllers/report_controller.dart';
import 'utils/app_theme.dart';

// ======================================================
// Entry point aplikasi Flutter SIGAP
// Menginisialisasi provider global untuk autentikasi dan laporan,
// lalu menjalankan widget root `SigapApp`.
// ======================================================
void main() {
  runApp(
    MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthController()),
        ChangeNotifierProvider(create: (_) => ReportController()),
      ],
      child: const SigapApp(),
    ),
  );
}

// ======================================================
// Aplikasi Root SIGAP
// Menyediakan tema global dan halaman splash awal.
// Digunakan sebagai widget utama pada `runApp`.
// Dependency penting: `AppTheme`, `SplashScreen`.
// ======================================================
class SigapApp extends StatelessWidget {
  const SigapApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'SIGAP',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.light,
      home: const SplashScreen(),
    );
  }
}

class SplashScreen extends StatefulWidget {
  const SplashScreen({super.key});
  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

// ======================================================
// Splash screen awal
// Memeriksa sesi pengguna yang tersimpan dan mengarahkan ke
// halaman dashboard atau login sesuai status autentikasi.
// ======================================================
class _SplashScreenState extends State<SplashScreen> {
  @override
  void initState() {
    super.initState();
    _checkSession();
  }

  /// Memverifikasi session yang tersimpan melalui `AuthController`.
  ///
  /// Jika token valid, navigasi beralih ke `DashboardScreen`.
  /// Jika tidak, pengguna diarahkan ke `LoginScreen`.
  Future<void> _checkSession() async {
    final auth    = context.read<AuthController>();
    final isLogin = await auth.checkSession();
    if (!mounted) return;
    Navigator.pushReplacement(context, MaterialPageRoute(
      builder: (_) => isLogin ? const DashboardScreen() : const LoginScreen(),
    ));
  }

  @override
  Widget build(BuildContext context) {
    return const Scaffold(
      body: Center(child: CircularProgressIndicator()),
    );
  }
}