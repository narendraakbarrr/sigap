<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\ReportCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserReportWebTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'user']);
        Role::firstOrCreate(['name' => 'admin']);
    }

    public function test_warga_can_create_update_and_delete_own_report(): void
    {
        $user = User::create([
            'name' => 'Warga Test',
            'email' => 'warga@example.com',
            'password' => 'password123',
        ]);
        $user->assignRole('user');

        $category = ReportCategory::create([
            'name' => 'Jalan',
            'icon' => 'road',
            'description' => 'Kategori jalan',
        ]);

        Storage::fake('public');

        $response = $this->actingAs($user)->post(route('user.reports.store'), [
            'title' => 'Jalan berlubang di depan rumah',
            'description' => 'Deskripsi laporan yang cukup panjang untuk validasi',
            'category_id' => $category->id,
            'location_address' => 'Jl. Sudirman No. 12',
            'urgency' => 'normal',
            'photo' => UploadedFile::fake()->image('report.jpg', 600, 400),
        ]);

        $response->assertRedirect(route('user.reports.index'));
        $response->assertSessionHas('success', 'Laporan berhasil dikirim!');

        $report = Report::where('user_id', $user->id)->latest()->first();
        $this->assertNotNull($report);
        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'user_id' => $user->id,
            'status' => Report::STATUS_DITERIMA,
        ]);
        Storage::disk('public')->assertExists($report->photo_path);

        $response = $this->actingAs($user)->put(route('user.reports.update', $report), [
            'title' => 'Judul laporan diperbarui',
            'description' => 'Deskripsi laporan diperbarui supaya tetap valid',
            'category_id' => $category->id,
            'location_address' => 'Jl. Asia Afrika No. 10',
            'urgency' => 'penting',
        ]);

        $response->assertRedirect(route('user.reports.show', $report));
        $response->assertSessionHas('success', 'Laporan berhasil diperbarui');

        $report->refresh();
        $this->assertSame('Judul laporan diperbarui', $report->title);
        $this->assertSame('penting', $report->urgency);

        $response = $this->actingAs($user)->delete(route('user.reports.destroy', $report));
        $response->assertRedirect(route('user.reports.index'));
        $response->assertSessionHas('success', 'Laporan berhasil dihapus');
        $this->assertSoftDeleted($report);
    }

    public function test_warga_cannot_access_report_milik_user_lain(): void
    {
        $user = User::create([
            'name' => 'Warga A',
            'email' => 'warga-a@example.com',
            'password' => 'password123',
        ]);
        $user->assignRole('user');

        $otherUser = User::create([
            'name' => 'Warga B',
            'email' => 'warga-b@example.com',
            'password' => 'password123',
        ]);
        $otherUser->assignRole('user');

        $category = ReportCategory::create([
            'name' => 'Air',
            'icon' => 'water',
            'description' => 'Kategori air',
        ]);

        $report = Report::create([
            'user_id' => $otherUser->id,
            'category_id' => $category->id,
            'title' => 'Laporan orang lain',
            'description' => 'Deskripsi laporan orang lain yang panjang',
            'location_address' => 'Jl. Orang Lain',
            'status' => Report::STATUS_DITERIMA,
            'urgency' => Report::URGENCY_NORMAL,
        ]);

        $response = $this->actingAs($user)->get(route('user.reports.show', $report));
        $response->assertStatus(403);
    }
}
