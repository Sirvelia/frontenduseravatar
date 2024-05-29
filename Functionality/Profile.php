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
    }

    # Interface to upload a new avatar from the admin page
    public function edit_user_profile($userData) {        
        echo '<h2>' . esc_html__('Avatar', 'frontend-user-avatar') . '</h2>';
        echo  '<p>' . esc_html__('Upload new avatar', 'frontend-user-avatar') . '</p>';
        echo '<p><input type="file" name="frontend-user-avatar" id="input"/></p>';
        echo '<input type="submit" name="avatar-submit-button" value="' . esc_html__('Update avatar', 'frontend-user-avatar') .  '"/>';
        echo '<script type="text/javascript">var form = document.getElementById("your-profile"); form.encoding = "multipart/form-data"; form.setAttribute("enctype", "multipart/form-data");</script>'; 
    }

    public function update_avatar($userID) {
        ProfileUpdater::update_profile($userID); 
    }
}    