(() => {
    const search = document.getElementById('beneficiarySearch');
    const rows = Array.from(document.querySelectorAll('[data-beneficiary-row]'));
    const noResults = document.getElementById('beneficiaryNoResults');
    const status = document.getElementById('beneficiarySearchStatus');

    if (!search) {
        return;
    }

    const filterBeneficiaries = () => {
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
                ? `${visibleCount} matching beneficiaries.`
                : `${visibleCount} beneficiaries shown.`;
        }
    };

    search.addEventListener('input', filterBeneficiaries);
    filterBeneficiaries();
})();
