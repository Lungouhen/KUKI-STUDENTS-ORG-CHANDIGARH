(() => {
    const form = document.getElementById('membership-form-modules');
    const copyButton = document.getElementById('copy-membership-link');
    const copyStatus = document.querySelector('.membership-copy-status');

    if (form) {
        const checkboxes = Array.from(form.querySelectorAll('input[name="modules[]"]'));
        const selectAllButton = document.getElementById('select-all-membership-modules');
        const sectionError = document.getElementById('membership-section-error');

        selectAllButton?.addEventListener('click', () => {
            checkboxes.forEach((checkbox) => {
                checkbox.checked = true;
            });
            if (sectionError) {
                sectionError.hidden = true;
            }
        });

        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                if (checkboxes.some((item) => item.checked) && sectionError) {
                    sectionError.hidden = true;
                }
            });
        });

        form.addEventListener('submit', (event) => {
            if (checkboxes.length > 0 && !checkboxes.some((checkbox) => checkbox.checked)) {
                event.preventDefault();
                if (sectionError) {
                    sectionError.hidden = false;
                }
                checkboxes[0].focus();
                return;
            }

            const submitter = event.submitter;
            if (submitter instanceof HTMLButtonElement) {
                submitter.disabled = true;
                submitter.setAttribute('aria-busy', 'true');
            }
        });
    }

    copyButton?.addEventListener('click', async () => {
        if (!copyStatus) {
            return;
        }

        try {
            await navigator.clipboard.writeText(copyButton.dataset.copyValue || '');
            copyStatus.textContent = 'Link copied.';
        } catch {
            const input = document.getElementById('membership-registration-link');
            input?.focus();
            input?.select();
            copyStatus.textContent = 'Select and copy the highlighted link.';
        }
    });
})();
