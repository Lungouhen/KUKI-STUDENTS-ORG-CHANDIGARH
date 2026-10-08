(() => {
    const modal = document.getElementById('recordFeeModal');
    const form = document.getElementById('recordFeeForm');
    const memberLabel = document.getElementById('recordFeeMember');
    const submitButton = form?.querySelector('button[type="submit"]');

    if (!modal || !form || !memberLabel) {
        return;
    }

    modal.addEventListener('show.bs.modal', (event) => {
        const trigger = event.relatedTarget;

        if (!(trigger instanceof HTMLElement)) {
            return;
        }

        if (trigger.dataset.action) {
            form.action = trigger.dataset.action;
        }

        memberLabel.textContent = trigger.dataset.member || '';
    });

    form.addEventListener('submit', () => {
        if (!submitButton || !form.reportValidity()) {
            return;
        }

        submitButton.disabled = true;
        submitButton.setAttribute('aria-busy', 'true');
        submitButton.textContent = 'Recording payment…';
    });
})();
