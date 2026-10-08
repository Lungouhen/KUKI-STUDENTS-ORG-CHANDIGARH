(() => {
    const form = document.getElementById('eventRegistrationForm');
    const button = form?.querySelector('button[type="submit"]');
    const label = document.getElementById('event-registration-label');
    const status = document.getElementById('event-registration-status');

    form?.addEventListener('submit', () => {
        if (!button || !form.reportValidity()) {
            return;
        }

        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
        if (label) {
            label.textContent = 'Submitting…';
        }
        if (status) {
            status.textContent = 'Your event registration is being submitted.';
        }
    });
})();
