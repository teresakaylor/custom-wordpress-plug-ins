<?php
/**
 * possible_create_webp_on_upload()
 * MANDATE: Automated, cost-free WebP generation on the Hostinger VPS (Origin).
 * Status: imagewebp() confirmed functional.
 * PHP 8.4 Compatible.
 */
function possible_create_webp_on_upload( $metadata ) {
    // Safety check: Function exists and file path is set
    if ( ! function_exists( 'imagewebp' ) || ! isset( $metadata['file'] ) ) {
        return $metadata;
    }

    $upload_dir = wp_upload_dir();
    $full_path = trailingslashit( $upload_dir['basedir'] ) . $metadata['file'];
    
    $path_info = pathinfo( $full_path );
    $extension = strtolower( $path_info['extension'] );
    $webp_path = $path_info['dirname'] . '/' . $path_info['filename'] . '.webp';
    
    $image = null;

    // Create image resource based on file type
    switch ( $extension ) {
        case 'jpg':
        case 'jpeg':
            $image = imagecreatefromjpeg( $full_path );
            break;
        case 'png':
            $image = imagecreatefrompng( $full_path );
            if ( $image ) {
                // Critical for PNG transparency preservation
                imagepalettetotruecolor( $image );
                imagealphablending( $image, false );
                imagesavealpha( $image, true );
            }
            break;
        default:
            return $metadata;
    }

    // Save the resource as a WebP file (Quality 85 provides a good balance)
    if ( $image ) {
        imagewebp( $image, $webp_path, 85 );
        imagedestroy( $image );
    }

    return $metadata;
}

// Hook into WordPress's file generation process (priority 20 ensures thumbnails are generated first)
add_filter( 'wp_generate_attachment_metadata', 'possible_create_webp_on_upload', 20 );
?>
