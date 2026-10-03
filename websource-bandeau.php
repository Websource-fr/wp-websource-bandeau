<?php
/**
 * Plugin Name:       WebsourceBandeau
 * Plugin URI:        https://www.websource.fr/modules-wordpress/module-bandeau-promotionnel-defilant-wordpress
 * Description:       Bandeau promotionnel défilant, pleine largeur, au-dessus de l'en-tête du site, avec jusqu'à 3 messages configurables, rotation automatique et fermeture mémorisée par le visiteur.
 * Version:           1.1.0
 * Requires at least: 6.0
 * Requires PHP:      8.1
 * Author:            Websource
 * Author URI:        https://www.websource.fr/
 * License:            GPL v2 or later
 * License URI:        https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:        websource-bandeau
 * Domain Path:        /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WB_VERSION', '1.1.0' );
define( 'WB_PLUGIN_FILE', __FILE__ );
define( 'WB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once WB_PLUGIN_DIR . 'includes/class-wb-activator.php';
require_once WB_PLUGIN_DIR . 'includes/class-wb-render.php';

register_activation_hook( __FILE__, array( 'WB_Activator', 'activate' ) );

function wb_load_textdomain(): void {
	load_plugin_textdomain( 'websource-bandeau', false, dirname( plugin_basename( WB_PLUGIN_FILE ) ) . '/languages' );
}
add_action( 'init', 'wb_load_textdomain' );

function wb_init_plugin(): void {
	WB_Render::init();

	if ( is_admin() ) {
		require_once WB_PLUGIN_DIR . 'admin/class-wb-admin.php';
		WB_Admin::init();
		require_once WB_PLUGIN_DIR . 'admin/class-wb-support-box.php';
		WB_Support_Box::init();
	}
}
add_action( 'plugins_loaded', 'wb_init_plugin' );
