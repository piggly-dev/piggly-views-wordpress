<?php
/**
 * Manages all plugin settings in a easy way.
 *
 * @since      1.0.0
 * @package    PigglyViews
 * @subpackage PigglyViews/includes
 * @author     Piggly DEV <dev@piggly.com.br>
 */
class PigglyViews_Settings
{
    /**
     * Plugin default settings.
     * 
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public static function default_settings ()
    {
        return 
            array
            (
                'current_version' => PIGGLY_VIEWS_VERSION,
                'db_version' => PIGGLY_VIEWS_DBVERSION,
                'show_column' => true,
                'disable_admin' => true,
                'format' => false,
                'cache' => true,
                'cache_hour' => 24,
                'keep_options' => true,
                'keep_db' => true
            );
    }
    
    /**
     * Get the current plugin settings merging the default settings with the
     * settings already stored in database.
     * 
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public static function current_settings ()
    {
        $current_settings = get_option ( PIGGLY_VIEWS_NAME .'_settings', array() );
        return array_merge( self::default_settings(), $current_settings );
    }
    
    /**
     * Save the plugin settings. The $settings variable will be merged with
     * $default_settings.
     * 
     * @param   array   $settings   Array with all Plugin Settings.
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public static function save_settings ( array $settings = array() )
    { 
        $settings = array_merge( self::default_settings(), $settings );
        update_option( PIGGLY_VIEWS_NAME .'_settings', $settings ); 
    }
    
    /**
     * Remove all settings.
     * 
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public static function remove_settings ()
    { 
        delete_option ( PIGGLY_VIEWS_NAME .'_settings' ); 
    }
        
    /**
     * Check if a setting key is equal to value.
     * 
     * @param   array    $settings      Array with settings.
     * @param   string   $key           Key to compare in settings.
     * @param   string   $value         Value expected.
     * @return  boolean
     * @access  public
     * @since   1.0.0
     */
    public static function check_setting ( $settings, $key, $value )
    {
        if ( isset( $settings[$key] ) ) :
            if ( $settings[$key] === $value ) :
                return true;
            endif;
        endif;
        
        return false;
    }
    
    /**
     * Configure and save all Startup Settings.
     * 
     * @return  boolean
     * @access  public
     * @since   1.0.0
     */
    public static function statup_settings ()
    {
        $current = self::current_settings();
        
        if ( !self::check_setting ( $current, 'current_version', PIGGLY_VIEWS_VERSION ) ) :
            set_transient( PIGGLY_VIEWS_NAME . '_upgrade_screen', true, 5 );
            $current['current_version'] = PIGGLY_VIEWS_VERSION;
        endif;
        
        self::save_settings( $current );
    }
}

