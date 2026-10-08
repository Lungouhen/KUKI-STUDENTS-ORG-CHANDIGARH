(() => {
    const search = document.getElementById('auditLogSearch');
    const rows = Array.from(document.querySelectorAll('[data-audit-log-row]'));
    const noResults = document.getElementById('auditLogNoResults');
    const status = document.getElementById('auditLogSearchStatus');

    if (!search) {
        return;
    }

    const filterLogs = () => {
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
                ? `${visibleCount} matching audit entries.`
                : `${visibleCount} audit entries shown.`;
        }
    };

    search.addEventListener('input', filterLogs);
    filterLogs();
})();
