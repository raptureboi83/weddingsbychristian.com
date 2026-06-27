@php
    $statePath = $livewireStatePath ?? $getStatePath();
    $uploadUrl = route('filament.admin.media.upload.chunk');
    $finalizeUrl = route('filament.admin.media.upload.finalize');
    $uploadDir = $uploadDirectory ?? 'media';
@endphp

<div
    x-data="chunkedMediaUpload({
        uploadUrl: @js($uploadUrl),
        finalizeUrl: @js($finalizeUrl),
        statePath: @js($statePath),
        directory: @js($uploadDir),
        csrf: @js(csrf_token()),
    })"
    wire:ignore
    class="chunked-media-upload"
>
    <button
        type="button"
        @click="openFilePicker()"
        @dragover.prevent="isDragging = true"
        @dragleave.prevent="isDragging = false"
        @drop.prevent="
            isDragging = false;
            if ($event.dataTransfer.files.length) {
                file = $event.dataTransfer.files[0];
                handleFile({ target: { files: [file] } });
            }
        "
        class="chunked-media-upload__dropzone"
        :class="{ 'is-dragging': isDragging }"
    >
        <div class="chunked-media-upload__content">
            <template x-if="!fileName">
                <p class="chunked-media-upload__hint">
                    Drag &amp; drop a file or
                    <span class="chunked-media-upload__browse">browse</span>
                    to upload
                </p>
            </template>

            <template x-if="fileName">
                <div class="chunked-media-upload__progress-ctn">
                    <p class="chunked-media-upload__filename" x-text="fileName"></p>
                    <p class="chunked-media-upload__status" x-text="status"></p>

                    <div class="chunked-media-upload__bar">
                        <div
                            class="chunked-media-upload__bar-fill"
                            :style="`width: ${progress}%`"
                        ></div>
                    </div>

<p class="chunked-media-upload__status">
    <span x-text="progress"></span>% uploaded
    <template x-if="speed">
        <span style="opacity:0.6;margin-left:0.5rem" x-text="`(${speed})`"></span>
    </template>
