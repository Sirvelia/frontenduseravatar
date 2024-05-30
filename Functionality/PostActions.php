<?php

namespace FrontendUserAvatar\Functionality;

use FrontendUserAvatar\Components\ProfileUpdater;

class PostActions {

    protected $plugin_name;
    protected $plugin_version;

    public function __construct($plugin_name, $plugin_version) {
        $this->plugin_name = $plugin_name;
        $this->plugin_version = $plugin_version;
        $this->add_post_actions();
    }

    private function add_post_non_logged_in_action($action, $function) {
        add_action("admin_post_nopriv_{$action}", [$this, $function]);
    }

    private function add_post_logged_in_action($action, $function) {
        add_action("admin_post_{$action}", [$this, $function]);
    }

    private function add_post_action($action, $function) {
        $this->add_post_non_logged_in_action($action, $function);
        $this->add_post_logged_in_action($action, $function);
    }

    private function add_post_actions() {
        $this->add_post_action('update_frontend_avatar', 'handle_avatar_upload');
    }

    public function handle_avatar_upload() {
        # Get current user
        $user_id = get_current_user_id();

        # Nonce verification
        if (!isset($_POST['update_frontend_avatar_nonce_field']) || !wp_verify_nonce( $_POST['update_frontend_avatar_nonce_field'], 'update_frontend_avatar_nonce' ) ) {
            wp_die(esc_html__('Security check', 'frontend-user-avatar'));
        }
        
        # If it the correct post, update profile
        if (isset($_POST['avatar_submit_button'])) {            
            ProfileUpdater::update_profile($user_id);   
        }
        
        # Go back to page
        wp_redirect(wp_get_referer());
        exit;     
    }
}
