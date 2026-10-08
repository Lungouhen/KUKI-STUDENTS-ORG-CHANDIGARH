(() => {
    document.querySelectorAll('[data-media-delete]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm('Permanently delete this unused image?')) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('[data-media-form]').forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('button[type="submit"]');
            if (!button || !form.reportValidity()) {
                return;
            }

            button.disabled = true;
            button.setAttribute('aria-busy', 'true');
            const isUpload = form.querySelector('input[name="_method"]') === null;
            button.textContent = isUpload ? 'Uploading image…' : 'Saving…';
        });
    });
})();
