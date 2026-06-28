@extends('layouts.app')

@section('title', 'Daftar Barang - Inventaris Laboratorium Informatika')

@section('content')
<section>
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
        <form action="{{ route('items.index') }}" method="GET" class="search-form" style="margin-bottom: 0;">
            <input type="search" name="search" placeholder="Cari nama barang..." value="{{ request('search') }}" aria-label="Search">
            <button type="submit">Cari</button>
            @if(request('search'))
                <a href="{{ route('items.index') }}" role="button" class="secondary outline" style="width: auto; margin: 0;">Reset</a>
            @endif
        </form>
        <a href="{{ route('items.create') }}" role="button">+ Tambah Barang</a>
    </div>
</section>

<section style="margin-top: 2rem;">
    <figure>
        <table role="grid">
            <thead>
                <tr>
                    <th scope="col">Kode Barang</th>
                    <th scope="col">Nama Barang</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Kuantitas</th>
                    <th scope="col">Status</th>
                    <th scope="col" style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td><strong>{{ $item->item_code }}</strong></td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>
                        @php
                            $badgeClass = 'badge-available';
                            if($item->status === 'Digunakan') $badgeClass = 'badge-in-use';
                            if($item->status === 'Rusak') $badgeClass = 'badge-damaged';
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $item->status }}</span>
                    </td>
                    <td>
                        <div class="actions" style="justify-content: flex-end;">
                            <a href="{{ route('items.edit', $item->id) }}" role="button" class="secondary outline">Edit</a>
                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data barang ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete outline">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 2rem;">
                        Belum ada data barang laboratorium informatika.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </figure>

    <div style="margin-top: 1rem; display: flex; justify-content: center;">
        {{ $items->withQueryString()->links() }}
    </div>
</section>
@endsection
