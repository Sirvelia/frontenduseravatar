<?php

namespace FrontendUserAvatar\Components;

class ProfileUpdater {
    public static function update_profile($userID) {
        if (!empty($_FILES['frontend-user-avatar']['name'])) {
            
            // Allowed mime types
            $mimes = [
                'jpg|jpeg|jpe' => 'image/jpeg',
                'gif'          => 'image/gif',
                'png'          => 'image/png',
            ];

            // Load necessary file handling functions if not already loaded
            if (!function_exists('wp_handle_upload')) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }

            // Security check to prevent PHP file uploads
            if (strstr($_FILES['frontend-user-avatar']['name'], '.php')) {
                wp_die('For security reasons, the extension ".php" cannot be in your file name.');
            }

            // Handle avatar upload
            $avatar = wp_handle_upload($_FILES['frontend-user-avatar'], [
                'mimes' => $mimes,
                'test_form' => false
            ]);                

            if ($avatar && !isset($avatar['error'])) {
                // Delete existing avatar
                self::avatar_delete($userID);

                // Update user meta with new avatar URL
                update_user_meta($userID, 'frontend-user-avatar', ['full' => $avatar['url']]);
            } else {
                // Handle upload error
                wp_die('Avatar upload failed: ' . $avatar['error']);
            }
        }
    }

    public static function avatar_delete($userID) {
        $old_avatar = get_user_meta($userID, 'frontend-user-avatar', true);
    
        if (is_array($old_avatar) && !empty($old_avatar['full'])) {
            $old_avatar_path = str_replace(wp_upload_dir()['baseurl'], wp_upload_dir()['basedir'], $old_avatar['full']);
    
            if (file_exists($old_avatar_path)) {
                unlink($old_avatar_path);
            }
        }
    
        delete_user_meta($userID, 'frontend-user-avatar');
    }
    
}