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

## Screenshot isi proyek
<img width="2559" height="1599" alt="image" src="https://github.com/user-attachments/assets/0e56978d-fc7f-47ad-bc1b-289a5d88d61d" />

<img width="2559" height="1599" alt="image" src="https://github.com/user-attachments/assets/29c93898-131c-4f0f-b7bd-6914c93fd254" />

# Laporan Perbaikan Bug dan Refactoring

## AI Usage Log
- **AI Tool yang Digunakan:** Gemini 3.1 Pro (High) & Claude Opus 4.6 (Thinking)
- **Tujuan Penggunaan:** 
  - Menganalisis kode proyek untuk menemukan celah bug atau masalah logika.
  - Membantu melakukan refactoring kode yang redundan.
  - Membantu menyusun dokumentasi laporan perbaikan dan modifikasi kode yang dibutuhkan.
- **Prompt/Interaksi Utama:** Menganalisis model, controller, dan form views terkait `LaboratoryItem`. AI diperintahkan untuk mengidentifikasi setidaknya 3 bugs, menerapkan setidaknya 2 refactoring (validasi dan duplikasi data), dan merangkum hasilnya ke dalam format log ini.

---

## Bug Fix Log

### Bug 1 — Pencarian yang Tidak Efisien (Logic Flaw)

**File:** `app/Http/Controllers/LaboratoryItemController.php`

**Masalah:** Fungsi pencarian pada halaman index menggunakan `$request->has('search')`. Ini bermasalah karena jika form pencarian di-submit dalam keadaan kosong (menghasilkan URL `?search=`), kondisi tersebut tetap bernilai `true` meskipun valuenya kosong, menyebabkan query dijalankan dengan pencarian `LIKE '%%'`.

**Kode SEBELUM Perbaikan:**
```php
public function index(Request $request)
{
    $query = LaboratoryItem::query();

    if ($request->has('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    $items = $query->orderBy('created_at', 'desc')->get();

    return view('items.index', compact('items'));
}
```

**Kode SESUDAH Perbaikan:**
```php
public function index(Request $request)
{
    $query = LaboratoryItem::query();

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    $items = $query->orderBy('created_at', 'desc')->paginate(10);

    return view('items.index', compact('items'));
}
```

**Penjelasan:** `filled()` memastikan parameter `search` ada DAN tidak kosong (empty string), sehingga query pencarian hanya dijalankan ketika pengguna benar-benar mengetikkan kata kunci.

---

### Bug 2 — Kurangnya Pagination pada Halaman Index (Performance Issue)

**File:** `app/Http/Controllers/LaboratoryItemController.php` dan `resources/views/items/index.blade.php`

**Masalah:** Menggunakan metode `->get()` pada query index akan memuat seluruh data dari tabel ke dalam memori. Jika aplikasi memiliki ribuan data barang, ini akan memakan banyak memori dan memperlambat load halaman.

**Kode Controller SEBELUM Perbaikan:**
```php
$items = $query->orderBy('created_at', 'desc')->get();
```

**Kode Controller SESUDAH Perbaikan:**
```php
$items = $query->orderBy('created_at', 'desc')->paginate(10);
```

**Kode View (index.blade.php) SEBELUM Perbaikan:**
```html
        </table>
    </figure>
</section>
```

**Kode View (index.blade.php) SESUDAH Perbaikan:**
```html
        </table>
    </figure>

    <div style="margin-top: 1rem; display: flex; justify-content: center;">
        {{ $items->withQueryString()->links() }}
    </div>
</section>
```

**Penjelasan:** `paginate(10)` hanya memuat 10 data per halaman, dan `withQueryString()` memastikan parameter pencarian tetap terbawa saat berpindah halaman.

---

### Bug 3 — Tidak Ada Batas Maksimal Kuantitas (Integer Overflow Vulnerability)

**File:** `app/Http/Controllers/LaboratoryItemController.php`, `resources/views/items/create.blade.php`, `resources/views/items/edit.blade.php`

