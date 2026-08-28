<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContentRequest;
use App\Http\Requests\UpdateContentRequest;
use App\Models\Content;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function index(Request $request): View
    {
        $contents = Content::query()
            ->when($request->string('search')->toString(), function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.contents.index', [
            'contents' => $contents,
            'search' => $request->string('search')->toString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.contents.create');
    }

    public function store(StoreContentRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('contents', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['is_priority'] = $request->boolean('is_priority');
        $data['order'] = $data['order'] ?? 0;

        Content::create($data);

        return redirect()->route('admin.contents.index')
            ->with('status', 'Konten berhasil ditambahkan.');
    }

    public function edit(Content $content): View
    {
        return view('admin.contents.edit', ['content' => $content]);
    }

    public function update(UpdateContentRequest $request, Content $content): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            if ($content->file_path) {
                Storage::disk('public')->delete($content->file_path);
            }
            $data['file_path'] = $request->file('file')->store('contents', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['is_priority'] = $request->boolean('is_priority');
        $data['order'] = $data['order'] ?? 0;

        $content->update($data);

        return redirect()->route('admin.contents.index')
            ->with('status', 'Konten berhasil diperbarui.');
    }

    public function destroy(Content $content): RedirectResponse
    {
        if ($content->file_path) {
            Storage::disk('public')->delete($content->file_path);
        }

        $content->delete();

        return redirect()->route('admin.contents.index')
            ->with('status', 'Konten berhasil dihapus.');
    }
}
