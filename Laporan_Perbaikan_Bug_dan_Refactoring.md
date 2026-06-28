# Laporan Perbaikan Bug dan Refactoring

## AI Usage Log
- **AI Tool yang Digunakan:** Gemini 3.1 Pro (High)
- **Tujuan Penggunaan:** 
  - Menganalisis kode proyek untuk menemukan celah bug atau masalah logika.
  - Membantu melakukan refactoring kode yang redundan.
  - Membantu menyusun dokumentasi laporan perbaikan dan modifikasi kode yang dibutuhkan.
- **Prompt/Interaksi Utama:** Menganalisis model, controller, dan form views terkait `LaboratoryItem`. AI diperintahkan untuk mengidentifikasi setidaknya 3 bugs, menerapkan setidaknya 2 refactoring (validasi dan duplikasi data), dan merangkum hasilnya ke dalam format log ini.

## Bug Fix Log

### 1. Pencarian yang Tidak Efisien (Logic Flaw)
- **Masalah:** Fungsi pencarian pada halaman index menggunakan `$request->has('search')`. Ini bermasalah karena jika form pencarian di-submit dalam keadaan kosong (menghasilkan URL `?search=`), kondisi tersebut tetap bernilai `true` meskipun valuenya kosong, menyebabkan query dijalankan dengan pencarian `LIKE '%%'`.
- **Sebelum Perbaikan:** `if ($request->has('search')) { ... }`
- **Setelah Perbaikan:** `if ($request->filled('search')) { ... }`

### 2. Kurangnya Pagination pada Halaman Index (Performance Issue)
- **Masalah:** Menggunakan metode `->get()` pada query index akan memuat seluruh data dari tabel ke dalam memori. Jika aplikasi memiliki ribuan data barang, ini akan memakan banyak memori dan memperlambat load halaman.
- **Sebelum Perbaikan:** `$items = $query->orderBy('created_at', 'desc')->get();`
- **Setelah Perbaikan:** Diganti dengan metode `->paginate(10);` di Controller, dan menambahkan tombol navigasi halaman `{{ $items->withQueryString()->links() }}` pada file view `index.blade.php`.

### 3. Tidak Ada Batas Maksimal Kuantitas (Integer Overflow Vulnerability)
- **Masalah:** Validasi Laravel hanya memastikan input kuantitas berupa angka bulat minimum 1, tetapi tidak membatasi ukuran maksimalnya. Tabel database menggunakan tipe kolom `integer` (maksimal 2.147.483.647). Jika pengguna memasukkan nilai melebihi batas tersebut, aplikasi akan error karena SQL Exception (`SQLSTATE[22003]: Numeric value out of range`).
- **Sebelum Perbaikan:** `'quantity' => 'required|integer|min:1'`
- **Setelah Perbaikan:** Diberikan batas batas maksimal sesuai standar INT 32-bit: `'quantity' => 'required|integer|min:1|max:2147483647'` dan atribut HTML form juga diperbarui dengan `max="2147483647"`.

### 4. Bug Opsi Default Form Edit (Missing Default Option Condition)
- **Masalah:** Pada form pengubahan data (Edit), elemen `<option value="" disabled>` untuk "Kategori" tidak diberikan pengecekan validasi jika opsi lama gagal termuat.
- **Sebelum Perbaikan:** `<option value="" disabled>Pilih Kategori...</option>`
- **Setelah Perbaikan:** `<option value="" disabled {{ old('category', $item->category) ? '' : 'selected' }}>Pilih Kategori...</option>`

## Refactoring Log

### 1. Ekstraksi Logika Validasi (Menghindari Duplikasi Kode)
- **Masalah:** Array aturan validasi field secara identik diketik ulang di dalam fungsi `store()` dan `update()`, menyebabkan duplikasi kode yang memperpanjang Controller.
- **Perbaikan:** Menambahkan method private `validateItem(Request $request, $id = null)` pada `LaboratoryItemController`. Fungsi ini merepresentasikan DRY (Don't Repeat Yourself) dan secara otomatis menyesuaikan syarat *unique* untuk `item_code` pada saat proses *update* berjalan. Fungsi `store()` dan `update()` kini menjadi lebih ramping dan bersih (hanya memanggil method tersebut).

### 2. Penggunaan Konstanta Enum untuk Menghapus Magic Strings
- **Masalah:** Opsi kategori (`Komputer`, `Laptop`, dll) dan status (`Baru`, `Digunakan`, `Rusak`) di hard-code di 5 titik (2 kali pada validasi controller, dan masing-masing di form `create` dan `edit`). Hal ini sangat tidak disarankan karena sangat rawan *typo*.
- **Perbaikan:** Dideklarasikan konstanta array publik (`CATEGORIES` dan `STATUSES`) pada model `LaboratoryItem`. Di Controller, validasi `in:` sekarang menggunakan fungsi `implode(',', LaboratoryItem::CATEGORIES)`. Di View (`create.blade.php` dan `edit.blade.php`), semua opsi select digenerate secara otomatis (dinamis) melalui perulangan `@foreach(\App\Models\LaboratoryItem::CATEGORIES as $cat)`. Hal ini membuat opsi terpusat pada satu file Model saja, sehingga jika ada penambahan kategori baru di masa mendatang, developer cukup memperbarui array konstanta pada Model.
