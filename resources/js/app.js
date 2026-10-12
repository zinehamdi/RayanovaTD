import './bootstrap';

// =========================================================================
// 🚀 Progressive Enhancement & Interactive Motion System
// =========================================================================

// Swap no-js class to js on document element immediately
if (typeof document !== 'undefined') {
    document.documentElement.classList.remove('no-js');
    document.documentElement.classList.add('js');
}

document.addEventListener('DOMContentLoaded', () => {
    // Smooth, Staggered Intersection Observer (translateY 24px -> 0, opacity 0 -> 1)
    const observerOptions = {
        root: null,
        rootMargin: '0px 0px -20px 0px',
        threshold: 0.05
    };

    const scrollObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-revealed');
                
                // If it's a scene, cascade reveal its inner animated elements smoothly
                if (entry.target.classList.contains('rn-scene')) {
                    const innerElements = entry.target.querySelectorAll('.reveal-child, .reveal-up, .reveal-scale, .reveal-left, .reveal-right');
                    innerElements.forEach((child, index) => {
                        setTimeout(() => {
                            child.classList.add('is-revealed');
                        }, Math.min((index + 1) * 60, 400));
                    });
                }

                // If it's a staggered container, reveal children sequentially
                const staggeredChildren = entry.target.querySelectorAll('.reveal-child');
                staggeredChildren.forEach((child, index) => {
                    setTimeout(() => {
                        child.classList.add('is-revealed');
                    }, index * 80);
                });

                // Animate stats counters inside revealed element
                entry.target.querySelectorAll('[data-count-to]').forEach(counter => {
                    animateCounter(counter);
                });

                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all scroll-animated scenes and elements
    const elementsToAnimate = document.querySelectorAll(
        '.rn-scene:not(:first-of-type), .reveal-on-scroll, .reveal-up, .reveal-scale, .reveal-stagger, .reveal-left, .reveal-right'
    );
    elementsToAnimate.forEach(el => scrollObserver.observe(el));

    // Stats counter animation utility with cubic ease-out
    function animateCounter(el) {
        const target = parseInt(el.dataset.countTo, 10);
        if (isNaN(target)) return;
        const suffix = el.dataset.countSuffix || '';
        const prefix = el.dataset.countPrefix || '';
        const duration = 1600;
        const start = performance.now();
        
        function update(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            // Ease-out cubic: 1 - (1 - t)^3
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = Math.round(target * eased);
            el.textContent = prefix + current.toLocaleString() + suffix;
            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }
        requestAnimationFrame(update);
    }

    // 🧭 Active Navigation & Scene Observer
    const navLinks = document.querySelectorAll('nav a[href*="#"]');
    const trackedScenes = document.querySelectorAll('section[id], [data-scene], #hero, #products, #export, #terroir, #certifications');
    
    if (trackedScenes.length && 'IntersectionObserver' in window) {
        const sceneObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const sceneId = entry.target.id;
                    if (sceneId) {
                        // Dispatch event for WhatsApp compact state & layout listeners
                        window.dispatchEvent(new CustomEvent('rn-scene-change', { detail: { id: sceneId } }));
                        
                        // Update aria-current on matching nav link
                        navLinks.forEach(link => {
                            const href = link.getAttribute('href') || '';
                            if (href.endsWith('#' + sceneId)) {
                                link.setAttribute('aria-current', 'true');
                            } else {
                                link.removeAttribute('aria-current');
                            }
                        });
                    }
                }
            });
        }, {
            root: null,
            rootMargin: '-20% 0px -60% 0px',
            threshold: 0
        });

        trackedScenes.forEach(s => sceneObserver.observe(s));
    }

    // Safety timeout fallback: ensure all reveal elements and scenes are visible after 2s even if IO misses
    setTimeout(() => {
        document.querySelectorAll('.rn-scene, .reveal-on-scroll, .reveal-up, .reveal-scale, .reveal-stagger, .reveal-child, .reveal-left, .reveal-right').forEach(el => {
            el.classList.add('is-revealed');
        });
    }, 2000);

    // Subtle parallax effect on scroll for corporate badges and background orbs (if present)
    const parallaxElements = document.querySelectorAll('[data-parallax]');
    if (parallaxElements.length > 0) {
        let ticking = false;
        function onScroll() {
            if (!ticking) {
                requestAnimationFrame(() => {
                    const scrollY = window.scrollY;
                    parallaxElements.forEach(el => {
                        const speed = parseFloat(el.dataset.parallax) || 0.12;
                        el.style.transform = `translate3d(0, ${scrollY * speed}px, 0)`;
                    });
                    ticking = false;
                });
                ticking = true;
            }
        }
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    // 🚢 Maritime Background Vertical Scroll Motion Engine (§21)
    const maritimeBg = document.getElementById('maritime-parallax-bg');
    const heroSection = document.getElementById('hero');
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (maritimeBg && !prefersReducedMotion) {
        let currentY = 0;
        let targetY = 0;
        let isRunning = false;
        let isHeroVisible = true;
        const ease = 0.08;
        const maxTravel = 80;

        function updateMaritimeTarget() {
            if (!isHeroVisible) return;
            const scrollY = window.pageYOffset || document.documentElement.scrollTop;
            const heroHeight = heroSection ? heroSection.offsetHeight : window.innerHeight;
            const progress = Math.min(Math.max(scrollY / heroHeight, 0), 1);
            targetY = -(progress * maxTravel);

            if (!isRunning) {
                isRunning = true;
                requestAnimationFrame(renderMaritimeMotion);
            }
        }

        function renderMaritimeMotion() {
            if (!isHeroVisible) {
                isRunning = false;
                return;
            }
            currentY += (targetY - currentY) * ease;
            maritimeBg.style.transform = `translate3d(0, ${currentY.toFixed(2)}px, 0)`;

            if (Math.abs(targetY - currentY) > 0.08) {
                requestAnimationFrame(renderMaritimeMotion);
            } else {
                isRunning = false;
            }
        }

        if (heroSection && 'IntersectionObserver' in window) {
            const heroObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    isHeroVisible = entry.isIntersecting;
                    if (isHeroVisible) {
                        window.addEventListener('scroll', updateMaritimeTarget, { passive: true });
                        updateMaritimeTarget();
                    } else {
                        window.removeEventListener('scroll', updateMaritimeTarget);
                        isRunning = false;
                    }
                });
            }, { root: null, threshold: 0 });

            heroObserver.observe(heroSection);
        } else {
            window.addEventListener('scroll', updateMaritimeTarget, { passive: true });
            updateMaritimeTarget();
        }
    }

});

