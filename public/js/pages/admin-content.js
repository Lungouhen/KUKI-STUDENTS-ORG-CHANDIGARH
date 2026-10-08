(() => {
    const selectAll = document.getElementById('select-all-content');
    const selections = Array.from(document.querySelectorAll('.content-selection'));
    const bulkForm = document.getElementById('bulk-content-form');
    const selectionError = document.querySelector('[data-content-selection-error]');

    const syncSelection = () => {
        const selectedCount = selections.filter((input) => input.checked).length;
        if (selectAll) {
            selectAll.checked = selections.length > 0 && selectedCount === selections.length;
            selectAll.indeterminate = selectedCount > 0 && selectedCount < selections.length;
        }
        if (selectionError && selectedCount > 0) {
            selectionError.hidden = true;
        }
    };

    selectAll?.addEventListener('change', () => {
        selections.forEach((input) => {
            input.checked = selectAll.checked;
        });
        syncSelection();
    });
    selections.forEach((input) => input.addEventListener('change', syncSelection));

    const actionSelect = document.querySelector('[data-content-action]');
    const bulkSchedule = document.querySelector('#bulk-content-form [data-content-schedule]');
    const updateBulkScheduleRequirement = () => {
        if (actionSelect && bulkSchedule) {
            bulkSchedule.required = actionSelect.value === 'scheduled';
        }
    };
    actionSelect?.addEventListener('change', updateBulkScheduleRequirement);
    updateBulkScheduleRequirement();

    bulkForm?.addEventListener('submit', (event) => {
        if (!selections.some((input) => input.checked)) {
            event.preventDefault();
            if (selectionError) {
                selectionError.hidden = false;
            }
            selectAll?.focus();
            return;
        }

        if (actionSelect?.value === 'delete' && !window.confirm('Delete the selected content items?')) {
            event.preventDefault();
            return;
        }

        const button = bulkForm.querySelector('button[type="submit"]');
        if (button) {
            button.disabled = true;
            button.setAttribute('aria-busy', 'true');
            button.textContent = 'Applying…';
        }
    });

    const modal = document.getElementById('addContentModal');
    if (modal?.dataset.reopenOnError === 'true' && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(modal).show();
    }

    const createForm = document.querySelector('[data-content-create-form]');
    const assetSelect = document.querySelector('[data-content-asset]');
    const imageFile = document.querySelector('[data-content-file]');
    const altText = document.querySelector('[data-content-alt]');

    assetSelect?.addEventListener('change', () => {
        if (assetSelect.value && imageFile) {
            imageFile.value = '';
        }
        if (altText) {
            altText.required = false;
        }
    });

    imageFile?.addEventListener('change', () => {
        if (imageFile.files?.length && assetSelect) {
            assetSelect.value = '';
        }
        if (altText) {
            altText.required = Boolean(imageFile.files?.length);
        }
    });

    document.querySelector('[data-content-status]')?.addEventListener('change', (event) => {
        const scheduledAt = createForm?.querySelector('[data-content-schedule]');
        if (scheduledAt) {
            scheduledAt.required = event.target.value === 'scheduled';
        }
    });

    const publicationStatus = document.querySelector('[data-content-status]');
    const scheduledAt = createForm?.querySelector('[data-content-schedule]');
    if (publicationStatus && scheduledAt) {
        scheduledAt.required = publicationStatus.value === 'scheduled';
    }

    createForm?.addEventListener('submit', () => {
        const button = createForm.querySelector('button[type="submit"]');
        if (!button || !createForm.reportValidity()) {
            return;
        }

        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
        button.textContent = 'Saving content…';
    });
})();
