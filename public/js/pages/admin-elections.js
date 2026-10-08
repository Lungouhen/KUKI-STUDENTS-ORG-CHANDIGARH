(() => {
    const modal = document.getElementById('addElectionModal');

    if (modal?.dataset.reopenOnError === 'true' && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(modal).show();
    }

    const form = document.getElementById('createElectionForm');
    form?.addEventListener('submit', () => {
        const submitButton = form.querySelector('button[type="submit"]');

        if (!submitButton || !form.reportValidity()) {
            return;
        }

        submitButton.disabled = true;
        submitButton.setAttribute('aria-busy', 'true');
        submitButton.textContent = 'Scheduling election…';
    });
})();
