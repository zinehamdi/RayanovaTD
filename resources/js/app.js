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
    // Subtle, Staggered Intersection Observer (translateY 20px -> 0, opacity 0 -> 1)
    const observerOptions = {
        root: null,
        rootMargin: '0px 0px -40px 0px',
        threshold: 0.1
    };

    const scrollObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-revealed');
                
                // If it's a staggered container, reveal children sequentially
                const staggeredChildren = entry.target.querySelectorAll('.reveal-child');
                staggeredChildren.forEach((child, index) => {
                    setTimeout(() => {
                        child.classList.add('is-revealed');
                    }, index * 90);
                });

                // Animate stats counters inside revealed element
                entry.target.querySelectorAll('[data-count-to]').forEach(counter => {
                    animateCounter(counter);
                });

                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all scroll-animated elements
    const elementsToAnimate = document.querySelectorAll(
        '.reveal-on-scroll, .reveal-up, .reveal-scale, .reveal-stagger, .reveal-left, .reveal-right'
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

    // Safety timeout fallback: ensure all reveal elements are visible after 2.5s even if IO misses
    setTimeout(() => {
        document.querySelectorAll('.reveal-on-scroll, .reveal-up, .reveal-scale, .reveal-stagger, .reveal-child').forEach(el => {
            el.classList.add('is-revealed');
        });
    }, 2500);

    // Subtle parallax effect on scroll for corporate badges and background orbs
    let ticking = false;
    function onScroll() {
        if (!ticking) {
            requestAnimationFrame(() => {
                const scrollY = window.scrollY;
                document.querySelectorAll('[data-parallax]').forEach(el => {
                    const speed = parseFloat(el.dataset.parallax) || 0.12;
                    el.style.transform = `translate3d(0, ${scrollY * speed}px, 0)`;
                });
                ticking = false;
            });
            ticking = true;
        }
    }
    window.addEventListener('scroll', onScroll, { passive: true });

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

    // 🎞️ Desktop Cinematic Scene Scroll Choreography Engine (§Phase 15)
    if (window.innerWidth >= 1024 && !prefersReducedMotion) {
        const scenes = Array.from(document.querySelectorAll('.rn-scene'));
        if (scenes.length > 1) {
            let choreoTicking = false;

            function updateSceneTransitions() {
                const vh = window.innerHeight;
                const stickyTop = 110; // 72px header + 1cm margin (~38px)

                for (let i = 0; i < scenes.length; i++) {
                    const scene = scenes[i];
                    const rect = scene.getBoundingClientRect();
                    const nextScene = scenes[i + 1];

                    // 1. Scene completely below viewport
                    if (rect.top >= vh) {
                        scene.style.transform = 'scale(0.975)';
                        scene.style.filter = 'brightness(0.90)';
                        scene.style.opacity = '1';
                        scene.style.visibility = 'visible';
                        continue;
                    }

                    // 2. Scene being covered by the next scene
                    if (nextScene) {
                        const nextRect = nextScene.getBoundingClientRect();

                        if (nextRect.top > stickyTop && nextRect.top < vh) {
                            // nextScene is actively climbing over scene
                            const progress = Math.max(0, Math.min(1, (vh - nextRect.top) / (vh - stickyTop)));

                            // Outgoing pinned scene recedes subtly, but stays 100% solid/opaque!
                            const outScale = 1.00 - (progress * 0.025);
                            const outBright = 1.00 - (progress * 0.20);
                            scene.style.transform = `scale(${outScale.toFixed(4)})`;
                            scene.style.filter = `brightness(${outBright.toFixed(3)})`;
                            scene.style.opacity = '1';
                            scene.style.visibility = 'visible';

                            // Incoming scene scales to full size
                            const inScale = 0.975 + (progress * 0.025);
                            nextScene.style.transform = `scale(${inScale.toFixed(4)})`;
                            nextScene.style.filter = 'brightness(1)';
                            nextScene.style.opacity = '1';
                            nextScene.style.visibility = 'visible';
                            continue;
                        } else if (nextRect.top <= stickyTop) {
                            // nextScene has FULLY covered this scene: hide completely so it never bleeds or overlaps!
                            scene.style.transform = 'scale(0.975)';
                            scene.style.filter = 'brightness(0.80)';
                            scene.style.opacity = '1';
                            scene.style.visibility = 'hidden';
                            continue;
                        }
                    }

                    // 3. Active resting scene or entering from below
                    if (rect.top > stickyTop && rect.top < vh) {
                        const enterProgress = Math.max(0, Math.min(1, (vh - rect.top) / (vh - stickyTop)));
                        const inScale = 0.975 + (enterProgress * 0.025);
                        scene.style.transform = `scale(${inScale.toFixed(4)})`;
                        scene.style.filter = 'brightness(1)';
                        scene.style.opacity = '1';
                        scene.style.visibility = 'visible';
                    } else {
                        // Firmly resting at top
                        scene.style.transform = 'scale(1)';
                        scene.style.filter = 'brightness(1)';
                        scene.style.opacity = '1';
                        scene.style.visibility = 'visible';
                    }
                }

                choreoTicking = false;
            }

            window.addEventListener('scroll', () => {
                if (!choreoTicking) {
                    requestAnimationFrame(updateSceneTransitions);
                    choreoTicking = true;
                }
            }, { passive: true });

            updateSceneTransitions();

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    updateSceneTransitions();
                } else {
                    scenes.forEach(s => {
                        s.style.transform = '';
                        s.style.opacity = '';
                        s.style.filter = '';
                        s.style.visibility = '';
                    });
                }
            }, { passive: true });
        }
    }
});
