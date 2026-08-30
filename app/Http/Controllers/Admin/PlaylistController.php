<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlaylistRequest;
use App\Http\Requests\UpdatePlaylistContentsRequest;
use App\Http\Requests\UpdatePlaylistRequest;
use App\Models\Content;
use App\Models\Playlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PlaylistController extends Controller
{
    public function index(): View
    {
        $playlists = Playlist::withCount('contents', 'displays')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.playlists.index', ['playlists' => $playlists]);
    }

    public function create(): View
    {
        return view('admin.playlists.create');
    }

    public function store(StorePlaylistRequest $request): RedirectResponse
    {
        $playlist = Playlist::create($request->validated());

        return redirect()->route('admin.playlists.edit', $playlist)
            ->with('status', 'Playlist berhasil dibuat. Silakan atur konten di bawah.');
    }

    public function edit(Playlist $playlist): View
    {
        $playlist->load('contents');

        $assignedIds = $playlist->contents->pluck('id')->all();

        $availableContents = Content::query()
            ->whereNotIn('id', $assignedIds)
            ->orderBy('title')
            ->get();

        return view('admin.playlists.edit', [
            'playlist' => $playlist,
            'availableContents' => $availableContents,
        ]);
    }

    public function update(UpdatePlaylistRequest $request, Playlist $playlist): RedirectResponse
    {
        $playlist->update($request->validated());

        return redirect()->route('admin.playlists.edit', $playlist)
            ->with('status', 'Playlist berhasil diperbarui.');
    }

    public function updateContents(UpdatePlaylistContentsRequest $request, Playlist $playlist): RedirectResponse
    {
        $contentIds = $request->validated('content_ids', []);

        $syncData = [];
        foreach ($contentIds as $order => $contentId) {
            $syncData[$contentId] = ['order' => $order];
        }

        $playlist->contents()->sync($syncData);

        return redirect()->route('admin.playlists.edit', $playlist)
            ->with('status', 'Urutan konten playlist berhasil disimpan.');
    }

    public function destroy(Playlist $playlist): RedirectResponse
    {
        $playlist->delete();

        return redirect()->route('admin.playlists.index')
            ->with('status', 'Playlist berhasil dihapus.');
    }
}
