/**
 * Zyna Core JavaScript
 * 
 * Integrates with Flowbite to provide enhanced component functionality
 * Works with both Blade and Livewire implementations
 */

import { initFlowbite } from 'flowbite';
import { initializeTheme, dispatchZynaEvent, isLivewireAvailable } from './utils.js';

class ZynaCore {
    constructor() {
        this.initialized = false;
        this.livewireIntegration = false;
    }

    /**
     * Initialize Zyna and Flowbite components
     */
    init() {
        if (this.initialized) {
            return;
        }

        // Initialize theme system
        initializeTheme();
        
        // Initialize Flowbite components
        this.initializeFlowbite();
        
        // Setup Livewire integration if available
        this.setupLivewireIntegration();
        
        // Setup Zyna-specific enhancements
        this.setupZynaEnhancements();
        
        this.initialized = true;
        
        // Dispatch initialization event
        dispatchZynaEvent('initialized', {
            livewireAvailable: isLivewireAvailable(),
            flowbiteVersion: this.getFlowbiteVersion()
        });
        
        console.log('Zyna initialized with Flowbite integration');
    }

    /**
     * Initialize Flowbite components
     */
    initializeFlowbite() {
        try {
            initFlowbite();
        } catch (error) {
            console.warn('Flowbite initialization failed:', error);
        }
    }

    /**
     * Setup Livewire integration for component re-initialization
     */
    setupLivewireIntegration() {
        if (typeof window.Livewire !== 'undefined') {
            this.livewireIntegration = true;
            
            // Reinitialize after Livewire component updates
            window.Livewire.hook('morph.updated', () => {
                this.reinitializeComponents();
            });

            // Reinitialize after new Livewire components are initialized
            window.Livewire.hook('component.initialized', () => {
                this.reinitializeComponents();
            });

            console.log('Livewire integration enabled');
        }
    }

    /**
     * Setup Zyna-specific enhancements
     */
    setupZynaEnhancements() {
        // Add custom event handlers for Zyna components
        this.setupCustomEventHandlers();
        
        // Setup accessibility enhancements
        this.setupAccessibilityEnhancements();
    }

    /**
     * Setup custom event handlers for Zyna-prefixed data attributes
     */
    setupCustomEventHandlers() {
        // Handle zyna-specific data attributes
        document.addEventListener('click', (event) => {
            const target = event.target.closest('[data-zyna-action]');
            if (target) {
                const action = target.getAttribute('data-zyna-action');
                this.handleZynaAction(action, target, event);
            }
        });
    }

    /**
     * Handle Zyna-specific actions
     */
    handleZynaAction(action, element, event) {
        switch (action) {
            case 'toggle-theme':
                this.toggleTheme();
                break;
            case 'copy-to-clipboard':
                this.copyToClipboard(element);
                break;
            default:
                console.warn(`Unknown Zyna action: ${action}`);
        }
    }

    /**
     * Setup accessibility enhancements
     */
    setupAccessibilityEnhancements() {
        // Add keyboard navigation support
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                this.handleEscapeKey();
            }
        });
    }

    /**
     * Handle escape key for closing modals/dropdowns
     */
    handleEscapeKey() {
        // Close any open dropdowns
        const openDropdowns = document.querySelectorAll('[data-dropdown-toggle]:not(.hidden)');
        openDropdowns.forEach(dropdown => {
            dropdown.classList.add('hidden');
        });
    }

    /**
     * Reinitialize components after DOM updates (Livewire)
     */
    reinitializeComponents() {
        this.initializeFlowbite();
        console.log('Components reinitialized after DOM update');
    }

    /**
     * Toggle dark mode theme
     */
    toggleTheme() {
        const html = document.documentElement;
        const isDark = html.classList.contains('dark');
        
        if (isDark) {
            html.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
        } else {
            html.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
        }
    }

    /**
     * Copy text to clipboard
     */
    async copyToClipboard(element) {
        const text = element.getAttribute('data-zyna-copy-text') || element.textContent;
        
        try {
            await navigator.clipboard.writeText(text);
            this.showCopyFeedback(element);
        } catch (error) {
            console.warn('Failed to copy to clipboard:', error);
        }
    }

    /**
     * Show visual feedback for copy action
     */
    showCopyFeedback(element) {
        const originalText = element.textContent;
        element.textContent = 'Copied!';
        element.classList.add('text-green-600');
        
        setTimeout(() => {
            element.textContent = originalText;
            element.classList.remove('text-green-600');
        }, 2000);
    }

    /**
     * Get Flowbite version if available
     */
    getFlowbiteVersion() {
        try {
            // Try to get version from Flowbite package
            return window.flowbite?.version || 'unknown';
        } catch (error) {
            return 'unknown';
        }
    }

    /**
     * Get Zyna instance
     */
    static getInstance() {
        if (!window.Zyna) {
            window.Zyna = new ZynaCore();
        }
        return window.Zyna;
    }

    /**
     * Manual reinitialization method for external use
     */
    static reinitialize() {
        const instance = ZynaCore.getInstance();
        instance.reinitializeComponents();
        return instance;
    }
}

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        ZynaCore.getInstance().init();
    });
} else {
    ZynaCore.getInstance().init();
}

// Export for manual initialization if needed
export default ZynaCore;