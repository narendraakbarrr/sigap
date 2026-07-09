import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../controllers/report_controller.dart';
import '../models/report_model.dart';
import '../models/category_model.dart';

class ReportEditScreen extends StatefulWidget {
  final ReportModel report;
  const ReportEditScreen({super.key, required this.report});

  @override
  State<ReportEditScreen> createState() => _ReportEditScreenState();
}

class _ReportEditScreenState extends State<ReportEditScreen> {
  late TextEditingController _titleCtrl;
  late TextEditingController _descCtrl;
  late TextEditingController _locationCtrl;
  CategoryModel? _selectedCategory;
  bool _isSubmitting = false;

  @override
  void initState() {
    super.initState();
    _titleCtrl    = TextEditingController(text: widget.report.title);
    _descCtrl     = TextEditingController(text: widget.report.description);
    _locationCtrl = TextEditingController(text: widget.report.locationAddress);

    // Fetch kategori & set kategori awal
    Future.microtask(() async {
      final ctrl = context.read<ReportController>();
      await ctrl.fetchCategories();

      // Set kategori yang sudah dipilih sebelumnya
      if (mounted && ctrl.categories.isNotEmpty) {
        setState(() {
          _selectedCategory = ctrl.categories.firstWhere(
            (c) => c.name == widget.report.categoryName,
            orElse: () => ctrl.categories.first,
          );
        });
      }
    });
  }

  @override
  void dispose() {
    _titleCtrl.dispose();
    _descCtrl.dispose();
    _locationCtrl.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (_titleCtrl.text.isEmpty ||
        _descCtrl.text.isEmpty ||
        _locationCtrl.text.isEmpty ||
        _selectedCategory == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Mohon lengkapi semua field'),
          backgroundColor: Colors.red,
        ),
      );
      return;
    }

    setState(() => _isSubmitting = true);

    try {
      final ctrl    = context.read<ReportController>();
      final success = await ctrl.updateReport(
        id:              widget.report.id,
        title:           _titleCtrl.text.trim(),
        description:     _descCtrl.text.trim(),
        categoryId:      _selectedCategory!.id,
        locationAddress: _locationCtrl.text.trim(),
      );

      if (!mounted) return;

      if (success) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Laporan berhasil diperbarui'),
            backgroundColor: Colors.green,
          ),
        );
        Navigator.pop(context, true); // true = ada perubahan
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(ctrl.errorMessage ?? 'Gagal memperbarui laporan'),
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
        title: const Text('Edit Laporan'),
        backgroundColor: Colors.deepOrange,
        foregroundColor: Colors.white,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [

            // Info: hanya bisa edit jika status diterima
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: Colors.blue.shade50,
                borderRadius: BorderRadius.circular(8),
                border: Border.all(color: Colors.blue.shade200),
              ),
              child: Row(children: [
                Icon(Icons.info_outline,
                    color: Colors.blue.shade700, size: 16),
                const SizedBox(width: 8),
                Expanded(
                  child: Text(
                    'Laporan hanya dapat diedit selama berstatus "Diterima"',
                    style: TextStyle(
                        fontSize: 12, color: Colors.blue.shade700),
                  ),
                ),
              ]),
            ),
            const SizedBox(height: 20),

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
                  Text(ctrl.categoryError!,
                      style: const TextStyle(color: Colors.red)),
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
                    decoration: const InputDecoration(
                        border: OutlineInputBorder()),
                    items: ctrl.categories
                        .map((c) => DropdownMenuItem(
                              value: c,
                              child: Text(c.name),
                            ))
                        .toList(),
                    onChanged: (val) =>
                        setState(() => _selectedCategory = val),
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
                border: OutlineInputBorder(),
                prefixIcon: Icon(Icons.location_on_outlined),
              ),
            ),
            const SizedBox(height: 24),

            // Tombol simpan
            SizedBox(
              width: double.infinity,
              height: 48,
              child: ElevatedButton(
                onPressed: _isSubmitting ? null : _submit,
                style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.deepOrange),
                child: _isSubmitting
                    ? const CircularProgressIndicator(
                        color: Colors.white)
                    : const Text('Simpan Perubahan',
                        style: TextStyle(
                            color: Colors.white, fontSize: 16)),
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
      child: Text(text,
          style: const TextStyle(
              fontWeight: FontWeight.w600, fontSize: 14)),
    );
  }
}