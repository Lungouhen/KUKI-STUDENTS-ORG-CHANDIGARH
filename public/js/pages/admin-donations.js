(() => {
    const search = document.getElementById('donationSearch');
    const rows = Array.from(document.querySelectorAll('[data-donation-row]'));
    const noResults = document.getElementById('donationNoResults');
    const status = document.getElementById('donationSearchStatus');

    if (!search) {
        return;
    }

    const filterDonations = () => {
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
                ? `${visibleCount} matching donations.`
                : `${visibleCount} donations shown.`;
        }
    };

    search.addEventListener('input', filterDonations);
    filterDonations();
})();
