<?php

namespace FrontendUserAvatar\Functionality;

class Avatar {
	protected $plugin_id;
    protected $plugin_version;
    
    public function __construct($plugin_id, $plugin_version) {
        $this->plugin_id = $plugin_id;
        $this->plugin_version = $plugin_version;      

        # Filters avatar data
        add_filter('get_avatar_data', [$this, 'filter_avatar'], 10, 2);
    }

    public function filter_avatar($args, $id_or_email) {
        $return_args = $args;
    
        # Get user ID
        $user = false;
        if (is_numeric($id_or_email) && $id_or_email > 0) {
            $user = (int) $id_or_email;
        } elseif (is_object($id_or_email) && isset($id_or_email->user_id) && $id_or_email->user_id > 0) {
            $user = $id_or_email->user_id;
        } elseif (is_object($id_or_email) && isset($id_or_email->ID) && isset($id_or_email->user_login) && $id_or_email->ID > 0) {
            $user = $id_or_email->ID;
        } elseif (is_string($id_or_email) && false !== strpos($id_or_email, '@')) {
            $user_obj = get_user_by('email', $id_or_email);
            if ($user_obj) {
                $user = $user_obj->ID;
            }
        }
    
        # If user exists, get custom avatar
        if ($user) {
            $avatar_id = get_user_meta($user, 'frontend-user-avatar', true);

            # If avatar exists
            if ($avatar_id) {
                $avatar_url = wp_get_attachment_url($avatar_id);

                # If url exists
                if ($avatar_url) {
                    $return_args['url'] = $avatar_url;
                }
            }
        }
    
        return $return_args;
    }    
}    