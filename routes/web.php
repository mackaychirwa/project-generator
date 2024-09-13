<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/create-project', [ProjectController::class, 'showForm'])->name('project.create');
Route::post('/create-project', [ProjectController::class, 'create']);
