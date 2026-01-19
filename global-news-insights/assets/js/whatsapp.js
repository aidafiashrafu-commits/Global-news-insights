/**
 * Global News Insights - WhatsApp Floating Button
 * 
 * Handles the WhatsApp floating button functionality
 * Toggles visibility, smooth animations, and messaging
 */

(function() {
    'use strict';

    // Get WhatsApp data from WordPress
    const whatsappData = typeof gni_data !== 'undefined' ? gni_data : {};
    const whatsappPhone = whatsappData.whatsapp_phone || '';
    const whatsappMessage = whatsappData.whatsapp_message || 'Hello!';

    /**
     * Initialize WhatsApp floating button
     */
    function initWhatsAppButton() {
        // Check if WhatsApp number is configured
        if ( !whatsappPhone ) {
            return;
        }

        const whatsappBtn = document.getElementById( 'gni-whatsapp-btn' );
        if ( !whatsappBtn ) {
            return;
        }

        // Handle button click
        whatsappBtn.addEventListener( 'click', function( e ) {
            e.preventDefault();
            openWhatsAppChat();
        } );

        // Scroll behavior - show/hide button based on scroll position
        handleScrollBehavior();
    }

    /**
     * Open WhatsApp chat
     */
    function openWhatsAppChat() {
        const message = encodeURIComponent( whatsappMessage );
        const url = 'https://wa.me/' + whatsappPhone + '?text=' + message;
        window.open( url, '_blank' );
        
        // Track engagement (optional)
        trackWhatsAppClick();
    }

    /**
     * Handle scroll behavior for button visibility
     */
    function handleScrollBehavior() {
        const whatsappBtn = document.getElementById( 'gni-whatsapp-btn' );
        if ( !whatsappBtn ) {
            return;
        }

        let lastScrollTop = 0;
        let scrollTimeout;

        window.addEventListener( 'scroll', function() {
            clearTimeout( scrollTimeout );
            
            const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            // Show button after scrolling down 300px
            if ( currentScroll > 300 ) {
                whatsappBtn.classList.add( 'visible' );
            } else {
                whatsappBtn.classList.remove( 'visible' );
            }

            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;

            scrollTimeout = setTimeout( function() {
                // Reset animation
            }, 150 );
        }, false );
    }

    /**
     * Track WhatsApp button clicks for analytics
     */
    function trackWhatsAppClick() {
        // Send analytics event if available
        if ( typeof gtag === 'function' ) {
            gtag( 'event', 'whatsapp_click', {
                'event_category': 'engagement',
                'event_label': 'floating_button',
                'value': 1
            } );
        }

        // Send to custom analytics endpoint if available
        if ( whatsappData.ajax_url ) {
            fetch( whatsappData.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=gni_track_whatsapp&nonce=' + ( whatsappData.nonce || '' )
            } ).catch( function( error ) {
                console.debug( 'WhatsApp tracking error:', error );
            } );
        }
    }

    /**
     * Add close button functionality
     */
    function initCloseButton() {
        const closeBtn = document.getElementById( 'gni-whatsapp-close' );
        if ( !closeBtn ) {
            return;
        }

        closeBtn.addEventListener( 'click', function( e ) {
            e.preventDefault();
            const whatsappBtn = document.getElementById( 'gni-whatsapp-btn' );
            if ( whatsappBtn ) {
                whatsappBtn.style.display = 'none';
                // Store preference (optional)
                localStorage.setItem( 'gni_whatsapp_hidden', 'true' );
            }
        } );
    }

    /**
     * Pulse animation for attention
     */
    function addPulseAnimation() {
        const whatsappBtn = document.getElementById( 'gni-whatsapp-btn' );
        if ( !whatsappBtn ) {
            return;
        }

        // Add pulse class every 5 seconds
        setInterval( function() {
            whatsappBtn.classList.add( 'pulse' );
            setTimeout( function() {
                whatsappBtn.classList.remove( 'pulse' );
            }, 600 );
        }, 5000 );
    }

    /**
     * Check if button was previously hidden
     */
    function checkHiddenPreference() {
        const isHidden = localStorage.getItem( 'gni_whatsapp_hidden' );
        if ( isHidden === 'true' ) {
            const whatsappBtn = document.getElementById( 'gni-whatsapp-btn' );
            if ( whatsappBtn ) {
                whatsappBtn.style.display = 'none';
            }
        }
    }

    /**
     * Initialize on DOM ready
     */
    function init() {
        if ( document.readyState === 'loading' ) {
            document.addEventListener( 'DOMContentLoaded', function() {
                checkHiddenPreference();
                initWhatsAppButton();
                initCloseButton();
                addPulseAnimation();
            } );
        } else {
            checkHiddenPreference();
            initWhatsAppButton();
            initCloseButton();
            addPulseAnimation();
        }
    }

    // Start initialization
    init();

})();
