(() => {
    const modal = document.getElementById('addPartnerModal');
    if (modal?.dataset.reopenOnError === 'true' && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(modal).show();
    }

    document.querySelectorAll('[data-delete-form]').forEach((button) => {
        button.addEventListener('click', () => {
            if (typeof window.confirmDelete === 'function') {
                window.confirmDelete(button.dataset.deleteForm, 'Remove this partner from the register?');
            }
        });
    });

    const form = document.getElementById('createPartnerForm');
    form?.addEventListener('submit', () => {
        const button = form.querySelector('button[type="submit"]');
        if (!button || !form.reportValidity()) {
            return;
        }

        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
        button.textContent = 'Registering partner…';
    });
})();
