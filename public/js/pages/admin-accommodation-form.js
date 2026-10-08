(() => {
    const form = document.querySelector('.admin-accommodation-form-page .accommodation-form');
    const photoInput = document.getElementById('accommodationPhoto');
    const preview = document.getElementById('accommodationPhotoPreview');
    const previewImage = document.getElementById('accommodationPhotoPreviewImage');
    const previewStatus = document.getElementById('accommodationPhotoPreviewStatus');
    const previewableImageTypes = new Set(['image/avif', 'image/gif', 'image/jpeg', 'image/png', 'image/webp']);
    let previewUrl;
    let previewRequest = 0;

    photoInput?.addEventListener('change', async () => {
        const request = ++previewRequest;
        const file = photoInput.files?.[0];

        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = undefined;
        }

        previewImage?.removeAttribute('src');
        if (preview) {
            preview.hidden = true;
        }

        if (!file || !previewableImageTypes.has(file.type) || !preview || !previewImage) {
            return;
        }

        if (previewStatus) {
            previewStatus.textContent = `Preparing image preview: ${file.name}`;
        }

        let bitmap;
        let canvas;

        try {
            bitmap = await createImageBitmap(file);
            if (request !== previewRequest) {
                return;
            }

            const scale = Math.min(1, 1600 / Math.max(bitmap.width, bitmap.height));
            canvas = document.createElement('canvas');
            canvas.width = Math.max(1, Math.round(bitmap.width * scale));
            canvas.height = Math.max(1, Math.round(bitmap.height * scale));
            const context = canvas.getContext('2d', { alpha: false });

            if (!context) {
                throw new Error('Image preview is unavailable.');
            }

            context.drawImage(bitmap, 0, 0, canvas.width, canvas.height);
            const safePng = await new Promise((resolve) => canvas.toBlob(resolve, 'image/png'));
            if (request !== previewRequest || !safePng) {
                return;
            }

            previewUrl = URL.createObjectURL(safePng);
            previewImage.src = previewUrl;
            preview.hidden = false;

            if (previewStatus) {
                previewStatus.textContent = `Selected image: ${file.name}`;
            }
        } catch {
            if (request === previewRequest && previewStatus) {
                previewStatus.textContent = 'This image could not be previewed.';
            }
        } finally {
            bitmap?.close();
            if (canvas) {
                canvas.width = 0;
                canvas.height = 0;
            }
        }
    });

    form?.addEventListener('submit', () => {
        const submitButton = form.querySelector('button[type="submit"]');

        if (!submitButton || !form.reportValidity()) {
            return;
        }

        submitButton.disabled = true;
        submitButton.setAttribute('aria-busy', 'true');
        submitButton.textContent = 'Saving listing…';
    });

    window.addEventListener('beforeunload', () => {
        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
        }
    });
})();
