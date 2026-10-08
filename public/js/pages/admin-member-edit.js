(() => {
    const form = document.getElementById('adminMemberEditForm');
    const photo = document.getElementById('admin-member-edit-photo');
    const photoStatus = document.getElementById('admin-member-edit-photo-status');
    const button = document.getElementById('admin-member-edit-save');
    const label = document.getElementById('admin-member-edit-save-label');
    const status = document.getElementById('admin-member-edit-status-message');

    photo?.addEventListener('change', () => {
        if (photoStatus) {
            photoStatus.textContent = photo.files?.[0]
                ? `${photo.files[0].name} selected.`
                : 'No new photo selected; the current photo will be kept.';
        }
    });

    form?.addEventListener('submit', () => {
        if (!button || !form.reportValidity()) {
            return;
        }

        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
        if (label) {
            label.textContent = 'Updating member…';
        }
        if (status) {
            status.textContent = 'Updating the member record.';
        }
    });
})();
