(() => {
    document.getElementById('printDonationReceipt')?.addEventListener('click', () => {
        window.print();
    });

    const logo = document.getElementById('donationReceiptLogo');
    logo?.addEventListener('error', () => {
        logo.onerror = null;
        logo.src = '/images/default-avatar-m.png';
    }, { once: true });
})();
