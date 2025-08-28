<?php

namespace FrontendUserAvatar\Functionality;

use FrontendUserAvatar\Components\ProfileUpdater;
use WP_User;

class Profile
{
    protected $plugin_id;
    protected $plugin_version;

    public function __construct($plugin_id, $plugin_version)
    {
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
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_script']);
    }

    # Interface to upload a new avatar from the admin page
    public function edit_user_profile(WP_User $user_data)
    {
        ob_start();
?>
        <h2 class="fua_admin_page_title"><?php echo esc_html__('Edit Avatar', 'frontenduseravatar'); ?></h2>

        <?php wp_nonce_field('fua_update_frontend_avatar_nonce', 'fua_update_frontend_avatar_nonce_field'); ?>

        <button id="fua_avatar_switch_button" class="fua_switch_avatar_button button" type="button" class="button">
            <img id="fua_avatar_preview" src="<?php echo get_avatar_url($user_data->ID); ?>" alt="<?php echo esc_html__('Current avatar', 'frontenduseravatar'); ?>" width="96" height="96">
        </button>

        <input id="fua_avatar_input" class="hidden" type="file" accept="image/*" name="frontend-user-avatar">

        <div>
            <p class="fua_helper_text"><small><?php echo esc_html__('Click on the avatar to select a new image', 'frontenduseravatar'); ?></small></p>
        </div>
<?php
        $avatar_html = ob_get_clean();

        echo $avatar_html;
    }

    public function update_avatar($user_id)
    {
        ProfileUpdater::update_profile($user_id);
    }

    public function enqueue_admin_script()
    {
        $screen = get_current_screen();

        if ($screen && $screen->id === 'user-edit' || $screen->id === 'profile') {
            wp_enqueue_script($this->plugin_id, frontenduseravatar_asset('app.js'), [], $this->plugin_version);
            wp_enqueue_style($this->plugin_id, frontenduseravatar_asset('app.css'), [], $this->plugin_version);
        }
    }
}
