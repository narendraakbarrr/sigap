## API Endpoints

### Auth

| Method | Endpoint           | Deskripsi        | Auth |
| ------ | ------------------ | ---------------- | ---- |
| POST   | `/api/v1/login`    | Login user/admin | ❌   |
| POST   | `/api/v1/register` | Registrasi warga | ❌   |
| POST   | `/api/v1/logout`   | Logout           | ✅   |

### Profile

| Method | Endpoint          | Deskripsi          | Auth |
| ------ | ----------------- | ------------------ | ---- |
| GET    | `/api/v1/profile` | Lihat profil       | ✅   |
| PUT    | `/api/v1/profile` | Update nama profil | ✅   |
| GET    | `/api/v1/me`      | Data user login    | ✅   |

### Reports

| Method | Endpoint                      | Deskripsi         | Auth | Role         |
| ------ | ----------------------------- | ----------------- | ---- | ------------ |
| GET    | `/api/v1/reports`             | List laporan      | ✅   | Semua        |
| POST   | `/api/v1/reports`             | Buat laporan baru | ✅   | User         |
| GET    | `/api/v1/reports/{id}`        | Detail laporan    | ✅   | Semua        |
| PUT    | `/api/v1/reports/{id}`        | Edit laporan      | ✅   | User (owner) |
| DELETE | `/api/v1/reports/{id}`        | Hapus laporan     | ✅   | User (owner) |
| PUT    | `/api/v1/reports/{id}/status` | Update status     | ✅   | Admin        |

### Categories

| Method | Endpoint             | Deskripsi     | Auth |
| ------ | -------------------- | ------------- | ---- |
| GET    | `/api/v1/categories` | List kategori | ✅   |

### Contoh Request Body

**Login:**

```json
{
  "email": "admin@sigap.com",
  "password": "password"
}
```

**Buat Laporan (multipart/form-data):**
title: Jalan berlubang
description: Terdapat lubang besar di tengah jalan
category_id: 1
location_address: Jl. Sudirman No. 45
urgency: darurat
photo: [file gambar, opsional]

**Update Status (Admin):**

```json
{
  "status": "in_progress",
  "notes": "Sedang ditangani",
  "task_description": "Tim Dinas PU sedang melakukan perbaikan"
}
```
