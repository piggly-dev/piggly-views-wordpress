<?php

/**
 * An easy way to track post views and get most reads posts by a range of date.
 *
 * @link              https://dev.piggly.com.br/opensource/wp/piggly-views
 * @since             1.0.0
 * @package           PigglyViews
 *
 * @wordpress-plugin
 * Plugin Name:       Piggly Views
 * Plugin URI:        https://dev.piggly.com.br/opensource/piggly-views
 * Description:       An easy way to track post views and get most reads posts by a range of date.
 * Version:           1.0.0
 * Author:            Piggly DEV
 * Author URI:        https://dev.piggly.com.br/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       piggly-views
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) 
{ die; }

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define( 'PIGGLY_VIEWS_VERSION', '1.0.0' );

/**
 * Currently database version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define( 'PIGGLY_VIEWS_DBVERSION', '1.0.0' );

/**
 * The plugin name
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define( 'PIGGLY_VIEWS_NAME', 'piggly_views' );

/** Loads the Settings Manager **/
require_once plugin_dir_path( __FILE__ ) . 'includes/class-piggly-views-settings.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-piggly-views-activator.php
 */
function activate_piggly_views() 
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-piggly-views-activator.php';
    PigglyViews_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-piggly-views-deactivator.php
 */
function deactivate_piggly_views() 
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-piggly-views-deactivator.php';
    PigglyViews_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_piggly_views' );
register_deactivation_hook( __FILE__, 'deactivate_piggly_views' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-piggly-views.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_piggly_views() 
{
    $plugin = new PigglyViews();
    $plugin->run();
}

// Startup plugin class
run_piggly_views();

// Setup the function to get a post collection
if ( !function_exists ( 'piggly_view_collection' ) ) :
    function piggly_view_collection ( $limit = 5, $days = 30 )
    { 
        $public = new PigglyViews_Public( PIGGLY_VIEWS_NAME, PIGGLY_VIEWS_VERSION, null );
        return $public->collection($limit, $days); 
    }
endif;
