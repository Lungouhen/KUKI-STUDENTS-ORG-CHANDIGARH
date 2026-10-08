(() => {
    document.querySelectorAll('.resource-delete-button').forEach((button) => {
        button.addEventListener('click', () => {
            if (typeof window.confirmDelete === 'function') {
                window.confirmDelete(button.dataset.formId, button.dataset.confirmMessage);
            }
        });
    });

    const uploadModal = document.getElementById('uploadResourceModal');
    const uploadForm = document.getElementById('uploadResourceForm');

    if (uploadModal?.dataset.reopenOnError === 'true' && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(uploadModal).show();
    }

    uploadForm?.addEventListener('submit', () => {
        const submitButton = uploadForm.querySelector('button[type="submit"]');

        if (!submitButton || !uploadForm.reportValidity()) {
            return;
        }

        submitButton.disabled = true;
        submitButton.setAttribute('aria-busy', 'true');
        submitButton.textContent = 'Uploading…';
    });
})();
