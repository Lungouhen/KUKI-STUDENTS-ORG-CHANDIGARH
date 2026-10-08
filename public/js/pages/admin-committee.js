(() => {
    document.querySelectorAll('.committee-delete-form').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm(form.dataset.confirmMessage || 'Remove this executive council member?')) {
                event.preventDefault();
            }
        });
    });

    const createForm = document.querySelector('.committee-create-form');
    createForm?.addEventListener('submit', () => {
        const submitButton = createForm.querySelector('button[type="submit"]');

        if (!submitButton || !createForm.reportValidity()) {
            return;
        }

        submitButton.disabled = true;
        submitButton.setAttribute('aria-busy', 'true');
        submitButton.textContent = 'Saving member…';
    });
})();
