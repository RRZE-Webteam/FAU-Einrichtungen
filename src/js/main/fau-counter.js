document.addEventListener('DOMContentLoaded', function() {
    function animateValue(obj, start, end, duration) {
        // Check for prefers-reduced-motion settings
        const mediaQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

        if (mediaQuery.matches) {
            // If user prefers reduced motion, just update the number without animation
            obj.innerHTML = end;
        } else {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                obj.innerHTML = Math.floor(progress * (end - start) + start);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }
    }

    const counters = document.querySelectorAll(".number");

    let observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                const idParts = entry.target.id.split('-');
                const instance = idParts[1];
                const number = idParts[2];
                animateValue(entry.target, 0, number, 1000);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    counters.forEach(counter => {
        observer.observe(counter);
    });
});
