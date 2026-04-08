    </main>

    <footer class="site-footer">
        <div class="container footer-layout">
            <div>
                <h3><?php echo e($site_name); ?></h3>
                <p>A basic recipe website built with HTML, CSS, PHP and MySQL.</p>
            </div>
            <div>
                <p>Colorful recipe inspiration built for a school project using PHP, CSS and MySQL.</p>
                <p>&copy; <?php echo date('Y'); ?> <?php echo e($site_name); ?></p>
            </div>
        </div>
    </footer>

    <script>
        const menuButton = document.querySelector('[data-nav-toggle]');
        const navPanel = document.querySelector('[data-nav-panel]');

        if (menuButton && navPanel) {
            menuButton.addEventListener('click', function () {
                navPanel.classList.toggle('is-open');
                const expanded = navPanel.classList.contains('is-open');
                menuButton.setAttribute('aria-expanded', expanded ? 'true' : 'false');
            });
        }

        const mediaInput = document.getElementById('recipe_media');
        const previewContainer = document.getElementById('selected-media-preview');
        const dropZone = document.getElementById('upload-drop-zone');
        const form = document.getElementById('recipe-form');
        const submitButton = document.getElementById('recipe-submit-button');
        const savingNote = document.getElementById('saving-note');
        const uploadProgressShell = document.getElementById('upload-progress-shell');
        const uploadProgressBar = document.getElementById('upload-progress-bar');
        const uploadProgressText = document.getElementById('upload-progress-text');
        const coverChoiceInput = document.getElementById('cover_choice');
        const removeExistingImagesInput = document.getElementById('remove_existing_images');
        const removeExistingVideosInput = document.getElementById('remove_existing_videos');
        const uploadedImagesInput = document.getElementById('uploaded_images');
        const uploadedVideosInput = document.getElementById('uploaded_videos');
        const removeUploadedImagesInput = document.getElementById('remove_uploaded_images');
        const removeUploadedVideosInput = document.getElementById('remove_uploaded_videos');
        const maxSingleFileSize = 256 * 1024 * 1024;

        if (mediaInput && previewContainer) {
            let selectedFiles = [];
            let stagedUploads = [];
            let isUploading = false;
            const removedExistingImages = new Set();
            const removedExistingVideos = new Set();
            const removedUploadedImages = new Set();
            const removedUploadedVideos = new Set();

            function syncStagedUploadInputs() {
                if (uploadedImagesInput) {
                    uploadedImagesInput.value = stagedUploads
                        .filter(function (item) { return item.kind === 'image'; })
                        .map(function (item) { return item.path; })
                        .join('\n');
                }

                if (uploadedVideosInput) {
                    uploadedVideosInput.value = stagedUploads
                        .filter(function (item) { return item.kind === 'video'; })
                        .map(function (item) { return item.path; })
                        .join('\n');
                }

                if (removeUploadedImagesInput) {
                    removeUploadedImagesInput.value = Array.from(removedUploadedImages).join('\n');
                }

                if (removeUploadedVideosInput) {
                    removeUploadedVideosInput.value = Array.from(removedUploadedVideos).join('\n');
                }
            }

            function setUploadProgress(percent, text) {
                if (uploadProgressBar) {
                    uploadProgressBar.style.width = percent + '%';
                }

                if (uploadProgressShell) {
                    uploadProgressShell.setAttribute('aria-hidden', percent > 0 && percent < 100 ? 'false' : 'true');
                }

                if (uploadProgressText && text) {
                    uploadProgressText.textContent = text;
                }
            }

            function syncInputFiles() {
                if (typeof DataTransfer === 'undefined') {
                    return;
                }

                const transfer = new DataTransfer();

                selectedFiles.forEach(function (file) {
                    transfer.items.add(file);
                });

                mediaInput.files = transfer.files;
            }

            function syncRemovedExistingMedia() {
                if (removeExistingImagesInput) {
                    removeExistingImagesInput.value = Array.from(removedExistingImages).join('\n');
                }

                if (removeExistingVideosInput) {
                    removeExistingVideosInput.value = Array.from(removedExistingVideos).join('\n');
                }
            }

            function updateCoverButtons() {
                document.querySelectorAll('.cover-select-button').forEach(function (button) {
                    const isActive = coverChoiceInput && coverChoiceInput.value === button.dataset.coverValue;
                    button.classList.toggle('active', !!isActive);
                });

                document.querySelectorAll('.existing-image-card').forEach(function (card) {
                    const imagePath = card.dataset.mediaPath || '';
                    const isCover = coverChoiceInput && coverChoiceInput.value === 'existing:' + imagePath;
                    card.classList.toggle('is-cover', !!isCover);
                });

                document.querySelectorAll('.selected-media-card').forEach(function (card) {
                    const imageIndex = card.dataset.imageIndex;
                    const coverValue = card.dataset.coverValue || '';
                    const isCover = coverChoiceInput && coverValue !== '' && coverChoiceInput.value === coverValue;
                    card.classList.toggle('is-cover', !!isCover);
                });
            }

            function ensureValidCoverChoice() {
                const existingCoverButtons = Array.from(document.querySelectorAll('.cover-select-button')).filter(function (button) {
                    return button.closest('.selected-media-card') !== null || button.closest('.existing-media-card') !== null;
                });

                let coverStillValid = false;

                if (coverChoiceInput && coverChoiceInput.value.indexOf('existing:') === 0) {
                    const currentValue = coverChoiceInput.value;
                    coverStillValid = existingCoverButtons.some(function (button) {
                        return button.dataset.coverValue === currentValue;
                    });
                } else if (coverChoiceInput && (coverChoiceInput.value.indexOf('uploaded:') === 0 || coverChoiceInput.value.indexOf('new:') === 0)) {
                    const currentValue = coverChoiceInput.value;
                    coverStillValid = existingCoverButtons.some(function (button) {
                        return button.dataset.coverValue === currentValue;
                    });
                }

                if (!coverStillValid) {
                    if (existingCoverButtons.length > 0) {
                        coverChoiceInput.value = existingCoverButtons[0].dataset.coverValue;
                    } else if (coverChoiceInput) {
                        coverChoiceInput.value = '';
                    }
                }

                updateCoverButtons();
            }

            function removeSelectedFile(indexToRemove) {
                selectedFiles = selectedFiles.filter(function (_, index) {
                    return index !== indexToRemove;
                });

                syncInputFiles();
                renderSelectedFiles();
            }

            function removeStagedUpload(uploadPath, kind) {
                stagedUploads = stagedUploads.filter(function (item) {
                    return item.path !== uploadPath;
                });

                if (kind === 'image') {
                    removedUploadedImages.add(uploadPath);
                } else if (kind === 'video') {
                    removedUploadedVideos.add(uploadPath);
                }

                syncStagedUploadInputs();
                renderSelectedFiles();
            }

            function createPreviewCardFromFile(file, index) {
                const card = document.createElement('div');
                card.className = 'selected-media-card';

                const mediaType = file.type || '';
                const objectUrl = URL.createObjectURL(file);

                if (mediaType.indexOf('image/') === 0) {
                    card.dataset.coverValue = 'new:' + index;

                    const image = document.createElement('img');
                    image.src = objectUrl;
                    image.alt = file.name;
                    image.onload = function () {
                        URL.revokeObjectURL(objectUrl);
                    };
                    card.appendChild(image);

                    const coverButton = document.createElement('button');
                    coverButton.type = 'button';
                    coverButton.className = 'cover-select-button';
                    coverButton.dataset.coverValue = 'new:' + index;
                    coverButton.textContent = 'Set as cover';
                    coverButton.addEventListener('click', function () {
                        if (coverChoiceInput) {
                            coverChoiceInput.value = coverButton.dataset.coverValue;
                            updateCoverButtons();
                        }
                    });
                    card.appendChild(coverButton);
                } else if (mediaType.indexOf('video/') === 0) {
                    const video = document.createElement('video');
                    video.src = objectUrl;
                    video.muted = true;
                    video.autoplay = true;
                    video.loop = true;
                    video.playsInline = true;
                    video.addEventListener('loadeddata', function () {
                        URL.revokeObjectURL(objectUrl);
                    });
                    card.appendChild(video);
                } else {
                    URL.revokeObjectURL(objectUrl);
                    const fileBox = document.createElement('div');
                    fileBox.className = 'selected-file-fallback';
                    fileBox.textContent = 'Selected file';
                    card.appendChild(fileBox);
                }

                const label = document.createElement('p');
                label.className = 'selected-media-name';
                label.textContent = file.name;

                const actionButton = document.createElement('button');
                actionButton.type = 'button';
                actionButton.className = 'remove-media-button';
                actionButton.textContent = 'Delete';
                actionButton.addEventListener('click', function () {
                    removeSelectedFile(index);
                });

                card.appendChild(label);
                card.appendChild(actionButton);

                return card;
            }

            function createPreviewCardFromUpload(item) {
                const card = document.createElement('div');
                card.className = 'selected-media-card';

                if (item.kind === 'image') {
                    card.dataset.coverValue = 'uploaded:' + item.path;

                    const image = document.createElement('img');
                    image.src = item.url;
                    image.alt = item.name;
                    card.appendChild(image);

                    const coverButton = document.createElement('button');
                    coverButton.type = 'button';
                    coverButton.className = 'cover-select-button';
                    coverButton.dataset.coverValue = 'uploaded:' + item.path;
                    coverButton.textContent = 'Set as cover';
                    coverButton.addEventListener('click', function () {
                        if (coverChoiceInput) {
                            coverChoiceInput.value = coverButton.dataset.coverValue;
                            updateCoverButtons();
                        }
                    });
                    card.appendChild(coverButton);
                } else {
                    const video = document.createElement('video');
                    video.src = item.url;
                    video.muted = true;
                    video.autoplay = true;
                    video.loop = true;
                    video.playsInline = true;
                    card.appendChild(video);
                }

                const label = document.createElement('p');
                label.className = 'selected-media-name';
                label.textContent = item.name;

                const actionButton = document.createElement('button');
                actionButton.type = 'button';
                actionButton.className = 'remove-media-button';
                actionButton.textContent = 'Delete';
                actionButton.addEventListener('click', function () {
                    removeStagedUpload(item.path, item.kind);
                });

                card.appendChild(label);
                card.appendChild(actionButton);

                return card;
            }

            function renderSelectedFiles() {
                previewContainer.innerHTML = '';

                if (selectedFiles.length === 0 && stagedUploads.length === 0) {
                    const emptyText = document.createElement('p');
                    emptyText.className = 'form-helper preview-empty-text';
                    emptyText.textContent = 'No new files selected yet.';
                    previewContainer.appendChild(emptyText);
                } else {
                    selectedFiles.forEach(function (file, index) {
                        previewContainer.appendChild(createPreviewCardFromFile(file, index));
                    });

                    stagedUploads.forEach(function (item) {
                        previewContainer.appendChild(createPreviewCardFromUpload(item));
                    });
                }

                ensureValidCoverChoice();
            }

            function uploadSelectedFiles() {
                if (isUploading || selectedFiles.length === 0) {
                    return Promise.resolve();
                }

                isUploading = true;

                const formData = new FormData();
                const filesToUpload = selectedFiles.slice();

                filesToUpload.forEach(function (file) {
                    formData.append('recipe_media[]', file);
                });

                selectedFiles = [];
                syncInputFiles();
                renderSelectedFiles();

                if (submitButton) {
                    submitButton.disabled = true;
                }

                if (savingNote) {
                    savingNote.textContent = 'Uploading media...';
                    savingNote.classList.remove('is-error');
                    savingNote.classList.add('is-visible');
                }

                setUploadProgress(5, 'Starting upload...');

                return new Promise(function (resolve, reject) {
                    const request = new XMLHttpRequest();
                    request.open('POST', 'upload-media.php', true);

                    request.upload.addEventListener('progress', function (event) {
                        if (event.lengthComputable) {
                            const percent = Math.max(5, Math.round((event.loaded / event.total) * 100));
                            setUploadProgress(percent, 'Uploading files... ' + percent + '%');
                        }
                    })

                    request.addEventListener('load', function () {
                        const text = request.responseText || '';
                        let data = null;

                        try {
                            data = JSON.parse(text);
                        } catch (error) {
                            if (text.indexOf('POST Content-Length') !== -1 || text.indexOf('exceeds the limit') !== -1) {
                                reject(new Error('The selected files are too large for the current PHP upload limit.'));
                                return;
                            }

                            reject(new Error('Upload failed. Please try smaller files or refresh the page.'));
                            return;
                        }

                        if (request.status < 200 || request.status >= 300 || !data.success) {
                            reject(new Error(data.message || 'Upload failed.'));
                            return;
                        }

                        resolve(data);
                    });

                    request.addEventListener('error', function () {
                        reject(new Error('Upload failed. Please check your connection and try again.'));
                    });

                    request.send(formData);
                })
                    .then(function (data) {
                        stagedUploads = stagedUploads.concat(data.items || []);
                        syncStagedUploadInputs();
                        setUploadProgress(100, 'Upload finished. Ready to save.');
                        renderSelectedFiles();
                    })
                    .catch(function (error) {
                        selectedFiles = filesToUpload.concat(selectedFiles);
                        syncInputFiles();
                        renderSelectedFiles();

                        if (savingNote) {
                            savingNote.textContent = error.message || 'Upload failed.';
                            savingNote.classList.add('is-error');
                            savingNote.classList.add('is-visible');
                        }

                        setUploadProgress(0, error.message || 'Upload failed.');

                        throw error;
                    })
                    .finally(function () {
                        isUploading = false;

                        if (submitButton) {
                            submitButton.disabled = false;
                            submitButton.textContent = submitButton.dataset.defaultLabel || 'Save';
                        }

                        if (selectedFiles.length > 0) {
                            uploadSelectedFiles();
                        } else if (savingNote && savingNote.textContent === 'Uploading media...') {
                            savingNote.classList.remove('is-error');
                            savingNote.classList.remove('is-visible');
                            setUploadProgress(0, 'Waiting for files...');
                        }
                    });
            }

            function addFilesToSelection(fileList) {
                let tooLargeMessage = '';

                Array.from(fileList || []).forEach(function (incomingFile) {
                    if (incomingFile.size > maxSingleFileSize) {
                        tooLargeMessage = incomingFile.name + ' is too large. Please keep each file under 256MB.';
                        return;
                    }

                    const alreadySelected = selectedFiles.some(function (existingFile) {
                        return existingFile.name === incomingFile.name &&
                            existingFile.size === incomingFile.size &&
                            existingFile.lastModified === incomingFile.lastModified;
                    });

                    if (!alreadySelected) {
                        selectedFiles.push(incomingFile);
                    }
                });

                syncInputFiles();
                renderSelectedFiles();

                if (tooLargeMessage && savingNote) {
                    savingNote.textContent = tooLargeMessage;
                    savingNote.classList.add('is-error');
                    savingNote.classList.add('is-visible');
                    setUploadProgress(0, tooLargeMessage);
                }

                uploadSelectedFiles();
            }

            mediaInput.addEventListener('change', function () {
                addFilesToSelection(mediaInput.files || []);
            });

            if (dropZone) {
                ['dragenter', 'dragover'].forEach(function (eventName) {
                    dropZone.addEventListener(eventName, function (event) {
                        event.preventDefault();
                        dropZone.classList.add('is-dragging');
                    });
                });

                ['dragleave', 'drop'].forEach(function (eventName) {
                    dropZone.addEventListener(eventName, function (event) {
                        event.preventDefault();
                        dropZone.classList.remove('is-dragging');
                    });
                });

                dropZone.addEventListener('drop', function (event) {
                    if (event.dataTransfer && event.dataTransfer.files) {
                        addFilesToSelection(event.dataTransfer.files);
                    }
                });
            }

            document.querySelectorAll('.remove-existing-button').forEach(function (button) {
                button.addEventListener('click', function () {
                    const value = button.dataset.removeValue || '';
                    const kind = button.dataset.removeKind || '';
                    const card = button.closest('.existing-media-card');

                    if (kind === 'image') {
                        removedExistingImages.add(value);
                    } else if (kind === 'video') {
                        removedExistingVideos.add(value);
                    }

                    syncRemovedExistingMedia();

                    if (card) {
                        card.remove();
                    }

                    ensureValidCoverChoice();
                });
            });

                document.querySelectorAll('.cover-select-button').forEach(function (button) {
                    button.addEventListener('click', function () {
                        if (coverChoiceInput) {
                        coverChoiceInput.value = button.dataset.coverValue || '';
                        updateCoverButtons();
                    }
                });
            });

            if (form && submitButton) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    if (isUploading) {
                        if (savingNote) {
                            savingNote.textContent = 'Please wait for the files to finish uploading.';
                            savingNote.classList.remove('is-error');
                            savingNote.classList.add('is-visible');
                        }
                        return;
                    }

                    submitButton.disabled = true;
                    submitButton.textContent = 'Saving...';

                    if (savingNote) {
                        savingNote.textContent = 'Saving recipe, please wait...';
                        savingNote.classList.remove('is-error');
                        savingNote.classList.add('is-visible');
                    }

                    setUploadProgress(100, 'Upload finished. Saving recipe...');

                    form.dataset.skipAsyncUpload = 'true';
                    form.submit();
                });

                form.addEventListener('submit', function (event) {
                    if (form.dataset.skipAsyncUpload === 'true') {
                        delete form.dataset.skipAsyncUpload;
                        return;
                    }

                    event.preventDefault();
                }, true);

                submitButton.dataset.defaultLabel = submitButton.textContent;
            }

            syncRemovedExistingMedia();
            syncStagedUploadInputs();
            setUploadProgress(0, 'Waiting for files...');
            renderSelectedFiles();
        }
    </script>
</body>
</html>
