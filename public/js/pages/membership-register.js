(() => {
    const form = document.getElementById('membershipRegistrationForm');
    const photo = document.getElementById('membership-photo');
    const photoStatus = document.getElementById('membership-photo-status');

    photo?.addEventListener('change', () => {
        if (photoStatus) {
            photoStatus.textContent = photo.files?.[0]
                ? `${photo.files[0].name} selected.`
                : 'No photo selected.';
        }
    });

    form?.addEventListener('submit', () => {
        const button = form.querySelector('button[type="submit"]');
        const label = document.getElementById('membership-submit-label');
        if (!button || !form.reportValidity()) {
            return;
        }

        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
        if (label) {
            label.textContent = 'Submitting application…';
        }
    });
})();
