import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:provider/provider.dart';
import '../controllers/report_controller.dart';
import '../models/category_model.dart';
import '../utils/app_colors.dart';

// ======================================================
// Halaman formulir pembuatan laporan
// Memungkinkan pengguna membuat laporan baru dengan kategori, urgensi, lokasi, dan opsi foto.
// Dependency penting: `ReportController`, `ImagePicker`, `CategoryModel`.
// ======================================================
class ReportFormScreen extends StatefulWidget {
  const ReportFormScreen({super.key});
  @override
  State<ReportFormScreen> createState() => _ReportFormScreenState();
}

class _ReportFormScreenState extends State<ReportFormScreen> {
  final _titleCtrl = TextEditingController();
  final _descCtrl = TextEditingController();
  final _locationCtrl = TextEditingController();
  File? _selectedPhoto;
  CategoryModel? _selectedCategory;
  String _selectedUrgency = 'normal';
  bool _isSubmitting = false;

  @override
  void initState() {
    super.initState();
    Future.microtask(() => context.read<ReportController>().fetchCategories());
  }

  @override
  void dispose() {
    _titleCtrl.dispose();
    _descCtrl.dispose();
    _locationCtrl.dispose();
    super.dispose();
  }

  /// Mengambil foto baru menggunakan kamera perangkat.
  Future<void> _pickPhoto() async {
    final picker = ImagePicker();
    final picked = await picker.pickImage(
      source: ImageSource.camera,
      imageQuality: 70,
    );
    if (picked != null) {
      setState(() => _selectedPhoto = File(picked.path));
    }
  }

  /// Mengambil foto dari galeri perangkat.
  Future<void> _pickFromGallery() async {
    final picker = ImagePicker();
    final picked = await picker.pickImage(
      source: ImageSource.gallery,
      imageQuality: 70,
    );
    if (picked != null) {
      setState(() => _selectedPhoto = File(picked.path));
    }
  }

