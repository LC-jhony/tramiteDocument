<?php

use App\Livewire\DocumentForm;
use App\Livewire\RemitirCreate;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', DocumentForm::class)->name('document');
Route::get('/remitir/{record}', RemitirCreate::class)->name('remitir.create');
