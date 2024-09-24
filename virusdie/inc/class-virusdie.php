<?php
/**
 * Primary class file for the Virusdie Plugin.
 *
 * @package Virusdie Plugin
 */

// Make sure the file is not directly accessible.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * class VDWS_Virusdie
 */
class VDWS_Virusdie
{

	private static $tab;
	private static $page;

	/**
	 * Notices to show at the head of the admin screen.
	 *
	 * @access public
	 *
	 * @var array
	 */
	public $admin_notices = array();

	/**
	 * Virusdie constructor.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->init();
	}

	/**
	 * Plugin initiation.
	 *
	 * A helper function, called by `VDWS_Virusdie::__construct()` to initiate actions, hooks and other features needed.
	 *
	 * @uses add_action()
	 * @uses add_filter()
	 *
	 * @return void
	 */
	public function init()
	{
		self::$page = esc_attr(isset($_GET['page']) ? $_GET['page'] : null);
		self::$tab = esc_attr(isset($_GET['tab']) ? $_GET['tab'] : 'scan');

		if ( self::$page === constant('VDWS_VIRUSDIE_PLUGIN_SLUG') ) {
			add_action( 'init', array($this, 'start_session') );
		}

		add_action( 'wp_logout', array($this, 'end_session') );
		add_action( 'wp_login', array($this, 'end_session') );

		add_action( 'plugins_loaded', array($this, 'load_i18n') );
		add_action( 'admin_menu', array($this, 'action_admin_menu') );

		// add_filter( 'plugin_action_links', array( $this, 'troubleshoot_plugin_action' ), 20, 4 ); // Will be used in future versions

		add_filter( 'plugin_action_links_' . plugin_basename( VDWS_VIRUSDIE_PLUGIN_FILE ), array( $this, 'page_plugin_action' ) );

		add_action( 'wp_ajax_virusdie_switcher', 'VDWS_VirusdieBehavior::vd_switcher' );
		// add_action( 'wp_ajax_nopriv_virusdie_switcher', 'VDWS_VirusdieBehavior::vd_switcher' ); // Will be used in future versions
		add_action( 'wp_ajax_virusdie_ajax_error', 'VDWS_VirusdieBehavior::vd_ajax_error' );
		add_action( 'wp_ajax_virusdie_start_scan', 'VDWS_VirusdieBehavior::vd_scan_start' );
		add_action( 'wp_ajax_virusdie_get_progress', 'VDWS_VirusdieBehavior::vd_get_progress' );
		add_action( 'wp_ajax_virusdie_apikey', 'VDWS_VirusdieBehavior::vd_get_apikey' );
		add_action( 'wp_ajax_virusdie_resend', 'VDWS_VirusdieBehavior::vd_resend' );
	}

	/**
	 * Initializes php session
	 */
	public function start_session()
	{
		if ( session_status() === PHP_SESSION_NONE ) {
			session_start();
		}
	}

	/**
	 * Close php session
	 */
	public function end_session()
	{
		if (session_status() !== PHP_SESSION_NONE) {
			session_destroy();
		}
	}

	/**
	 * Load translations.
	 *
	 * Loads the textdomain needed to get translations for our plugin.
	 *
	 * @uses load_plugin_textdomain()
	 * @uses basename()
	 * @uses dirname()
	 *
	 * @return void
	 */
	public function load_i18n()
	{
		load_plugin_textdomain( 'virusdie', false, basename( __DIR__ ) . '/../languages/' );
	}

	/**
	 * Enqueue CSS assets.
	 *
	 * Conditionally enqueue our CSS when viewing plugin related pages in wp-admin.
	 *
	 * @uses wp_enqueue_style()
	 * @uses plugins_url()
	 * @uses esc_html__()
	 *
	 * @return void
	 */
	public static function enqueuesCss()
	{
		$screen = get_current_screen();
		// Don't enqueue anything unless we're on the virusdie page.
		if ( ( !isset($_GET['page']) || 'virusdie' !== $_GET['page']) && !in_array($screen->base, array(
				'dashboard',
				'welcome',
				'scan-start',
				'scan-error',
				'error',
			))) {
				return;
		}
		wp_enqueue_style('virusdie-style', constant('VDWS_VIRUSDIE_PLUGIN_URL') . 'assets/css/virusdie.css', array(), constant('VDWS_VIRUSDIE_PLUGIN_VERSION'));
		if (in_array(self::$tab, array('free', 'premium'))) {
			wp_enqueue_style('jvector-virusdie-style', constant('VDWS_VIRUSDIE_PLUGIN_URL') . 'assets/css/jquery-jvectormap-2.0.5.css', array('virusdie-style'), constant('VDWS_VIRUSDIE_PLUGIN_VERSION'));
		}
	}

