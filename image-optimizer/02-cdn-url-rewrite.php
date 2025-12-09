<?php
/**
 * possible_rewrite_cdn_urls() - FINAL STABLE VERSION
 * MANDATE: Route media through BunnyCDN & prevent crash on Schema array input.
 * Status: Removed the crashing 'final_output' hook.
 * PHP 8.4 Compatible.
 */
// FIX: Set $content = '' to make the argument optional, and allow array/object input
function possible_rewrite_cdn_urls( $content = '' ) { 
 
    // CRITICAL: Only run str_replace if the input is confirmed to be a STRING (HTML, post content, text widget).
    if ( ! is_string( $content ) || empty( $content ) ) {
        return $content; 
    }
    
    // CRITICAL: The ACTUAL BunnyCDN Hostname you are using.
    $cdn_hostname = 'https://possible-this-media.b-cdn.net'; 
    $origin_uploads_path = '/wp-content/uploads/';
    
    // Define the full origin and CDN prefixes
    $origin_url = 'https://possiblethis.com' . $origin_uploads_path;
    $cdn_url    = $cdn_hostname . $origin_uploads_path;
    
    // Perform the high-performance string replacement
    $content = str_replace( $origin_url, $cdn_url, $content );
    
    return $content;
}

// Hook into all major content and asset output filters
add_filter( 'the_content', 'possible_rewrite_cdn_urls', 10 );
add_filter( 'post_thumbnail_html', 'possible_rewrite_cdn_urls', 10 );
add_filter( 'widget_text', 'possible_rewrite_cdn_urls', 10 ); 
// REMOVED THE CRASHING add_filter( 'final_output', 'possible_rewrite_cdn_urls', 10 );
?>
