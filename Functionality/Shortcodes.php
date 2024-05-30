<?php

namespace FrontendUserAvatar\Functionality;

class Shortcodes {

	protected $plugin_id;
    protected $plugin_version;
    
    public function __construct($plugin_id, $plugin_version) {
        $this->plugin_id = $plugin_id;
        $this->plugin_version = $plugin_version;
        
        add_shortcode('frontend-user-avatar', [$this, 'frontend_user_avatar_shortcode']);
    }

    # Frontend shortcode
    public function frontend_user_avatar_shortcode() {
        # If user not logged, return
        if (!is_user_logged_in()) {
            return;
        }
        
        # Get user data
        $user_id = get_current_user_id();        
        $user_data = get_userdata($user_id);

        # Start HTML print
        ob_start();

        echo '<form id="frontend-avatar-form" method="post" enctype="multipart/form-data" action="' . esc_url(admin_url('admin-post.php')) . '">';
        echo '<input type="hidden" name="action" value="update_frontend_avatar">';
        wp_nonce_field('update_frontend_avatar_nonce', 'update_frontend_avatar_nonce_field');
        echo '<p><input type="file" name="frontend-user-avatar" id="input"/></p>';

        # If the user has permission
        if (current_user_can('upload_files')) {
            if (empty($user_data->user_avatar)) {
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