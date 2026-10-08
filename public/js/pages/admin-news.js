(() => {
    const selectAll = document.getElementById('select-all-news');
    const selections = Array.from(document.querySelectorAll('.news-selection'));
    const bulkForm = document.getElementById('newsBulkForm');
    const bulkError = document.querySelector('[data-news-bulk-error]');

    const updateSelectAll = () => {
        const selectedCount = selections.filter((input) => input.checked).length;
        if (selectAll) {
            selectAll.checked = selections.length > 0 && selectedCount === selections.length;
            selectAll.indeterminate = selectedCount > 0 && selectedCount < selections.length;
        }
        if (bulkError && selectedCount > 0) {
            bulkError.hidden = true;
        }
    };

    selectAll?.addEventListener('change', () => {
        selections.forEach((input) => {
            input.checked = selectAll.checked;
        });
        updateSelectAll();
    });
    selections.forEach((input) => input.addEventListener('change', updateSelectAll));

    bulkForm?.addEventListener('submit', (event) => {
        if (!selections.some((input) => input.checked)) {
            event.preventDefault();
            if (bulkError) {
                bulkError.hidden = false;
            }
            selectAll?.focus();
            return;
        }

        const button = bulkForm.querySelector('button[type="submit"]');
        if (button) {
            button.disabled = true;
            button.setAttribute('aria-busy', 'true');
            button.textContent = 'Applying…';
        }
    });

    document.querySelectorAll('[data-news-schedule-status]').forEach((status) => {
        const publishAt = status.closest('form')?.querySelector('[data-news-schedule-time]');
        if (!publishAt) {
            return;
        }

        const updateScheduleRequirement = () => {
            publishAt.required = status.value === 'scheduled';
        };
        status.addEventListener('change', updateScheduleRequirement);
        updateScheduleRequirement();
    });

    document.querySelectorAll('[data-news-form]').forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('button[type="submit"]');
            if (!button || !form.reportValidity()) {
                return;
            }

            button.disabled = true;
            button.setAttribute('aria-busy', 'true');
            button.textContent = 'Saving announcement…';
        });
    });
})();
