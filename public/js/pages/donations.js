(() => {
    const amountInput = document.getElementById('donation-amount');
    const amountButtons = document.querySelectorAll('[data-donation-amount]');

    if (!amountInput || amountButtons.length === 0) {
        return;
    }

    const updateSelection = () => {
        amountButtons.forEach((button) => {
            const isSelected = Number(button.dataset.donationAmount) === Number(amountInput.value);
            button.setAttribute('aria-pressed', String(isSelected));
        });
    };

    amountButtons.forEach((button) => {
        button.addEventListener('click', () => {
            amountInput.value = button.dataset.donationAmount;
            updateSelection();
            amountInput.dispatchEvent(new Event('input', { bubbles: true }));
        });
    });

    amountInput.addEventListener('input', updateSelection);
    updateSelection();
})();