	/**
	 * Enqueue JavaScript assets.
	 *
	 * Conditionally enqueue our JavaScript when viewing plugin related pages in wp-admin.
	 *
	 * @uses plugins_url()
	 * @uses wp_enqueue_script()
	 * @uses wp_localize_script()
	 * @uses esc_html__()
	 *
	 * @return void
	 */
	public static function enqueuesJs()
	{
		$screen = get_current_screen();
		// Don't enqueue anything unless we're on the virusdie page.
		if (
			(!isset($_GET['page']) || 'virusdie' !== $_GET['page']) &&
			!in_array($screen->base, array('dashboard', 'welcome', 'scan-start', 'scan-error', 'error'))
		) {
			return;
		}
		$tab = VDWS_Virusdie::get_current_tab();
		wp_enqueue_script( 'socket-io-virusdie', constant('VDWS_VIRUSDIE_SITE_PANEL') . '/socket.io/socket.io.js', array(), null, true);
		wp_enqueue_script( 'socketio-virusdie', constant('VDWS_VIRUSDIE_PLUGIN_URL') . 'assets/js/vdws-socketio.js', array('socket-io-virusdie'), null, true);
		if ( $tab === 'auth-pass' ) {
			wp_enqueue_script( 'auth-pass-virusdie', constant('VDWS_VIRUSDIE_PLUGIN_URL') . 'assets/js/vdws-resend.js');
		}
		if ( in_array($tab, array('free', 'premium') ) ) {
			wp_enqueue_script( 'sweetalert2-virusdie', constant('VDWS_VIRUSDIE_PLUGIN_URL') . 'assets/js/sweetalert2.all.min.js', array(), null, true);
			wp_enqueue_script( 'jvector-virusdie', constant('VDWS_VIRUSDIE_PLUGIN_URL') . 'assets/js/jquery-jvectormap-2.0.5.min.js', array('jquery'), null, true);
			wp_enqueue_script( 'world-virusdie', constant('VDWS_VIRUSDIE_PLUGIN_URL') . 'assets/js/jquery-jvectormap-world-mill.js', array('jvector-virusdie'), null, true);
			wp_enqueue_script( 'map-virusdie', constant('VDWS_VIRUSDIE_PLUGIN_URL') . 'assets/js/vdws-map.js', array('jvector-virusdie', 'world-virusdie'), null, true);
		}
		if ( $tab === 'free' ) {
			wp_enqueue_script( 'modals-virusdie', constant('VDWS_VIRUSDIE_PLUGIN_URL') . 'assets/js/vdws-modals.js', array(), null, true);
		}
		if ( $tab === 'premium' ) {
			wp_enqueue_script( 'switcher-virusdie', constant('VDWS_VIRUSDIE_PLUGIN_URL') . 'assets/js/vdws-switcher.js', array(), null, true);
		}
		if ( $tab === 'scan-start' ) {
			wp_enqueue_script( 'progressbar-virusdie', constant('VDWS_VIRUSDIE_PLUGIN_URL') . 'assets/js/progressbar.js', array(), null, true);
			wp_enqueue_script( 'scanner-virusdie', constant('VDWS_VIRUSDIE_PLUGIN_URL') . 'assets/js/vdws-scanner.js', array('progressbar-virusdie'), null, true);
		}
		if ( $tab === 'welcome' ) {
			wp_enqueue_script( 'tiny-slider-virusdie', constant('VDWS_VIRUSDIE_PLUGIN_URL') . 'assets/js/tiny-slider.js', array(), null, true);
			wp_enqueue_script( 'slider-virusdie', constant('VDWS_VIRUSDIE_PLUGIN_URL') . 'assets/js/vdws-slider.js', array(), null, true);
		}
		if ( in_array($tab, array('free','premium','scan-start','scan-error','welcome', 'error') ) ) {
			wp_enqueue_script( 'usermenu-virusdie', constant('VDWS_VIRUSDIE_PLUGIN_URL') . 'assets/js/vdws-usermenu.js', array(), null, true);
		}
	}

