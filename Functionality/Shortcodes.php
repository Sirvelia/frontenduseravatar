<?php

namespace FrontendUserAvatar\Functionality;

class Shortcodes {

	protected $plugin_id;
    protected $plugin_version;
    
    public function __construct($plugin_id, $plugin_version) {
        $this->plugin_id = $plugin_id;
        $this->plugin_version = $plugin_version;
        
        add_shortcode('frontend-user-avatar', [ $this, 'frontend_user_avatar_shortcode' ]);
        add_shortcode('frontend-avatar-preview', [ $this, 'frontend_user_avatar_preview_shortcode' ]);
        add_action( 'wp_enqueue_scripts', [ $this, 'frontend_user_avatar_shortcode_scripts' ] );
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
        ?>

        <form class="fua_shortcode_form" method="POST" enctype="multipart/form-data" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <input type="hidden" name="action" value="fua_update_frontend_avatar">
            <?php wp_nonce_field( 'fua_update_frontend_avatar_nonce', 'fua_update_frontend_avatar_nonce_field' ); ?>

            <button id="fua_avatar_switch_button" class="fua_switch_avatar_button fua_button" type="button" class="button" style="--fua-upload-text: '<?php echo esc_html__( 'Upload', 'frontenduseravatar' ); ?>'">
                <img id="fua_avatar_preview" src="<?php echo get_avatar_url( $user_data->ID ); ?>" alt="<?php echo esc_html__( 'Current avatar', 'frontenduseravatar' ); ?>" width="96" height="96">
            </button>

            <input id="fua_avatar_input" class="hidden" type="file" accept="image/*" name="frontend-user-avatar">

            <input class="fua_input_submit fua_button fua_button_primary" type="submit" value="<?php echo esc_html__('Save avatar', 'frontenduseravatar'); ?>" />
        </form>
        
        <?php
        return ob_get_clean();
    }

    public function frontend_user_avatar_preview_shortcode()
    {
        if ( !is_user_logged_in() ) {
            return;
        }

        $user_id    = get_current_user_id();        
        $user_data  = get_userdata( $user_id );

        ob_start();
        ?>
        
        <img src="<?php echo get_avatar_url( $user_data->ID ); ?>" alt="<?php echo esc_html__( 'Current avatar', 'frontenduseravatar' ); ?>" width="96" height="96">

        <?php
        return ob_get_clean();
    }

    public function frontend_user_avatar_shortcode_scripts()
    {
        $post_content = get_the_content();

        if ( has_shortcode( $post_content, 'frontend-user-avatar' ) || has_shortcode( $post_content, 'frontend-avatar-preview' ) ) {
            wp_enqueue_script( $this->plugin_id, frontenduseravatar_asset( 'app.js' ), [], $this->plugin_version );
            wp_enqueue_style( $this->plugin_id, frontenduseravatar_asset( 'app.css' ), [], $this->plugin_version );
        }
    }
}      