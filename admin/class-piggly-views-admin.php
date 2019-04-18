<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      1.0.0
 * @since      1.0.1 Fixed Welcome & Upgraded page loading
 * @since      1.0.2 Fixed Keep Database settings
 * @package    PigglyViews
 * @subpackage PigglyViews/admin
 * @author     Piggly DEV <dev@piggly.com.br>
 */
class PigglyViews_Admin 
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      PigglyViews_Loader    $this->loader    Maintains and registers all hooks for the plugin.
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
     * Nounce verification.
     *
     * @since    1.0.0
     * @access   private
     * @var      mixed    $nounce       Nounce is valid or not.
     */
    private $nounce;
    
    /**
     * Initialize the class and set its properties.
     * 
     * @param   string                              $plugin_name      The name of this plugin.
     * @param   string                              $version          The version of this plugin.
     * @param   \PigglyViews\PigglyViews_Loader     $loader           Handle all actions hooks and filters.
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function __construct( $plugin_name, $version, $loader ) 
    {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;
        $this->loader      = $loader;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function enqueue_styles() 
    {
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/piggly-views-admin.css', array(), $this->version, 'all' );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function enqueue_scripts() 
    { 
        //wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . '/js/piggly-views-admin.js', array(), $this->version, false ); 
    }
    
    /**
     * Register all actions.
     * 
     * @return  void
     * @access  public
     * @since   1.0.0
     * @since   1.0.1 Fixed Welcome & Upgraded page loading
     */
    public function register_actions ()
    {
        $this->loader->add_action ( 'admin_menu', $this, 'setup_menus' );
        $this->loader->add_action ( 'add_meta_boxes', $this, 'metabox' );
        $this->loader->add_action ( 'save_post', $this, 'save_metabox' );
        
        $current_settings = PigglyViews_Settings::current_settings();
        
        if ( PigglyViews_Settings::check_setting ( $current_settings, 'show_column', true ) ) :
            $this->loader->add_filter ( 'manage_posts_columns', $this, 'add_column' );
            $this->loader->add_filter ( 'manage_posts_custom_column', $this, 'populate_column', 10, 2 );
        endif;
    }
    
    /**
     * Run all Admin core.
     * 
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function init ()
    {        
        $this->nounce = false;
        
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) :
            $nounce = filter_input ( INPUT_POST, PIGGLY_VIEWS_NAME.'_option_page', FILTER_SANITIZE_STRING );
        
            if ( isset ( $nounce ) ) :
                $this->nounce = wp_verify_nonce ( $nounce, PIGGLY_VIEWS_NAME.'_saving' ) ? true : false;
            endif;
        endif;
    }
    
    /**
     * Remove the Welcome and Upgraded menu.
     * 
     * @return  void
     * @access  public
     * @since   1.0.0
     * @since   1.0.1 Fixed Welcome & Upgraded page loading
     */
    public function destroy_menus ()
    {
        global $submenu;
        
        unset ( $submenu['piggly-views'] );
    }
    
    /**
     * Create the Welcome menu for Plugin.
     * 
     * @return  void
     * @access  public
     * @since   1.0.0
     * @since   1.0.1 Fixed Welcome & Upgraded page loading
     */
    public function create_menu_welcome ()
    {
        add_submenu_page
            (
                'piggly-views',
                __( 'Welcome to Piggly Views', PIGGLY_VIEWS_NAME ),
                __( 'Welcome to Piggly Views', PIGGLY_VIEWS_NAME ),
                'activate_plugins',
                'piggly-views-welcome',
                array ( &$this, 'include_welcome_page' )
            );
    }
    
    /**
     * Create the Upgraded menu for Plugin.
     * 
     * @return  void
     * @access  public
     * @since   1.0.0
     * @since   1.0.1 Fixed Welcome & Upgraded page loading
     */
    public function create_upgraded_menu ()
    {
        add_submenu_page
            (
                'piggly-views',
                __( 'What is new in Piggly Views', PIGGLY_VIEWS_NAME ),
                __( 'What is new in Piggly Views', PIGGLY_VIEWS_NAME ),
                'activate_plugins',
                'piggly-views-upgraded',
                array ( &$this, 'include_upgraded_page' )
            );
    }
    
    /**
     * Include the Welcome page.
     * 
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function include_welcome_page ()
    { require_once ( plugin_dir_path( __FILE__ ) . 'partials/piggly-views-admin-welcome.php' ); }
    
    /**
     * Include the Upgraded page.
     * 
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function include_upgraded_page ()
    { require_once ( plugin_dir_path( __FILE__ ) . 'partials/piggly-views-admin-upgraded.php' ); }
    
    /**
     * Create the plugin menu.
     * 
     * @return  void
     * @access  public
     * @since   1.0.0
     * @since   1.0.1 Fixed Welcome & Upgraded page loading
     */
    public function setup_menus ()
    {
        add_menu_page
            (
                'Piggly Views',
                'Piggly Views',
                'manage_options',
                'piggly-views',
                array ( &$this, 'display_options' ),
                'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDgwIDEwODAiPjx0aXRsZT5QaWdnbHkgVmlldzwvdGl0bGU+PHBhdGggZD0iTTU0MCw4MDcuMTRjLTY2LjUxLDAtMTI5LjgxLTEzLjY1LTE4OC4xNC00MC41OC00Ni41OC0yMS41MS05MC01MS40NS0xMjkuMTUtODktNjYuNDctNjMuODEtOTcuMjMtMTI3LjExLTk4LjUxLTEyOS43OEwxMjAuNDcsNTQwbDMuNzMtNy43OGMxLjI4LTIuNjcsMzItNjYsOTguNTEtMTI5Ljc4LDM5LjEyLTM3LjU1LDgyLjU3LTY3LjQ5LDEyOS4xNS04OSw1OC4zMy0yNi45MywxMjEuNjMtNDAuNTgsMTg4LjE0LTQwLjU4czEyOS44MywxMy42NSwxODguMjEsNDAuNThjNDYuNjEsMjEuNSw5MC4xLDUxLjQ1LDEyOS4yOCw4OSw2Ni41OCw2My44MSw5Ny40NSwxMjcuMSw5OC43MywxMjkuNzZMOTYwLDU0MGwtMy43Niw3LjgxYy0xLjI4LDIuNjYtMzIuMTUsNjYtOTguNzMsMTI5Ljc2LTM5LjE4LDM3LjU0LTgyLjY3LDY3LjQ5LTEyOS4yOCw4OUM2NjkuODMsNzkzLjQ5LDYwNi41MSw4MDcuMTQsNTQwLDgwNy4xNFpNMTYwLjc2LDU0MGMxMC4wNywxOC4yMiwzOC43OCw2NS42OCw4Ny44MywxMTIuNTFDMzMxLjA1LDczMS4yMiw0MjkuMDksNzcxLjE0LDU0MCw3NzEuMTRjMTExLjM2LDAsMjA5LjgtNDAuMjIsMjkyLjU4LTExOS41Niw0OC43My00Ni43MSw3Ny4wNy05My40Nyw4Ny4wNy0xMTEuNTYtMTAuMTItMTguMjUtMzguOTEtNjUuNjktODgtMTEyLjVDNzQ5LDM0OC43OCw2NTAuOTEsMzA4Ljg2LDU0MCwzMDguODZjLTExMS4zNiwwLTIwOS43Miw0MC4yMi0yOTIuMzYsMTE5LjU1QzE5OSw0NzUuMTMsMTcwLjcyLDUyMS45MSwxNjAuNzYsNTQwWiIgc3R5bGU9ImZpbGw6IzMyNDQ1NCIvPjxwYXRoIGQ9Ik01NDAsNzM2LjkzYy0xMDguNTksMC0xOTYuOTMtODguMzQtMTk2LjkzLTE5Ni45M1M0MzEuNDEsMzQzLjA3LDU0MCwzNDMuMDdBMTk3LDE5NywwLDAsMSw3MjIuNTgsNDY2LjEyYTE4LDE4LDAsMSwxLTMzLjM3LDEzLjUxQTE2MC4zMiwxNjAuMzIsMCwwLDAsNTQwLDM3OS4wN2MtODguNzQsMC0xNjAuOTMsNzIuMTktMTYwLjkzLDE2MC45M1M0NTEuMjYsNzAwLjkzLDU0MCw3MDAuOTNBMTYwLjMyLDE2MC4zMiwwLDAsMCw2ODkuMjEsNjAwLjM3YTE4LDE4LDAsMSwxLDMzLjM3LDEzLjUxQTE5NywxOTcsMCwwLDEsNTQwLDczNi45M1oiIHN0eWxlPSJmaWxsOiMzMjQ0NTQiLz48cGF0aCBkPSJNNjEwLjIxLDU0MGwzNS42Mi0yNWExMDguNzEsMTA4LjcxLDAsMSwwLDAsNTBaIiBzdHlsZT0iZmlsbDojMzI0NDU0Ii8+PC9zdmc+',
                66
            );
        
        if ( ! get_transient( PIGGLY_VIEWS_NAME . '_upgraded_screen' ) ) :
            $this->create_menu_welcome();
        else :
            $this->create_upgraded_menu();
        endif;    
    }
    
    /**
     * Display plugin options
     * 
     * @return  void
     * @access  public
     * @since   1.0.0
     * @since   1.0.2 Fixed Keep Database settings
     */
    public function display_options ()
    {         
        if ( !current_user_can('manage_options') ) :
            require_once ( plugin_dir_path( __FILE__ ) . 'partials/piggly-views-admin-unauthorized.php' );
            return;
        endif;
        
        $current_settings = PigglyViews_Settings::current_settings();
        $updated          = false;
        
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) :
            if ( $this->nounce === false ) :
                require_once ( plugin_dir_path( __FILE__ ) . 'partials/piggly-views-admin-unauthorized.php' );
                return;
            endif;
        
            $show_column    = filter_input ( INPUT_POST, PIGGLY_VIEWS_NAME.'_show_column', FILTER_VALIDATE_INT, array("options" => array( "default" => 0 ) ) );
            $disable_admin  = filter_input ( INPUT_POST, PIGGLY_VIEWS_NAME.'_disable_admin', FILTER_VALIDATE_INT, array("options" => array( "default" => 0 ) )  );
            $format         = filter_input ( INPUT_POST, PIGGLY_VIEWS_NAME.'_format', FILTER_VALIDATE_INT, array("options" => array( "default" => 0 ) )  );
            $cache          = filter_input ( INPUT_POST, PIGGLY_VIEWS_NAME.'_cache', FILTER_VALIDATE_INT, array("options" => array( "default" => 0 ) )  );
            $cache_hour     = filter_input ( INPUT_POST, PIGGLY_VIEWS_NAME.'_cache_hour', FILTER_VALIDATE_INT, array("options" => array( "default" => 24 ) )  );
            $keep_options   = filter_input ( INPUT_POST, PIGGLY_VIEWS_NAME.'_keep_options', FILTER_VALIDATE_INT, array("options" => array( "default" => 0 ) )  );
            $keep_db        = filter_input ( INPUT_POST, PIGGLY_VIEWS_NAME.'_keep_db', FILTER_VALIDATE_INT, array("options" => array( "default" => 0 ) )  );

            if ( isset ( $show_column ) ) :
                $current_settings['show_column'] = $show_column === 0 ? false : true;
            endif;      

            if ( isset ( $disable_admin ) ) :
                $current_settings['disable_admin'] = $disable_admin === 0 ? false : true;
            endif;      

            if ( isset ( $format ) ) :
                $current_settings['format'] = $format === 0 ? false : true;
            endif;

            if ( isset ( $cache ) ) :
                $current_settings['cache'] = $cache === 0 ? false : true;
            endif;

            if ( isset ( $cache_hour ) ) :
                $current_settings['cache_hour'] = $cache_hour === 0 ? 24 : $cache_hour;
            endif;

            if ( isset ( $keep_options ) ) :
                $current_settings['keep_options'] = $keep_options === 0 ? false : true;
            endif;

            if ( isset ( $keep_db ) ) :
                $current_settings['keep_db'] = $keep_db === 0 ? false : true;
            endif;

            PigglyViews_Settings::save_settings( $current_settings );
            $updated = true;        
        endif;
        
        require_once ( plugin_dir_path( __FILE__ ) . 'partials/piggly-views-admin-display.php' );
    }
    
    /**
     * Create the meta box.
     * 
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function metabox ()
    {
        add_meta_box
        (
            'piggly-views',
            'Piggly Views',
            array ( &$this, 'display_metabox' ),
            'post',
            'side'
        );
    }
    
    
    /**
     * Display meta box form.
     * 
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function display_metabox ()
    { require_once ( plugin_dir_path( __FILE__ ) . 'partials/piggly-views-admin-metabox.php' ); }
    
    /**
     * Save meta box.
     * 
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function save_metabox ( $post_id )
    {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) :
            return;
        endif;
        
        if ( $parent_id = wp_is_post_revision( $post_id ) ) :
            $post_id = $parent_id;
        endif;
        
        $disable = filter_input( INPUT_POST, PIGGLY_VIEWS_NAME.'_disable', FILTER_SANITIZE_NUMBER_INT, array( 'options' => array ( 'default' => 0 ) ) );
        $disable = $disable === 0 ? false : true;
        
        update_post_meta ( $post_id, PIGGLY_VIEWS_NAME.'_disable', $disable );
    }
    
    /**
     * Add Views column to Post Table
     * 
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function add_column ( $columns )
    {
        $columns[PIGGLY_VIEWS_NAME] = '<svg xmlns="http://www.w3.org/2000/svg" style="display: inline-block; width: 16px; margin-right: 7px;" viewBox="0 0 16.78 10.52"><defs><style>.a{fill:#22adc2;}.b{fill:#324454;}</style></defs><title>Sem título-2</title><path class="a" d="M6.69,15.83l11.5-7.18a9.38,9.38,0,0,0-5.74-1.91c-6.19,0-8.84,5.5-8.84,5.5A11,11,0,0,0,6.69,15.83Z" transform="translate(-3.61 -6.74)"/><path class="b" d="M12.5,17.26A8.49,8.49,0,0,1,9,16.5a9.11,9.11,0,0,1-2.42-1.67,9.22,9.22,0,0,1-1.85-2.44l-.07-.15.07-.15A9.09,9.09,0,0,1,6.54,9.66,8.93,8.93,0,0,1,9,8,8.62,8.62,0,0,1,16,8a8.93,8.93,0,0,1,2.42,1.68,9,9,0,0,1,1.86,2.43l.07.15-.07.15a9.13,9.13,0,0,1-1.86,2.44A9.11,9.11,0,0,1,16,16.5,8.49,8.49,0,0,1,12.5,17.26Zm-7.13-5A9.32,9.32,0,0,0,7,14.35a7.86,7.86,0,0,0,11,0,9.37,9.37,0,0,0,1.63-2.1A9.32,9.32,0,0,0,18,10.13a7.86,7.86,0,0,0-11,0A9.37,9.37,0,0,0,5.37,12.24Z" transform="translate(-3.61 -6.74)"/><path class="b" d="M12.5,15.94a3.7,3.7,0,0,1,0-7.4,3.64,3.64,0,0,1,2.09.65,3.69,3.69,0,0,1,1.34,1.66.33.33,0,0,1-.19.44.34.34,0,0,1-.44-.18,3,3,0,1,0,0,2.26.34.34,0,0,1,.44-.18.33.33,0,0,1,.19.44,3.69,3.69,0,0,1-1.34,1.66A3.64,3.64,0,0,1,12.5,15.94Z" transform="translate(-3.61 -6.74)"/><path class="b" d="M13.82,12.24l.67-.47a2,2,0,1,0,0,.94Z" transform="translate(-3.61 -6.74)"/></svg> ' . __( 'Views', PIGGLY_VIEWS_NAME );
        return $columns;
    }
    
    /**
     * Populate Views column in Post Table
     * 
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function populate_column ( $column, $post_id )
    {
        switch ( $column ) 
        {
            case PIGGLY_VIEWS_NAME:
                echo self::get_views( $post_id, PigglyViews_Settings::current_settings()['format'] );
                break;
        }
    }
    
    /**
     * Get views for a post ID.
     * 
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public static function get_views ( $post_id, $format = true )
    {
	global $wpdb;
        $current_settings = PigglyViews_Settings::current_settings();
        
        $cache          = $current_settings['cache']; 
        $cache_time     = $current_settings['cache_hour']; 
        $flush          = false;
        $transient_name = PIGGLY_VIEWS_NAME . "_cache_$post_id";
        $count          = 0;
        $table_name     = $wpdb->prefix . 'pigglyviews';
        
        if ( get_post_meta( $post_id, PIGGLY_VIEWS_NAME.'_disable', true ) ):
            return __( 'Not tracking', PIGGLY_VIEWS_NAME );
        endif;
        
        if ( $cache )
        {
            include_once(ABSPATH . 'wp-includes/pluggable.php');
            
            $p3 = filter_input ( INPUT_GET, 'P3_NOCACHE', FILTER_SANITIZE_STRING );
            
            if( false === ( $count = get_transient($transient_name) ) || ( current_user_can( 'manage_options' )  && !isset( $p3 ) ) ) :
                $flush = true;
                $count = 0;
            endif;
        }
        
        if ( $flush ) :
            $query  = "SELECT views FROM {$table_name} WHERE post_id = $post_id";
            $result = $wpdb->get_var($query);

            if ( $result ) :
                $count = $result;
            endif;

            if ( $cache )
            { set_transient( $transient_name , $count, 60 * $cache_time ); }
        endif;
        
        if ( $count === 0 )
        { return '—'; }
        
        if ( $format ) :
            return round ( intval($count) / 1000, 2 ).'K';
        else:
            return number_format($count, 0, '.', ',');
        endif;
    }
}
