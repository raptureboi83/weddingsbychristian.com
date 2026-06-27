@php
    $chunkUrl = route('filament.admin.media.upload.chunk');
    $finalizeUrl = route('filament.admin.media.upload.finalize');
    $listUrl = route('filament.admin.media.list');
@endphp

<div
    x-data="visualMediaPicker({
        statePath: @js($targetStatePath),
        existingPath: @js($existingPath ?? ''),
        uploadChunkUrl: @js($chunkUrl),
        finalizeUrl: @js($finalizeUrl),
        listUrl: @js($listUrl),
        directory: @js($uploadDirectory ?? 'media'),
        csrf: @js(csrf_token()),
        imageOnly: @js($imageOnly ?? false),
        acceptedTypes: @js($acceptedFileTypes ?? ($imageOnly ? 'image/jpeg,image/png,image/gif,image/webp,image/svg+xml' : '')),
    })"
    wire:ignore
    class="visual-media-picker"
>
    @php
        $cmpLabel = isset($getLabel) ? $getLabel() : '';
        $cmpHelper = isset($getHelperText) ? $getHelperText() : '';
    @endphp
    @if (filled($cmpLabel))
        <label class="media-picker-library__label">{{ $cmpLabel }}</label>
    @endif
    @if (filled($cmpHelper))
        <p class="media-picker-library__helper">{{ $cmpHelper }}</p>
    @endif

    <input type="hidden" :value="selectedPath" x-bind:name="statePath" />

    <!-- Upload dropzone -->
    <button
        type="button"
        @click="openFilePicker()"
        @dragover.prevent="isDragging = true"
        @dragleave.prevent="isDragging = false"
        @drop.prevent="
            isDragging = false;
            if ($event.dataTransfer.files.length) {
                file = $event.dataTransfer.files[0];
                handleUpload({ target: { files: [file] } });
            }
        "
        class="media-picker-library__dropzone"
        :class="{ 'is-dragging': isDragging }"
    >
        <div class="media-picker-library__content">
            <template x-if="!fileName && !selectedPath">
                <p class="media-picker-library__hint">
                    Drag &amp; drop a file or
                    <span class="media-picker-library__browse">browse</span>
                    to upload
                </p>
            </template>

            <template x-if="!fileName && selectedPath">
                <div class="media-picker-library__existing">
                    <p class="media-picker-library__attached">File attached</p>
                    <p class="media-picker-library__hint">
                        Drag &amp; drop or
                        <span class="media-picker-library__browse">browse</span>
                        to replace
                    </p>
                </div>
            </template>

            <template x-if="fileName">
                <div class="media-picker-library__progress-ctn">
                    <p class="media-picker-library__filename" x-text="fileName"></p>
                    <p class="media-picker-library__status" x-text="status"></p>

                    <div class="media-picker-library__bar">
                        <div
                            class="media-picker-library__bar-fill"
                            :style="`width: ${progress}%`"
                        ></div>
                    </div>

                    <p class="media-picker-library__status">
                        <span x-text="progress"></span>% uploaded
                        <template x-if="speed">
                            <span style="opacity:0.6;margin-left:0.5rem" x-text="`(${speed})`"></span>
                        </template>
                    </p>

                    <template x-if="previewUrl">
                        <div class="media-picker-library__preview">
                            <template x-if="isImage">
                                <img :src="previewUrl" class="media-picker-library__preview-media" />
                            </template>
                            <template x-if="isVideo">
                                <video :src="previewUrl" controls class="media-picker-library__preview-media"></video>
                            </template>
                            <template x-if="!isImage && !isVideo">
                                <a :href="previewUrl" target="_blank" class="media-picker-library__preview-link">View file &rarr;</a>
                            </template>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </button>

    <template x-if="errorMessage">
        <div class="media-picker-library__error" x-text="errorMessage"></div>
    </template>

    <!-- Current selection info -->
    <template x-if="selectedPath && !fileName">
        <div class="media-picker-library__selected">
            <a :href="selectedPreviewUrl" target="_blank" class="media-picker-library__selected-link" x-text="selectedName || selectedPath"></a>
        </div>
    </template>

    <!-- Choose from library button -->
    <div class="media-picker-library__actions">
        <button type="button" class="media-picker-library__lib-btn" @click="openLibrary()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            Choose from library
        </button>
    </div>

    <!-- Library drawer overlay -->
    <template x-teleport="body">
        <div
            x-show="libraryOpen"
            class="media-picker-library__overlay"
            x-transition:enter="transition-all duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-all duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="closeLibrary()"
        >
            <div
                class="media-picker-library__drawer"
                x-transition:enter="transition-all duration-300"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition-all duration-300"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                @click.stop
            >
                <div class="media-picker-library__drawer-header">
                    <h3 class="media-picker-library__drawer-title">Media Library</h3>
                    <button type="button" class="media-picker-library__drawer-close" @click="closeLibrary()">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>

                <div class="media-picker-library__drawer-search">
                    <input
                        type="text"
                        class="media-picker-library__drawer-input"
                        placeholder="Search files..."
                        x-model="librarySearch"
                        @input.debounce.300ms="fetchLibrary()"
                    />
                </div>

                <div class="media-picker-library__drawer-body" x-ref="drawerBody">
                    <template x-if="libraryLoading">
                        <p class="media-picker-library__drawer-empty">Loading...</p>
                    </template>

                    <template x-if="!libraryLoading && libraryFiles.length === 0">
                        <p class="media-picker-library__drawer-empty">No media files found.</p>
                    </template>

                    <div class="media-picker-library__drawer-grid" x-show="!libraryLoading && libraryFiles.length">
                        <template x-for="file in libraryFiles" :key="file.id || file.path">
                            <button
                                type="button"
                                class="media-picker-library__drawer-item"
                                :class="{ 'is-selected': selectedPath === file.path }"
                                @click="selectFromLibrary(file)"
                                :title="file.name"
                            >
                                <template x-if="file.is_image">
                                    <div class="media-picker-library__drawer-thumb-wrap">
                                        <img :src="file.url" class="media-picker-library__drawer-thumb" loading="lazy" x-on:error="this.style.display='none';this.nextElementSibling.style.display='block'" />
                                        <span class="media-picker-library__drawer-icon" style="display:none">
                                            <svg viewBox="0 0 40 40" style="width:100%;height:100%"><rect width="40" height="40" fill="var(--gray-700, rgba(148,163,184,0.1))" rx="4"/><path d="M36 20c0 8.837-7.163 16-16 16S4 28.837 4 20 11.163 4 20 4s16 7.163 16 16z" fill="rgba(148,163,184,0.08)"/><path d="M14 16a2 2 0 100-4 2 2 0 000 4zM28 12l-6 8-4-4-8 10h20l-2-14z" fill="rgba(148,163,184,0.25)"/></svg>
                                        </span>
                                    </div>
                                </template>
                                <template x-if="!file.is_image && file.is_video">
                                    <span class="media-picker-library__drawer-icon">
                                        <svg viewBox="0 0 40 40" style="width:100%;height:100%"><rect width="40" height="40" fill="rgba(148,163,184,0.1)" rx="4"/><path d="M16 13.33v13.34l10.67-6.67z" fill="#9ca3af"/></svg>
                                    </span>
                                </template>
                                <template x-if="!file.is_image && !file.is_video">
                                    <span class="media-picker-library__drawer-icon">
                                        <svg viewBox="0 0 40 40" style="width:100%;height:100%"><rect width="40" height="40" fill="rgba(148,163,184,0.06)" rx="4"/><path d="M22 10H14a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V14l-6-4z" fill="rgba(148,163,184,0.2)" stroke="rgba(148,163,184,0.3)"/><path d="M22 10v4h4" fill="none" stroke="rgba(148,163,184,0.3)"/></svg>
                                    </span>
                                </template>
                                <span class="media-picker-library__drawer-name" x-text="file.name"></span>
                            </button>
                        </template>
                    </div>

                    <template x-if="libraryHasMore">
                        <div class="media-picker-library__drawer-more">
                            <button type="button" class="media-picker-library__drawer-load" @click="loadMore()">
                                Load more
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </template>

    <style>
        .visual-media-picker { display:flex; flex-direction:column; gap:0.75rem; }
        .media-picker-library__label { font-size:0.875rem; font-weight:600; color:var(--color-white, #f3f4f6); margin:0; }
        .media-picker-library__helper { margin:0; font-size:0.75rem; color:var(--gray-400, #9ca3af); margin-top:-0.35rem; }
        .media-picker-library__dropzone { width:100%; min-height:4.5rem; padding:0.95rem 1rem; border:1px solid var(--gray-700, rgba(148,163,184,0.45)); border-radius:0.75rem; background:rgba(255,255,255,0.04); color:inherit; text-align:center; display:flex; align-items:center; justify-content:center; transition:border-color 0.15s,background 0.15s,box-shadow 0.15s; cursor:pointer; }
        .media-picker-library__dropzone:hover { background:rgba(255,255,255,0.085); }
        .media-picker-library__dropzone.is-dragging { border-color:var(--primary-500, rgba(245,158,11,0.9)); box-shadow:0 0 0 3px color-mix(in srgb, var(--primary-500, #f59e0b) 20%, transparent); background:color-mix(in srgb, var(--primary-500, #f59e0b) 8%, transparent); }
        .media-picker-library__content { width:100%; }
        .media-picker-library__hint { margin:0; font-size:0.875rem; color:var(--gray-300, rgba(203,213,225,0.95)); line-height:1.4; }
        .media-picker-library__browse { color:var(--primary-500, #f59e0b); font-weight:600; }
        .media-picker-library__existing { display:flex; flex-direction:column; gap:0.2rem; }
        .media-picker-library__attached { margin:0; font-size:0.875rem; font-weight:600; color:var(--color-white, rgba(248,250,252,0.96)); }
        .media-picker-library__progress-ctn { margin:0 auto; max-width:28rem; display:flex; flex-direction:column; gap:0.45rem; }
        .media-picker-library__filename { margin:0; font-size:0.875rem; font-weight:600; color:var(--color-white, rgba(248,250,252,0.98)); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .media-picker-library__status { margin:0; font-size:0.875rem; color:var(--gray-300, rgba(203,213,225,0.95)); line-height:1.4; }
        .media-picker-library__bar { height:0.45rem; border-radius:9999px; overflow:hidden; background:var(--gray-700, rgba(148,163,184,0.28)); }
        .media-picker-library__bar-fill { height:100%; border-radius:9999px; background:var(--primary-500, #f59e0b); transition:width 0.25s ease; }
        .media-picker-library__preview { margin-top:0.5rem; }
        .media-picker-library__preview-media { max-height:10rem; width:100%; border-radius:0.5rem; object-fit:contain; background:rgba(0,0,0,0.03); }
        .media-picker-library__preview-link { font-size:0.875rem; color:var(--primary-500, #f59e0b); font-weight:600; }
        .media-picker-library__selected { }
        .media-picker-library__selected-link { font-size:0.875rem; color:var(--primary-500, #f59e0b); font-weight:600; }
        .media-picker-library__error { border:1px solid rgba(239,68,68,0.4); border-radius:0.75rem; background:rgba(239,68,68,0.1); color:#fca5a5; font-size:0.875rem; padding:0.65rem 0.85rem; }
        .media-picker-library__actions { display:flex; gap:0.5rem; }
        .media-picker-library__lib-btn { display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; border:1px solid var(--gray-700, rgba(148,163,184,0.35)); border-radius:0.5rem; background:rgba(255,255,255,0.04); color:var(--gray-200, rgba(248,250,252,0.85)); font-size:0.875rem; cursor:pointer; transition:border-color 0.15s,background 0.15s; }
        .media-picker-library__lib-btn:hover { border-color:var(--primary-500, #f59e0b); background:color-mix(in srgb, var(--primary-500, #f59e0b) 6%, transparent); }
        .media-picker-library__overlay { position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.65); backdrop-filter:blur(2px); }
        .media-picker-library__drawer { position:absolute; top:0; right:0; bottom:0; width:min(100%, 420px); background:var(--gray-900, #111827); display:flex; flex-direction:column; box-shadow:0 0 0 1px rgba(255,255,255,0.1), -8px 0 30px rgba(0,0,0,0.5); }
        .media-picker-library__drawer-header { display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid rgba(255,255,255,0.06); }
        .media-picker-library__drawer-title { font-size:1rem; font-weight:600; color:var(--color-white, #fff); margin:0; line-height:1.5; letter-spacing:-0.01em; }
        .media-picker-library__drawer-close { display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; border:0; border-radius:0.5rem; background:transparent; color:var(--gray-400, #9ca3af); cursor:pointer; transition:background 0.15s,color 0.15s; }
        .media-picker-library__drawer-close:hover { background:rgba(255,255,255,0.1); color:var(--color-white, #fff); }
        .media-picker-library__drawer-search { padding:0.75rem 1.5rem; border-bottom:1px solid rgba(255,255,255,0.06); }
        .media-picker-library__drawer-input { width:100%; padding:0.55rem 0.8rem; border:1px solid var(--gray-700, rgba(148,163,184,0.25)); border-radius:0.5rem; background:var(--gray-800, rgba(148,163,184,0.06)); color:var(--color-white, #f3f4f6); font-size:0.875rem; outline:none; transition:border-color 0.15s,box-shadow 0.15s; }
        .media-picker-library__drawer-input::placeholder { color:var(--gray-500, #6b7280); }
        .media-picker-library__drawer-input:focus { border-color:var(--primary-500, #f59e0b); box-shadow:0 0 0 1px var(--primary-500, #f59e0b); }
        .media-picker-library__drawer-body { flex:1; overflow-y:auto; padding:1.5rem 1.5rem; }
        .media-picker-library__drawer-empty { text-align:center; padding:2rem 0; color:var(--gray-500, #6b7280); font-size:0.875rem; }
        .media-picker-library__drawer-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(5.5rem,1fr)); gap:0.5rem; }
        .media-picker-library__drawer-item { display:flex; flex-direction:column; align-items:center; gap:0.25rem; padding:0.35rem; border:2px solid transparent; border-radius:0.5rem; cursor:pointer; background:rgba(255,255,255,0.04); transition:border-color 0.12s,background 0.12s; text-align:center; }
        .media-picker-library__drawer-item:hover { background:rgba(255,255,255,0.08); }
        .media-picker-library__drawer-item.is-selected { border-color:var(--primary-500, #f59e0b); background:color-mix(in srgb, var(--primary-500, #f59e0b) 10%, transparent); }
        .media-picker-library__drawer-thumb-wrap { width:100%; position:relative; }
        .media-picker-library__drawer-thumb { width:100%; aspect-ratio:1; object-fit:cover; border-radius:0.3rem; background:var(--gray-800, rgba(0,0,0,0.2)); }
        .media-picker-library__drawer-icon { width:100%; aspect-ratio:1; display:flex; align-items:center; justify-content:center; }
        .media-picker-library__drawer-name { font-size:0.6rem; color:var(--gray-400, #9ca3af); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:100%; }
        .media-picker-library__drawer-more { text-align:center; padding:0.75rem 0; }
        .media-picker-library__drawer-load { padding:0.4rem 1rem; border:1px solid var(--gray-700, rgba(148,163,184,0.3)); border-radius:0.375rem; background:transparent; color:var(--gray-400, #9ca3af); font-size:0.8125rem; cursor:pointer; transition:border-color 0.15s,color 0.15s; }
        .media-picker-library__drawer-load:hover { border-color:var(--primary-500, #f59e0b); color:var(--primary-500, #f59e0b); }
    </style>

    <script>
        function visualMediaPicker(config) {
            return {
                statePath: config.statePath,
                existingPath: config.existingPath,
                directory: config.directory,
                csrf: config.csrf,
                chunkSize: 10 * 1024 * 1024,
                selectedPath: config.existingPath || '',
                selectedPreviewUrl: '',
                selectedName: '',
                selectedIsImage: false,
                selectedIsVideo: false,
                fileName: '',
                progress: 0,
                status: '',
                errorMessage: '',
                isDragging: false,
                speed: '',
                uploadedBytes: 0,
                uploadStartTime: null,
                previewUrl: '',
                isImage: false,
                isVideo: false,
                imageOnly: config.imageOnly,
                acceptedTypes: config.acceptedTypes,
                libraryOpen: false,
                libraryFiles: [],
                libraryLoading: false,
                librarySearch: '',
                libraryPage: 1,
                libraryHasMore: false,

                init() {
                    if (this.selectedPath) {
                        this.selectedName = this.selectedPath.split('/').pop();
                    }
                },

                async setFormValue(path, value) {
                    const el = this.$root.closest('[wire\\:id]');
                    const wireId = el?.getAttribute('wire:id');
                    if (wireId && window.Livewire) {
                        const comp = window.Livewire.find(wireId);
                        if (comp?.set) { comp.set(path, value, false); return; }
                    }
                    if (typeof this.$wire !== 'undefined') {
                        this.$wire.set(path, value, false); return;
                    }
                },

                openFilePicker() {
                    const self = this;
                    const input = document.createElement('input');
                    input.type = 'file';
                    if (this.acceptedTypes) input.accept = this.acceptedTypes;
                    input.style.cssText = 'position:fixed;opacity:0;visibility:hidden;pointer-events:none';
                    input.tabIndex = -1;
                    document.body.appendChild(input);
                    input.addEventListener('change', function onChange() {
                        input.removeEventListener('change', onChange);
                        document.body.removeChild(input);
                        if (this.files?.[0]) self.handleUpload({ target: { files: this.files } });
                    });
                    input.click();
                },

                uploadWithProgress(formData, chunkIndex, totalChunks) {
                    const self = this;
                    return new Promise((resolve, reject) => {
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', config.uploadChunkUrl);
                        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                        xhr.setRequestHeader('Accept', 'application/json');
                        xhr.upload.onprogress = (e) => {
                            if (e.lengthComputable) {
                                const chunkProgress = e.loaded / e.total;
                                const totalBytesSoFar = self.uploadedBytes + e.loaded;
                                const elapsed = (Date.now() - self.uploadStartTime) / 1000;
                                const speedBps = elapsed > 0 ? totalBytesSoFar / elapsed : 0;
                                self.speed = `${((speedBps * 8) / (1024 * 1024)).toFixed(1)} Mbps`;
                                self.progress = Math.round(((chunkIndex + chunkProgress) / totalChunks) * 90);
                            }
                        };
                        xhr.onload = () => resolve({ ok: xhr.status >= 200 && xhr.status < 300, status: xhr.status, text() { return xhr.responseText; } });
                        xhr.onerror = () => reject(new Error('Network error during upload.'));
                        xhr.onabort = () => reject(new Error('Upload aborted.'));
                        xhr.send(formData);
                    });
                },

                async handleUpload(event) {
                    const file = event.target.files?.[0];
                    if (!file) return;

                    this.fileName = file.name;
                    this.progress = 0;
                    this.errorMessage = '';
                    this.status = 'Preparing upload...';
                    this.uploadedBytes = 0;
                    this.uploadStartTime = Date.now();
                    this.speed = '';
                    this.previewUrl = '';
                    this.isImage = /\.(jpg|jpeg|png|gif|webp|svg|bmp|ico)$/i.test(file.name);
                    this.isVideo = /\.(mp4|webm|mov|avi|mkv|flv|wmv|m4v)$/i.test(file.name);

                    const uploadId = Date.now() + '-' + Math.random().toString(36).slice(2, 12);
                    const totalChunks = Math.ceil(file.size / this.chunkSize);

                    try {
                        for (let chunkIndex = 0; chunkIndex < totalChunks; chunkIndex++) {
                            const start = chunkIndex * this.chunkSize;
                            const end = Math.min(start + this.chunkSize, file.size);
                            const blob = file.slice(start, end);
                            const fd = new FormData();
                            fd.append('_token', this.csrf);
                            fd.append('upload_id', uploadId);
                            fd.append('chunk_index', chunkIndex);
                            fd.append('total_chunks', totalChunks);
                            fd.append('original_name', file.name);
                            fd.append('chunk', blob, file.name + '.part');
                            this.status = `Uploading chunk ${chunkIndex + 1} of ${totalChunks}...`;
                            const res = await this.uploadWithProgress(fd, chunkIndex, totalChunks);
                            if (!res.ok) throw new Error((await res.text()) || `Chunk ${chunkIndex + 1} failed`);
                            this.uploadedBytes += blob.size;
                            this.progress = Math.round(((chunkIndex + 1) / totalChunks) * 90);
                        }

                        this.status = 'Finalizing upload...';
                        this.progress = 95;
                        const res = await fetch(config.finalizeUrl, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                            body: JSON.stringify({ upload_id: uploadId, total_chunks: totalChunks, original_name: file.name, directory: this.directory }),
                        });
                        if (!res.ok) throw new Error((await res.text()) || 'Finalize failed');
                        const payload = await res.json();

                        this.previewUrl = payload.url;
                        await this.setFormValue(this.statePath, payload.path);
                        this.selectedPath = payload.path;
                        this.selectedName = file.name;
                        this.selectedPreviewUrl = payload.url;
                        this.progress = 100;
                        this.status = 'Upload complete.';
                        this.errorMessage = '';
                    } catch (e) {
                        this.errorMessage = e.message || 'Upload failed';
                        this.status = 'Upload failed.';
                    }
                },

                async openLibrary() {
                    this.libraryOpen = true;
                    this.libraryPage = 1;
                    this.librarySearch = '';
                    this.libraryFiles = [];
                    await this.fetchLibrary();
                },

                closeLibrary() {
                    this.libraryOpen = false;
                },

                async fetchLibrary() {
                    this.libraryLoading = true;
                    try {
                        const params = new URLSearchParams({ page: this.libraryPage, per_page: 50 });
                        if (this.librarySearch) params.set('search', this.librarySearch);
                        if (this.imageOnly) params.set('type', 'image');
                        const res = await fetch(config.listUrl + '?' + params.toString());
                        if (!res.ok) throw new Error('Failed to load library');
                        const data = await res.json();
                        if (this.libraryPage === 1) {
                            this.libraryFiles = data.data || data;
                        } else {
                            this.libraryFiles = this.libraryFiles.concat(data.data || data);
                        }
                        this.libraryHasMore = data.next_page_url || (data.data && data.data.length >= 50);
                    } catch (e) {
                        console.error('Library fetch error:', e);
                    } finally {
                        this.libraryLoading = false;
                    }
                },

                async loadMore() {
                    this.libraryPage++;
                    await this.fetchLibrary();
                },

                selectFromLibrary(file) {
                    this.selectedPath = file.path;
                    this.selectedPreviewUrl = file.url;
                    this.selectedName = file.name;
                    this.selectedIsImage = file.is_image;
                    this.selectedIsVideo = file.is_video;
                    this.fileName = '';
                    this.errorMessage = '';
                    this.setFormValue(this.statePath, file.path);
                    this.closeLibrary();
                },
            };
        }
    </script>
</div>
