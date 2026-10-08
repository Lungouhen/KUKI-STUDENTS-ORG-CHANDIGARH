(() => {
    const form = document.getElementById('memberPortalLoginForm');
    const button = form?.querySelector('button[type="submit"]');
    const label = document.getElementById('member-login-label');
    const status = document.getElementById('member-login-status');

    form?.addEventListener('submit', () => {
        if (!button || !form.reportValidity()) {
            return;
        }

        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
        if (label) {
            label.textContent = 'Signing in…';
        }
        if (status) {
            status.textContent = 'Checking your member details.';
        }
    });
})();
