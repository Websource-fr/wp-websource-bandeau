<?php
/**
 * Vue : réglages du bandeau.
 *
 * @var array $settings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$messages = $settings['messages'] ?? array();
while ( count( $messages ) < 3 ) {
	$messages[] = array(
		'text'      => '',
		'link_url'  => '',
		'link_text' => '',
		'bold'      => 0,
	);
}
?>
<div class="wrap">
	<h1><?php esc_html_e( 'WebsourceBandeau — Réglages', 'websource-bandeau' ); ?></h1>
	<p><?php esc_html_e( 'Bandeau défilant pleine largeur affiché au-dessus de l’en-tête du site. Configurez jusqu’à 3 messages ci-dessous.', 'websource-bandeau' ); ?></p>

	<form method="post" action="options.php">
		<?php settings_fields( 'wb_settings_group' ); ?>

		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'Activer le bandeau', 'websource-bandeau' ); ?></th>
				<td>
					<label>
						<input type="checkbox" name="wb_settings[enabled]" value="1" <?php checked( ! empty( $settings['enabled'] ) ); ?> />
						<?php esc_html_e( 'Afficher le bandeau sur le site', 'websource-bandeau' ); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="wb_bg_color"><?php esc_html_e( 'Couleur de fond', 'websource-bandeau' ); ?></label></th>
				<td><input type="text" id="wb_bg_color" name="wb_settings[bg_color]" class="wb-color-field" value="<?php echo esc_attr( $settings['bg_color'] ?? '#1d2327' ); ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="wb_text_color"><?php esc_html_e( 'Couleur du texte', 'websource-bandeau' ); ?></label></th>
				<td><input type="text" id="wb_text_color" name="wb_settings[text_color]" class="wb-color-field" value="<?php echo esc_attr( $settings['text_color'] ?? '#ffffff' ); ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="wb_rotation_seconds"><?php esc_html_e( 'Durée d’affichage par message (secondes)', 'websource-bandeau' ); ?></label></th>
				<td><input type="number" id="wb_rotation_seconds" name="wb_settings[rotation_seconds]" min="2" step="1" value="<?php echo esc_attr( $settings['rotation_seconds'] ?? 6 ); ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Fermeture par le visiteur', 'websource-bandeau' ); ?></th>
				<td>
					<label>
						<input type="checkbox" name="wb_settings[dismissible]" value="1" <?php checked( ! empty( $settings['dismissible'] ) ); ?> />
						<?php esc_html_e( 'Afficher un bouton de fermeture', 'websource-bandeau' ); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="wb_remember_days"><?php esc_html_e( 'Ne pas réafficher pendant (jours)', 'websource-bandeau' ); ?></label></th>
				<td>
					<input type="number" id="wb_remember_days" name="wb_settings[remember_days]" min="0" step="1" value="<?php echo esc_attr( $settings['remember_days'] ?? 1 ); ?>" />
					<p class="description"><?php esc_html_e( '0 = mémorisé indéfiniment (jusqu’à ce que le visiteur vide son stockage local).', 'websource-bandeau' ); ?></p>
				</td>
			</tr>
		</table>

		<h2><?php esc_html_e( 'Messages (jusqu’à 3)', 'websource-bandeau' ); ?></h2>

		<?php foreach ( $messages as $index => $message ) : ?>
			<h3><?php printf( /* translators: %d: message number */ esc_html__( 'Message %d', 'websource-bandeau' ), $index + 1 ); ?></h3>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="wb_message_text_<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Texte', 'websource-bandeau' ); ?></label></th>
					<td><input type="text" id="wb_message_text_<?php echo esc_attr( $index ); ?>" name="wb_settings[messages][<?php echo esc_attr( $index ); ?>][text]" class="large-text" value="<?php echo esc_attr( $message['text'] ?? '' ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="wb_message_link_url_<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Lien (optionnel)', 'websource-bandeau' ); ?></label></th>
					<td><input type="url" id="wb_message_link_url_<?php echo esc_attr( $index ); ?>" name="wb_settings[messages][<?php echo esc_attr( $index ); ?>][link_url]" class="regular-text" value="<?php echo esc_attr( $message['link_url'] ?? '' ); ?>" placeholder="https://" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="wb_message_link_text_<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Texte du lien', 'websource-bandeau' ); ?></label></th>
					<td><input type="text" id="wb_message_link_text_<?php echo esc_attr( $index ); ?>" name="wb_settings[messages][<?php echo esc_attr( $index ); ?>][link_text]" class="regular-text" value="<?php echo esc_attr( $message['link_text'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'En savoir plus', 'websource-bandeau' ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Texte en gras', 'websource-bandeau' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="wb_settings[messages][<?php echo esc_attr( $index ); ?>][bold]" value="1" <?php checked( ! empty( $message['bold'] ) ); ?> />
							<?php esc_html_e( 'Mettre ce message en gras', 'websource-bandeau' ); ?>
						</label>
					</td>
				</tr>
			</table>
		<?php endforeach; ?>

		<?php submit_button(); ?>
	</form>

	<h2><?php esc_html_e( 'Intégration manuelle', 'websource-bandeau' ); ?></h2>
	<p>
		<?php esc_html_e( 'Le bandeau s’affiche automatiquement via le hook wp_body_open(). Si votre thème ne l’appelle pas, ajoutez ceci juste après la balise <body> de header.php :', 'websource-bandeau' ); ?>
	</p>
	<pre>&lt;?php do_action( 'websource_bandeau_render' ); ?&gt;</pre>
</div>
