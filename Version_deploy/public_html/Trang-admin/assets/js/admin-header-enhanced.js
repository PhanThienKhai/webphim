/**
 * ADMIN HEADER ENHANCEMENTS
 * Galaxy Studio - Enhanced admin header interactions
 */

(function() {
    'use strict';
    
    // Initialize when DOM is ready
    function initAdminHeader() {
        setupHeaderInteractions();
        setupUserDropdown();
        setupHeaderEffects();
        setupKeyboardNavigation();
    }
    
    // Header interactions
    function setupHeaderInteractions() {
        const sideToggle = document.querySelector('.side-header-toggle');
        const headerSection = document.querySelector('.header-section');
        
        if (sideToggle) {
            // Add click ripple effect
            sideToggle.addEventListener('click', function(e) {
                createRippleEffect(e, this);
            });
            
            // Add keyboard support
            sideToggle.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        }
        
        // Header scroll effect
        if (headerSection) {
            let lastScrollY = window.scrollY;
            
            window.addEventListener('scroll', function() {
                const currentScrollY = window.scrollY;
                
                if (currentScrollY > 50) {
                    headerSection.style.background = 'linear-gradient(135deg, rgba(44, 62, 80, 0.95) 0%, rgba(52, 73, 94, 0.95) 50%, rgba(44, 62, 80, 0.95) 100%)';
                    headerSection.style.backdropFilter = 'blur(15px)';
                } else {
                    headerSection.style.background = 'linear-gradient(135deg, #2c3e50 0%, #34495e 50%, #2c3e50 100%)';
                    headerSection.style.backdropFilter = 'none';
                }
                
                lastScrollY = currentScrollY;
            });
        }
    }
    
    // User dropdown enhancements
    function setupUserDropdown() {
        const userDropdowns = document.querySelectorAll('.adomx-dropdown');
        
        userDropdowns.forEach(dropdown => {
            const toggle = dropdown.querySelector('.toggle');
            const menu = dropdown.querySelector('.adomx-dropdown-menu');
            
            if (toggle && menu) {
                let hoverTimeout;
                
                // Mouse events
                dropdown.addEventListener('mouseenter', function() {
                    clearTimeout(hoverTimeout);
                    showDropdown(menu);
                });
                
                dropdown.addEventListener('mouseleave', function() {
                    hoverTimeout = setTimeout(() => {
                        hideDropdown(menu);
                    }, 200);
                });
                
                // Click events
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    toggleDropdown(menu);
                });
                
                // Keyboard events
                toggle.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        toggleDropdown(menu);
                    } else if (e.key === 'Escape') {
                        hideDropdown(menu);
                    }
                });
            }
        });
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.adomx-dropdown')) {
                const openMenus = document.querySelectorAll('.adomx-dropdown-menu.show');
                openMenus.forEach(menu => hideDropdown(menu));
            }
        });
    }
    
    function showDropdown(menu) {
        menu.classList.add('show');
        menu.style.opacity = '1';
        menu.style.visibility = 'visible';
        menu.style.transform = 'translateY(0)';
    }
    
    function hideDropdown(menu) {
        menu.classList.remove('show');
        menu.style.opacity = '0';
        menu.style.visibility = 'hidden';
        menu.style.transform = 'translateY(-10px)';
    }
    
    function toggleDropdown(menu) {
        if (menu.classList.contains('show')) {
            hideDropdown(menu);
        } else {
            showDropdown(menu);
        }
    }
    
    // Header visual effects
    function setupHeaderEffects() {
        // Animate header elements on load
        const headerElements = document.querySelectorAll('.header-logo, .side-header-toggle, .header-notification-area li');
        
        headerElements.forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(-20px)';
            
            setTimeout(() => {
                element.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, index * 100);
        });
        
        // Add hover effects to notification items
        const notificationItems = document.querySelectorAll('.header-notification-area li');
        
        notificationItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    }
    
    // Ripple effect for buttons
    function createRippleEffect(event, element) {
        const ripple = document.createElement('span');
        const rect = element.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s ease-out;
            pointer-events: none;
        `;
        
        element.style.position = 'relative';
        element.style.overflow = 'hidden';
        element.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }
    
    // Keyboard navigation
    function setupKeyboardNavigation() {
        const focusableElements = document.querySelectorAll(
            '.side-header-toggle, .header-notification-area a, .adomx-dropdown .toggle'
        );
        
        focusableElements.forEach(element => {
            element.setAttribute('tabindex', '0');
            
            element.addEventListener('focus', function() {
                this.style.outline = '2px solid #3498db';
                this.style.outlineOffset = '2px';
            });
            
            element.addEventListener('blur', function() {
                this.style.outline = 'none';
            });
        });
        
        // Global keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Alt + M to toggle side menu
            if (e.altKey && e.key === 'm') {
                e.preventDefault();
                const sideToggle = document.querySelector('.side-header-toggle');
                if (sideToggle) {
                    sideToggle.click();
                }
            }
            
            // Esc to close all dropdowns
            if (e.key === 'Escape') {
                const openMenus = document.querySelectorAll('.adomx-dropdown-menu.show');
                openMenus.forEach(menu => hideDropdown(menu));
            }
        });
    }
    
    // Add CSS for ripple animation
    function addRippleStyles() {
        if (!document.getElementById('ripple-styles')) {
            const style = document.createElement('style');
            style.id = 'ripple-styles';
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(2);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    // Initialize everything
    function init() {
        addRippleStyles();
        initAdminHeader();
        
        // Add loaded class to body
        setTimeout(() => {
            document.body.classList.add('admin-header-loaded');
        }, 500);
    }
    
    // Start when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
})();