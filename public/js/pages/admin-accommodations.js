(() => {
    document.querySelectorAll('.accommodation-delete-button').forEach((button) => {
        button.addEventListener('click', () => {
            if (typeof window.confirmDelete === 'function') {
                window.confirmDelete(button.dataset.formId, button.dataset.confirmMessage);
            }
        });
    });
})();
