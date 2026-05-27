<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaboratoryItemController;

Route::get('/', function () {
    return redirect()->route('items.index');
});

Route::resource('items', LaboratoryItemController::class)->except(['show']);
