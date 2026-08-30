<?php

use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DisplayController;
use App\Http\Controllers\Admin\PlaylistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\DisplayController as PublicDisplayController;
use App\Models\Content;
use App\Models\Display;
use App\Models\Playlist;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/display/{unique_code}', [PublicDisplayController::class, 'show'])
    ->name('display.show');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        $displays = Display::with('playlist')->orderBy('name')->get();

        return view('admin.dashboard', [
            'contentCount' => Content::count(),
            'playlistCount' => Playlist::count(),
            'displayCount' => $displays->count(),
            'onlineDisplayCount' => $displays->filter(fn (Display $display) => $display->is_online)->count(),
            'displays' => $displays,
        ]);
    })->name('dashboard');

    Route::resource('contents', ContentController::class)->except(['show'])->names('admin.contents');

    Route::resource('playlists', PlaylistController::class)->except(['show'])->names('admin.playlists');
    Route::put('playlists/{playlist}/contents', [PlaylistController::class, 'updateContents'])
        ->name('admin.playlists.contents.update');

    Route::resource('displays', DisplayController::class)->except(['show'])->names('admin.displays');
    Route::post('displays/{display}/regenerate-code', [DisplayController::class, 'regenerateCode'])
        ->name('admin.displays.regenerate-code');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
