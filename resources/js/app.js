const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

const revealOptions = {
    root: null,
    rootMargin: '0px 0px -10% 0px',
    threshold: 0.12,
};

let observer;
let motionRoot;

function revealElement(element) {
    element.dataset.motionState = 'visible';
}

function ensureMotionReady() {
    document.documentElement.classList.add('motion-ready');
}

function observeAfterPaint(elements) {
    if (elements.length === 0 || reduceMotion.matches) {
        return;
    }

    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            elements.forEach((element) => observer?.observe(element));
        });
    });
}

function prepareRevealElements() {
    ensureMotionReady();

    if (reduceMotion.matches) {
        document.querySelectorAll('[data-motion-reveal], [data-motion-card]').forEach(revealElement);
        return;
    }

    observer ??= new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (! entry.isIntersecting) {
                return;
            }

            revealElement(entry.target);
            observer.unobserve(entry.target);
        });
    }, revealOptions);

    const pending = [];

    const sections = Array.from(document.querySelectorAll('main section'))
        .filter((section) => ! section.closest('[data-home-hero]'));

    sections.forEach((section) => {
        if (section.dataset.motionPrepared === 'true') {
            return;
        }

        section.dataset.motionPrepared = 'true';
        section.dataset.motionReveal = '';
        pending.push(section);
    });

    document.querySelectorAll('[data-motion-card]').forEach((card, index) => {
        if (card.dataset.motionPrepared === 'true') {
            return;
        }

        card.dataset.motionPrepared = 'true';
        card.style.setProperty('--motion-delay', `${Math.min(index % 4, 3) * 55}ms`);
        pending.push(card);
    });

    observeAfterPaint(pending);
}

function playHomeHero() {
    const hero = document.querySelector('[data-home-hero]');

    if (! hero || hero.dataset.motionPlayed === 'true') {
        return;
    }

    hero.dataset.motionPlayed = 'true';

    if (reduceMotion.matches) {
        hero.dataset.motionState = 'visible';
        return;
    }

    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            hero.dataset.motionState = 'visible';
        });
    });
}

const motionMutationObserver = new MutationObserver((mutations) => {
    if (mutations.some((mutation) => mutation.addedNodes.length > 0)) {
        initializeMotion();
    }
});

function observeMotionRoot() {
    const nextRoot = document.querySelector('main');

    if (nextRoot === motionRoot) {
        return;
    }

    motionMutationObserver.disconnect();
    motionRoot = nextRoot;

    if (motionRoot) {
        motionMutationObserver.observe(motionRoot, { childList: true, subtree: true });
    }
}

function initializeMotion() {
    ensureMotionReady();
    observeMotionRoot();
    prepareRevealElements();
    playHomeHero();
}

document.addEventListener('DOMContentLoaded', initializeMotion);
document.addEventListener('livewire:navigated', initializeMotion);

reduceMotion.addEventListener?.('change', initializeMotion);

initializeMotion();
