<?php
/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    PigglyViews
 * @subpackage PigglyViews/includes
 * @author     Piggly DEV <dev@piggly.com.br>
 */
class PigglyViews_Deactivator 
{

    /**
     * None.
     * 
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public static function deactivate() 
    { }

    /**
     * Drop the Piggly Views core table.
     * 
     * @global  global  $wpdb               Wordpress Database Object.
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public static function drop_database ()
    {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'pigglyviews';
        $SQL        = "DROP TABLE IF EXISTS $table_name";
        
        $wpdb->query( $SQL );
    }
}
