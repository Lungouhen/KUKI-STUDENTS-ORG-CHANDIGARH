(() => {
    const modal = document.getElementById('addCandidateModal');

    if (modal?.dataset.reopenOnCandidateError === 'true' && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(modal).show();
    }

    document.querySelectorAll('.candidate-vote-form, .candidate-add-form').forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('button[type="submit"]');

            if (!button || !form.reportValidity()) {
                return;
            }

            button.disabled = true;
            button.setAttribute('aria-busy', 'true');
            button.textContent = form.classList.contains('candidate-add-form')
                ? 'Adding candidate…'
                : 'Saving vote total…';
        });
    });
})();
