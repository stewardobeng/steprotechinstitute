import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Initialize theme from localStorage
function initTheme() {
    const darkMode = localStorage.getItem('darkMode');
    const html = document.documentElement;
    if (darkMode === 'true') {
        html.classList.add('dark');
    } else {
        // Default to light theme (when darkMode is 'false' or null)
        html.classList.remove('dark');
    }
}

// Initialize theme before Alpine starts
initTheme();

// Make theme toggle function available globally
window.toggleTheme = function() {
    const html = document.documentElement;
    const isDark = html.classList.contains('dark');
    if (isDark) {
        html.classList.remove('dark');
        localStorage.setItem('darkMode', 'false');
    } else {
        html.classList.add('dark');
        localStorage.setItem('darkMode', 'true');
    }
    return !isDark;
};

Alpine.start();