	/**
	 * Add item to the admin menu.
	 *
	 * @uses add_menu_page()
	 * @uses __()
	 *
	 * @return void
	 */
	public function action_admin_menu()
	{
		$critical_issues = 0;
		$issue_counts = get_transient( 'virusdie-site-status-result' );
		if ( false !== $issue_counts ) {
			$issue_counts = json_decode( $issue_counts );
			//$critical_issues = absint( $issue_counts->critical );
			$critical_issues = 0;
		}
		$critical_count = sprintf(
			'<span class="update-plugins count-%d"><span class="update-count">%s</span></span>',
			esc_html( $critical_issues ),
			sprintf(
				'%d<span class="screen-reader-text"> %s</span>',
				esc_html( $critical_issues ),
				esc_html( 'Critical issues', 'Issue counter label for the admin menu', 'virusdie' )
			)
		);
		$menu_title =
			sprintf(
				// translators: %s: Critical issue counter, if any.
				_x( 'Virusdie %s', 'Menu Title', constant('VDWS_VIRUSDIE_PLUGIN_SLUG') ),
				( ! $issue_counts || $critical_issues < 1 ? '' : $critical_count )
			);
		add_menu_page(
			_x( 'Virusdie', 'Page Title', 'virusdie' ),
			$menu_title,
			'view_site_health_checks',
			constant('VDWS_VIRUSDIE_PLUGIN_SLUG'),
			array( $this, 'dashboard_page' ),
			constant('VDWS_VIRUSDIE_PLUGIN_URL') . 'assets/img/icons/icon-menu-w.png',
			2
		);
	}

	/**
	 * Add a troubleshooting action link to plugins.
	 *
	 * @param $actions
	 * @param $plugin_file
	 * @param $plugin_data
	 * @param $context
	 *
	 * @return array
	 */
	public function troubleshoot_plugin_action( $actions, $plugin_file, $plugin_data, $context )
	{
		// Don't add anything if this is a Must-Use plugin, we can't touch those.
		if ( 'mustuse' === $context ) {
			return $actions;
		}
		$plugin_file = sanitize_text_field($plugin_file);
		// Only add troubleshooting actions to active plugins.
		if ( ! is_plugin_active( $plugin_file ) ) {
			return $actions;
		}
		// Set a slug if the plugin lives in the plugins directory root.
		if ( ! stristr( $plugin_file, '/' ) ) {
			$plugin_slug = $plugin_file;
		} else { // Set the slug for plugin inside a folder.
			$plugin_slug = explode( '/', $plugin_file );
			$plugin_slug = $plugin_slug[0];
		}
		$actions['troubleshoot'] = sprintf(
			'<a href="'.esc_url('.%s.').'">' . esc_url('.%s.') . '</a>',
			add_query_arg(
				array(
					'virusdie-troubleshoot-plugin' => $plugin_slug,
					'_wpnonce' => wp_create_nonce( 'virusdie-troubleshoot-plugin-' . $plugin_slug ),
				),
				admin_url( 'plugins.php' )
			),
			esc_html__( 'Troubleshoot', 'virusdie' )
		);
		return $actions;
	}

	/**
	 * Add a quick-access action link to the Virusdie page.
	 *
	 * @param $actions
	 *
	 * @return array
	 */
	public function page_plugin_action( $actions )
	{
		$page_link = sprintf(
			'<a href="%s">%s</a>',
			menu_page_url( 'virusdie', false ),
			_x( 'Virusdie WP Plugin', 'Menu, Section and Page Title', 'virusdie' )
		);
		array_unshift( $actions, $page_link );
		return $actions;
	}

	/**
	 * Render our admin page.
	 *
	 * @uses VDWS_Virusdie::get_current_tab()
	 *
	 * @return void
	 */
	public function dashboard_page()
	{
		new VDWS_VirusdieBehavior();
	}

	public static function get_current_tab()
	{
		return self::$tab;
	}

	public static function set_current_tab( $tab )
	{
		if (!is_string(($tab)))
			return false;
		self::$tab = $tab;
		return true;
	}