**Masalah:** Validasi Laravel hanya memastikan input kuantitas berupa angka bulat minimum 1, tetapi tidak membatasi ukuran maksimalnya. Tabel database menggunakan tipe kolom `integer` (maksimal 2.147.483.647). Jika pengguna memasukkan nilai melebihi batas tersebut, aplikasi akan error karena SQL Exception (`SQLSTATE[22003]: Numeric value out of range`).

**Kode Validasi Controller SEBELUM Perbaikan:**
```php
'quantity' => 'required|integer|min:1',
```

**Kode Validasi Controller SESUDAH Perbaikan:**
```php
'quantity' => 'required|integer|min:1|max:2147483647',
```

**Kode HTML Form (create & edit) SEBELUM Perbaikan:**
```html
<input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
```

**Kode HTML Form (create & edit) SESUDAH Perbaikan:**
```html
<input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" max="2147483647" required>
```

**Penjelasan:** Validasi ganda diterapkan di sisi server (Laravel `max:2147483647`) dan sisi client (HTML `max="2147483647"`) untuk mencegah integer overflow.

---

### Bug 4 — Bug Opsi Default Form Edit Kategori (Missing Default Option Condition)

**File:** `resources/views/items/edit.blade.php`

**Masalah:** Pada form pengubahan data (Edit), elemen `<option value="" disabled>` untuk "Kategori" tidak diberikan pengecekan kondisi `selected` jika data lama gagal termuat. Akibatnya, ketika terjadi kasus edge (misal: data kategori null), tidak ada opsi default yang terpilih, sehingga dropdown terlihat kosong dan membingungkan pengguna.

**Kode SEBELUM Perbaikan:**
```html
<select id="category" name="category" required>
    <option value="" disabled>Pilih Kategori...</option>
    <option value="Komputer" {{ old('category', $item->category) == 'Komputer' ? 'selected' : '' }}>Komputer</option>
    <option value="Laptop" {{ old('category', $item->category) == 'Laptop' ? 'selected' : '' }}>Laptop</option>
    <option value="Jaringan" {{ old('category', $item->category) == 'Jaringan' ? 'selected' : '' }}>Jaringan</option>
    <option value="Aksesoris" {{ old('category', $item->category) == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
    <option value="Lainnya" {{ old('category', $item->category) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
</select>
```

**Kode SESUDAH Perbaikan:**
```html
<select id="category" name="category" required>
    <option value="" disabled {{ old('category', $item->category) ? '' : 'selected' }}>Pilih Kategori...</option>
    @foreach(\App\Models\LaboratoryItem::CATEGORIES as $cat)
        <option value="{{ $cat }}" {{ old('category', $item->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
    @endforeach
</select>
```

**Penjelasan:** Opsi placeholder "Pilih Kategori..." sekarang otomatis terpilih jika nilai `old()` dan `$item->category` keduanya kosong/null, menghindari tampilan dropdown kosong.

---

## Refactoring Log

### Refactoring 1 — Ekstraksi Logika Validasi (Menghindari Duplikasi Kode / DRY Principle)

**File:** `app/Http/Controllers/LaboratoryItemController.php`

**Masalah:** Array aturan validasi field secara identik diketik ulang di dalam fungsi `store()` dan `update()`, menyebabkan duplikasi kode yang memperpanjang Controller dan berisiko inkonsistensi ketika ada perubahan aturan validasi.

**Kode SEBELUM Refactoring (fungsi `store()`):**
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'item_code' => 'required|unique:laboratory_items|max:255',
        'name'      => 'required|max:100',
        'category'  => 'required|in:Komputer,Laptop,Jaringan,Aksesoris,Lainnya',
        'quantity'  => 'required|integer|min:1',
        'status'    => 'required|in:Baru,Digunakan,Rusak',
    ]);

    LaboratoryItem::create($validated);

    return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan.');
}
```

**Kode SEBELUM Refactoring (fungsi `update()`):**
```php
public function update(Request $request, LaboratoryItem $item)
{
    $validated = $request->validate([
        'item_code' => 'required|max:255|unique:laboratory_items,item_code,' . $item->id,
        'name'      => 'required|max:100',
        'category'  => 'required|in:Komputer,Laptop,Jaringan,Aksesoris,Lainnya',
        'quantity'  => 'required|integer|min:1',
        'status'    => 'required|in:Baru,Digunakan,Rusak',
    ]);

    $item->update($validated);

    return redirect()->route('items.index')->with('success', 'Data barang berhasil diperbarui.');
}
```

**Kode SESUDAH Refactoring (method private baru + `store()` dan `update()` yang lebih bersih):**
```php
private function validateItem(Request $request, $id = null)
{
    $rules = [
        'item_code' => 'required|max:255|unique:laboratory_items,item_code' . ($id ? ',' . $id : ''),
        'name'      => 'required|max:100',
        'category'  => 'required|in:' . implode(',', LaboratoryItem::CATEGORIES),
        'quantity'  => 'required|integer|min:1|max:2147483647',
        'status'    => 'required|in:' . implode(',', LaboratoryItem::STATUSES),
    ];

    return $request->validate($rules);
}

