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

php xion migrate

---
**Reset & Migrasi Ulang**

php xion migrate:fresh

---

**Seeder
Buat Seeder**
php xion make:seed users

**Jalankan Seeder**

php xion seed

---
**Controller
Buat Controller Baru**

php xion make:controller Controller

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
php xion make:table users
php xion make:relasi users to order

## Instalasi


```bash
git clone https://github.com/aditiyasubakti/xion.git
cd xion
```
---
<img width="1911" height="886" alt="Screenshot 2025-11-17 001149" src="https://github.com/user-attachments/assets/0ec046f1-a367-4ac2-9801-36e2677a2ddd" />
