(() => {
    const filters = document.getElementById('adminMemberFilters');
    const status = document.getElementById('admin-member-status');

    status?.addEventListener('change', () => {
        if (typeof filters?.requestSubmit === 'function') {
            filters.requestSubmit();
        } else {
            filters?.submit();
        }
    });

    document.querySelectorAll('[data-member-delete]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm(form.dataset.confirmMessage || 'Delete this member?')) {
                event.preventDefault();
            }
        });
    });
})();
