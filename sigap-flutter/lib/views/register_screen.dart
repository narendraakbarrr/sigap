import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../controllers/auth_controller.dart';
import '../services/api_service.dart';
import '../models/user_model.dart';
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
    return Scaffold(
      appBar: AppBar(title: const Text('Daftar Akun')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Column(children: [
          TextField(controller: _nameCtrl,
              decoration: const InputDecoration(
                  labelText: 'Nama Lengkap',
                  border: OutlineInputBorder())),
          const SizedBox(height: 16),
          TextField(controller: _emailCtrl,
              keyboardType: TextInputType.emailAddress,
              decoration: const InputDecoration(
                  labelText: 'Email', border: OutlineInputBorder())),
          const SizedBox(height: 16),
          TextField(controller: _passwordCtrl,
              obscureText: true,
              decoration: const InputDecoration(
                  labelText: 'Password', border: OutlineInputBorder())),
          const SizedBox(height: 24),
          SizedBox(
            width: double.infinity, height: 48,
            child: ElevatedButton(
              onPressed: _isLoading ? null : _handleRegister,
              style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.deepOrange),
              child: _isLoading
                  ? const CircularProgressIndicator(color: Colors.white)
                  : const Text('Daftar',
                      style: TextStyle(color: Colors.white)),
            ),
          ),
        ]),
      ),
    );
  }
}