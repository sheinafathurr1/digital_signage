@php
    $display = $display ?? null;
@endphp

<div>
    <x-input-label for="name" value="Nama Layar" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
        value="{{ old('name', $display->name ?? '') }}" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div>
    <x-input-label for="location" value="Lokasi" />
    <x-text-input id="location" name="location" type="text" class="mt-1 block w-full"
        placeholder="Contoh: Lobi Utama, Lantai 1" value="{{ old('location', $display->location ?? '') }}" />
    <x-input-error :messages="$errors->get('location')" class="mt-2" />
</div>

<div>
    <x-input-label for="orientation" value="Orientasi" />
    <select id="orientation" name="orientation" required
        class="mt-1 block w-full border-border focus:border-2 focus:border-primary focus:ring-0 focus:shadow-focus rounded-md">
        <option value="landscape" {{ old('orientation', $display->orientation ?? 'landscape') === 'landscape' ? 'selected' : '' }}>Landscape (1920x1080)</option>
        <option value="portrait" {{ old('orientation', $display->orientation ?? '') === 'portrait' ? 'selected' : '' }}>Portrait</option>
    </select>
    <x-input-error :messages="$errors->get('orientation')" class="mt-2" />
</div>

<div>
    <x-input-label for="playlist_id" value="Playlist" />
    <select id="playlist_id" name="playlist_id"
        class="mt-1 block w-full border-border focus:border-2 focus:border-primary focus:ring-0 focus:shadow-focus rounded-md">
        <option value="">-- Tidak ada playlist --</option>
        @foreach ($playlists as $playlist)
            <option value="{{ $playlist->id }}" {{ (string) old('playlist_id', $display->playlist_id ?? '') === (string) $playlist->id ? 'selected' : '' }}>
                {{ $playlist->name }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('playlist_id')" class="mt-2" />
</div>

@if ($display)
    <div class="bg-background rounded-md p-4">
        <p class="text-sm text-muted">URL Layar Publik:</p>
        <a href="{{ route('display.show', $display->unique_code) }}" target="_blank" class="text-primary hover:underline font-mono text-sm">
            {{ route('display.show', $display->unique_code) }}
        </a>
    </div>
@endif
