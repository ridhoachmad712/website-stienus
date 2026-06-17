// Scroll-reveal: progressively reveal any element marked with [data-reveal]
// as it scrolls into view. Falls back to "always visible" when the browser
// lacks IntersectionObserver or the user prefers reduced motion.
const initScrollReveal = () => {
    const elements = document.querySelectorAll('[data-reveal]:not(.is-visible)');

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduceMotion || !('IntersectionObserver' in window)) {
        elements.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    obs.unobserve(entry.target);
                }
            });
        },
        { rootMargin: '0px 0px -10% 0px', threshold: 0.12 }
    );

    elements.forEach((el) => observer.observe(el));
};

document.addEventListener('DOMContentLoaded', initScrollReveal);
// Re-scan after Livewire SPA navigation / DOM updates.
document.addEventListener('livewire:navigated', initScrollReveal);
document.addEventListener('livewire:update', initScrollReveal);
