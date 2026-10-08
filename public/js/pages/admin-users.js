(() => {
    const search = document.getElementById('adminUserSearch');
    const rows = Array.from(document.querySelectorAll('[data-admin-user-row]'));
    const noResults = document.getElementById('adminUserNoResults');
    const status = document.getElementById('adminUserSearchStatus');

    if (!search) {
        return;
    }

    const filterUsers = () => {
        const query = search.value.trim().toLowerCase();
        let visibleCount = 0;

        rows.forEach((row) => {
            const matches = row.textContent.toLowerCase().includes(query);
            row.hidden = !matches;
            visibleCount += matches ? 1 : 0;
        });

        if (noResults) {
            noResults.hidden = visibleCount > 0;
        }

        if (status) {
            status.textContent = query
                ? `${visibleCount} matching users.`
                : `${visibleCount} users shown.`;
        }
    };

    search.addEventListener('input', filterUsers);
    filterUsers();
})();