public function store(Request $request)
{
    $validated = $this->validateItem($request);

    LaboratoryItem::create($validated);

    return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan.');
}

public function update(Request $request, LaboratoryItem $item)
{
    $validated = $this->validateItem($request, $item->id);

    $item->update($validated);

    return redirect()->route('items.index')->with('success', 'Data barang berhasil diperbarui.');
}
```

**Penjelasan:** Aturan validasi dipusatkan di satu method `validateItem()`. Parameter opsional `$id` memungkinkan method tersebut secara otomatis menangani `unique` rule untuk kasus *store* (tanpa `$id`) maupun *update* (dengan pengecualian `$id`). Hal ini menerapkan prinsip **DRY (Don't Repeat Yourself)**.

---

### Refactoring 2 — Penggunaan Konstanta Enum untuk Menghapus Magic Strings

**File:** `app/Models/LaboratoryItem.php`, `app/Http/Controllers/LaboratoryItemController.php`, `resources/views/items/create.blade.php`, `resources/views/items/edit.blade.php`

**Masalah:** Opsi kategori (`Komputer`, `Laptop`, dll) dan status (`Baru`, `Digunakan`, `Rusak`) di hard-code di 5 titik berbeda (2 kali pada validasi controller, dan masing-masing di form `create` dan `edit`). Hal ini sangat tidak disarankan karena sangat rawan *typo* dan sulit dimaintain.

**Kode Model SEBELUM Refactoring:**
```php
class LaboratoryItem extends Model
{
    protected $fillable = [
        'item_code',
        'name',
        'category',
        'quantity',
        'status',
    ];
}
```

**Kode Model SESUDAH Refactoring:**
```php
class LaboratoryItem extends Model
{
    protected $fillable = [
        'item_code',
        'name',
        'category',
        'quantity',
        'status',
    ];

    public const CATEGORIES = ['Komputer', 'Laptop', 'Jaringan', 'Aksesoris', 'Lainnya'];
    public const STATUSES = ['Baru', 'Digunakan', 'Rusak'];
}
```

**Kode View Dropdown SEBELUM Refactoring (create.blade.php):**
```html
<select id="category" name="category" required>
    <option value="" disabled selected>Pilih Kategori...</option>
    <option value="Komputer" {{ old('category') == 'Komputer' ? 'selected' : '' }}>Komputer</option>
    <option value="Laptop" {{ old('category') == 'Laptop' ? 'selected' : '' }}>Laptop</option>
    <option value="Jaringan" {{ old('category') == 'Jaringan' ? 'selected' : '' }}>Jaringan</option>
    <option value="Aksesoris" {{ old('category') == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
    <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
</select>
```

**Kode View Dropdown SESUDAH Refactoring (create.blade.php):**
```html
<select id="category" name="category" required>
    <option value="" disabled selected>Pilih Kategori...</option>
    @foreach(\App\Models\LaboratoryItem::CATEGORIES as $cat)
        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
    @endforeach
</select>
```

**Penjelasan:** Konstanta `CATEGORIES` dan `STATUSES` dideklarasikan satu kali di Model. Semua validasi controller menggunakan `implode(',', LaboratoryItem::CATEGORIES)` dan semua dropdown view menggunakan `@foreach`. Jika ada penambahan kategori baru, developer cukup menambahkan satu item ke array konstanta di Model tanpa harus mengubah file lain.

