<?php

use App\Livewire\DocumentForm;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', DocumentForm::class)->name('document');
