<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://jazibsawar.com
 * @since      1.0.0
 *
 * @package    Bb_Audio_Playlist_Player
 * @subpackage Bb_Audio_Playlist_Player/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Bb_Audio_Playlist_Player
 * @subpackage Bb_Audio_Playlist_Player/public
 * @author     Jazib Sawar <jazibsawar@gmail.com>
 */
class Bb_Audio_Playlist_Player_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $playlist;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		require_once plugin_dir_path( __FILE__ ) . 'playlist/class.playlist.php';

		$this->playlist = new Playlist($this->plugin_name,$this->version = $version);
	}

}
