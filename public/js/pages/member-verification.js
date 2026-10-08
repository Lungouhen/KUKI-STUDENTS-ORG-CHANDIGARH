(() => {
    const form = document.getElementById('memberVerificationForm');
    const button = document.getElementById('verification-submit');
    const label = document.getElementById('verification-submit-label');
    const status = document.getElementById('verification-status');

    form?.addEventListener('submit', () => {
        if (!button || !form.reportValidity()) {
            return;
        }

        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
        if (label) {
            label.textContent = 'Checking…';
        }
        if (status) {
            status.textContent = 'Checking the member record.';
        }
    });
})();
