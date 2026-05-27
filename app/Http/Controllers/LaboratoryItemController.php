<?php

namespace App\Http\Controllers;

use App\Models\LaboratoryItem;
use Illuminate\Http\Request;

class LaboratoryItemController extends Controller
{
    public function index(Request $request)
    {
        $query = LaboratoryItem::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $items = $query->orderBy('created_at', 'desc')->get();

        return view('items.index', compact('items'));
    }

    public function create()
    {
        return view('items.create');
    }

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

    public function edit(LaboratoryItem $item)
    {
        return view('items.edit', compact('item'));
    }

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

    public function destroy(LaboratoryItem $item)
    {
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus.');
    }
}
