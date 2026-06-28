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
                        <option value="" disabled {{ old('category', $item->category) ? '' : 'selected' }}>Pilih Kategori...</option>
                        @foreach(\App\Models\LaboratoryItem::CATEGORIES as $cat)
                            <option value="{{ $cat }}" {{ old('category', $item->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>
                
                <div>
                    <label for="quantity">Kuantitas <span style="color: red;">*</span></label>
                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $item->quantity) }}" min="1" max="2147483647" required>
                    @error('quantity')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div>
                <label for="status">Status <span style="color: red;">*</span></label>
                <select id="status" name="status" required>
                    <option value="" disabled {{ old('status', $item->status) ? '' : 'selected' }}>Pilih Status...</option>
                    @foreach(\App\Models\LaboratoryItem::STATUSES as $stat)
                        <option value="{{ $stat }}" {{ old('status', $item->status) == $stat ? 'selected' : '' }}>{{ $stat }}</option>
                    @endforeach
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