  /// Menangani validasi input dan pengiriman laporan baru.
  ///
  /// Memastikan semua field wajib terisi, lalu memanggil
  /// `ReportController.createReport` dan menampilkan notifikasi hasil.
  Future<void> _submit() async {
    if (_titleCtrl.text.isEmpty ||
        _descCtrl.text.isEmpty ||
        _locationCtrl.text.isEmpty ||
        _selectedCategory == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Mohon lengkapi semua field yang wajib'),
          backgroundColor: AppColors.dangerRed,
        ),
      );
      return;
    }

    setState(() => _isSubmitting = true);

    try {
      final ctrl = context.read<ReportController>();
      final success = await ctrl.createReport(
        title: _titleCtrl.text.trim(),
        description: _descCtrl.text.trim(),
        categoryId: _selectedCategory!.id,
        locationAddress: _locationCtrl.text.trim(),
        urgency: _selectedUrgency,
        photo: _selectedPhoto,
      );

      if (!mounted) return;

      if (success) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Laporan berhasil dikirim!'),
            backgroundColor: AppColors.successGreen,
          ),
        );
        Navigator.pop(context);
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(ctrl.errorMessage ?? 'Gagal mengirim laporan'),
            backgroundColor: AppColors.dangerRed,
          ),
        );
      }
    } finally {
      if (mounted) setState(() => _isSubmitting = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final ctrl = context.watch<ReportController>();
    final textTheme = Theme.of(context).textTheme;

    return Scaffold(
      backgroundColor: AppColors.slate100,
      appBar: AppBar(
        title: const Text('Buat Laporan'),
        centerTitle: true,
        backgroundColor: AppColors.primaryBlue,
        foregroundColor: AppColors.white,
        elevation: 0,
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.fromLTRB(20, 16, 20, 24),
          child: Center(
            child: ConstrainedBox(
              constraints: const BoxConstraints(maxWidth: 560),
              child: Card(
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
                        'Form pelaporan',
                        style: textTheme.titleMedium?.copyWith(
                          fontWeight: FontWeight.w700,
                          color: AppColors.ink900,
                        ),
                      ),
                      const SizedBox(height: 8),
                      Text(
                        'Isi rincian laporan agar petugas dapat menangani masalah dengan cepat.',
                        style: textTheme.bodyMedium?.copyWith(color: AppColors.slate600),
                      ),
                      const SizedBox(height: 18),
                      _label('Judul laporan *'),
                      TextField(
                        controller: _titleCtrl,
                        decoration: const InputDecoration(
                          hintText: 'Contoh: Jalan berlubang depan masjid',
                        ),
                      ),
                      const SizedBox(height: 14),
                      _label('Kategori *'),
                      if (ctrl.isLoadingCategories)
                        const Center(child: CircularProgressIndicator())
                      else if (ctrl.categoryError != null)
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              ctrl.categoryError!,
                              style: const TextStyle(color: AppColors.dangerRed),
                            ),
                            const SizedBox(height: 8),
                            OutlinedButton(
                              onPressed: () => context.read<ReportController>().fetchCategories(),
                              child: const Text('Coba lagi'),
                            ),
                          ],
                        )
                      else
                        DropdownButtonFormField<CategoryModel>(
                          value: _selectedCategory,
                          hint: const Text('Pilih kategori'),
                          decoration: const InputDecoration(),
                          items: ctrl.categories
                              .map((category) => DropdownMenuItem(value: category, child: Text(category.name)))
                              .toList(),
                          onChanged: (value) => setState(() => _selectedCategory = value),
                        ),
                      const SizedBox(height: 14),
                      _label('Urgensi *'),
                      DropdownButtonFormField<String>(
                        value: _selectedUrgency,
                        decoration: const InputDecoration(),
                        items: const [
                          DropdownMenuItem(value: 'normal', child: Text('Normal')),
                          DropdownMenuItem(value: 'penting', child: Text('Penting')),
                          DropdownMenuItem(value: 'darurat', child: Text('Darurat 🚨')),
                        ],
                        onChanged: (value) => setState(() => _selectedUrgency = value!),
                      ),
                      const SizedBox(height: 14),
                      _label('Deskripsi *'),
                      TextField(
                        controller: _descCtrl,
                        maxLines: 4,
                        decoration: const InputDecoration(
                          hintText: 'Jelaskan kerusakan atau gangguan secara detail...',
                        ),
                      ),
                      const SizedBox(height: 14),
                      _label('Alamat lokasi *'),
                      TextField(
                        controller: _locationCtrl,
                        decoration: const InputDecoration(
                          hintText: 'Contoh: Jl. Sudirman No. 12, Bandung',
                          prefixIcon: Icon(Icons.location_on_outlined),
                        ),
                      ),
                      const SizedBox(height: 14),
                      _label('Foto kerusakan (opsional)'),
                      Row(
                        children: [
                          Expanded(
                            child: OutlinedButton.icon(
                              onPressed: _pickPhoto,
                              icon: const Icon(Icons.camera_alt_outlined),
                              label: const Text('Kamera'),
                            ),
                          ),
                          const SizedBox(width: 8),
                          Expanded(
                            child: OutlinedButton.icon(
                              onPressed: _pickFromGallery,
                              icon: const Icon(Icons.photo_library_outlined),
                              label: const Text('Galeri'),
                            ),
                          ),
                        ],
                      ),
                      if (_selectedPhoto != null) ...[
                        const SizedBox(height: 10),
                        Stack(
                          children: [
                            ClipRRect(
                              borderRadius: BorderRadius.circular(12),
                              child: Image.file(
                                _selectedPhoto!,
                                height: 150,
                                width: double.infinity,
                                fit: BoxFit.cover,
                              ),
                            ),
                            Positioned(
                              top: 8,
                              right: 8,
                              child: GestureDetector(
                                onTap: () => setState(() => _selectedPhoto = null),
                                child: Container(
                                  decoration: const BoxDecoration(
                                    color: AppColors.dangerRed,
                                    shape: BoxShape.circle,
                                  ),
                                  padding: const EdgeInsets.all(4),
                                  child: const Icon(
                                    Icons.close,
                                    color: AppColors.white,
                                    size: 18,
                                  ),
                                ),
                              ),
                            ),
                          ],
                        ),
                      ],
                      const SizedBox(height: 20),
                      SizedBox(
                        width: double.infinity,
                        height: 50,
                        child: ElevatedButton(
                          onPressed: _isSubmitting ? null : _submit,
                          style: ElevatedButton.styleFrom(
                            backgroundColor: AppColors.primaryBlue,
                            foregroundColor: AppColors.white,
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(14),
                            ),
                          ),
                          child: _isSubmitting
                              ? const SizedBox(
                                  width: 22,
                                  height: 22,
                                  child: CircularProgressIndicator(
                                    strokeWidth: 2.2,
                                    color: AppColors.white,
                                  ),
                                )
                              : const Text(
                                  'Kirim Laporan',
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

  Widget _label(String text) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 6),
      child: Text(
        text,
        style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 14),
      ),
    );
  }
}
