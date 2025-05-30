/**
 * Zyna Utility Functions
 * 
 * Helper functions for component interactions
 */

/**
 * Check if Livewire is available
 */
export function isLivewireAvailable() {
    return typeof window.Livewire !== 'undefined';
}

/**
 * Check if Flowbite is available
 */
export function isFlowbiteAvailable() {
    return typeof window.initFlowbite !== 'undefined';
}

/**
 * Safely dispatch a custom event
 */
export function dispatchZynaEvent(eventName, detail = {}) {
    const event = new CustomEvent(`zyna:${eventName}`, {
        detail,
        bubbles: true,
        cancelable: true
    });
    
    document.dispatchEvent(event);
    return event;
}

/**
 * Get current theme preference
 */
export function getCurrentTheme() {
    return localStorage.getItem('color-theme') || 
           (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
}

/**
 * Apply theme to document
 */
export function applyTheme(theme) {
    const html = document.documentElement;
    
    if (theme === 'dark') {
        html.classList.add('dark');
    } else {
        html.classList.remove('dark');
    }
    
    localStorage.setItem('color-theme', theme);
    dispatchZynaEvent('theme-changed', { theme });
}

/**
 * Initialize theme on page load
 */
export function initializeTheme() {
    const theme = getCurrentTheme();
    applyTheme(theme);
}

export default {
    isLivewireAvailable,
    isFlowbiteAvailable,
    dispatchZynaEvent,
    getCurrentTheme,
    applyTheme,
    initializeTheme
};