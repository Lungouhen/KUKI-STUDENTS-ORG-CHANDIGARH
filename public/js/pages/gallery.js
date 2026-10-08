(() => {
    const grid = document.getElementById('galleryGrid');
    const filters = document.querySelectorAll('[data-gallery-filter]');
    const status = document.getElementById('gallery-filter-status');

    const updateLayout = () => {
        const masonryInstance = grid && window.jQuery?.data(grid, 'masonry');
        masonryInstance?.arrange({
            filter: (item) => !item.hidden,
        });
    };

    if (grid && window.jQuery?.fn?.imagesLoaded && window.jQuery?.fn?.masonry) {
        window.jQuery(grid).imagesLoaded(() => {
            window.jQuery(grid).masonry({
                itemSelector: '.masonry-item',
                columnWidth: '.masonry-item',
                percentPosition: true,
            });
        });
    }

    if (window.jQuery?.fn?.magnificPopup) {
        window.jQuery('.popup-gallery').magnificPopup({
            type: 'image',
            gallery: { enabled: true },
        });
    }

    filters.forEach((filter) => {
        filter.addEventListener('click', () => {
            const selectedCategory = filter.dataset.galleryFilter;
            let visibleCount = 0;

            filters.forEach((button) => {
                const isSelected = button === filter;
                button.setAttribute('aria-pressed', String(isSelected));
                button.classList.toggle('active', isSelected);
            });

            grid?.querySelectorAll('.masonry-item').forEach((item) => {
                item.hidden = selectedCategory !== 'all'
                    && item.dataset.galleryCategory !== selectedCategory;
                if (!item.hidden) {
                    visibleCount += 1;
                }
            });

            if (status) {
                status.textContent = `${visibleCount} ${visibleCount === 1 ? 'photo' : 'photos'} shown.`;
            }
            updateLayout();
        });
    });
})();
