<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $display->name }} &mdash; Digital Signage</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        html, body { height: 100%; margin: 0; background: #000; overflow: hidden; }
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        .ticker-track { display: inline-block; white-space: nowrap; animation: marquee 25s linear infinite; }
    </style>
</head>
<body class="font-sans antialiased text-white">
    <div
        x-data="displaySlideshow({
            uniqueCode: @js($display->unique_code),
            pollUrl: @js(route('api.display.contents', $display->unique_code)),
        })"
        x-init="init()"
        class="relative w-screen h-screen bg-black"
    >
        <!-- Jam & tanggal -->
        <div class="absolute top-4 right-6 z-30 text-right drop-shadow-lg">
            <div class="text-3xl font-semibold tabular-nums" x-text="clockTime"></div>
            <div class="text-sm text-gray-300" x-text="clockDate"></div>
        </div>

        <div class="absolute top-4 left-6 z-30 drop-shadow-lg">
            <div class="text-lg font-medium">{{ $display->name }}</div>
            @if ($display->location)
                <div class="text-xs text-gray-300">{{ $display->location }}</div>
            @endif
        </div>

        <!-- Status kosong / loading -->
        <template x-if="!currentItem">
            <div class="w-full h-full flex items-center justify-center">
                <div class="text-center text-gray-400">
                    <template x-if="loading">
                        <p class="text-xl">Memuat konten&hellip;</p>
                    </template>
                    <template x-if="!loading">
                        <p class="text-xl">Belum ada konten untuk ditampilkan.</p>
                    </template>
                </div>
            </div>
        </template>

        <!-- Slide aktif -->
        <template x-if="currentItem">
            <div class="w-full h-full" :class="currentItem.is_priority ? 'ring-8 ring-red-600 ring-inset' : ''">
                <template x-if="currentItem.type === 'image'">
                    <img :src="currentItem.file_url" :alt="currentItem.title" class="w-full h-full object-contain bg-black">
                </template>

                <template x-if="currentItem.type === 'video'">
                    <video
                        :src="currentItem.file_url"
                        class="w-full h-full object-contain bg-black"
                        autoplay
                        muted
                        playsinline
                        @ended="advance()"
                    ></video>
                </template>

                <template x-if="currentItem.type === 'text'">
                    <div class="w-full h-full flex items-center justify-center p-16"
                        :class="currentItem.is_priority ? 'bg-red-700' : 'bg-slate-800'">
                        <p class="text-5xl font-semibold text-center leading-snug whitespace-pre-line" x-text="currentItem.text_body"></p>
                    </div>
                </template>

                <template x-if="currentItem.type === 'html-embed'">
                    <div class="w-full h-full overflow-hidden bg-white text-black" x-html="currentItem.text_body"></div>
                </template>

                <template x-if="currentItem.is_priority">
                    <div class="absolute top-0 left-0 right-0 bg-red-600 text-white text-center py-2 text-lg font-bold tracking-wide z-20">
                        &#9888; PENGUMUMAN PRIORITAS
                    </div>
                </template>
            </div>
        </template>

        <!-- Ticker bawah -->
        <div class="absolute bottom-0 left-0 right-0 bg-black/70 border-t border-white/10 py-2 overflow-hidden z-30">
            <span class="ticker-track text-sm text-gray-200" x-text="tickerText"></span>
        </div>
    </div>

    <script>
        function displaySlideshow({ uniqueCode, pollUrl }) {
            return {
                uniqueCode,
                pollUrl,
                contents: [],
                priorityContents: [],
                queue: [],
                currentIndex: 0,
                loading: true,
                clockTime: '',
                clockDate: '',
                slideTimer: null,
                pollTimerId: null,
                lastSignature: '',

                get currentItem() {
                    return this.queue.length ? this.queue[this.currentIndex] : null;
                },

                get tickerText() {
                    const parts = this.priorityContents.length
                        ? this.priorityContents.map(c => `PRIORITAS: ${c.title}`)
                        : [];
                    parts.push('{{ $display->name }}' + '{{ $display->location ? " - ".$display->location : "" }}');
                    return parts.join('        •        ');
                },

                init() {
                    this.updateClock();
                    setInterval(() => this.updateClock(), 1000);

                    this.fetchContents();
                    this.pollTimerId = setInterval(() => this.fetchContents(), 30000);
                },

                updateClock() {
                    const now = new Date();
                    this.clockTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    this.clockDate = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                },

                fetchContents() {
                    fetch(this.pollUrl, { headers: { Accept: 'application/json' } })
                        .then((response) => {
                            if (!response.ok) throw new Error('Gagal memuat data layar.');
                            return response.json();
                        })
                        .then((data) => {
                            this.loading = false;
                            const signature = JSON.stringify([data.contents, data.priority_contents]);
                            if (signature === this.lastSignature) return;

                            this.lastSignature = signature;
                            this.contents = data.contents ?? [];
                            this.priorityContents = data.priority_contents ?? [];
                            this.rebuildQueue();
                        })
                        .catch(() => {
                            this.loading = false;
                        });
                },

                rebuildQueue() {
                    const regular = this.contents.map((c) => ({ ...c }));
                    const priority = this.priorityContents.map((c) => ({ ...c }));

                    let newQueue = [];
                    if (priority.length === 0) {
                        newQueue = regular;
                    } else if (regular.length === 0) {
                        newQueue = priority;
                    } else {
                        regular.forEach((item, index) => {
                            newQueue.push(item);
                            newQueue.push(priority[index % priority.length]);
                        });
                    }

                    this.queue = newQueue;
                    this.currentIndex = 0;
                    this.scheduleNext();
                },

                scheduleNext() {
                    clearTimeout(this.slideTimer);

                    const item = this.currentItem;
                    if (!item) return;

                    if (item.type === 'video') {
                        // Video normally advances via the @ended event; this is only
                        // a safety net in case playback is blocked or the file is broken.
                        this.slideTimer = setTimeout(() => this.advance(), 180000);
                        return;
                    }

                    const durationMs = Math.max(parseInt(item.duration, 10) || 10, 1) * 1000;
                    this.slideTimer = setTimeout(() => this.advance(), durationMs);
                },

                advance() {
                    if (this.queue.length === 0) return;
                    this.currentIndex = (this.currentIndex + 1) % this.queue.length;
                    this.scheduleNext();
                },
            };
        }
    </script>
</body>
</html>
