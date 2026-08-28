<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDisplayRequest;
use App\Http\Requests\UpdateDisplayRequest;
use App\Models\Display;
use App\Models\Playlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DisplayController extends Controller
{
    public function index(): View
    {
        $displays = Display::with('playlist')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.displays.index', ['displays' => $displays]);
    }

    public function create(): View
    {
        return view('admin.displays.create', [
            'playlists' => Playlist::orderBy('name')->get(),
        ]);
    }

    public function store(StoreDisplayRequest $request): RedirectResponse
    {
        $display = Display::create($request->validated());

        return redirect()->route('admin.displays.index')
            ->with('status', "Layar \"{$display->name}\" berhasil dibuat dengan kode {$display->unique_code}.");
    }

    public function edit(Display $display): View
    {
        return view('admin.displays.edit', [
            'display' => $display,
            'playlists' => Playlist::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateDisplayRequest $request, Display $display): RedirectResponse
    {
        $display->update($request->validated());

        return redirect()->route('admin.displays.index')
            ->with('status', 'Layar berhasil diperbarui.');
    }

    public function destroy(Display $display): RedirectResponse
    {
        $display->delete();

        return redirect()->route('admin.displays.index')
            ->with('status', 'Layar berhasil dihapus.');
    }

    public function regenerateCode(Display $display): RedirectResponse
    {
        $display->update(['unique_code' => Display::generateUniqueCode()]);

        return redirect()->route('admin.displays.index')
            ->with('status', "Kode unik layar \"{$display->name}\" berhasil dibuat ulang.");
    }
}
