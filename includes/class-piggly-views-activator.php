<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    PigglyViews
 * @subpackage PigglyViews/includes
 * @author     Piggly DEV <dev@piggly.com.br>
 */
class PigglyViews_Activator 
{
    /**
     * Run all tasks when activating the plugin.
     * 
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public static function activate () 
    {
        if ( !is_admin() ) :
            return;
        endif;
        
        PigglyViews_Settings::statup_settings();
        PigglyViews_Activator::create_database();
        PigglyViews_Activator::setup_welcome();
    }

    /**
     * Create the Piggly Views core table.
     * 
     * @global  global  $wpdb               Wordpress Database Object.
     * @global  global  $ivl_db_version     Plugin Database Version.
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public static function create_database ()
    {
        global $wpdb;
        global $ivl_db_version;
        
        $current_settings     = PigglyViews_Settings::current_settings();

        $ivl_db_version       = PIGGLY_VIEWS_DBVERSION;
        $installed_db_version = $current_settings['db_version'];
        $prefix               = $wpdb->prefix;
        $table_name           = $prefix . 'pigglyviews';
        
        /** Setting the default charset collation **/
        $charset_collate = '';

        if ( !empty ( $wpdb->charset ) ) :
            $charset_collate = 'DEFAULT CHARACTER SET '.$wpdb->charset;
        endif;

        if ( !empty ( $wpdb->collate ) ) :
            $charset_collate .= ' COLLATE '.$wpdb->collate;
        endif;
        
        // Table no exist
        if( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) :
            // Create table
            
            $SQL = 
                "CREATE TABLE $table_name 
                (
                    id int(11) NOT NULL AUTO_INCREMENT,
                    post_id int(15) NOT NULL,
                    views int(14) NOT NULL,
                    last_viewed timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
                    UNIQUE KEY id (id)
                ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            @dbDelta( $SQL );
            
            $current_settings['db_version'] = $ivl_db_version;
            
        elseif ( $installed_db_version !== $ivl_db_version ) :
            
            // Upgrade table
            $current_settings['db_version'] = $ivl_db_version;
        
        endif;
        
        PigglyViews_Settings::save_settings( $current_settings );
    }
    
    /**
     * Create a transient to show the Welcome (or Upgraded) Screen.
     * 
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public static function setup_welcome ()
    { set_transient( PIGGLY_VIEWS_NAME . '_welcome_screen', true, 5 ); }
}
