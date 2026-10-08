(() => {
    document.querySelectorAll('.member-document-action-form').forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('button[type="submit"]');

            if (!button || !form.reportValidity()) {
                return;
            }

            button.disabled = true;
            button.setAttribute('aria-busy', 'true');
            button.textContent = 'Processing…';
        });
    });
})();
