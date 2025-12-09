<?php
/**
 * possible_force_webp_vary() - FORCE VARY: ACCEPT HEADER (EARLY EXECUTION)
 * MANDATE: Stabilize WebP delivery by forcing all caching layers to distinguish 
 * between WebP and non-WebP supporting browsers.
 * Status: Changed hook to ensure early header delivery.
 * PHP 8.4 Compatible.
 */
function possible_force_webp_vary_header() {
    // Check if headers have not been sent to prevent a fatal error
    if ( ! headers_sent() ) {
        // Force the header early
        header( 'Vary: Accept', false );
    }
}

// Change the hook to 'send_headers' (early execution hook)
add_action( 'send_headers', 'possible_force_webp_vary_header', 1 ); 
?>
