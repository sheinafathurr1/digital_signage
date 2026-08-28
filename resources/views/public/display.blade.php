<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $display->name }} &mdash; Digital Signage</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|fira-code:400&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        html, body { height: 100%; margin: 0; background: #FAFAF8; overflow: hidden; }
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        .ticker-track { display: inline-block; white-space: nowrap; animation: marquee 25s linear infinite; }
        .ticker-fade {
            mask-image: linear-gradient(to right, transparent, black 4rem, black calc(100% - 4rem), transparent);
            -webkit-mask-image: linear-gradient(to right, transparent, black 4rem, black calc(100% - 4rem), transparent);
        }
        @keyframes kenburns {
            from { transform: scale(1); }
            to { transform: scale(1.08); }
        }
        .kenburns-active {
            animation: kenburns var(--dur, 8s) ease-out forwards;
        }
        @keyframes slideprogress {
            from { width: 0%; }
            to { width: 100%; }
        }
        .slideprogress-bar {
            animation: slideprogress var(--dur, 8s) linear forwards;
        }
        @media (prefers-reduced-motion: reduce) {
            .ticker-track { animation: none; }
        }
    </style>
</head>
<body class="font-sans antialiased text-ink">
    <div
        x-data="displaySlideshow({
            uniqueCode: @js($display->unique_code),
            pollUrl: @js(route('api.display.contents', $display->unique_code)),
        })"
        x-init="init()"
        class="relative w-screen h-screen bg-background"
    >
        <!-- Jam & tanggal -->
        <div class="absolute top-4 right-6 z-30 bg-surface/95 border border-border rounded-xl shadow-card px-5 py-3 text-right">
            <div class="flex items-baseline justify-end gap-1">
                <span class="text-5xl font-bold tabular-nums tracking-tight text-ink" x-text="clockMain"></span>
                <span class="text-xl font-semibold tabular-nums text-primary" x-text="clockSeconds"></span>
            </div>
            <div class="flex items-center justify-end gap-1.5 mt-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5 text-muted shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                <span class="text-sm font-medium text-muted" x-text="clockDate"></span>
            </div>
        </div>

        <div class="absolute top-4 left-6 z-30 flex items-center gap-2.5 bg-surface/95 border border-border rounded-xl shadow-card px-5 py-3">
            <span class="w-2 h-2 rounded-full bg-success animate-pulse motion-reduce:animate-none shrink-0"></span>
            <div>
                <div class="text-lg font-semibold text-ink leading-tight">{{ $display->name }}</div>
                @if ($display->location)
                    <div class="text-xs text-muted">{{ $display->location }}</div>
                @endif
            </div>
        </div>

        <!-- Status kosong / loading -->
        <template x-if="!currentItem">
            <div class="w-full h-full flex items-center justify-center">
                <div class="text-center text-muted">
                    <template x-if="loading">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-8 h-8 rounded-full border-4 border-primary/20 border-t-primary animate-spin motion-reduce:animate-none [animation-duration:1.2s]"></div>
                            <p class="text-body">Memuat konten&hellip;</p>
                        </div>
                    </template>
                    <template x-if="!loading">
                        <div class="flex flex-col items-center gap-4">
                            <span class="w-16 h-16 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3 3h18v18H3V3Zm10.5 6a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                                </svg>
                            </span>
                            <p class="text-heading font-semibold text-ink">Belum ada konten untuk ditampilkan.</p>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        <!-- Slide aktif -->
        <template x-if="currentItem">
            <div class="w-full h-full transition-opacity duration-slow ease"
                :class="[currentItem.is_priority ? 'ring-8 ring-danger ring-inset' : '', fading ? 'opacity-0' : 'opacity-100']"
            >
                <template x-for="n in (currentItem.type === 'image' ? [currentIndex] : [])" :key="'image-' + currentIndex">
                    <div class="relative w-full h-full overflow-hidden bg-background">
                        <div class="absolute inset-0 bg-cover bg-center blur-2xl scale-110 opacity-50"
                            :style="'background-image: url(\'' + currentItem.file_url + '\')'"></div>
                        <img :src="currentItem.file_url" :alt="currentItem.title"
                            class="kenburns-active relative w-full h-full object-contain"
                            :style="'--dur: ' + Math.max(parseInt(currentItem.duration, 10) || 10, 1) + 's'">
                    </div>
                </template>

                <template x-if="currentItem.type === 'video'">
                    <video
                        :src="currentItem.file_url"
                        class="w-full h-full object-contain bg-background"
                        autoplay
                        muted
                        playsinline
                        @ended="advance()"
                    ></video>
                </template>

                <template x-if="currentItem.type === 'text'">
                    <div class="w-full h-full flex items-center justify-center p-16"
                        :class="currentItem.is_priority ? 'bg-danger' : 'bg-primary'">
                        <p class="text-display font-bold text-on-primary text-center whitespace-pre-line" x-text="currentItem.text_body"></p>
                    </div>
                </template>

                <template x-if="currentItem.type === 'html-embed'">
                    <div class="w-full h-full overflow-hidden bg-surface text-ink" x-html="currentItem.text_body"></div>
                </template>

                <template x-if="currentItem.is_priority">
                    <div class="absolute top-1 left-0 right-0 bg-danger text-on-primary text-center py-2 text-lg font-bold tracking-wide z-20">
                        <span class="inline-block animate-pulse motion-reduce:animate-none">&#9888;</span> PENGUMUMAN PRIORITAS
                    </div>
                </template>
            </div>
        </template>

        <!-- Progress bar durasi slide -->
        <template x-if="currentItem && currentItem.type !== 'video'">
            <div class="absolute top-0 left-0 right-0 h-1 z-40 bg-ink/10">
                <template x-for="n in [currentIndex]" :key="'progress-' + currentIndex">
                    <div class="h-full slideprogress-bar"
                        :class="currentItem.is_priority ? 'bg-danger' : 'bg-primary'"
                        :style="'--dur: ' + Math.max(parseInt(currentItem.duration, 10) || 10, 1) + 's'"
                    ></div>
                </template>
            </div>
        </template>

        <!-- Indikator slide -->
        <template x-if="queue.length > 1">
            <div class="absolute bottom-14 left-0 right-0 z-30 flex items-center justify-center">
                <div class="flex items-center gap-1.5 bg-surface/90 border border-border rounded-full shadow-card px-3 py-2">
                    <template x-for="(item, index) in queue" :key="index">
                        <span class="h-1.5 rounded-full transition-all duration-base"
                            :class="[
                                index === currentIndex ? 'w-6' : 'w-1.5',
                                item.is_priority
                                    ? (index === currentIndex ? 'bg-danger' : 'bg-danger/30')
                                    : (index === currentIndex ? 'bg-primary' : 'bg-border'),
                            ]"
                        ></span>
                    </template>
                </div>
            </div>
        </template>

        <!-- Ticker bawah -->
        <div class="absolute bottom-0 left-0 right-0 bg-surface/95 border-t border-border py-2 overflow-hidden z-30 shadow-[0_-2px_8px_rgba(44,36,32,0.08)]">
            <div class="ticker-fade overflow-hidden">
                <span class="ticker-track text-sm text-muted" x-text="tickerText"></span>
            </div>
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
                fading: false,
                loading: true,
                clockMain: '',
                clockSeconds: '',
                clockDate: '',
                slideTimer: null,
                fadeTimer: null,
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
                    const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    const segments = time.split(/[.:]/);
                    this.clockMain = segments.slice(0, 2).join('.');
                    this.clockSeconds = segments[2] ?? '00';
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
                    this.fading = false;
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

                    clearTimeout(this.fadeTimer);
                    this.fading = true;
                    this.fadeTimer = setTimeout(() => {
                        this.currentIndex = (this.currentIndex + 1) % this.queue.length;
                        this.fading = false;
                        this.scheduleNext();
                    }, 400);
                },
            };
        }
    </script>
</body>
</html>
