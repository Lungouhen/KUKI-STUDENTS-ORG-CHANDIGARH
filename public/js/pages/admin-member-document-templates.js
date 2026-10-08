(() => {
    document.querySelectorAll('.document-template-action-form').forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('button[type="submit"]');

            if (!button || !form.reportValidity()) {
                return;
            }

            button.disabled = true;
            button.setAttribute('aria-busy', 'true');
            button.textContent = 'Preparing preview…';
        });
    });
})();
