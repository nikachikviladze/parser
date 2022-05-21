<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PagesController;
use App\Models\Blog;
use Illuminate\Support\Facades\Cache;

Route::get('/',  [PagesController::class, 'index']);
Route::get('/download',  [PagesController::class, 'download']);
Route::post('/upload',  [PagesController::class, 'upload']);

Route::get('/files',  [PagesController::class, 'files'])->name('files');
Route::delete('/files/{name}',  [PagesController::class, 'destroy_file'])->name('file_delete');

Route::get('blog', function(){

    $blogs = Cache::remember('blogs', 60*60*24, function () {
        return Blog::all();
    });

    


    dd($blogs);
});