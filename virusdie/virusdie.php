<?php
/**
 * Plugins primary file, in charge of including all other dependencies.
 *
 * @package Virusdie
 * @wordpress-plugin
 *
 * Plugin Name: Virusdie | One-click website security
 * Description: One-Click Website security with Virusdie Wordpress Plugin
 * Author: Virusdie
 * Version: 1.1.3
 * Requires PHP: 5.6
 * Author URI: https://virusdie.com
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: virusdie
 * Network: true
 */

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

// Plugin version, name, path, URL
define( 'VDWS_VIRUSDIE_PLUGIN_VERSION',   '1.1.3' );
define( 'VDWS_VIRUSDIE_PLUGIN_DIRECTORY', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'VDWS_VIRUSDIE_PLUGIN_URL',       trailingslashit( plugins_url( '/', __FILE__ ) ) );
define( 'VDWS_VIRUSDIE_PLUGIN_ADMIN_URL', admin_url( 'admin.php?page=virusdie', is_ssl() ? 'https' : 'http' ) );
define( 'VDWS_VIRUSDIE_PLUGIN_FILE',      'virusdie' );
define( 'VDWS_VIRUSDIE_PLUGIN_SLUG',      'virusdie' );

// Plugin option names
define( 'VDWS_VIRUSDIE_OPT_SYNCFILE',     'wdws_virusdie_syncfile' );
define( 'VDWS_VIRUSDIE_OPT_API_KEY',      'wdws_virusdie_apikey' );
define( 'VDWS_VIRUSDIE_OPT_USERS_EXISTS', 'wdws_virusdie_users' );

// Virusdie REST API server and access key
define( 'VDWS_VIRUSDIE_API_HOST',   'https://virusdie.com/api/' );
define( 'VDWS_VIRUSDIE_API2_HOST',  'https://new.virusdie.com/internal-api/' );
define( 'VDWS_VIRUSDIE_SHARED_KEY', 'JTojh1Jt81K5CM94YDxYnmd7pjG4etf9Bh8vSE2zg47Sa911hu4h71adSy2yjjUF' ); // ID 7e5fabc0d877968b9d23d468dd83d4d7723fcc3c

// Plugin sites
define( 'VDWS_VIRUSDIE_SITE_LANDING', 'https://virusdie.com' );
define( 'VDWS_VIRUSDIE_SITE_PANEL',   'https://new.virusdie.com' );
define( 'VDWS_VIRUSDIE_SITE_ACCOUNT', 'https://myaccount.virusdie.com' );

// Include class files
require_once( __DIR__ . '/inc/class-virusdie.php' );
require_once( __DIR__ . '/inc/tools/class-virusdie-api.php' );
require_once( __DIR__ . '/inc/tools/class-virusdie-user.php' );
require_once( __DIR__ . '/inc/tools/class-virusdie-site.php' );
require_once( __DIR__ . '/inc/tools/class-virusdie-behavior.php' );
require_once( __DIR__ . '/inc/tools/class-virusdie-messages.php' );
require_once( __DIR__ . '/inc/tools/class-virusdie-view.php' );
require_once( __DIR__ . '/inc/tools/class-virusdie-helper.php' );
require_once( __DIR__ . '/inc/tools/class-virusdie-geo.php' );

// Initialize the plugin
new VDWS_Virusdie();
new VDWS_VirusdieApiClient();
new VDWS_VirusdieHelper();

// Setup scheduled events
register_activation_hook( __FILE__, array('VDWS_Virusdie', 'plugin_activation') );
register_deactivation_hook( __FILE__, array('VDWS_Virusdie', 'plugin_deactivation') );
register_uninstall_hook( __FILE__, array('VDWS_Virusdie', 'plugin_unistall') );
