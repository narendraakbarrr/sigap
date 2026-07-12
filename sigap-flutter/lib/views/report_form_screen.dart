import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:provider/provider.dart';
import '../controllers/report_controller.dart';
import '../models/category_model.dart';

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

  Future<void> _submit() async {
    if (_titleCtrl.text.isEmpty ||
        _descCtrl.text.isEmpty ||
        _locationCtrl.text.isEmpty ||
        _selectedCategory == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Mohon lengkapi semua field yang wajib'),
          backgroundColor: Colors.red,
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
            backgroundColor: Colors.green,
          ),
        );
        Navigator.pop(context);
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(ctrl.errorMessage ?? 'Gagal mengirim laporan'),
            backgroundColor: Colors.red,
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

    return Scaffold(
      appBar: AppBar(
        title: const Text('Buat Laporan'),
        backgroundColor: Colors.deepOrange,
        foregroundColor: Colors.white,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Judul
            _label('Judul Laporan *'),
            TextField(
              controller: _titleCtrl,
              decoration: const InputDecoration(
                hintText: 'Contoh: Jalan berlubang depan masjid',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 16),

            // Kategori
            _label('Kategori *'),
            if (ctrl.isLoadingCategories)
              const Center(child: CircularProgressIndicator())
            else if (ctrl.categoryError != null)
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    ctrl.categoryError!,
                    style: const TextStyle(color: Colors.red),
                  ),
                  const SizedBox(height: 8),
                  OutlinedButton(
                    onPressed: () =>
                        context.read<ReportController>().fetchCategories(),
                    child: const Text('Coba lagi'),
                  ),
                ],
              )
            else
              DropdownButtonFormField<CategoryModel>(
                value: _selectedCategory,
                hint: const Text('Pilih kategori'),
                decoration: const InputDecoration(border: OutlineInputBorder()),
                items: ctrl.categories
                    .map((c) => DropdownMenuItem(value: c, child: Text(c.name)))
                    .toList(),
                onChanged: (val) => setState(() => _selectedCategory = val),
              ),
            const SizedBox(height: 16),

            _label('Urgensi *'),
            DropdownButtonFormField<String>(
              value: _selectedUrgency,
              decoration: const InputDecoration(border: OutlineInputBorder()),
              items: const [
                DropdownMenuItem(value: 'normal', child: Text('Normal')),
                DropdownMenuItem(value: 'penting', child: Text('Penting')),
                DropdownMenuItem(value: 'darurat', child: Text('Darurat 🚨')),
              ],
              onChanged: (val) => setState(() => _selectedUrgency = val!),
            ),
            const SizedBox(height: 16),

            // Deskripsi
            _label('Deskripsi *'),
            TextField(
              controller: _descCtrl,
              maxLines: 4,
              decoration: const InputDecoration(
                hintText: 'Jelaskan kerusakan/gangguan secara detail...',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 16),

            // Lokasi
            _label('Alamat Lokasi *'),
            TextField(
              controller: _locationCtrl,
              decoration: const InputDecoration(
                hintText: 'Contoh: Jl. Sudirman No. 12, Bandung',
                border: OutlineInputBorder(),
                prefixIcon: Icon(Icons.location_on_outlined),
              ),
            ),
            const SizedBox(height: 16),

            // Foto
            _label('Foto Kerusakan (opsional)'),
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
              const SizedBox(height: 8),
              Stack(
                children: [
                  ClipRRect(
                    borderRadius: BorderRadius.circular(8),
                    child: Image.file(
                      _selectedPhoto!,
                      height: 150,
                      width: double.infinity,
                      fit: BoxFit.cover,
                    ),
                  ),
                  Positioned(
                    top: 4,
                    right: 4,
                    child: GestureDetector(
                      onTap: () => setState(() => _selectedPhoto = null),
                      child: Container(
                        decoration: const BoxDecoration(
                          color: Colors.red,
                          shape: BoxShape.circle,
                        ),
                        child: const Icon(
                          Icons.close,
                          color: Colors.white,
                          size: 20,
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ],
            const SizedBox(height: 24),

            // Submit
            SizedBox(
              width: double.infinity,
              height: 48,
              child: ElevatedButton(
                onPressed: _isSubmitting ? null : _submit,
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.deepOrange,
                ),
                child: _isSubmitting
                    ? const CircularProgressIndicator(color: Colors.white)
                    : const Text(
                        'Kirim Laporan',
                        style: TextStyle(color: Colors.white, fontSize: 16),
                      ),
              ),
            ),
            const SizedBox(height: 16),
          ],
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
