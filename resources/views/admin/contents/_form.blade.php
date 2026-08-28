@php
    $content = $content ?? null;
@endphp

<div x-data="{ type: '{{ old('type', $content->type ?? 'image') }}' }" class="space-y-6">
    <div>
        <x-input-label for="title" value="Judul" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
            value="{{ old('title', $content->title ?? '') }}" required autofocus />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="type" value="Tipe Konten" />
        <select id="type" name="type" x-model="type" required
            class="mt-1 block w-full border-border focus:border-primary focus:ring-0 rounded-md">
            <option value="image" {{ old('type', $content->type ?? '') === 'image' ? 'selected' : '' }}>Gambar</option>
            <option value="video" {{ old('type', $content->type ?? '') === 'video' ? 'selected' : '' }}>Video</option>
            <option value="text" {{ old('type', $content->type ?? '') === 'text' ? 'selected' : '' }}>Teks</option>
            <option value="html-embed" {{ old('type', $content->type ?? '') === 'html-embed' ? 'selected' : '' }}>HTML Embed</option>
        </select>
        <x-input-error :messages="$errors->get('type')" class="mt-2" />
    </div>

    <div x-show="type === 'image' || type === 'video'" x-cloak>
        <x-input-label for="file" value="Berkas (Gambar: jpg/png, maks 5MB &middot; Video: mp4, maks 50MB)" />
        <input id="file" name="file" type="file" accept="image/jpeg,image/png,video/mp4"
            class="mt-1 block w-full text-sm text-ink border-border focus:border-primary focus:ring-0 rounded-md" />
        <x-input-error :messages="$errors->get('file')" class="mt-2" />

        @if (($content->file_path ?? null))
            <div class="mt-3">
                @if ($content->type === 'image')
                    <img src="{{ $content->file_url }}" alt="{{ $content->title }}" class="h-32 rounded-md border border-border object-cover">
                @else
                    <video src="{{ $content->file_url }}" class="h-32 rounded-md border border-border" controls muted></video>
                @endif
                <p class="text-xs text-muted mt-1">Kosongkan berkas jika tidak ingin mengganti file yang sudah ada.</p>
            </div>
        @endif
    </div>

    <div x-show="type === 'text' || type === 'html-embed'" x-cloak>
        <x-input-label for="text_body" value="Isi Teks / Kode HTML" />
        <textarea id="text_body" name="text_body" rows="5"
            class="mt-1 block w-full border-border focus:border-primary focus:ring-0 rounded-md">{{ old('text_body', $content->text_body ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('text_body')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <x-input-label for="duration" value="Durasi Tampil (detik)" />
            <x-text-input id="duration" name="duration" type="number" min="1" max="3600" class="mt-1 block w-full"
                value="{{ old('duration', $content->duration ?? 10) }}" required />
            <p class="text-xs text-muted mt-1">Untuk video, durasi mengikuti panjang video.</p>
            <x-input-error :messages="$errors->get('duration')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="order" value="Urutan Default" />
            <x-text-input id="order" name="order" type="number" min="0" class="mt-1 block w-full"
                value="{{ old('order', $content->order ?? 0) }}" />
            <x-input-error :messages="$errors->get('order')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="start_date" value="Tanggal Mulai Tayang" />
            <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full"
                value="{{ old('start_date', optional($content->start_date ?? null)->format('Y-m-d')) }}" />
            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="end_date" value="Tanggal Selesai Tayang" />
            <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full"
                value="{{ old('end_date', optional($content->end_date ?? null)->format('Y-m-d')) }}" />
            <p class="text-xs text-muted mt-1">Kosongkan agar tayang tanpa batas waktu.</p>
            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
        </div>
    </div>

    <div class="flex items-center gap-6">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1"
                {{ old('is_active', $content->is_active ?? true) ? 'checked' : '' }}
                class="rounded border-border text-primary shadow-none focus:ring-primary">
            <span class="text-sm text-ink">Aktif (tayang di layar)</span>
        </label>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_priority" value="1"
                {{ old('is_priority', $content->is_priority ?? false) ? 'checked' : '' }}
                class="rounded border-border text-danger shadow-none focus:ring-danger">
            <span class="text-sm text-ink">Prioritas / Darurat (menyela slideshow normal)</span>
        </label>
    </div>
</div>
