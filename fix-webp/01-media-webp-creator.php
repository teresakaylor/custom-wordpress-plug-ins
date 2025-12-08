<?php
/**
 * possible_create_webp_on_upload()
 * MANDATE: Automated, cost-free WebP generation for ALL generated image sizes (Original + Thumbnails).
 * Status: Only processing JPG/JPEG files (to prevent PNG bloat/failure).
 * PHP 8.4 Compatible.
 */
function possible_create_webp_on_upload( $metadata ) {
    
    // Safety check: Function exists and file path is set
    if ( ! function_exists( 'imagewebp' ) || ! isset( $metadata['file'] ) ) {
        return $metadata;
    }

    $upload_dir = wp_upload_dir();
    $upload_base = trailingslashit( $upload_dir['basedir'] );
    
    // 1. Define the files to process: Original + All sizes
    $files_to_process = array();
    
    // Add the main file (original)
    $files_to_process[] = array( 'file' => $metadata['file'] );
    
    // Add all generated sizes (thumbnails, medium, large, etc.)
    if ( isset( $metadata['sizes'] ) && is_array( $metadata['sizes'] ) ) {
        foreach ( $metadata['sizes'] as $size_data ) {
            $files_to_process[] = $size_data;
        }
    }

    // 2. Loop through every single file size and convert
    foreach ( $files_to_process as $file_data ) {
        if ( ! isset( $file_data['file'] ) ) {
            continue;
        }

        $relative_path = $file_data['file'];
        $full_path = $upload_base . $relative_path;
        
        $path_info = pathinfo( $full_path );
        $extension = strtolower( $path_info['extension'] );
        $webp_path = $path_info['dirname'] . '/' . $path_info['filename'] . '.webp';
        
        $image = null;

        // CRITICAL: Only target JPG/JPEG to avoid PNG bloat (Cost Governance)
        switch ( $extension ) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg( $full_path );
                break;
            default:
                // Skip processing if not JPG/JPEG
                continue 2; // Move to the next item in the outer loop
        }

        // Save the resource as a WebP file (Quality 75 for cost-governed compression)
        if ( $image ) {
            imagewebp( $image, $webp_path, 75 ); 
            imagedestroy( $image );
        }
    }

    return $metadata;
}

// Hook into WordPress's file generation process (priority 20)
add_filter( 'wp_generate_attachment_metadata', 'possible_create_webp_on_upload', 20 );
?>
