<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://jazibsawar.com
 * @since      1.0.0
 *
 * @package    Bb_Audio_Playlist_Player
 * @subpackage Bb_Audio_Playlist_Player/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Bb_Audio_Playlist_Player
 * @subpackage Bb_Audio_Playlist_Player/includes
 * @author     Jazib Sawar <jazibsawar@gmail.com>
 */
class Bb_Audio_Playlist_Player_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'bb-audio-playlist-player',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
