import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../controllers/auth_controller.dart';
import '../models/user_model.dart';
import '../services/api_service.dart';
import '../utils/app_colors.dart';
import 'dashboard_screen.dart';

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});
  @override
  State<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  final _nameCtrl     = TextEditingController();
  final _emailCtrl    = TextEditingController();
  final _passwordCtrl = TextEditingController();
  bool _isLoading = false;

  // Menangani proses registrasi pengguna baru
  Future<void> _handleRegister() async {
    setState(() => _isLoading = true);
    try {
      final api  = ApiService();
      final data = await api.register(
        _nameCtrl.text.trim(),
        _emailCtrl.text.trim(),
        _passwordCtrl.text,
      );
      if (!mounted) return;
      if (data['token'] != null) {
        await api.saveToken(data['token']);
        final auth = context.read<AuthController>();
        auth.currentUser = UserModel.fromJson(data['user']);
        auth.notifyListeners();
        Navigator.pushAndRemoveUntil(context,
            MaterialPageRoute(builder: (_) => const DashboardScreen()),
            (r) => false);
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text(data['message'] ?? 'Registrasi gagal')));
      }
    } catch (_) {
      ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Tidak dapat terhubung ke server')));
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final textTheme = Theme.of(context).textTheme;

    return Scaffold(
      backgroundColor: AppColors.slate100,
      appBar: AppBar(
        title: const Text('Daftar Akun'),
        centerTitle: true,
        backgroundColor: AppColors.primaryBlue,
        foregroundColor: AppColors.white,
        elevation: 0,
      ),
      body: SafeArea(
        child: Center(
          child: ConstrainedBox(
            constraints: const BoxConstraints(maxWidth: 430),
            child: SingleChildScrollView(
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 24),
              child: Card(
                elevation: 0,
                color: AppColors.white,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(24),
                  side: const BorderSide(color: AppColors.slate200),
                ),
                child: Padding(
                  padding: const EdgeInsets.all(24),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Container(
                        padding: const EdgeInsets.all(14),
                        decoration: BoxDecoration(
                          color: AppColors.primaryBlueLight,
                          borderRadius: BorderRadius.circular(18),
                        ),
                        child: const Icon(
                          Icons.person_add_alt_rounded,
                          color: AppColors.primaryBlueDark,
                          size: 34,
                        ),
                      ),
                      const SizedBox(height: 16),
                      Text(
                        'Buat akun SIGAP',
                        style: textTheme.headlineSmall?.copyWith(
                          fontWeight: FontWeight.w700,
                          color: AppColors.ink900,
                        ),
                      ),
                      const SizedBox(height: 8),
                      Text(
                        'Daftarkan diri Anda untuk mulai melaporkan masalah publik secara cepat.',
                        style: textTheme.bodyMedium?.copyWith(
                          color: AppColors.slate600,
                          height: 1.45,
                        ),
                      ),
                      const SizedBox(height: 22),
                      TextField(
                        controller: _nameCtrl,
                        decoration: const InputDecoration(
                          labelText: 'Nama Lengkap',
                          prefixIcon: Icon(Icons.person_outline),
                        ),
                      ),
                      const SizedBox(height: 14),
                      TextField(
                        controller: _emailCtrl,
                        keyboardType: TextInputType.emailAddress,
                        decoration: const InputDecoration(
                          labelText: 'Email',
                          prefixIcon: Icon(Icons.email_outlined),
                        ),
                      ),
                      const SizedBox(height: 14),
                      TextField(
                        controller: _passwordCtrl,
                        obscureText: true,
                        decoration: const InputDecoration(
                          labelText: 'Password',
                          prefixIcon: Icon(Icons.lock_outline),
                        ),
                      ),
                      const SizedBox(height: 20),
                      SizedBox(
                        width: double.infinity,
                        height: 50,
                        child: ElevatedButton(
                          onPressed: _isLoading ? null : _handleRegister,
                          style: ElevatedButton.styleFrom(
                            backgroundColor: AppColors.primaryBlue,
                            foregroundColor: AppColors.white,
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(14),
                            ),
                          ),
                          child: _isLoading
                              ? const SizedBox(
                                  width: 22,
                                  height: 22,
                                  child: CircularProgressIndicator(
                                    strokeWidth: 2.2,
                                    color: AppColors.white,
                                  ),
                                )
                              : const Text(
                                  'Daftar',
                                  style: TextStyle(fontWeight: FontWeight.w600),
                                ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }
}