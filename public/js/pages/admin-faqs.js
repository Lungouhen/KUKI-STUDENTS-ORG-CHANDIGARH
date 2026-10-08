(() => {
    const search = document.querySelector('[data-faq-search]');
    const rows = Array.from(document.querySelectorAll('[data-faq-row]'));
    const noResults = document.querySelector('[data-faq-no-results]');
    const searchStatus = document.querySelector('[data-faq-search-status]');

    search?.addEventListener('input', () => {
        const query = search.value.trim().toLocaleLowerCase();
        let visibleCount = 0;

        rows.forEach((row) => {
            const matches = row.textContent.toLocaleLowerCase().includes(query);
            row.hidden = !matches;
            visibleCount += Number(matches);
        });

        if (noResults) {
            noResults.hidden = visibleCount > 0 || rows.length === 0;
        }
        if (searchStatus) {
            searchStatus.textContent = query
                ? `${visibleCount} matching FAQs on this page`
                : `${rows.length} FAQs on this page`;
        }
    });

    document.querySelectorAll('[data-faq-delete]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm('Delete this FAQ?')) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('[data-faq-form]').forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('button[type="submit"]');
            if (!button || !form.reportValidity()) {
                return;
            }

            button.disabled = true;
            button.setAttribute('aria-busy', 'true');
            button.textContent = 'Saving FAQ…';
        });
    });
})();
