<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PagesController;



Route::get('/',  [PagesController::class, 'index']);
Route::get('/download',  [PagesController::class, 'download']);
Route::post('/upload',  [PagesController::class, 'upload']);

Route::get('/files',  [PagesController::class, 'files'])->name('files');
Route::delete('/files/{name}',  [PagesController::class, 'destroy_file'])->name('file_delete');