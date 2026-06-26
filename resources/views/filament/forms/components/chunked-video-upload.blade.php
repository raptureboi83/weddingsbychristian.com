@php
    $statePath = 'data.video_path';
    $existingVideoPath = data_get($this->data ?? [], 'video_path');
@endphp

<div
    x-data="chunkedVideoUpload({
        uploadUrl: @js($uploadUrl),
        finalizeUrl: @js($finalizeUrl),
        statePath: @js($statePath),
        existingPath: @js($existingVideoPath),
        csrf: @js(csrf_token()),
    })"
    class="chunked-video-upload"
>
    <input
        x-ref="fileInput"
        type="file"
        accept="video/mp4,video/quicktime,video/webm,.mp4,.mov,.webm"
        @change="handleFile($event)"
        class="chunked-video-upload__input"
        aria-hidden="true"
        tabindex="-1"
    />

    <button
        type="button"
        @click="$refs.fileInput.click()"
        @dragover.prevent="isDragging = true"
        @dragleave.prevent="isDragging = false"
        @drop.prevent="
            isDragging = false;
            if ($event.dataTransfer.files.length) {
                $refs.fileInput.files = $event.dataTransfer.files;
                handleFile({ target: $refs.fileInput });
            }
        "
        class="chunked-video-upload__dropzone"
        :class="{
            'is-dragging': isDragging
        }"
    >
        <div class="chunked-video-upload__content">
            <template x-if="!fileName && !existingPath">
                <p class="chunked-video-upload__hint">
                    Drag & Drop your files or
                    <span class="chunked-video-upload__browse">Browse</span>
                </p>
            </template>

            <template x-if="existingPath && !fileName">
                <div class="chunked-video-upload__existing">
                    <p class="chunked-video-upload__attached">Existing video attached</p>
                    <p class="chunked-video-upload__hint">
                        Drag & Drop your files or
                        <span class="chunked-video-upload__browse">Browse</span>
                        to replace it
                    </p>
                </div>
            </template>

            <template x-if="fileName">
                <div class="chunked-video-upload__progress-ctn">
                    <p class="chunked-video-upload__filename" x-text="fileName"></p>
                    <p class="chunked-video-upload__status" x-text="status"></p>

                    <div class="chunked-video-upload__bar">
                        <div
                            class="chunked-video-upload__bar-fill"
                            :style="`width: ${progress}%`"
                        ></div>
                    </div>

                    <p class="chunked-video-upload__status">
                        <span x-text="progress"></span>% uploaded
                    </p>
                </div>
            </template>
        </div>
    </button>

    <p class="chunked-video-upload__helper">
        Upload the film file using chunked upload in the background.
    </p>

    <template x-if="errorMessage">
        <div class="chunked-video-upload__error" x-text="errorMessage"></div>
    </template>

    <style>
        .chunked-video-upload {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .chunked-video-upload__input {
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

        .chunked-video-upload__dropzone {
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

        .chunked-video-upload__dropzone:hover {
            background: rgba(148, 163, 184, 0.1);
        }

        .chunked-video-upload__dropzone.is-dragging {
            border-color: rgba(245, 158, 11, 0.9);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
            background: rgba(245, 158, 11, 0.08);
        }

        .chunked-video-upload__content {
            width: 100%;
        }

        .chunked-video-upload__hint,
        .chunked-video-upload__helper,
        .chunked-video-upload__status {
            margin: 0;
            font-size: 0.875rem;
            color: rgba(203, 213, 225, 0.95);
            line-height: 1.4;
        }

        .chunked-video-upload__browse {
            color: #f59e0b;
            font-weight: 600;
        }

        .chunked-video-upload__existing {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .chunked-video-upload__attached {
            margin: 0;
            font-size: 0.875rem;
            font-weight: 600;
            color: rgba(248, 250, 252, 0.96);
        }

        .chunked-video-upload__progress-ctn {
            margin: 0 auto;
            max-width: 28rem;
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
        }

        .chunked-video-upload__filename {
            margin: 0;
            font-size: 0.875rem;
            font-weight: 600;
            color: rgba(248, 250, 252, 0.98);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chunked-video-upload__bar {
            height: 0.45rem;
            border-radius: 9999px;
            overflow: hidden;
            background: rgba(148, 163, 184, 0.28);
        }

        .chunked-video-upload__bar-fill {
            height: 100%;
            border-radius: 9999px;
            background: #f59e0b;
            transition: width 0.25s ease;
        }

        .chunked-video-upload__helper {
            font-size: 0.875rem;
        }

        .chunked-video-upload__error {
            border: 1px solid rgba(239, 68, 68, 0.4);
            border-radius: 0.75rem;
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            font-size: 0.875rem;
            padding: 0.65rem 0.85rem;
        }
    </style>

    <script>
        function chunkedVideoUpload(config) {
            return {
                uploadUrl: config.uploadUrl,
                finalizeUrl: config.finalizeUrl,
                statePath: config.statePath,
                existingPath: config.existingPath ?? null,
                csrf: config.csrf,
                chunkSize: 25 * 1024 * 1024,
                fileName: '',
                progress: 0,
                status: 'Waiting for file',
                errorMessage: '',
                isDragging: false,

                async setFormValue(path, value) {
                    if (typeof this.$wire !== 'undefined') {
                        return await this.$wire.set(path, value);
                    }

                    const component = this.$root.closest('[wire\\:id]');
                    const wireId = component ? component.getAttribute('wire:id') : null;

                    if (wireId && window.Livewire) {
                        return await window.Livewire.find(wireId).set(path, value);
                    }

                    throw new Error(`Unable to update form state for ${path}.`);
                },

                async handleFile(event) {
                    const file = event.target.files?.[0];
                    if (!file) return;

                    this.fileName = file.name;
                    this.progress = 0;
                    this.errorMessage = '';
                    this.status = 'Preparing upload...';

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

                            const response = await fetch(this.uploadUrl, {
                                method: 'POST',
                                body: formData,
                                credentials: 'same-origin',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                },
                            });

                            if (!response.ok) {
                                const text = await response.text();
                                throw new Error(text || `Chunk upload failed on part ${chunkIndex + 1}.`);
                            }

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
                            }),
                        });

                        if (!finalizeResponse.ok) {
                            const text = await finalizeResponse.text();
                            throw new Error(text || 'Upload completed, but final assembly failed.');
                        }

                        const payload = await finalizeResponse.json();

                        await this.setFormValue(this.statePath, payload.path);

                        this.progress = 100;
                        this.status = 'Upload complete.';
                        this.existingPath = payload.path;
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