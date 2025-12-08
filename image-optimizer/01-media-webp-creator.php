<?php
/**
 * possible_create_webp_on_upload() - FINAL ROBUST VERSION
 * MANDATE: Reliably generate WebP for ALL JPG sizes (Original + Thumbnails).
 * Status: Logic simplified to ensure stable file path construction inside the loop.
 * PHP 8.4 Compatible.
 */
function possible_create_webp_on_upload( $metadata ) {
    
    // Safety check: Always return metadata to prevent upload crash
    if ( ! function_exists( 'imagewebp' ) || ! isset( $metadata['file'] ) ) {
        return $metadata; 
    }

    $upload_dir = wp_upload_dir();
    $upload_base = trailingslashit( $upload_dir['basedir'] );
    
    // 1. Identify the directory where all files reside (e.g., /wp-content/uploads/2025/12/)
    $upload_subdir_path = dirname( $metadata['file'] );
    $full_upload_dir = $upload_base . $upload_subdir_path . '/';

    // 2. Collect all filenames to process (Original + All sizes)
    $files_to_process = array();
    
    // Add the main original filename (must be processed first)
    $files_to_process[] = basename( $metadata['file'] ); 
    
    // Add all generated size filenames
    if ( isset( $metadata['sizes'] ) && is_array( $metadata['sizes'] ) ) {
        foreach ( $metadata['sizes'] as $size_data ) {
            $files_to_process[] = $size_data['file'];
        }
    }

    // 3. Loop through every single filename
    foreach ( $files_to_process as $filename ) {
        
        // Construct the full absolute path for the current file size (Original or Thumbnail)
        $full_file_path = $full_upload_dir . $filename;
        
        $path_info = pathinfo( $full_file_path );
        $extension = strtolower( $path_info['extension'] );
        
        // CRITICAL CHECK: Skip if not JPG/JPEG (Cost Governance)
        if ( $extension !== 'jpg' && $extension !== 'jpeg' ) {
            continue; 
        }

        $webp_path = $path_info['dirname'] . '/' . $path_info['filename'] . '.webp';
        
        // Ensure the file exists before attempting to read it
        if ( ! file_exists( $full_file_path ) ) {
            continue; 
        }
        
        // Create image resource from JPG/JPEG
        $image = imagecreatefromjpeg( $full_file_path );
        
        // Save the resource as a WebP file (Quality 75)
        if ( $image ) {
            imagewebp( $image, $webp_path, 75 ); 
            imagedestroy( $image );
        }
    }

    return $metadata; // MUST return metadata to complete the upload process successfully.
}

// Keep the priority high to ensure stability and wait for thumbnails to be written
add_filter( 'wp_generate_attachment_metadata', 'possible_create_webp_on_upload', 9999 );
?>
