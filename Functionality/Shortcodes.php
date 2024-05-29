<?php

namespace FrontendUserAvatar\Functionality;

use FrontendUserAvatar\Components\ProfileUpdater;

class Shortcodes {
	protected $plugin_id;
    protected $plugin_version;
    
    public function __construct($plugin_id, $plugin_version) {
        $this->plugin_id = $plugin_id;
        $this->plugin_version = $plugin_version;
        
        add_shortcode('frontend-user-avatar', [$this, 'frontend_user_avatar_shortcode']);

        # Register hook for POST form submission
        add_action('admin_post_update_frontend_avatar', [$this, 'handle_avatar_upload']);
        add_action('admin_post_nopriv_update_frontend_avatar', [$this, 'handle_avatar_upload']);
    }

    # Handle POST form submission
    public function handle_avatar_upload() {
        if (!is_user_logged_in()) {
            wp_redirect(home_url());
            exit;
        }

        $userID = get_current_user_id();

        if (isset($_POST['avatar_submit_button'])) {
            ProfileUpdater::update_profile(($userID));
        }

        $redirectTo = isset($_POST['redirect_to']) ? esc_url_raw($_POST['redirect_to']) : home_url();
        wp_redirect($redirectTo);
        exit;
    }

    # Frontend shortcode
    public function frontend_user_avatar_shortcode() {
        # If user not logged, return
        if (!is_user_logged_in()) {
            return;
        }
        
        # Get user data
        $userID = get_current_user_id();        
        $userData = get_userdata($userID);
        $currentUrl = home_url(add_query_arg(null, null));

        # Start HTML print
        ob_start();

        echo '<form id="frontend-avatar-form" method="post" enctype="multipart/form-data" action="' . esc_url(admin_url('admin-post.php')) . '">';
        echo '<input type="hidden" name="action" value="update_frontend_avatar">';
        echo '<input type="hidden" name="redirect_to" value="' . esc_url($currentUrl) . '">';
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

        echo '</form>';
        
        return ob_get_clean();
    }
}      