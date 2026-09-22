<?php
/**
 * Écran de réglages WebsourceBandeau.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WB_Admin {

	public static function init(): void {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
	}

	public static function enqueue_assets( string $hook ): void {
		if ( 'toplevel_page_websource-bandeau' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_add_inline_script(
			'wp-color-picker',
			'jQuery(function($){ $(".wb-color-field").wpColorPicker(); });'
		);
	}

	public static function register_menu(): void {
		add_menu_page(
			__( 'Bandeau promo', 'websource-bandeau' ),
			__( 'Bandeau promo', 'websource-bandeau' ),
			'manage_options',
			'websource-bandeau',
			array( __CLASS__, 'render_settings_page' ),
			'dashicons-megaphone',
			60
		);
	}

	public static function register_settings(): void {
		register_setting( 'wb_settings_group', 'wb_settings', array( __CLASS__, 'sanitize_settings' ) );
	}

	public static function sanitize_settings( array $input ): array {
		$output = array(
			'enabled'          => ! empty( $input['enabled'] ) ? 1 : 0,
			'bg_color'         => sanitize_hex_color( $input['bg_color'] ?? '' ) ?: '#1d2327',
			'text_color'       => sanitize_hex_color( $input['text_color'] ?? '' ) ?: '#ffffff',
			'rotation_seconds' => max( 2, (int) ( $input['rotation_seconds'] ?? 6 ) ),
			'dismissible'      => ! empty( $input['dismissible'] ) ? 1 : 0,
			'remember_days'    => max( 0, (int) ( $input['remember_days'] ?? 1 ) ),
			'messages'         => array(),
		);

		$raw_messages = isset( $input['messages'] ) && is_array( $input['messages'] ) ? $input['messages'] : array();

		for ( $i = 0; $i < 3; $i++ ) {
			$raw = $raw_messages[ $i ] ?? array();
			$output['messages'][] = array(
				'text'      => sanitize_text_field( $raw['text'] ?? '' ),
				'link_url'  => ! empty( $raw['link_url'] ) ? esc_url_raw( $raw['link_url'] ) : '',
				'link_text' => sanitize_text_field( $raw['link_text'] ?? '' ),
				'bold'      => ! empty( $raw['bold'] ) ? 1 : 0,
			);
		}

		return $output;
	}

	public static function render_settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$settings = WB_Render::get_settings();
		include WB_PLUGIN_DIR . 'admin/views/settings-page.php';
	}
}
