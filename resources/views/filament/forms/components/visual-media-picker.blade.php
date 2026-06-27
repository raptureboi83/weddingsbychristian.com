@php
    $chunkUrl = route('filament.admin.media.upload.chunk');
    $finalizeUrl = route('filament.admin.media.upload.finalize');
    $listUrl = route('filament.admin.media.list');
@endphp

<div
    x-data="visualMediaPicker({
        statePath: @js($livewireStatePath),
        files: @js($mediaFiles),
        uploadChunkUrl: @js($uploadChunkUrl),
        uploadFinalizeUrl: @js($finalizeUrl),
        listUrl: @js($listUrl),
        directory: @js($uploadDirectory),
        csrf: @js(csrf_token()),
        acceptedFileTypes: @js($acceptedFileTypes),
    })"
    wire:ignore
    class="visual-media-picker"
>
    <input type="hidden" :value="selectedPath" x-bind:name="statePath" />

    <div class="vmp-current" x-show="selectedPath" style="display:none">
        <template x-if="selectedPreviewUrl">
            <div class="vmp-current-preview">
                <template x-if="selectedIsImage">
                    <img :src="selectedPreviewUrl" class="vmp-current-img" />
                </template>
                <template x-if="!selectedIsImage && selectedIsVideo">
                    <video :src="selectedPreviewUrl" controls class="vmp-current-img"></video>
                </template>
                <template x-if="!selectedIsImage && !selectedIsVideo">
                    <a :href="selectedPreviewUrl" target="_blank" class="vmp-current-link">View file &rarr;</a>
                </template>
            </div>
        </template>
        <p class="vmp-current-name" x-text="selectedName"></p>
    </div>

    <div class="vmp-grid-label">Choose from library</div>
    <div class="vmp-grid" x-show="files.length" :class="{ 'vmp-grid-empty': !files.length }">
        <template x-for="(file, i) in files" :key="file.path">
            <button
                type="button"
                class="vmp-item"
                :class="{ 'vmp-item-selected': selectedPath === file.path }"
                @click="selectFile(file)"
                :title="file.name"
            >
                <template x-if="file.is_image">
                    <img :src="file.url" class="vmp-item-thumb" loading="lazy" />
                </template>
                <template x-if="!file.is_image && file.is_video">
                    <span class="vmp-item-video-icon">
                        <svg viewBox="0 0 40 40" style="width:100%;height:100%"><rect width="40" height="40" fill="#f3f4f6" rx="4"/><path d="M16 13.33v13.34l10.67-6.67z" fill="#9ca3af"/></svg>
                    </span>
                </template>
                <template x-if="!file.is_image && !file.is_video">
                    <span class="vmp-item-file-icon">
                        <svg viewBox="0 0 40 40" style="width:100%;height:100%"><rect width="40" height="40" fill="#f3f4f6" rx="4"/><path d="M22 10H14a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V14l-6-4z" fill="#d1d5db" stroke="#9ca3af"/><path d="M22 10v4h4" fill="none" stroke="#9ca3af"/></svg>
                    </span>
                </template>
                <span class="vmp-item-name" x-text="file.name"></span>
            </button>
        </template>
    </div>
    <p class="vmp-empty" x-show="!files.length">No media files yet.</p>

    <div class="vmp-upload-area">
        <button type="button" class="vmp-upload-btn" @click="openFilePicker()">
            <template x-if="!isUploading">
                <span>+ Upload new file</span>
            </template>
            <template x-if="isUploading">
                <span>Uploading&hellip;</span>
            </template>
        </button>

        <template x-if="isUploading">
            <div class="vmp-upload-progress">
                <p class="vmp-upload-status" x-text="uploadStatus"></p>
                <div class="vmp-upload-bar"><div class="vmp-upload-bar-fill" :style="`width:${uploadProgress}%`"></div></div>
            </div>
        </template>

        <template x-if="uploadError">
            <p class="vmp-upload-error" x-text="uploadError"></p>
        </template>
    </div>

    <style>
        .visual-media-picker { display:flex; flex-direction:column; gap:0.75rem; }
        .vmp-current { display:flex; flex-direction:column; gap:0.25rem; }
        .vmp-current-preview { }
        .vmp-current-img { max-height:8rem; width:100%; border-radius:0.375rem; object-fit:contain; background:rgba(0,0,0,0.03); }
        .vmp-current-link { font-size:0.875rem; color:#f59e0b; font-weight:600; }
        .vmp-current-name { font-size:0.875rem; font-weight:600; color:rgba(248,250,252,0.9); }
        .vmp-grid-label { font-size:0.8125rem; font-weight:500; color:rgba(203,213,225,0.8); text-transform:uppercase; letter-spacing:0.05em; }
        .vmp-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(5rem,1fr)); gap:0.5rem; max-height:12rem; overflow-y:auto; padding:0.25rem 0; }
        .vmp-grid-empty { min-height:3rem; }
        .vmp-item { display:flex; flex-direction:column; align-items:center; gap:0.2rem; padding:0.35rem; border:2px solid transparent; border-radius:0.5rem; cursor:pointer; background:rgba(148,163,184,0.06); transition:border-color 0.12s,background 0.12s; text-align:center; }
        .vmp-item:hover { background:rgba(148,163,184,0.12); }
        .vmp-item-selected { border-color:#f59e0b; background:rgba(245,158,11,0.1); }
        .vmp-item-thumb { width:100%; aspect-ratio:1; object-fit:cover; border-radius:0.25rem; }
        .vmp-item-video-icon, .vmp-item-file-icon { width:100%; aspect-ratio:1; display:flex; align-items:center; justify-content:center; }
        .vmp-item-name { font-size:0.675rem; color:rgba(203,213,225,0.8); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:100%; }
        .vmp-empty { font-size:0.875rem; color:rgba(203,213,225,0.5); }
        .vmp-upload-area { margin-top:0.25rem; }
        .vmp-upload-btn { display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; border:1px dashed rgba(148,163,184,0.4); border-radius:0.5rem; background:rgba(148,163,184,0.04); color:rgba(248,250,252,0.85); font-size:0.875rem; cursor:pointer; transition:border-color 0.15s,background 0.15s; }
        .vmp-upload-btn:hover { border-color:#f59e0b; background:rgba(245,158,11,0.06); }
        .vmp-upload-progress { margin-top:0.5rem; display:flex; flex-direction:column; gap:0.3rem; }
        .vmp-upload-status { font-size:0.8125rem; color:rgba(203,213,225,0.8); margin:0; }
        .vmp-upload-bar { height:0.35rem; border-radius:9999px; overflow:hidden; background:rgba(148,163,184,0.28); }
        .vmp-upload-bar-fill { height:100%; border-radius:9999px; background:#f59e0b; transition:width 0.25s; }
        .vmp-upload-error { font-size:0.8125rem; color:#fca5a5; margin-top:0.25rem; }
    </style>

    <script>
        function visualMediaPicker(config) {
            return {
                statePath: config.statePath,
                files: config.files,
                selectedPath: '',
                selectedPreviewUrl: '',
                selectedName: '',
                selectedIsImage: false,
                selectedIsVideo: false,
                isUploading: false,
                uploadProgress: 0,
                uploadStatus: '',
                uploadError: '',
                chunkSize: 10 * 1024 * 1024,

                init() {
                    const existing = this.$root.querySelector('input[type="hidden"]');
                    if (existing?.value) {
                        const match = this.files.find(f => f.path === existing.value);
                        if (match) this.selectFile(match);
                    }
                },
                acceptedTypes: config.acceptedFileTypes || '',

                selectFile(file) {
                    this.selectedPath = file.path;
                    this.selectedPreviewUrl = file.url;
                    this.selectedName = file.name;
                    this.selectedIsImage = file.is_image;
                    this.selectedIsVideo = file.is_video;
                    this.setFormValue(this.statePath, file.path);
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
                        if (this.files?.[0]) self.handleUpload(this.files[0]);
                    });
                    input.click();
                },

                async handleUpload(file) {
                    this.isUploading = true;
                    this.uploadProgress = 0;
                    this.uploadStatus = 'Preparing...';
                    this.uploadError = '';
                    const uploadId = Date.now() + '-' + Math.random().toString(36).slice(2, 12);
                    const totalChunks = Math.ceil(file.size / this.chunkSize);

                    try {
                        for (let i = 0; i < totalChunks; i++) {
                            const start = i * this.chunkSize;
                            const end = Math.min(start + this.chunkSize, file.size);
                            const fd = new FormData();
                            fd.append('_token', config.csrf);
                            fd.append('upload_id', uploadId);
                            fd.append('chunk_index', i);
                            fd.append('total_chunks', totalChunks);
                            fd.append('original_name', file.name);
                            fd.append('chunk', file.slice(start, end), file.name + '.part');
                            this.uploadStatus = `Uploading ${i+1}/${totalChunks}...`;
                            this.uploadProgress = Math.round((i / totalChunks) * 90);

                            const res = await fetch(config.uploadChunkUrl, {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': config.csrf, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                                body: fd,
                            });
                            if (!res.ok) throw new Error((await res.text()) || `Chunk ${i+1} failed`);
                        }

                        this.uploadStatus = 'Finalizing...';
                        this.uploadProgress = 95;
                        const res = await fetch(config.uploadFinalizeUrl, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': config.csrf, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                            body: JSON.stringify({ upload_id: uploadId, total_chunks: totalChunks, original_name: file.name, directory: config.directory }),
                        });
                        if (!res.ok) throw new Error((await res.text()) || 'Finalize failed');
                        const payload = await res.json();
                        this.uploadProgress = 100;
                        this.uploadStatus = 'Complete';

                        const newFile = payload.media_file || {
                            path: payload.path,
                            name: file.name,
                            url: payload.url,
                            type: file.type?.startsWith('video/') ? 'video' : (file.type?.startsWith('image/') ? 'image' : 'file'),
                            is_image: file.type?.startsWith('image/') ?? /\.(jpg|jpeg|png|gif|webp|svg)$/i.test(file.name),
                            is_video: file.type?.startsWith('video/') ?? /\.(mp4|webm|mov|avi|mkv)$/i.test(file.name),
                            size: '',
                        };
                        this.files.unshift(newFile);
                        this.selectFile(newFile);
                        this.isUploading = false;
                    } catch (e) {
                        this.uploadError = e.message || 'Upload failed';
                        this.isUploading = false;
                    }
                },
            };
        }
    </script>
</div>
