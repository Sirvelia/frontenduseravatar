<?php

namespace FrontendUserAvatar\Functionality;

class Shortcodes {

    protected $plugin_name;
    protected $plugin_version;
    
    public function __construct($plugin_name, $plugin_version) {
        $this->plguin_name = $plugin_name;
        $this->plugin_version = $plugin_version;

        add_action('admin_enqueue_scripts', [$this, 'register_scripts']);

        add_action('show_user_profile', [$this, 'fua_show_user_profile_picture']); 
        add_action('edit_user_profile', [$this, 'fua_show_user_profile_picture']); 

        add_action('personal_options_update', [$this, 'fua_save_profile_picture']);
        add_action('edit_user_profile_update', [$this, 'fua_save_profile_picture']);

        add_filter('get_avatar', [$this, 'fua_change_avatar'], 10, 5);
    }

    //Function to show the profile picture
    public function fua_change_avatar($avatar, $id_or_email, $size, $default, $alt) {
        #If it is an email
        if (!is_numeric($id_or_email) && is_email($id_or_email)) {
            $user = get_user_by('email', $id_or_email);
            if ($user) {
                $id_or_email = $user->ID;
            }
        }

        //Default avatar if not email nor id
        if (!is_numeric($id_or_email)) {
            return $avatar;
        }

        //get URL
        $saved = get_user_meta($id_or_email, 'profile_picture', true);
        
        //If it's valid
        if (filter_var($saved, FILTER_VALIDATE_URL)) {
            // Return the saved image
            return sprintf('<img src="%s" alt="%s" class="avatar avatar-%d photo" width="%d" height="%d" />', esc_url($saved), esc_attr($alt), $size, $size, $size);
        }

        return $avatar;
    }

    //Function to add fields to update the profile picture
    public function fua_show_user_profile_picture($profile_user) {
        ?>
        <h3>Profile Picture</h3>
        <table class="form-table">
            <tr>
                <th><label for="profile_picture">Upload Profile Picture</label></th>
                <td>
                    <input type="hidden" id="profile_picture" name="profile_picture" value="<?php echo esc_attr(get_user_meta($profile_user->ID, 'profile_picture', true)); ?>" />
                    <input type="button" class="button" id="profile_picture_button" value="Upload Picture" />
                    <br />
                    <span class="description">Upload a profile picture.</span>
                    <div id="profile_picture_preview" style="margin-top:10px;">
                        <?php if (get_user_meta($profile_user->ID, 'profile_picture', true)): ?>
                            <img src="<?php echo esc_attr(get_user_meta($profile_user->ID, 'profile_picture', true)); ?>" style="width: 150px; height: 150px;" />
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        </table>
        <?php
    }

    //Function to save the profile picture
    public function fua_save_profile_picture($user_id) {
        if (current_user_can('edit_user', $user_id)) {
            update_user_meta($user_id, 'profile_picture', $_POST['profile_picture']);
        }
    }

    //Function to register scripts
    public function register_scripts($hook) {
        if ($hook != 'user-edit.php' && $hook != 'profile.php') {
            return;
        }
        wp_enqueue_media();
        wp_enqueue_script('upload', plugin_dir_url(__FILE__) . 'upload.js', array('jquery'), $this->plugin_version, true);
    }
}