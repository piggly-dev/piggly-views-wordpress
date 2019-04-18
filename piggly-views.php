<?php
/*
 * Piggly Views v1.1.0
 * https://dev.piggly.com.br/opensource/piggly-views
 *
 * Copyright (c) 2019 Piggly DEV
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

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
 * Version:           1.1.0
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
define( 'PIGGLY_VIEWS_VERSION', '1.1.0' );

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

/**
 * Loads the welcome or upgraded page when needed.
 * 
 * @return  void
 * @since   1.0.1 Fixed Welcome & Upgraded page loading
 */
function piggly_views_welcome ()
{
    if ( ! get_transient( PIGGLY_VIEWS_NAME . '_welcome_screen' ) ) :
        return;
    endif;

    // Delete the redirect transient
    delete_transient ( PIGGLY_VIEWS_NAME . '_welcome_screen' );

    // Return if activating from network, or bulk
    if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) :
        return;
    endif;
    
    if ( ! get_transient( PIGGLY_VIEWS_NAME . '_upgraded_screen' ) ) :
        wp_safe_redirect( add_query_arg( array( 'page' => 'piggly-views-welcome' ), admin_url( 'admin.php' ) ) );
    else :
        wp_safe_redirect( add_query_arg( array( 'page' => 'piggly-views-updgraded' ), admin_url( 'admin.php' ) ) );

        // Delete the upgraded transient
        delete_transient ( PIGGLY_VIEWS_NAME . '_upgraded_screen' );
    endif;
}

add_action ( 'admin_init', 'piggly_views_welcome' );
        
// Removes welcome and upgraded menus
$admin = new PigglyViews_Admin( PIGGLY_VIEWS_NAME, PIGGLY_VIEWS_VERSION, null );
add_action ( 'admin_head', array( &$admin, 'destroy_menus' ) );