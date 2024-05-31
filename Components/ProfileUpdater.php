<?php

namespace FrontendUserAvatar\Components;

class ProfileUpdater {
    public static function update_profile($user_id) {
        if (!is_user_logged_in()) {
            wp_redirect(home_url());
            exit;
        }    

        # Nonce verification
        if (!isset($_POST['fua_update_frontend_avatar_nonce_field']) || !wp_verify_nonce( $_POST['fua_update_frontend_avatar_nonce_field'], 'fua_update_frontend_avatar_nonce' ) ) {
            wp_die(esc_html__('Security check', 'frontend-user-avatar'));
        }

        if (isset($_FILES['frontend-user-avatar']) && !empty($_FILES['frontend-user-avatar']['name'])) {
            
            # Allowed mime types
            $mimes = apply_filters('fua/allowed_mime_types', [
                'jpg|jpeg|jpe' => 'image/jpeg',
                'gif'          => 'image/gif',
                'png'          => 'image/png',
            ]);

            # Load necessary file handling functions if not already loaded
            if (!function_exists('wp_handle_upload')) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }

            # Security check to prevent PHP file uploads
            if (strstr($_FILES['frontend-user-avatar']['name'], '.php')) {
                wp_die(esc_html__('The extension ".php" cannot be in your file name.', 'frontend-user-avatar'));
            }

            # Handle avatar upload
            $avatar = wp_handle_upload($_FILES['frontend-user-avatar'], [
                'mimes' => $mimes,
                'test_form' => false
            ]);                

            if ($avatar && !isset($avatar['error'])) {
                # Delete existing avatar
                self::avatar_delete($user_id);

                $filename = $avatar['file'];

                $attachment = array(
                    'guid'           => $avatar['url'], 
                    'post_mime_type' => $avatar['type'],
                    'post_title'     => sanitize_file_name(pathinfo($filename, PATHINFO_FILENAME)),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );

                # Insert attachment
                $attach_id = wp_insert_attachment($attachment, $filename);
                
                # Generate metadata
                require_once ABSPATH . 'wp-admin/includes/image.php';
                $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
                wp_update_attachment_metadata($attach_id, $attach_data);

                # Update user meta with new avatar URL
                update_user_meta($user_id, 'frontend-user-avatar', $attach_id);
            } else {
                # Handle upload error
                wp_die(esc_html__('Avatar upload failed: ', 'frontend-user-avatar') . $avatar['error']);
            }
        }        
    }

    public static function avatar_delete($userID) {
        # Get old avatar
        $old_avatar = get_user_meta($userID, 'frontend-user-avatar', true);
    
        wp_delete_attachment($old_avatar, true);
        
        # Delete old avatar
        delete_user_meta($userID, 'frontend-user-avatar');
    }    
}