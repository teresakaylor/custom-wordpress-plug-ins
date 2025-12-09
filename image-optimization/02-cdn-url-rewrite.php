<?php
/**
 * possible_rewrite_cdn_urls()
 * MANDATE: Route media through the BunnyCDN Pull Zone for low latency.
 * PHP 8.4 Compatible.
 */
function possible_rewrite_cdn_urls( $content ) {
    
    // Define BunnyCDN Hostname and upload path
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
add_filter( 'the_content', 'possible_rewrite_cdn_urls' );
add_filter( 'post_thumbnail_html', 'possible_rewrite_cdn_urls' );
add_filter( 'widget_text', 'possible_rewrite_cdn_urls' ); 
add_filter( 'final_output', 'possible_rewrite_cdn_urls' );
add_filter( 'rank_math/json_ld', 'possible_rewrite_cdn_urls', 10 );
add_filter( 'rank_math/head', 'possible_rewrite_cdn_urls', 10 );
?>
