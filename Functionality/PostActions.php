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
        $this->add_post_action('fua_update_frontend_avatar', 'handle_avatar_upload');
    }

    public function handle_avatar_upload() {
        # Get current user
        $user_id = get_current_user_id(); 
        
        # Update profile                   
        ProfileUpdater::update_profile($user_id);           
        
        # Go back to page
        wp_redirect(wp_get_referer());
        exit;     
    }
}
