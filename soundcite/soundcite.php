<?php
/*
Plugin Name: Soundcite
Plugin URI: https://github.com/NUKnightLab/soundcite-wordpress-plugin
Description: Enable Soundcite embedding in WordPress
Version: 0.1
Author: Paul Schreiber
Author URI: http://paulschreiber.com/
*/

class Soundcite {

	public static function hooks() {
		// handle Soundcite shortcode
		add_shortcode( 'soundcite', array( get_called_class(), 'shortcode_handler' ) );

		// load the Soundcite JavaScript and CSS
		add_action( 'admin_enqueue_scripts', array( get_called_class(), 'enqueue_scripts' ) );

		// TinyMCE: allow <span>s
		add_filter( 'tiny_mce_before_init', array( get_called_class(), 'tinymce_allow_span' ) );

		// KSES: Allow Soundcite data- attibutes on <span>s
		add_action( 'init', array( get_called_class(), 'kses_allow_span' ) );
	}

	/**
	 * Enqueue the Soundcite JS and CSS
	 *
	 * @param string $hook The current admin page.
	 */
	public static function enqueue_scripts( $hook = null ) {
		if ( is_admin() && ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		wp_enqueue_script( 'soundcite', 'https://cdn.knightlab.com/libs/soundcite/latest/js/soundcite.min.js', array( 'jquery' ) );
		wp_enqueue_style( 'soundcite', 'https://cdn.knightlab.com/libs/soundcite/latest/css/player.css' );
	}

	/**
	 * Builds the Soundcite shortcode output and configuration output.
	 *
	 * @param array  $attr {
	 *     Attributes of the soundcite shortcode.
	 *
	 *     @type string $color   rgb color values (0-255) triplet such as "123,45,25"
	 * }
	 * @param string $content Shortcode content
	 * @return string empty string
	 */
	public static function shortcode_handler( $atts, $content = null ) {
		self::enqueue_scripts();

		$config = [];

		/**
		 * Add the SoundCloud Client ID to the configuration.
		 * See https://soundcite.knightlab.com/ for instructions on obtaining an ID.
		 *
		 * @param string $client_id client id
		 */
		$client_id = apply_filters( 'soundcloud_client_id', false );

		$color_text = false;
		if ( isset( $atts['color'] ) && $atts['color'] && preg_match( '/[0-9]{1,3},[0-9]{1,3},[0-9]{1,3}/', $atts['color'] ) ) {
			$color = $atts['color'];
		}

		if ( $client_id || $color ) {
			$script = 'var SOUNDCITE_CONFIG = {';

			if ( $client_id ) {
				$script .= "soundcloud_client_id: '$client_id'";
			}
			if ( $client_id && $color ) {
				$script .= ',';
			}
			if ( $color ) {
				$script .= "update_playing_element: function(el, percentage) { el.style.cssText = 'background: linear-gradient(to right, rgba($color,.15)' + percentage + '%, rgba($color,.05)' + (percentage + 1) + '%);';}\n";
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
}

Soundcite::hooks();
