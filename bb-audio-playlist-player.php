<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://jazibsawar.com
 * @since             1.0.0
 * @package           Bb_Audio_Playlist_Player
 *
 * @wordpress-plugin
 * Plugin Name:       Audio Playlist Player
 * Plugin URI:        https://github.com/jazibsawar/bb_audio_playlist_player/
 * Description:       Fastest WordPress Audio Playlist plugin that is utilizing WP playlist and Custom Post Type to handle external sources.
 * Version:           1.0.0
 * Author:            Jazib Sawar
 * Author URI:        https://github.com/jazibsawar/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bb-audio-playlist-player
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_NAME_VERSION', '1.0.0' );
define( 'PLUGIN_BASE_NAME' , plugin_basename(__FILE__));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bb-audio-playlist-player-activator.php
 */
function activate_bb_audio_playlist_player() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bb-audio-playlist-player-activator.php';
	Bb_Audio_Playlist_Player_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bb-audio-playlist-player-deactivator.php
 */
function deactivate_bb_audio_playlist_player() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bb-audio-playlist-player-deactivator.php';
	Bb_Audio_Playlist_Player_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bb_audio_playlist_player' );
register_deactivation_hook( __FILE__, 'deactivate_bb_audio_playlist_player' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bb-audio-playlist-player.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bb_audio_playlist_player() {

	$plugin = new Bb_Audio_Playlist_Player();
	$plugin->run();

}
run_bb_audio_playlist_player();
