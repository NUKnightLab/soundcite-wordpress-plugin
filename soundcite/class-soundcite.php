<?php
define( 'SOUNDCITE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) ); // All have trailing slash
define( 'SOUNDCITE_PLUGIN_VERSION', '0.1' );
define( 'SOUNDCITE_PLUGIN_BASE_NAME', plugin_basename( __FILE__ ) );

class Soundcite {

	public static function hooks() {

		add_action( 'wp_head', array( get_called_class(), 'soundcite_config' ) );

		// load the Soundcite JavaScript and CSS
		add_action( 'admin_enqueue_scripts', array( get_called_class(), 'enqueue_scripts' ) );

		// TinyMCE: allow <span>s
		add_filter( 'tiny_mce_before_init', array( get_called_class(), 'tinymce_allow_span' ) );

		// KSES: Allow Soundcite data- attibutes on <span>s
		add_action( 'init', array( get_called_class(), 'kses_allow_span' ) );

		// add section to settings / media
		add_action( 'admin_init', array( get_called_class(), 'admin_init' ) );

		add_filter( 'mce_css', array( get_called_class(), 'my_plugin_editor_style' ) );
	}

	public static function admin_init() {
		register_setting( 'media', 'soundcite_soundcloud_client_id', 'sanitize_text_field' );
		register_setting( 'media', 'soundcite_background_color', 'sanitize_hex_color' );

		add_settings_section(
			'soundcite_settings_section',
			'Knight Lab Soundcite Settings',
			array( get_called_class(), 'settings_section_cb' ),
			'media'
		);
		add_settings_field(
			'soundcite_soundcloud_client_id',
			'Sound Cloud Client ID',
			array( get_called_class(), 'soundcloud_field_cb' ),
			'media',
			'soundcite_settings_section',
			array(
				'label_for' => 'soundcite_soundcloud_client_id',
			)
		);
		add_settings_field(
			'soundcite_background_color',
			'Background Color for Clips',
			array( get_called_class(), 'bgcolor_field_cb' ),
			'media',
			'soundcite_settings_section',
			array(
				'label_for' => 'soundcite_background_color',
			)
		);
	}

	public static function settings_section_cb() {
		?>

		<p><strong>Please note</strong> SoundCloud imposes rate limits on streaming audio with third-party tools like Soundcite.
			By default, all Soundcite users use the same <em>client ID</em>, which can
		lead to cases where your Soundcite clips temporarily stop working. You can prevent this from happening by registering
		for your own free SoundCloud client ID and pasting it in the field below.</p>
		<p>If you don't use SoundCloud to host your Soundcite audio, you can ignore this.</p>
		<?php
	}

	public static function soundcloud_field_cb() {
		$setting = get_option( 'soundcite_soundcloud_client_id' );
	?>
	<input type="text" size="40" id="soundcite_soundcloud_client_id" name="soundcite_soundcloud_client_id" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
		<p>
			<strong>How to get a SoundCloud client ID</strong>
		</p>
		<ul style="list-style-type: disc; padding-left: 3em;">
		<li>Go to <a href="https://developers.soundcloud.com/" target="_blank">https://developers.soundcloud.com/</a></li>
		<li>Click "Register a new application</li>
		<li>Fill in the application for and click "Register"</li>
		<li>Copy the "Client ID" field. This is your API key</li>
		<li>Be sure to save the app</li>
		</ul>
		<?php
	}

	public static function bgcolor_field_cb() {
		$setting = get_option( 'soundcite_background_color' );
	?>
	<input type="text" size="10" id="soundcite_background_color" name="soundcite_background_color" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
		<p>Enter a CSS hex color (e.g. <em>#DF4E13</em>) to change the color of your clips. Transparency is always 15%.</p>
		<?php
	}

	public static function enqueue_scripts( $hook = null ) {
		if ( is_admin() && ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		wp_enqueue_script( 'soundcite', 'https://cdn.knightlab.com/libs/soundcite/latest/js/soundcite.min.js', array( 'jquery' ) );
		wp_enqueue_style( 'soundcite', 'https://cdn.knightlab.com/libs/soundcite/latest/css/player.css' );
	}

	public static function soundcite_config() {
		self::enqueue_scripts();

		$config = [];

		$client_id = get_option( 'soundcite_soundcloud_client_id' );
		$color = get_option( 'soundcite_background_color' );

		self::render_soundcite_config( $client_id, $color );
	}

	public static function render_soundcite_config( $client_id, $color ) {
		if ( $client_id || $color ) {
			$script = 'var SOUNDCITE_CONFIG = {';

			if ( $client_id ) {
				$script .= "soundcloud_client_id: '$client_id'";
			}
			if ( $client_id && $color ) {
				$script .= ',';
			}
			if ( $color ) {
				$script .= "background_color: '$color'\n";
			}

			$script .= "};\n";
			wp_add_inline_script( 'soundcite', $script );
		}

	}

	/**
	 * Allow span tags within TinyMCE
	 * http://vip.wordpress.com/documentation/register-additional-html-attributes-for-tinymce-and-wp-kses/
	 *
	 * @param $options
	 *
	 * @return mixed
	 */
	public static function tinymce_allow_span( $options ) {
		if ( ! isset( $options['extended_valid_elements'] ) ) {
			$options['extended_valid_elements'] = '';
		}

		$options['extended_valid_elements'] .= ',span[class|id|style|data-url|data-id|data-start|data-end|data-plays]';

		return $options;
	}

	/**
	 * Allow data-xyz attributes on span tags; used for Soundcite
	 * http://vip.wordpress.com/documentation/register-additional-html-attributes-for-tinymce-and-wp-kses/
	 */
	public static function kses_allow_span() {
		global $allowedposttags;

		$tags = array( 'span' );
		$new_attributes = array(
			'data-url' => true,
			'data-id' => true,
			'data-start' => true,
			'data-end' => true,
			'data-plays' => true,
		);

		foreach ( $tags as $tag ) {
			if ( isset( $allowedposttags[ $tag ] ) && is_array( $allowedposttags[ $tag ] ) ) {
				$allowedposttags[ $tag ] = array_merge( $allowedposttags[ $tag ], $new_attributes );
			}
		}
	}

	public static function my_plugin_editor_style( $mce_css ) {
		// https://shellcreeper.com/add-editor-style-from-plugin/
		$mce_css .= ', ' . plugins_url( 'editor-style.css', __FILE__ );
		return $mce_css;
	}
}

Soundcite::hooks();
