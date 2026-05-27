# Sistem Informasi Inventaris Laboratorium Informatika (SILO LAB)

Sistem Informasi Inventaris Laboratorium Informatika adalah sebuah web application sederhana berbasis Laravel 12 yang dibuat untuk memenuhi tugas praktikum pemrograman web. Aplikasi ini berfokus pada manajemen Data Barang Laboratorium Informatika dengan antarmuka yang bersih, minimalis, dan menggunakan Pico.css untuk styling yang optimal.

## Prasyarat (Prerequisites)
Pastikan sistem Anda telah memiliki hal-hal berikut sebelum memulai instalasi:
- PHP 8.2 atau lebih baru
- Composer (Package Manager untuk PHP)
- Node.js & NPM (Opsional untuk frontend asset bundling)
- Ekstensi PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML (Standar bawaan instalasi PHP modern)

## Panduan Instalasi (Installation Guide)

Ikuti langkah-langkah di bawah ini untuk menjalankan project ini di komputer lokal Anda:

1. **Clone/Download Repository ini** (atau ekstrak zip tugas jika diberikan dalam bentuk zip).

2. **Buka Terminal/Command Prompt** arahkan ke direktori project ini:
   ```bash
   cd silo-lab
   ```

3. **Install Dependensi PHP dengan Composer**:
   ```bash
   composer install
   ```

4. **Persiapan Environment**:
   Salin file konfigurasi environment dari template yang disediakan:
   ```bash
   copy .env.example .env
   ```
   *Atau gunakan perintah `cp .env.example .env` di Unix/Mac.*

5. **Generate Application Key**:
   Jalankan perintah ini untuk mengatur kunci enkripsi aplikasi:
   ```bash
   php artisan key:generate
   ```

6. **Migrasi Database**:
   Aplikasi ini menggunakan SQLite secara default untuk memudahkan pengaturan (tidak perlu config server MySQL eksternal). Jalankan perintah berikut untuk membuat tabel database `laboratory_items`:
   ```bash
   php artisan migrate
   ```

7. **Jalankan Development Server**:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui browser di alamat: `http://localhost:8000`.

## Penjelasan Implementasi MVC (Model-View-Controller)

Project ini dibangun dengan menerapkan arsitektur MVC secara disiplin sesuai dengan kaidah framework Laravel:

- **Model (`app/Models/LaboratoryItem.php`)**: Bertanggung jawab atas pengelolaan logika data dan representasi tabel `laboratory_items` dalam database. Model ini telah dilengkapi dengan `$fillable` (Mass Assignment Protection) untuk mengamankan data yang masuk ke database.

- **Controller (`app/Http/Controllers/LaboratoryItemController.php`)**: Bertindak sebagai penghubung (middleman) antara Route, Model, dan View. Controller ini menerapkan *Resourceful Routing* dengan berbagai *action method* untuk operasi CRUD (Create, Read, Update, Delete) serta memvalidasi data form sebelum diteruskan ke model.

- **View (`resources/views/items/...`)**: Bertanggung jawab menampilkan data (Presentation Layer) menggunakan templating engine *Blade*. Terdapat sebuah *layout utama* di `layouts/app.blade.php` yang mengatur kerangka standar HTML dan meng-include Pico.css, sementara view spesifik seperti `index`, `create`, dan `edit` mewarisi (*extend*) layout tersebut dan hanya meng-inject konten dinamisnya.

Terima kasih.
