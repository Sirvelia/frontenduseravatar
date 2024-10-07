<?php

namespace FrontendUserAvatar\Functionality;

use FrontendUserAvatar\Components\ProfileUpdater;

class Profile {
	protected $plugin_id;
    protected $plugin_version;
    
    public function __construct($plugin_id, $plugin_version) {
        $this->plugin_id = $plugin_id;
        $this->plugin_version = $plugin_version;  
              
        # Hook for own profile (new fields)
        add_action('show_user_profile', [$this, 'edit_user_profile']); 

        # Hook for another user profile (new fields)
        add_action('edit_user_profile', [$this, 'edit_user_profile']); 

        # Hook for editing own profile (save fields)
        add_action('personal_options_update', [$this, 'update_avatar']);

        # Hook for editing another user profile (save fields)
        add_action('edit_user_profile_update', [$this, 'update_avatar']);

        # Enqueue script to add multipart/form-data to the encoding of your-profile form
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_script' ] );
    }

    # Interface to upload a new avatar from the admin page
    public function edit_user_profile($user_data) {        
        echo '<h2 class="fua_admin_page_title">' . esc_html__('Avatar', 'frontenduseravatar') . '</h2>';
        wp_nonce_field('fua_update_frontend_avatar_nonce', 'fua_update_frontend_avatar_nonce_field');
        echo '<p class="fua_admin_page_description">' . esc_html__('Upload new avatar', 'frontenduseravatar') . '</p>';
        echo '<p><input class="fua_input_file" type="file" name="frontend-user-avatar"/></p>';        
    }

    public function update_avatar($user_id) {
        ProfileUpdater::update_profile($user_id); 
    }

    public function enqueue_admin_script()
    {
        $screen = get_current_screen();

        if ( $screen && $screen->id === 'user-edit' ) {
            wp_enqueue_script( $this->plugin_id, frontenduseravatar_asset( 'app.js' ), [], $this->plugin_version );
        }
    }
}    