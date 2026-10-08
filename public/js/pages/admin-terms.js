(() => {
    const modal = document.getElementById('addTermModal');

    if (modal?.dataset.reopenOnError === 'true' && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(modal).show();
    }

    const form = document.getElementById('createTermForm');
    form?.addEventListener('submit', () => {
        const button = form.querySelector('button[type="submit"]');

        if (!button || !form.reportValidity()) {
            return;
        }

        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
        button.textContent = 'Saving term…';
    });
})();
