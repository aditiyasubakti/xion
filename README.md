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

## Instalasi

1. Clone repository ini:
Database

Xion mendukung MySQL dan SQLite. Konfigurasi ada di .env.

Migration

Buat tabel:

php blueprint make:table users


Migrasi semua tabel:

php blueprint migrate


Reset tabel:

php blueprint migrate:fresh

Seeder

Buat seeder:

php blueprint make:seed users


Jalankan seeder:

php blueprint seed

Controller

Buat controller baru:

php blueprint make:controller UserController


Contoh controller:

namespace App\Http\Controllers;

use App\Models\Users;
use View;

class UserController
{
    public function index()
    {
        $users = (new Users())->all();
        View::make('about', ['title' => 'About Page', 'users' => $users]);
    }
}

Model

Xion menyediakan model sederhana:

namespace App\Models;

use App\Core\Model;

class Users extends Model
{
    protected $table = "users";
}


Fungsi model:

all() → ambil semua data

find($id) → ambil data berdasarkan ID

where($column, $value) → ambil data berdasarkan kolom tertentu

insert($data) → tambah data

updateData($id, $data) → update data

delete($id) → hapus data

```bash
git clone https://github.com/aditiyasubakti/xion.git
cd xion
