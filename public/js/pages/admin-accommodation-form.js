(() => {
    const form = document.querySelector('.admin-accommodation-form-page .accommodation-form');
    const photoInput = document.getElementById('accommodationPhoto');
    const preview = document.getElementById('accommodationPhotoPreview');
    const previewImage = document.getElementById('accommodationPhotoPreviewImage');
    const previewStatus = document.getElementById('accommodationPhotoPreviewStatus');
    const previewableImageTypes = new Set(['image/avif', 'image/gif', 'image/jpeg', 'image/png', 'image/webp']);
    let previewUrl;

    photoInput?.addEventListener('change', () => {
        const file = photoInput.files?.[0];

        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = undefined;
        }

        if (!file || !previewableImageTypes.has(file.type) || !preview || !previewImage) {
            if (preview) {
                preview.hidden = true;
            }
            return;
        }

        previewUrl = URL.createObjectURL(file);
        previewImage.src = previewUrl;
        preview.hidden = false;

        if (previewStatus) {
            previewStatus.textContent = `Selected image: ${file.name}`;
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
