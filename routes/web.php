<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\VolunteerController;

Route::get('/volunteers/create', [VolunteerController::class, 'create'])->name('volunteers.create');
Route::post('/volunteers', [VolunteerController::class, 'store'])->name('volunteers.store');
Route::get('/volunteers/{id}', [VolunteerController::class, 'show'])->name('volunteers.show');
Route::get('/scan/{token}', [VolunteerController::class, 'scan'])->name('volunteers.scan');
