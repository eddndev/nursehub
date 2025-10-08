import './bootstrap';
import { gsap } from 'gsap';

// ========================================
// NurseHub Theme Toggle System
// ========================================

/**
 * Initialize theme on page load
 */
function initTheme() {
    // Check for saved theme preference or default to 'system'
    const savedTheme = localStorage.getItem('theme') || 'system';
    applyTheme(savedTheme);

    // Expose toggle function globally for Alpine.js
    window.toggleTheme = function(theme) {
        localStorage.setItem('theme', theme);
        applyTheme(theme);
    };
}

/**
 * Apply theme to document
 * @param {string} theme - 'dark', 'light', or 'system'
 */
function applyTheme(theme) {
    const html = document.documentElement;

    if (theme === 'dark') {
        html.classList.add('dark');
    } else if (theme === 'light') {
        html.classList.remove('dark');
    } else { // system
        // Check system preference
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }
    }
}

// Listen for system theme changes when in 'system' mode
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    const savedTheme = localStorage.getItem('theme') || 'system';
    if (savedTheme === 'system') {
        applyTheme('system');
    }
});

// Initialize theme immediately (before DOM loads) to prevent flash
initTheme();

// Re-initialize on DOM ready to sync any UI elements
document.addEventListener('DOMContentLoaded', initTheme);
