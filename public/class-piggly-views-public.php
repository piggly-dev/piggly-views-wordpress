<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @since      1.0.0
 * @since      1.0.1                            Upgraded to get different types of posts.
  * @since     1.1.0                            Added [piggly_views_collection] and enabled counter without AJAX.
 * @package    PigglyViews
 * @subpackage PigglyViews/public
 * @author     Piggly DEV <dev@piggly.com.br>
 */
class PigglyViews_Public 
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      PigglyViews_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;
    
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
     * @return  void
     * @access  public
     * @since   1.0.0
     * @param   string                              $plugin_name      The name of this plugin.
     * @param   string                              $version          The version of this plugin.
     * @param   \PigglyViews\PigglyViews_Loader     $loader           Handle all actions hooks and filters.
     */
    public function __construct( $plugin_name, $version, $loader ) 
    {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;
        $this->loader      = $loader;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function enqueue_styles() 
    {
        //wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/piggly-views-public.css', array(), $this->version, 'all' );
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     * @since   1.1.0 Only loads AJAX script when a cache server is enabled.
     */
    public function enqueue_scripts() 
    {
        global $post;
        
        $ignore_logged = PigglyViews_Settings::current_settings()['disable_admin'];
        
        if ( ( ( defined( 'WP_CACHE' ) && WP_CACHE ) || defined('WPFC_MAIN_PATH') ) ) :
            if ( $ignore_logged && is_user_logged_in() ) :
                return;
            endif;
            
            if ( !wp_is_post_revision( $post ) && ( is_single() || is_page() ) ) :
                wp_enqueue_script( $this->plugin_name.'_counter', plugin_dir_url( __FILE__ ) . 'js/piggly-views-cache.js', array( 'jquery' ), $this->version, true );
                wp_localize_script( $this->plugin_name.'_counter', 'pigglyCore', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'post_id' => intval( $post->ID ), 'enabled' => ( is_singular() || is_page() ) ? '1' : '0'  ) );
            endif;
        endif;
    }
    
    /**
     * Run all Public core.
     * @return  void
     * @access  public
     * @since   1.0.0
     * @since   1.1.0 Added [piggly_views_collection] and enabled counter without AJAX.
     */
    public function init ()
    {
        add_shortcode ( 'piggly_views', array( &$this, 'shortcode') );
        add_shortcode ( 'piggly_views_collection', array( &$this, 'shortcode_collection') );
        
        $ignore_logged = PigglyViews_Settings::current_settings()['disable_admin'];
        
        if ( ( ( defined( 'WP_CACHE' ) && WP_CACHE ) || defined('WPFC_MAIN_PATH') ) ) :
            if ( $ignore_logged && is_user_logged_in() ) :
                return;
            endif;
            
            add_action ( 'wp_ajax_piggly_views_counter', array( &$this, 'hits_ajax' ) );
            add_action ( 'wp_ajax_nopriv_piggly_views_counter', array( &$this, 'hits_ajax' ) );
        else :
            if ( $ignore_logged && is_user_logged_in() ) :
                return;
            endif;
            
            add_action ( 'wp_head', array( &$this, 'hits' ), 12 );
        endif;
    }
    
    /**
     * Create the SHORTCODE [piggly_views id="post_id"].
     * 
     * @global  object      $post       Post object.
     * @param   arrray      $atts       Shortcode attributes.
     * @param   string      $content    Shortcode content.
     * @return  int
     * @access  public
     * @since   1.0.0
     */
    public function shortcode ( $atts, $content = null )
    {
        global $post;
        
        $atts = 
            shortcode_atts 
            (
                array ( 'id' => get_the_ID() ),
                $atts
            );
        
        if ( isset ( $atts['id'] ) && is_numeric($atts['id']) ) :
            return PigglyViews_Admin::get_views( $atts['id'] );
        endif;
        
        return 0;
    }
    
    
    /**
     * Create the SHORTCODE [piggly_views_collection limit="X" days="X" types="post, page, attachment"].
     * 
     * @global  object      $post       Post object.
     * @param   arrray      $atts       Shortcode attributes.
     * @param   string      $content    Shortcode content.
     * @return  int
     * @access  public
     * @since   1.1.0
     */
    public function shortcode_collection ( $atts, $content = null )
    {        
        $atts = 
            shortcode_atts 
            (
                array 
                ( 
                    'limit' => 5,
                    'days' => 30,
                    'types' => ''
                ),
                $atts
            );
        
        $output         = '';
        $no_whitespaces = preg_replace( '/\s*,\s*/', ',', filter_var( $atts['types'], FILTER_SANITIZE_STRING ) );
        $types          = explode( ',', $no_whitespaces );
        $types          = isset ( $types[0] ) && $types[0] === '' ? array() : $types;
        
        $most_viewed = $this->collection( $atts['limit'], $atts['days'], $types );
        
        if ( !empty( $most_viewed ) ) :
            $template = '<a href="{{link}}" title="{{title}}" rel="bookmark" class="post"><div class="left"><div class="cover" style="background-image: url({{thumbnail}});" data-item="{{index}}"></div></div><div class="right"><h3 class="title">{{title}}</h3><div class="prop"><span class="category">{{category}}</span><span class="author">{{author}}</span><span class="date">{{date}}</span></div></div></a>';
            $index    = 1;
            
            foreach( $most_viewed as $post ) :
                $postID   = $post->post_id;
                $url      = wp_get_attachment_image_src(wp_get_attachment_url( get_post_thumbnail_id($postID) ))[0];
                $link     = get_permalink($postID);
                $title    = get_the_title($postID);
                $category = get_the_category($postID);
                $date     = get_the_date( 'd M y', $postID);
                $author   = get_the_author_meta('display_name', $post->post_author);
                
                $template = str_replace( '{{thumbnail}}', $url, $template );
                $template = str_replace( '{{link}}', $link, $template );
                $template = str_replace( '{{title}}', $title, $template );
                $template = str_replace( '{{category}}', $category[0]->name, $template );
                $template = str_replace( '{{date}}', $date, $template );
                $template = str_replace( '{{author}}', $author, $template );
                $template = str_replace( '{{index}}', $index, $template );
                
                $output .= ' '.$template.' ';    

                $index++;            
            endforeach;
        endif;
        
        return $output;
    }

    /**
     * Stores a new hit in POST_ID.
     * 
     * @global  object      $wpdb       Database object.
     * @param   string      $content    Content.
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function hits ( $content = '' )
    {
        global $wpdb;
        
        if ( !( is_single() || is_page() ) ):
            return;
        endif;
        
        $post_id    = get_the_ID();
        $table_name = $wpdb->prefix . 'pigglyviews';
            
        if ( get_post_meta( $post_id, PIGGLY_VIEWS_NAME.'_disable', true ) ):
            return $content;
        endif;
        
        if ( !$wpdb->query ( "UPDATE $table_name SET views = views+1 WHERE post_id = $post_id") )
        { @$wpdb->query("INSERT INTO $table_name ( post_id, views ) VALUES ( $post_id, 1 )"); }
        
        return $content;
    }
    
    /**
     * Stores a new hit in POST_ID using AJAX.
     * 
     * @global  object      $wpdb       Database object.
     * @param   string      $content    Content.
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function hits_ajax ( $content = '' )
    {
        global $wpdb;
        
        $enable     = filter_input ( INPUT_POST, 'enabled', FILTER_SANITIZE_NUMBER_INT, array( 'options' => array ( 'default' => 0 ) ) );
        $post_id    = filter_input ( INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT );
        $table_name = $wpdb->prefix . 'pigglyviews';
        
        if ( !isset($enable) || $enable === 0 ):
            return;
        endif;
        
        if ( is_numeric( $post_id ) ) :
            
            if ( get_post_meta( $post_id, PIGGLY_VIEWS_NAME.'_disable', true ) ):
                return $content;
            endif;
            
            if ( !$wpdb->query ( "UPDATE $table_name SET views = views+1 WHERE post_id = $post_id") )
            { @$wpdb->query("INSERT INTO $table_name ( post_id, views ) VALUES ( $post_id, 1 )"); }
        endif;
        
        return $content;
	wp_die();
    }
    
    /**
     * Get a collection of most views posts. Where $days is the range between NOW and X($days) days.
     * 
     * @global  object  $wpdb       Database object.
     * @param   int     $limit      Number of posts to return.
     * @param   int     $days       Range of days to filter.
     * @param   array   $types      Post types to get, default is 'POST'.
     * @return  array               Array with Posts ID and Author ID.
     * @access  public
     * @since   1.0.0
     * @since   1.0.1   Upgraded to get different types of posts.
     */
    public function collection ( $limit = 5, $days = 30, $types = array() )
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'pigglyviews';
       
        if ( !empty($types) ) :
            $types_ = [];
        
            foreach ( $types as $type ):
                $types_[] = 'post_type LIKE \'' . $type . '\'';
            endforeach;
            
            $types_ = implode( ' OR ', $types_ );
            $types_ = '(' . $types_ . ')';
        else:
            $types_ = '(post_type LIKE \'post\')';
        endif;
        
        return $wpdb->get_results
               ( "SELECT v.post_id, p.post_author FROM $table_name v INNER JOIN wp_posts p ON ( v.post_id = p.ID ) WHERE (p.post_date BETWEEN DATE_SUB(NOW(), INTERVAL $days DAY) AND NOW()) AND $types_ ORDER BY v.views DESC LIMIT $limit;" );
    }

}
