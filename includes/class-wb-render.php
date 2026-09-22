<?php
/**
 * Rendu public du bandeau : hook prioritaire sur wp_body_open, avec repli sur
 * the_content (thèmes sans wp_body_open) et hook manuel websource_bandeau_render
 * pour les thèmes très anciens.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WB_Render {

	/**
	 * Empêche un double affichage si plusieurs points d'accroche se déclenchent
	 * sur la même page (wp_body_open + the_content, par exemple).
	 */
	private static bool $rendered = false;

	public static function init(): void {
		add_action( 'wp_body_open', array( __CLASS__, 'render_bar' ), 5 );
		add_action( 'websource_bandeau_render', array( __CLASS__, 'render_bar' ) );
		add_filter( 'the_content', array( __CLASS__, 'maybe_prepend_to_content' ), 1 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
	}

	public static function get_settings(): array {
		$defaults = array(
			'enabled'          => 0,
			'bg_color'         => '#1d2327',
			'text_color'       => '#ffffff',
			'rotation_seconds' => 6,
			'dismissible'      => 1,
			'remember_days'    => 1,
			'messages'         => array(),
		);
		return wp_parse_args( get_option( 'wb_settings', array() ), $defaults );
	}

	/**
	 * Retourne les messages actifs (non vides), au maximum 3.
	 */
	private static function get_active_messages( array $settings ): array {
		$messages = array_slice( (array) $settings['messages'], 0, 3 );
		return array_values(
			array_filter(
				$messages,
				static fn( $message ) => is_array( $message ) && '' !== trim( (string) ( $message['text'] ?? '' ) )
			)
		);
	}

	public static function enqueue_assets(): void {
		$settings = self::get_settings();
		if ( empty( $settings['enabled'] ) || empty( self::get_active_messages( $settings ) ) ) {
			return;
		}

		wp_enqueue_style( 'wb-bandeau', WB_PLUGIN_URL . 'assets/css/wb-bandeau.css', array(), WB_VERSION );
		wp_enqueue_script( 'wb-bandeau', WB_PLUGIN_URL . 'assets/js/wb-bandeau.js', array(), WB_VERSION, true );

		wp_add_inline_style(
			'wb-bandeau',
			sprintf(
				':root{--wb-bg:%s;--wb-text:%s;--wb-rotation:%ds;}',
				sanitize_hex_color( $settings['bg_color'] ) ?: '#1d2327',
				sanitize_hex_color( $settings['text_color'] ) ?: '#ffffff',
				max( 2, (int) $settings['rotation_seconds'] )
			)
		);

		wp_localize_script(
			'wb-bandeau',
			'wbBandeauConfig',
			array(
				'dismissible'   => ! empty( $settings['dismissible'] ),
				'rememberDays'  => max( 0, (int) $settings['remember_days'] ),
				'storageKey'    => 'wb_bandeau_dismissed_' . md5( home_url( '/' ) ),
			)
		);
	}

	/**
	 * Point d'accroche principal (wp_body_open, ou appel manuel via
	 * do_action('websource_bandeau_render') dans un thème sans wp_body_open).
	 */
	public static function render_bar(): void {
		if ( self::$rendered || is_admin() ) {
			return;
		}

		$settings = self::get_settings();
		if ( empty( $settings['enabled'] ) ) {
			return;
		}

		$messages = self::get_active_messages( $settings );
		if ( empty( $messages ) ) {
			return;
		}

		self::$rendered = true;

		echo self::build_markup( $messages, $settings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- construit avec échappement interne.
	}

	/**
	 * Repli : si aucun rendu n'a eu lieu via wp_body_open (thème ancien sans ce hook),
	 * on préfixe le contenu principal de la page.
	 */
	public static function maybe_prepend_to_content( string $content ): string {
		if ( self::$rendered || ! is_main_query() || ! in_the_loop() || is_admin() ) {
			return $content;
		}

		$settings = self::get_settings();
		if ( empty( $settings['enabled'] ) ) {
			return $content;
		}

		$messages = self::get_active_messages( $settings );
		if ( empty( $messages ) ) {
			return $content;
		}

		self::$rendered = true;

		return self::build_markup( $messages, $settings ) . $content;
	}

	private static function build_markup( array $messages, array $settings ): string {
		$dismissible = ! empty( $settings['dismissible'] );

		ob_start();
		?>
		<div id="wb-bandeau" class="wb-bandeau" data-wb-bandeau <?php echo $dismissible ? 'data-wb-dismissible' : ''; ?>>
			<div class="wb-bandeau-track">
				<?php foreach ( $messages as $index => $message ) : ?>
					<div class="wb-bandeau-message" style="--wb-index: <?php echo esc_attr( (string) $index ); ?>;">
						<?php if ( ! empty( $message['bold'] ) ) : ?>
							<strong><?php echo esc_html( $message['text'] ); ?></strong>
						<?php else : ?>
							<?php echo esc_html( $message['text'] ); ?>
						<?php endif; ?>
						<?php if ( ! empty( $message['link_url'] ) ) : ?>
							<a class="wb-bandeau-link" href="<?php echo esc_url( $message['link_url'] ); ?>">
								<?php echo esc_html( $message['link_text'] ?: __( 'En savoir plus', 'websource-bandeau' ) ); ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<?php if ( $dismissible ) : ?>
				<button type="button" class="wb-bandeau-close" aria-label="<?php esc_attr_e( 'Fermer le bandeau', 'websource-bandeau' ); ?>">&times;</button>
			<?php endif; ?>
		</div>
		<?php
		return (string) ob_get_clean();
	}
}
