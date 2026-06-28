<?php

namespace App\Http\Controllers;

use App\Models\LaboratoryItem;
use Illuminate\Http\Request;

class LaboratoryItemController extends Controller
{
    public function index(Request $request)
    {
        $query = LaboratoryItem::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $items = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('items.index', compact('items'));
    }

    public function create()
    {
        return view('items.create');
    }

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

    public function edit(LaboratoryItem $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, LaboratoryItem $item)
    {
        $validated = $this->validateItem($request, $item->id);

        $item->update($validated);

        return redirect()->route('items.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy(LaboratoryItem $item)
    {
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus.');
    }
}
