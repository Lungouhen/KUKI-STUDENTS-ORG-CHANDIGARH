(() => {
    const form = document.getElementById('contactForm');
    const button = form?.querySelector('button[type="submit"]');
    const label = document.getElementById('contact-submit-label');
    const status = document.getElementById('contact-submit-status');

    form?.addEventListener('submit', () => {
        if (!button || !form.reportValidity()) {
            return;
        }

        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
        if (label) {
            label.textContent = 'Sending…';
        }
        if (status) {
            status.textContent = 'Your message is being sent.';
        }
    });
})();
