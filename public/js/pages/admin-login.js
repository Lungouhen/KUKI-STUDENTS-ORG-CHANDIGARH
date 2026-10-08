(() => {
    const form = document.getElementById('adminLoginForm');
    const button = form?.querySelector('button[type="submit"]');
    const label = document.getElementById('admin-login-label');
    const status = document.getElementById('admin-login-status');

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
            status.textContent = 'Submitting the administrator sign-in request.';
        }
    });
})();
