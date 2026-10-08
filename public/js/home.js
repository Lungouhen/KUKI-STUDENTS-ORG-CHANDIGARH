(() => {
    const counters = document.querySelectorAll('[data-counter-target]');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const formatter = new Intl.NumberFormat('en-IN');

    const finishCounter = (counter) => {
        const target = Number(counter.dataset.counterTarget);
        if (Number.isFinite(target) && target >= 0) {
            counter.textContent = formatter.format(target);
        }
    };

    const animateCounter = (counter) => {
        const target = Number(counter.dataset.counterTarget);
        if (!Number.isFinite(target) || target < 0) {
            return;
        }

        if (reducedMotion || target === 0) {
            finishCounter(counter);
            return;
        }

        const duration = 900;
        let startTime;

        const step = (timestamp) => {
            startTime ??= timestamp;
            const progress = Math.min((timestamp - startTime) / duration, 1);
            const easedProgress = 1 - ((1 - progress) ** 3);

            counter.textContent = formatter.format(Math.floor(target * easedProgress));

            if (progress < 1) {
                window.requestAnimationFrame(step);
            } else {
                finishCounter(counter);
            }
        };

        window.requestAnimationFrame(step);
    };

    if (!('IntersectionObserver' in window)) {
        counters.forEach(animateCounter);
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });

    counters.forEach((counter) => observer.observe(counter));
})();
