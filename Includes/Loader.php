<?php

namespace FrontendUserAvatar\Includes;

class Loader
{
    public function __construct()
    {
        $this->loadDependencies();

        add_action('plugins_loaded', [$this, 'loadPluginTextdomain']);
    }

    private function loadDependencies()
    {
        //FUNCTIONALITY CLASSES
        foreach (glob(FRONTENDUSERAVATAR_PATH . 'Functionality/*.php') as $filename) {
            $class_name = '\\FrontendUserAvatar\Functionality\\' . basename($filename, '.php');
            if (class_exists($class_name)) {
                try {
                    new $class_name(FRONTENDUSERAVATAR_NAME, FRONTENDUSERAVATAR_VERSION);
                } catch (\Throwable $e) {
                    frontenduseravatar_log($e);
                    continue;
                }
            }
        }

        //ADMIN FUNCTIONALITY
        if( is_admin() ) {
            foreach (glob(FRONTENDUSERAVATAR_PATH . 'Functionality/Admin/*.php') as $filename) {
                $class_name = '\\FrontendUserAvatar\Functionality\Admin\\' . basename($filename, '.php');
                if (class_exists($class_name)) {
                    try {
                        new $class_name(FRONTENDUSERAVATAR_NAME, FRONTENDUSERAVATAR_VERSION);
                    } catch (\Throwable $e) {
                        frontenduseravatar_log($e);
                        continue;
                    }
                }
            }
        }
    }

    public function loadPluginTextdomain()
    {
        load_plugin_textdomain('frontend-user-avatar', false, dirname(FRONTENDUSERAVATAR_BASENAME) . '/languages/');
    }
}
