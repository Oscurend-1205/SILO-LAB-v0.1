@extends('layouts.app')

@section('title', 'Edit Barang - Inventaris Laboratorium Informatika')

@section('content')
<section>
    <div style="margin-bottom: 2rem;">
        <h2>Edit Data Barang</h2>
        <a href="{{ route('items.index') }}" role="button" class="secondary outline" style="width: auto; padding: 0.4rem 0.8rem; font-size: 0.85rem;">&larr; Kembali</a>
    </div>

    <article>
        <form action="{{ route('items.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid">
                <div>
                    <label for="item_code">Kode Barang (Unik) <span style="color: red;">*</span></label>
                    <input type="text" id="item_code" name="item_code" value="{{ old('item_code', $item->item_code) }}" placeholder="Contoh: LAB-001" required>
                    @error('item_code')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>
                
                <div>
                    <label for="name">Nama Barang <span style="color: red;">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $item->name) }}" placeholder="Nama barang" required maxlength="100">
                    @error('name')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="grid">
                <div>
                    <label for="category">Kategori <span style="color: red;">*</span></label>
                    <select id="category" name="category" required>
                        <option value="" disabled>Pilih Kategori...</option>
                        <option value="Komputer" {{ old('category', $item->category) == 'Komputer' ? 'selected' : '' }}>Komputer</option>
                        <option value="Laptop" {{ old('category', $item->category) == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                        <option value="Jaringan" {{ old('category', $item->category) == 'Jaringan' ? 'selected' : '' }}>Jaringan</option>
                        <option value="Aksesoris" {{ old('category', $item->category) == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                        <option value="Lainnya" {{ old('category', $item->category) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('category')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>
                
                <div>
                    <label for="quantity">Kuantitas <span style="color: red;">*</span></label>
                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $item->quantity) }}" min="1" required>
                    @error('quantity')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div>
                <label for="status">Status <span style="color: red;">*</span></label>
                <select id="status" name="status" required>
                    <option value="" disabled {{ old('status', $item->status) ? '' : 'selected' }}>Pilih Status...</option>
                    <option value="Baru" {{ old('status', $item->status) == 'Baru' ? 'selected' : '' }}>Baru</option>
                    <option value="Digunakan" {{ old('status', $item->status) == 'Digunakan' ? 'selected' : '' }}>Digunakan</option>
                    <option value="Rusak" {{ old('status', $item->status) == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                </select>
                @error('status')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit">Update Data Barang</button>
        </form>
    </article>
</section>
@endsection
