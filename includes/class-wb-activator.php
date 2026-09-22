<?php
/**
 * Activation : réglages par défaut.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WB_Activator {

	public static function activate(): void {
		$defaults = array(
			'enabled'          => 0,
			'bg_color'         => '#1d2327',
			'text_color'       => '#ffffff',
			'rotation_seconds' => 6,
			'dismissible'      => 1,
			'remember_days'    => 1,
			'messages'         => array(
				array(
					'text'      => __( 'Livraison offerte dès 50€ d’achat !', 'websource-bandeau' ),
					'link_url'  => '',
					'link_text' => '',
					'bold'      => 0,
				),
				array(
					'text'      => '',
					'link_url'  => '',
					'link_text' => '',
					'bold'      => 0,
				),
				array(
					'text'      => '',
					'link_url'  => '',
					'link_text' => '',
					'bold'      => 0,
				),
			),
		);
		add_option( 'wb_settings', $defaults );
	}
}
