(() => {
    const form = document.getElementById('adminMemberCreateForm');
    const photo = document.getElementById('admin-member-photo');
    const photoStatus = document.getElementById('admin-member-photo-status');
    const button = document.getElementById('admin-member-save');
    const label = document.getElementById('admin-member-save-label');
    const formStatus = document.getElementById('admin-member-form-status');

    photo?.addEventListener('change', () => {
        if (photoStatus) {
            photoStatus.textContent = photo.files?.[0]
                ? `${photo.files[0].name} selected.`
                : 'No photo selected.';
        }
    });

    form?.addEventListener('submit', () => {
        if (!button || !form.reportValidity()) {
            return;
        }

        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
        if (label) {
            label.textContent = 'Saving member…';
        }
        if (formStatus) {
            formStatus.textContent = 'Saving the member record.';
        }
    });
})();
