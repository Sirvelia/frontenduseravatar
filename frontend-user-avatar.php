<?php

/**
 * @wordpress-plugin
 * Plugin Name:       FrontendUserAvatar
 * Plugin URI:        https://sirvelia.com/
 * Description:       A WordPress plugin made with PLUBO.
 * Version:           1.0.0
 * Author:            Sirvelia
 * Author URI:        https://sirvelia.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       frontend-user-avatar
 * Domain Path:       /languages
 * Update URI:        false
 * Requires Plugins:
 */

if (!defined('WPINC')) {
    die('YOU SHALL NOT PASS!');
}

// PLUGIN CONSTANTS
define('FRONTENDUSERAVATAR_NAME', 'frontend-user-avatar');
define('FRONTENDUSERAVATAR_VERSION', '1.0.0');
define('FRONTENDUSERAVATAR_PATH', plugin_dir_path(__FILE__));
define('FRONTENDUSERAVATAR_BASENAME', plugin_basename(__FILE__));
define('FRONTENDUSERAVATAR_URL', plugin_dir_url(__FILE__));
define('FRONTENDUSERAVATAR_ASSETS_PATH', FRONTENDUSERAVATAR_PATH . 'dist/' );
define('FRONTENDUSERAVATAR_ASSETS_URL', FRONTENDUSERAVATAR_URL . 'dist/' );

// AUTOLOAD
if (file_exists(FRONTENDUSERAVATAR_PATH . 'vendor/autoload.php')) {
    require_once FRONTENDUSERAVATAR_PATH . 'vendor/autoload.php';
}

// LYFECYCLE
register_activation_hook(__FILE__, [FrontendUserAvatar\Includes\Lyfecycle::class, 'activate']);
register_deactivation_hook(__FILE__, [FrontendUserAvatar\Includes\Lyfecycle::class, 'deactivate']);
register_uninstall_hook(__FILE__, [FrontendUserAvatar\Includes\Lyfecycle::class, 'uninstall']);

// LOAD ALL FILES
$loader = new FrontendUserAvatar\Includes\Loader();
