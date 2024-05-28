<?php

namespace FrontendUserAvatar\Functionality;

use FrontendUserAvatar\Components\ProfileUpdater;

class Shortcodes {
	protected $plugin_id;
    protected $plugin_version;
    protected $profileUpdater;
    
    public function __construct($plugin_id, $plugin_version) {
        $this->plugin_id = $plugin_id;
        $this->plugin_version = $plugin_version;
        $this->profileUpdater = new ProfileUpdater();
        
        add_shortcode('frontend-user-avatar', [$this, 'frontend_user_avatar_shortcode']);
    }

    public function frontend_user_avatar_shortcode() {
        # If user not logged, return
        if (!is_user_logged_in()) {
            return;
        }
        
        # Get user data
        $userID = get_current_user_id();        
        $userData = get_userdata($userID);
        
        # Check if the form has been submitted, and update profile avatar
        if (isset($_POST['avatar_submit_button'])) {
            $this->profileUpdater->update_profile($userID); #més informació, no???
        }

        # Start HTML print
        ob_start();

        echo '<form id="frontend-avatar-form" method="post" enctype="multipart/form-data"';
        echo '<p><input type="file" name="frontend-user-avatar" id="input"/></p>';

        # If the user has permission
        if (current_user_can('upload_files')) {
            if (empty($userData->user_avatar)) {
                echo '<p class="description">' . esc_html__('No avatar. Upload one.', 'frontend-user-avatar') . '</p>';            
            } else {
                echo '<p class="description">' . esc_html__('Upload a new avatar') . '</p>';
            }

            echo '<input type="submit" name="avatar_submit_button" value="' . esc_html__('Update avatar', 'frontend-user-avatar') . '" />';

        }
        return ob_get_clean();
    }
}      