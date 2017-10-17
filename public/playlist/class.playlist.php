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

class Playlist
{
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

    private $type = 'audio';
    private $instance = 0;
    private $class = 'bb-playlist';
 
    public function __construct($plugin_name,$version)
    {
        $this->plugin_name = $plugin_name;
		$this->version = $version;
        add_shortcode('_bb_playlist', array( $this, 'bb_playlist_shortcode' ));
    }

    public function bb_playlist_shortcode( $atts = array()) {
        $atts = shortcode_atts(
            array(
                'style'          => 'light',
                'autoplay'       => 'false',
                'id'             => -1
            ), 
            $atts, 
            '_bb_playlist' 
        );

        if ( $atts['id'] != -1 
            && 'bb_playlist_player' === get_post_type( $atts['id'] ) 
            && 'publish' === get_post_status( $atts['id'] ) ) {

            global $content_width;
            $this->instance++;

            // Autoplay:
            $autoplay = wp_validate_boolean( $atts['autoplay'] ) ? 'autoplay="yes"' : '';
    
            // Enqueue default scripts and styles for the playlist.
            if( 1 === $this->instance ){
                do_action( 'wp_playlist_scripts', esc_attr( $this->type ), esc_attr( $atts['style'] ) );
                wp_enqueue_style( $this->plugin_name, plugin_dir_url(__DIR__ . '..') . 'css/bb-playlist.css', array(), $this->version, 'all' );
            }   

            /* HTML output for playlist*/
            $html = '';

            $html .= sprintf( '<div class="wp-playlist wp-%s-playlist wp-playlist-%s ' .  esc_attr( $this->class ) . '">', 
                $this->type, esc_attr( $atts['style'] )
            );

            /* Audio player current song info */
            $html .= '<div class="wp-playlist-current-item"></div>';   

            $html .= '<audio controls="controls" ' . $autoplay . ' preload="none" width="100%" style="visibility: hidden"></audio>';

            // Next/Previous:
            $html .= '<div class="wp-playlist-next"></div><div class="wp-playlist-prev"></div>';

            $html .= sprintf( '
                <script class="wp-playlist-script" type="application/json">{
                    "type":"%s",
                    "tracklist":true,
                    "tracknumbers":true,
                    "images":true,
                    "artists":true,
                    "tracks":[%s]
                }</script>', 
                esc_attr( $this->type ), 
                $this->get_tracks_from_playlist( $atts['id'] )
            );
            // Close div container:
            $html .= '</div>';
            return $html;
        }
        else {
            return '<p>Invalid Playlist ID.</p>';
        }
    }

    public function get_tracks_from_playlist($playlist_id) {
        $playlists = get_post_meta( $playlist_id, 'bb_playlist',true);
        $image_url = sprintf( '%s/wp-includes/images/media/%s.png', get_site_url(), $this->type );
        if(has_post_thumbnail($playlist_id)){
            $image_url = get_the_post_thumbnail_url($playlist_id);
        }
        $width = '48';
        $height = '64';
        $data = array();
        if(is_array($playlists) && count($playlists) > 0) {
            foreach($playlists as $key => $playlist) {
                $data[$key]['src']                      = esc_url( $playlist['url'] );
                $data[$key]['title']                    = sanitize_text_field( $playlist['name'] );
                $data[$key]['type']                     = sanitize_text_field( $this->type );
                $data[$key]['caption']                  = '';
                $data[$key]['description']              = '';
                $data[$key]['image']['src']             = esc_url( $image_url );
                $data[$key]['image']['width']           = intval( $width );
                $data[$key]['image']['height']          = intval( $height );
                $data[$key]['thumb']['src']             = esc_url( $image_url );
                $data[$key]['thumb']['width']           = intval( $width );
                $data[$key]['thumb']['height']          = intval( $height );
                $data[$key]['meta']['length_formatted'] = sanitize_text_field( '' );

                $data[$key]['meta']['artist'] = sanitize_text_field( '' );
                $data[$key]['meta']['album']  = sanitize_text_field( '' );
                $data[$key]['meta']['genre']  = sanitize_text_field( '' );
            }
        }

        return substr(strip_tags( nl2br( json_encode( $data ))),1,-1);
    }
}