<?php

namespace FrontendUserAvatar\Components;

class ProfileUpdater {
    public static function update_profile($user_id) {
        if (!is_user_logged_in()) {
            wp_redirect(home_url());
            exit;
        }       

        if (!empty($_FILES['frontend-user-avatar']['name'])) {
            
            # Allowed mime types
            $mimes = [
                'jpg|jpeg|jpe' => 'image/jpeg',
                'gif'          => 'image/gif',
                'png'          => 'image/png',
            ];

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

                # Update user meta with new avatar URL
                update_user_meta($user_id, 'frontend-user-avatar', ['full' => $avatar['url']]);
            } else {
                # Handle upload error
                wp_die(esc_html__('Avatar upload failed: ', 'frontend-user-avatar') . $avatar['error']);
            }
        }        
    }

    public static function avatar_delete($userID) {
        # Get old avatar
        $old_avatar = get_user_meta($userID, 'frontend-user-avatar', true);
    
        # Unlink
        if (is_array($old_avatar) && !empty($old_avatar['full'])) {
            $old_avatar_path = str_replace(wp_upload_dir()['baseurl'], wp_upload_dir()['basedir'], $old_avatar['full']);
    
            if (file_exists($old_avatar_path)) {
                unlink($old_avatar_path);
            }
        }
        
        # Delete old avatar
        delete_user_meta($userID, 'frontend-user-avatar');
    }
    
}