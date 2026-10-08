(() => {
    const assetSelect = document.querySelector('[data-gallery-asset]');
    const fileInput = document.querySelector('[data-gallery-file]');
    const altTextInput = document.querySelector('[data-gallery-alt]');
    const form = document.querySelector('[data-gallery-form]');
    const selectionError = document.querySelector('[data-gallery-selection-error]');

    assetSelect?.addEventListener('change', () => {
        if (assetSelect.value && fileInput) {
            fileInput.value = '';
        }
        if (altTextInput) {
            altTextInput.required = false;
        }
        if (selectionError) {
            selectionError.hidden = true;
        }
    });

    fileInput?.addEventListener('change', () => {
        if (fileInput.files?.length && assetSelect) {
            assetSelect.value = '';
        }
        if (altTextInput) {
            altTextInput.required = Boolean(fileInput.files?.length);
        }
        if (selectionError) {
            selectionError.hidden = true;
        }
    });

    form?.addEventListener('submit', (event) => {
        const hasAsset = Boolean(assetSelect?.value);
        const hasFile = Boolean(fileInput?.files?.length);
        if (!hasAsset && !hasFile) {
            event.preventDefault();
            if (selectionError) {
                selectionError.hidden = false;
            }
            assetSelect?.focus();
            return;
        }

        const button = form.querySelector('button[type="submit"]');
        if (!button || !form.reportValidity()) {
            return;
        }

        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
        button.textContent = 'Uploading photo…';
    });

    document.querySelectorAll('[data-gallery-delete]').forEach((deleteForm) => {
        deleteForm.addEventListener('submit', (event) => {
            if (!window.confirm('Delete this gallery photo?')) {
                event.preventDefault();
            }
        });
    });
})();
