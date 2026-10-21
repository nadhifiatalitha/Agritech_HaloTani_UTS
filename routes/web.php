<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {return redirect('/petani');});

// Route::get('/petani/login', LoginPetani::class)->name('filament.petani.auth.login');

// Route CRUD jadwal tanam
Route::resource('jadwal-tanam',JadwalTanamController::class);