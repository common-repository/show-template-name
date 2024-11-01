<?php

namespace Bubuku\Plugins\ShowTemplateName;

use const PHP_VERSION;

class Plugin {
    
    public const _CLASSES_ = __DIR__;
    public const CURRENT_VERSION = '2';

    private const SUPPORTED_PHP_VERSION = '7.2.0';
    private const LANG_DIR = '/languages';

    public function __invoke() {

        \define(__NAMESPACE__ . '\_PLUGIN_', dirname(__DIR__));
        \define(__NAMESPACE__ . '\PLUGIN_NAME', basename(_PLUGIN_));

        if (version_compare(PHP_VERSION, self::SUPPORTED_PHP_VERSION) < 0) {
            return add_action('admin_notices', [ $this, 'you_need_recent_version_of_PHP' ]);
        }

        if (\defined('WP_UNINSTALL_PLUGIN')) {
            $this->uninstall();
        } else {
            $this->initialize();
        }
    }

    private function initialize(): void {
        
        if ( is_admin() ) {
            require_once(self::_CLASSES_ . '/admin/filter-action.php'); 
            if (class_exists('Bubuku\Plugins\ShowTemplateName\Admin\FilterAction')) {
                $filter_action_admin = new \Bubuku\Plugins\ShowTemplateName\Admin\FilterAction();
            }

        } else {
            require_once(self::_CLASSES_ . '/front/filter-action.php'); 
            if (class_exists('Bubuku\Plugins\ShowTemplateName\Front\FilterAction')) {
                $filter_action_admin = new \Bubuku\Plugins\ShowTemplateName\Front\FilterAction();
            }
        }
        
    }

    /**
     * Tasks of uninstallation  :(
     *
     * @return void
     */
    private function uninstall(): void {
        ( new Settings() )->unregister();
    }

    /**
     * Show a warning when PHP version is not recent.
     *
     * @return void
     */
    final public function you_need_recent_version_of_PHP(): void {
        $msg   = sprintf('You need %s version of PHP for <strong>Show Template Name</strong> plugin', self::SUPPORTED_PHP_VERSION);
        $alert = __($msg, PLUGIN_NAME);

        echo str_replace('{{ alert }}', $alert, \file_get_contents(_PLUGIN_ . '/views/need_php_version.tpl'));
    }

}