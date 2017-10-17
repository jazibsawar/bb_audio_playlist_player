<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://jazibsawar.com
 * @since      1.0.0
 *
 * @package    Bb_Audio_Playlist_Player
 * @subpackage Bb_Audio_Playlist_Player/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bb_Audio_Playlist_Player
 * @subpackage Bb_Audio_Playlist_Player/admin
 * @author     Jazib Sawar <jazibsawar@gmail.com>
 */
class Bb_Audio_Playlist_Player_Admin {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if($this->is_bb_playlist_player()){
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bb-admin-playlist.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if($this->is_bb_playlist_player()){
			wp_enqueue_script( $this->plugin_name . '_sortable', plugin_dir_url( __FILE__ ) . 'js/Sortable.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bb-admin-playlist.js', array( 'jquery' , $this->plugin_name . '_sortable' ), $this->version, false );
		}
	}

	private function is_bb_playlist_player() {
		global $pagenow, $typenow;
		if($pagenow == 'post.php' || ($pagenow == 'post-new.php' && $typenow == 'bb_playlist_player' )) {
			return true;
		}
		return false;
	}

	public function bb_cpt() {
		$singular = 'Audio Playlist';
		$plural = 'Audio Playlists';
	   
		$labels = array(
		 'name'                    => $singular,
		 'singular_name'           => $singular,
		 'add_new'                 => 'Add New',
		 'add_new_item'            => 'Add New ' . $singular,
		 'edit'                    => 'Edit',
		 'edit_item'               => 'Edit ' . $singular,
		 'new_item'                => 'New ' . $singular,
		 'view'                    => 'View ' . $singular,
		 'view_item'               => 'View ' . $singular,
		 'search_item'             => 'Search ' . $singular,
		 'parent'                  => 'Parent ' . $singular,
		 'not_found'               => 'No ' . $plural . ' found',
		 'not_found_in_trash'      => 'No ' . $plural . ' In found',   
		 );
	   
		$args = array(
		 'labels'                  => $labels,
		 'public'                  => false,
		 'publicly_queryable'      => false,
		 'exclude_from_search'     => true,
		 'show_ui'                 => true,
		 'show_in_nav_menus'       => true,
		 'show_in_menu'            => true,
		 'show_in_admin_bar'       => true,
		 'menu_position'           => 9,
		 'menu_icon'               => 'dashicons-format-audio',
		 'capability_type'         => 'post',
		 'map_meta_cap'            =>  true,
		 'register_meta_box_cb'    => array($this,'bb_cpt_playlist_metabox'),
		 'delete_with_user'        => false,
		 'hierarchical'            => false,
		 'query_var'               => true,
		 'can_export'              => true,
		 'has_archive'             => false,
		 'rewrite'          => array(
		  'slug'                => 'bb_playlist_player',
		  'with_front'          => false,
		  'feeds'               => false,
		  'pages'               => false,
		 ), 
		 'supports'         => array(
		  'title',
		  'thumbnail'
		 ) 
		  
		 );
		register_post_type( 'bb_playlist_player', $args );
	}

	public function bb_cpt_playlist_metabox() {
		add_meta_box(
			'bb_playlist_metabox', 
			__( 'Playlists', 'bb_playlist_metabox' ), 
			array( $this, 'bb_playlist_metabox_cb' ), 
			'bb_playlist_player', 
			'normal', 
			'low'
		);
		add_meta_box(
			'bb_shortcode_metabox', 
			__( 'Shortcode', 'bb_shortcode_metabox' ), 
			array( $this, 'bb_shortcode_metabox_cb' ), 
			'bb_playlist_player', 
			'side', 
			'low'
		);
	}

	public function bb_shortcode_metabox_cb($post) {
		$status = get_post_status($post->ID);
		if($status && $status === "publish"){
			echo '<input style="width: 100%;padding: 5px 10px;" type="text" readonly value="[_bb_playlist id='.$post->ID.' ]" >';
		}
	}

	public function bb_playlist_metabox_cb( $post ) {
		$playlists = get_post_meta( $post->ID, 'bb_playlist',true);
		wp_nonce_field( basename( __FILE__ ), 'bb_audio_playlist_player_nonce' );
		?>
		<div class="bb-playlist">
			<div class="bb-add-playlist">
				<input type="button" name="add_song" id="add_song" class="button button-primary button-large" value="Add Song">
			</div>
			<h1>List of Songs:</h1>
			<ul id="playlist-items" class="playlist-items">
				<?php if(is_array($playlists) && count($playlists) > 0) { ?>
				<input type="hidden" value="<?php echo count($playlists); ?>" id="playlist_count" >
				<?php foreach($playlists as $key => $playlist) { ?>
				<li class="playlist-item">
					<table class="accordion-content-header">
						<tr>
							<td class="handle"><span class="dashicons dashicons-move"></span></td>
							<td class="input name">
								<div class="input_label">Name: </div>
								<div class="input_field">
									<input type="text" name="playlist[<?php echo $key; ?>][name]" value="<?php echo esc_attr($playlist['name']); ?>" placeholder="Name:">
								</div>
							</td>
							<td class="icon-right remove">
								<span class="dashicons dashicons-trash" title="Remove"></span>
							</td>
							<td class="icon-right duplicate">
								<span class="dashicons dashicons-admin-page" title="Duplicate"></span>
							</td>
							<td class="icon-right">
								<span class="dashicons dashicons-arrow-down accordion-toggle" title="Toggle"></span>
							</td>
						</tr>
					</table>
					<div class="accordion-content">
						<br>
						<div>
							<div class="input_label">Author: </div>
							<div class="input_field author">
								<input type="text" name="playlist[<?php echo $key; ?>][author]" value="<?php echo esc_attr($playlist['author']); ?>" placeholder="Author:" >
							</div>
						</div>
						<div>
							<div class="input_label">URL: </div>
							<div class="input_field url">
								<input type="text" name="playlist[<?php echo $key; ?>][url]" value="<?php echo esc_attr($playlist['url']); ?>" placeholder="Url:">
							</div>
						</div>
					</div>
				</li>
				<?php } ?>
				<?php } else { ?>
				<input type="hidden" value="0" id="playlist_count" >
				<li class="playlist-item" id="playlist-item-empty">No Song found in playlist. Please add</li>
			<?php } ?>
			</ul>
		</div>
		<?php
	}

	public function bb_cpt_playlist_metabox_save($post_id) {
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_none = (isset($_POST['bb_audio_playlist_player_nonce'])) && wp_verify_nonce( $_POST['bb_audio_playlist_player_nonce'] );
	
		if($is_autosave || $is_revision || $is_valid_none){
			return $post_id;
		}

		/* Get the meta key. */
		$meta_key = 'bb_playlist';

		/* Get the meta key. */
		$post_key = 'playlist';


		/* Get the posted data and sanitize it for use as an HTML class. */
		$playlist = isset( $_POST[$post_key] ) ? (array) $_POST[$post_key] : array();
		
		// Any of the WordPress data sanitization functions can be used here
		$playlist = array_map( 'esc_attr', $playlist );

		if(count($playlist) > 0){
			update_post_meta( $post_id, 'bb_playlist', $_POST['playlist']);
		}
		else {
			delete_post_meta( $post_id, $meta_key);
		}
	}
}
