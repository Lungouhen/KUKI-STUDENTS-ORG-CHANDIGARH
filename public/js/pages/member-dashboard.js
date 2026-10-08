(() => {
    const tablist = document.querySelector('.member-dashboard-page [role="tablist"]');
    const tabs = Array.from(tablist?.querySelectorAll('[role="tab"]') ?? []);

    tablist?.addEventListener('keydown', (event) => {
        const currentTab = event.target.closest('[role="tab"]');
        const currentIndex = tabs.indexOf(currentTab);
        if (currentIndex === -1) {
            return;
        }

        let nextIndex;
        if (event.key === 'ArrowRight') {
            nextIndex = (currentIndex + 1) % tabs.length;
        } else if (event.key === 'ArrowLeft') {
            nextIndex = (currentIndex - 1 + tabs.length) % tabs.length;
        } else if (event.key === 'Home') {
            nextIndex = 0;
        } else if (event.key === 'End') {
            nextIndex = tabs.length - 1;
        } else {
            return;
        }

        event.preventDefault();
        tabs[nextIndex].focus();
        tabs[nextIndex].click();
    });

    document.getElementById('printMemberIdCard')?.addEventListener('click', () => {
        window.print();
    });
})();
