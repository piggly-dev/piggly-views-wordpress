<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://dev.piggly.com.br/
 * @since      1.0.0
 *
 * @package    PigglyViews
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) :
    exit;
endif;

if ( current_user_can( 'delete_plugins' ) ) :
    
    /** Loads the Settings Manager **/
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-piggly-views-settings.php';
    
    $current_settings = PigglyViews_Settings::current_settings();
    
    if ( !$current_settings['keep_options'] ) :
        PigglyViews_Settings::remove_settings();
    endif;
    
    if ( !$current_settings['keep_db'] ) :
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-piggly-views-deactivator.php';
        PigglyViews_Deactivator::drop_database();
    endif;
    
endif;
