(() => {
    const form = document.querySelector('.admin-accommodation-create-page .accommodation-form');
    const photoInput = document.getElementById('accommodationPhoto');
    const preview = document.getElementById('accommodationPhotoPreview');
    const previewImage = document.getElementById('accommodationPhotoPreviewImage');
    const previewStatus = document.getElementById('accommodationPhotoPreviewStatus');
    let previewUrl;

    photoInput?.addEventListener('change', () => {
        const file = photoInput.files?.[0];

        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = undefined;
        }

        if (!file || !file.type.startsWith('image/') || !preview || !previewImage) {
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
