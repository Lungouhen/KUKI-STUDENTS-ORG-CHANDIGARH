(() => {
    const selectAll = document.getElementById('select-all-events');
    const selections = Array.from(document.querySelectorAll('.event-selection'));
    const bulkForm = document.getElementById('eventsBulkForm');
    const bulkError = document.querySelector('[data-events-bulk-error]');

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

    document.querySelectorAll('[data-schedule-status]').forEach((publicationStatus) => {
        const scheduledAt = publicationStatus.closest('form')?.querySelector('[data-schedule-time]');
        if (!scheduledAt) {
            return;
        }

        const updateScheduleRequirement = () => {
            scheduledAt.required = publicationStatus.value === 'scheduled';
        };

        publicationStatus.addEventListener('change', updateScheduleRequirement);
        updateScheduleRequirement();
    });

    document.querySelectorAll('[data-event-delete]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm('Delete this event?')) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('[data-event-form]').forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('button[type="submit"]');
            if (!button || !form.reportValidity()) {
                return;
            }

            button.disabled = true;
            button.setAttribute('aria-busy', 'true');
            button.textContent = 'Saving event…';
        });
    });
})();