</p>

                    <template x-if="previewUrl">
                        <div class="chunked-media-upload__preview">
                            <template x-if="isImage">
                                <img :src="previewUrl" class="chunked-media-upload__preview-media" />
                            </template>
                            <template x-if="isVideo">
                                <video :src="previewUrl" controls class="chunked-media-upload__preview-media"></video>
                            </template>
                            <template x-if="!isImage && !isVideo">
                                <a :href="previewUrl" target="_blank" class="chunked-media-upload__preview-link">
                                    View file &rarr;
                                </a>
                            </template>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </button>

    <template x-if="errorMessage">
        <div class="chunked-media-upload__error" x-text="errorMessage"></div>
    </template>

    <style>
        .chunked-media-upload {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .chunked-media-upload__input {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .chunked-media-upload__dropzone {
            width: 100%;
            min-height: 4.5rem;
            padding: 0.95rem 1rem;
            border: 1px solid rgba(148, 163, 184, 0.45);
            border-radius: 0.75rem;
            background: rgba(148, 163, 184, 0.06);
            color: inherit;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: border-color 0.15s ease, background-color 0.15s ease, box-shadow 0.15s ease;
            cursor: pointer;
        }

        .chunked-media-upload__dropzone:hover {
            background: rgba(148, 163, 184, 0.1);
        }

        .chunked-media-upload__dropzone.is-dragging {
            border-color: rgba(245, 158, 11, 0.9);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
            background: rgba(245, 158, 11, 0.08);
        }

        .chunked-media-upload__content {
            width: 100%;
        }

        .chunked-media-upload__hint {
            margin: 0;
            font-size: 0.875rem;
            color: rgba(203, 213, 225, 0.95);
            line-height: 1.4;
        }

        .chunked-media-upload__browse {
            color: #f59e0b;
            font-weight: 600;
        }

        .chunked-media-upload__progress-ctn {
            margin: 0 auto;
            max-width: 28rem;
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
        }

        .chunked-media-upload__filename {
            margin: 0;
            font-size: 0.875rem;
            font-weight: 600;
            color: rgba(248, 250, 252, 0.98);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chunked-media-upload__status {
            margin: 0;
            font-size: 0.875rem;
            color: rgba(203, 213, 225, 0.95);
            line-height: 1.4;
        }

        .chunked-media-upload__bar {
            height: 0.45rem;
            border-radius: 9999px;
            overflow: hidden;
            background: rgba(148, 163, 184, 0.28);
        }

        .chunked-media-upload__bar-fill {
            height: 100%;
            border-radius: 9999px;
            background: #f59e0b;
            transition: width 0.25s ease;
        }

        .chunked-media-upload__preview {
            margin-top: 0.5rem;
        }

        .chunked-media-upload__preview-media {
            max-height: 10rem;
            width: 100%;
            border-radius: 0.5rem;
            object-fit: contain;
            background: rgba(0,0,0,0.03);
        }

        .chunked-media-upload__preview-link {
            font-size: 0.875rem;
            color: #f59e0b;
            font-weight: 600;
            text-decoration: underline;
        }

        .chunked-media-upload__error {
            border: 1px solid rgba(239, 68, 68, 0.4);
            border-radius: 0.75rem;
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            font-size: 0.875rem;
            padding: 0.65rem 0.85rem;
        }
    </style>

    <script>
        function chunkedMediaUpload(config) {
            return {
                uploadUrl: config.uploadUrl,
                finalizeUrl: config.finalizeUrl,
                statePath: config.statePath,
                directory: config.directory,
                csrf: config.csrf,
                chunkSize: 10 * 1024 * 1024,
                fileName: '',
                progress: 0,
                status: 'Waiting for file',
                errorMessage: '',
                isDragging: false,
                speed: '',
                uploadedBytes: 0,
                uploadStartTime: null,
                previewUrl: '',
                isImage: false,
                isVideo: false,

                openFilePicker() {
                    const self = this;
                    const input = document.createElement('input');
                    input.type = 'file';
                    input.style.position = 'fixed';
                    input.style.opacity = '0';
                    input.style.visibility = 'hidden';
                    input.style.pointerEvents = 'none';
                    input.tabIndex = -1;
                    document.body.appendChild(input);

                    input.addEventListener('change', function onChange() {
                        input.removeEventListener('change', onChange);
                        document.body.removeChild(input);
                        if (this.files?.[0]) {
                            self.handleFile({ target: { files: this.files } });
                        }
                    });

                    input.click();
                },

                async setFormValue(path, value) {
                    const el = this.$root.closest('[wire\\:id]');
                    const wireId = el?.getAttribute('wire:id');

                    if (wireId && window.Livewire) {
                        const comp = window.Livewire.find(wireId)
                        if (comp?.set) {
                            comp.set(path, value, false)
                            return
                        }
                    }

                    if (typeof this.$wire !== 'undefined') {
                        this.$wire.set(path, value, false)
                        return
                    }

                    throw new Error(`Unable to update form state for ${path}.`)
                },

                uploadWithProgress(formData, chunkIndex, totalChunks) {
                    const self = this;
                    return new Promise((resolve, reject) => {
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', this.uploadUrl);
                        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                        xhr.setRequestHeader('Accept', 'application/json');

                        xhr.upload.onprogress = (e) => {
                            if (e.lengthComputable) {
                                const chunkProgress = e.loaded / e.total;
                                const bytesForCurrentChunk = e.loaded;
                                const totalBytesSoFar = self.uploadedBytes + bytesForCurrentChunk;
                                const elapsed = (Date.now() - self.uploadStartTime) / 1000;
                                const speedBps = elapsed > 0 ? totalBytesSoFar / elapsed : 0;
                                const speedMbps = (speedBps * 8) / (1024 * 1024);
                                self.speed = `${speedMbps.toFixed(1)} Mbps`;
                                self.progress = Math.round(((chunkIndex + chunkProgress) / totalChunks) * 90);
                            }
                        };

                        xhr.onload = () => resolve({
                            ok: xhr.status >= 200 && xhr.status < 300,
                            status: xhr.status,
                            async text() { return xhr.responseText; },
                        });

                        xhr.onerror = () => reject(new Error('Network error during upload.'));
                        xhr.onabort = () => reject(new Error('Upload aborted.'));

                        xhr.send(formData);
                    });
                },

                async handleFile(event) {
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

                    const uploadId = `${Date.now()}-${Math.random().toString(36).slice(2, 12)}`;
                    const totalChunks = Math.ceil(file.size / this.chunkSize);

                    try {
                        for (let chunkIndex = 0; chunkIndex < totalChunks; chunkIndex++) {
                            const start = chunkIndex * this.chunkSize;
                            const end = Math.min(start + this.chunkSize, file.size);
                            const blob = file.slice(start, end);

                            const formData = new FormData();
                            formData.append('_token', this.csrf);
                            formData.append('upload_id', uploadId);
                            formData.append('chunk_index', chunkIndex);
                            formData.append('total_chunks', totalChunks);
                            formData.append('original_name', file.name);
                            formData.append('chunk', blob, `${file.name}.part`);

                            this.status = `Uploading chunk ${chunkIndex + 1} of ${totalChunks}...`;

                            const response = await this.uploadWithProgress(formData, chunkIndex, totalChunks);

                            if (!response.ok) {
                                const text = await response.text();
                                throw new Error(text || `Chunk upload failed on part ${chunkIndex + 1}.`);
                            }

                            this.uploadedBytes += blob.size;
                            this.progress = Math.round(((chunkIndex + 1) / totalChunks) * 90);
                        }

                        this.status = 'Finalizing upload...';

                        const finalizeResponse = await fetch(this.finalizeUrl, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrf,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                upload_id: uploadId,
                                total_chunks: totalChunks,
                                original_name: file.name,
                                directory: this.directory,
                            }),
                        });

                        if (!finalizeResponse.ok) {
                            const text = await finalizeResponse.text();
                            throw new Error(text || 'Upload completed, but final assembly failed.');
                        }

                        const payload = await finalizeResponse.json();

                        this.previewUrl = payload.url;

                        await this.setFormValue(this.statePath, payload.path);

                        this.progress = 100;
                        this.status = 'Upload complete.';
                        this.errorMessage = '';
                    } catch (error) {
                        this.errorMessage = error.message || 'Upload failed.';
                        this.status = 'Upload failed.';
                    }
                },
            };
        }
    </script>
</div>