	/**
	 * Display styled admin notices.
	 *
	 * @uses printf()
	 *
	 * @param string $message A sanitized string containing our notice message.
	 * @param string $status A string representing the status type.
	 *
	 * @return void
	 */
	static function display_notice( $message, $status = 'success' )
	{
		printf(
			'<div class="notice notice-%s inline"><p>%s</p></div>',
			esc_attr( $status ),
			$message
		);
	}

	/**
	 * Display admin notices if we have any queued.
	 *
	 * @return void
	 */
	public function admin_notices()
	{
		foreach ( $this->admin_notices as $admin_notice ) {
			printf(
				'<div class="notice notice-%s"><p>%s</p></div>',
				esc_attr( $admin_notice->type ),
				$admin_notice->message
			);
		}
	}

	/**
	 * Conditionally show a form for providing filesystem credentials when introducing our troubleshooting mode plugin.
	 *
	 * @uses wp_nonce_url()
	 * @uses add_query_arg()
	 * @uses admin_url()
	 * @uses request_filesystem_credentials()
	 * @uses WP_Filesystem
	 *
	 * @param array $args Any WP_Filesystem arguments you wish to pass.
	 *
	 * @return bool
	 */
	static function get_filesystem_credentials( $args = array() )
	{
		$args = array_merge(array(
			'page' => 'virusdie',
			'tab' => 'troubleshoot',
		), $args);
		$url = wp_nonce_url( add_query_arg( $args, admin_url() ) );
		$creds = request_filesystem_credentials( $url, '', false, WP_CONTENT_DIR, array( 'virusdie-troubleshoot-mode', 'action', '_wpnonce' ) );
		if ( false === $creds ) {
			return false;
		}
		if ( ! WP_Filesystem( $creds ) ) {
			request_filesystem_credentials( $url, '', true, WPMU_PLUGIN_DIR, array( 'virusdie-troubleshoot-mode', 'action', '_wpnonce' ) );
			return false;
		}
		return true;
	}

	public static function current_location( $with_uri = true )
	{
		$protocol = (
			isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
			isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
		) ? 'https://' : 'http://';
		return $protocol . $_SERVER['SERVER_NAME'] . ($with_uri ? $_SERVER['REQUEST_URI'] : '/wp-admin/admin.php?page=virusdie&tab=scan-start');
	}

	public static function plugin_activation() {}

	public static function plugin_deactivation() {}

	public static function plugin_unistall() {
		delete_option( constant('VDWS_VIRUSDIE_OPT_API_KEY') );
		delete_option( constant('VDWS_VIRUSDIE_OPT_USERS_EXISTS') );
	}

	public static function get_api_key()
	{
		return get_option( constant('VDWS_VIRUSDIE_OPT_API_KEY') );
	}

	public static function set_api_key( $apikey )
	{
		if ( !is_string($apikey) )
			return false;
		return update_option( constant('VDWS_VIRUSDIE_OPT_API_KEY'), $apikey );
	}

	public static function del_api_key()
	{
		return delete_option( constant('VDWS_VIRUSDIE_OPT_API_KEY') );
	}

	public static function is_user_exist( $email )
	{
		if ( is_string($email) && ( $users = get_option( constant('VDWS_VIRUSDIE_OPT_USERS_EXISTS') ) ) ) {
			$users = json_decode($users, true);
			return is_array($users) && in_array($email, $users);
		}
		return false;
	}

	public static function set_user_exist( $email )
	{
		if ( !is_string($email) )
			return false;
		if ( !get_option( constant('VDWS_VIRUSDIE_OPT_USERS_EXISTS') ) ) {
			update_option( constant('VDWS_VIRUSDIE_OPT_USERS_EXISTS'), json_encode( array() ) );
		}
		$users = get_option( constant('VDWS_VIRUSDIE_OPT_USERS_EXISTS') );
		$users = json_decode($users, true);
		if (!in_array($email, $users))
			$users[] = $email;
		update_option( constant('VDWS_VIRUSDIE_OPT_USERS_EXISTS'), json_encode( $users ) );
	}

	public static function get_domain()
	{
		return isset($_SESSION['vdws_domain']) ? $_SESSION['vdws_domain'] : $_SERVER['SERVER_NAME'];
	}

}
