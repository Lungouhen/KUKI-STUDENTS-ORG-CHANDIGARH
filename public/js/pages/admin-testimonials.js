(() => {
    document.querySelectorAll('[data-testimonial-delete]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm('Delete this testimonial?')) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('[data-testimonial-form]').forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('button[type="submit"]');
            if (!button || !form.reportValidity()) {
                return;
            }

            button.disabled = true;
            button.setAttribute('aria-busy', 'true');
            button.textContent = 'Saving testimonial…';
        });
    });
})();
