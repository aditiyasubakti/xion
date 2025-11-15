# Xion Framework
<img width="500" height="500" alt="favicon" src="https://github.com/user-attachments/assets/f0b39f25-ec1a-4561-b3c7-5423b0ce476d" />


Xion adalah **PHP micro-framework** ringan yang dirancang untuk membuat aplikasi web dengan cepat dan mudah. Framework ini mendukung **routing**, **controller**, **middleware**, **database migration & seeding**, serta **MVC sederhana**.

---

## Fitur Utama

- **Routing**: Mendukung `GET`, `POST`, route parameter, dan route group.
- **Controller**: Mudah membuat controller dengan CLI.
- **View**: Integrasi sederhana dengan template PHP.
- **Middleware**: Registrasi middleware untuk route tertentu.
- **Database**: Mendukung MySQL & SQLite.
- **Migration & Seeder**: Buat tabel dan data awal dengan CLI.
- **CLI**: Perintah untuk mengelola server, migration, seeder, controller, dan konfigurasi.

---
## Database

Xion mendukung **MySQL** dan **SQLite**.  
Konfigurasi database ada di file `.env`.

---
## Migration
**Migrasi Semua Tabel**
php blueprint migrate
---
**Reset & Migrasi Ulang**
php blueprint migrate:fresh
--
**Seeder
Buat Seeder**
php blueprint make:seed users

**Jalankan Seeder**
php blueprint seed

---
**Controller
Buat Controller Baru**
php blueprint make:controller Controller
---
| Fungsi                   | Keterangan                            |
| ------------------------ | ------------------------------------- |
| `all()`                  | Ambil semua data dari tabel           |
| `find($id)`              | Ambil data berdasarkan ID             |
| `where($column, $value)` | Ambil data berdasarkan kolom tertentu |
| `insert($data)`          | Tambah data baru                      |
| `updateData($id, $data)` | Update data berdasarkan ID            |
| `delete($id)`            | Hapus data berdasarkan ID             |

### Buat Tabel
```bash
php blueprint make:table users

## Instalasi


```bash
git clone https://github.com/aditiyasubakti/xion.git
cd xion
