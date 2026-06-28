<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
