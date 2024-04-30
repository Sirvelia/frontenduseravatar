<?php

namespace FrontendUserAvatar\Includes;

class Lyfecycle
{
    public static function activate($network_wide)
    {
        do_action('FrontendUserAvatar/setup', $network_wide);
    }

    public static function deactivate($network_wide)
    {
        do_action('FrontendUserAvatar/deactivation', $network_wide);
    }

    public static function uninstall()
    {
        do_action('FrontendUserAvatar/cleanup');
    }
}
