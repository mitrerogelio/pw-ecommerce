/* ============================================================
   WireCommerce — main.js
   Vanilla ES6+, no dependencies.
============================================================ */

const CART_STORAGE_KEY = 'wirecommerce_cart';

// ============================================================
// Cart badge
// ============================================================

function updateCartBadge() {
    const badge = document.getElementById('cart-count');
    if (!badge) return;

    let total = 0;
    try {
        const raw = localStorage.getItem(CART_STORAGE_KEY);
        if (raw) {
            const items = JSON.parse(raw);
            if (Array.isArray(items)) {
                total = items.reduce((sum, item) => sum + (parseInt(item.qty, 10) || 0), 0);
            }
        }
    } catch (_) {
        // Corrupted storage — treat as empty cart
    }

    badge.textContent = String(total);
    badge.style.display = total === 0 ? 'none' : '';
}

// ============================================================
// Mobile menu toggle
// ============================================================

function initMobileMenu() {
    const toggle = document.querySelector('.mobile-menu-toggle');
    if (!toggle) return;

    toggle.addEventListener('click', () => {
        const isOpen = document.body.classList.toggle('nav-open');
        toggle.setAttribute('aria-expanded', String(isOpen));
        toggle.setAttribute('aria-label', isOpen ? 'Close navigation menu' : 'Open navigation menu');
    });
}

// ============================================================
// Shop dropdown
// ============================================================

function initShopDropdown() {
    const triggers = document.querySelectorAll('.nav-dropdown-trigger');

    triggers.forEach(trigger => {
        const parentLi = trigger.closest('li');
        if (!parentLi) return;

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = parentLi.classList.toggle('dropdown-open');
            trigger.setAttribute('aria-expanded', String(isOpen));
        });
    });

    // Close any open dropdown when clicking outside
    document.addEventListener('click', () => {
        document.querySelectorAll('.nav-list__item--has-dropdown.dropdown-open').forEach(li => {
            li.classList.remove('dropdown-open');
            const trigger = li.querySelector('.nav-dropdown-trigger');
            if (trigger) trigger.setAttribute('aria-expanded', 'false');
        });
    });

    // Keyboard: close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('.nav-list__item--has-dropdown.dropdown-open').forEach(li => {
            li.classList.remove('dropdown-open');
            const trigger = li.querySelector('.nav-dropdown-trigger');
            if (trigger) {
                trigger.setAttribute('aria-expanded', 'false');
                trigger.focus();
            }
        });
    });
}

// ============================================================
// Bootstrap
// ============================================================

// ============================================================
// Product gallery — thumb buttons swap the main image
// ============================================================

function initProductGallery() {
    const mainImg = document.getElementById('gallery-main-img');
    if (!mainImg) return;

    document.querySelectorAll('.gallery__thumb-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const fullSrc = btn.dataset.fullSrc;
            const fullAlt = btn.dataset.fullAlt || '';
            if (fullSrc) {
                mainImg.src = fullSrc;
                mainImg.alt = fullAlt;
            }
            document.querySelectorAll('.gallery__thumb-btn').forEach(b => b.classList.remove('is-active'));
            btn.classList.add('is-active');
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    updateCartBadge();
    initMobileMenu();
    initShopDropdown();
    initProductGallery();
});

// ============================================================
// Public API — cart.php (and any other template) can call
// WireCommerce.updateCartBadge() after mutating localStorage.
// ============================================================

window.WireCommerce = {
    updateCartBadge,
    CART_STORAGE_KEY,
};
