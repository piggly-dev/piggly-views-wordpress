<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://dev.piggly.com.br/
 * @since      1.0.0
 *
 * @package    PigglyViews
 * @subpackage PigglyViews/admin/partials
 */

?>
<div class="piggly-views">
    <div class="wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16.78 10.52">
            <path d="M6.69,15.83l11.5-7.18a9.38,9.38,0,0,0-5.74-1.91c-6.19,0-8.84,5.5-8.84,5.5A11,11,0,0,0,6.69,15.83Z" transform="translate(-3.61 -6.74)" style="fill:#22adc2"/><path d="M12.5,17.26A8.49,8.49,0,0,1,9,16.5a9.11,9.11,0,0,1-2.42-1.67,9.22,9.22,0,0,1-1.85-2.44l-.07-.15.07-.15A9.09,9.09,0,0,1,6.54,9.66,8.93,8.93,0,0,1,9,8,8.62,8.62,0,0,1,16,8a8.93,8.93,0,0,1,2.42,1.68,9,9,0,0,1,1.86,2.43l.07.15-.07.15a9.13,9.13,0,0,1-1.86,2.44A9.11,9.11,0,0,1,16,16.5,8.49,8.49,0,0,1,12.5,17.26Zm-7.13-5A9.32,9.32,0,0,0,7,14.35a7.86,7.86,0,0,0,11,0,9.37,9.37,0,0,0,1.63-2.1A9.32,9.32,0,0,0,18,10.13a7.86,7.86,0,0,0-11,0A9.37,9.37,0,0,0,5.37,12.24Z" transform="translate(-3.61 -6.74)" style="fill:#324454"/><path d="M12.5,15.94a3.7,3.7,0,0,1,0-7.4,3.64,3.64,0,0,1,2.09.65,3.69,3.69,0,0,1,1.34,1.66.33.33,0,0,1-.19.44.34.34,0,0,1-.44-.18,3,3,0,1,0,0,2.26.34.34,0,0,1,.44-.18.33.33,0,0,1,.19.44,3.69,3.69,0,0,1-1.34,1.66A3.64,3.64,0,0,1,12.5,15.94Z" transform="translate(-3.61 -6.74)" style="fill:#324454"/><path d="M13.82,12.24l.67-.47a2,2,0,1,0,0,.94Z" transform="translate(-3.61 -6.74)" style="fill:#324454"/>
        </svg>

        <span class="caption">
            PIGGLY <strong>VIEW</strong>
        </span>

        <h1><?php _e( 'What\'s new?', PIGGLY_VIEWS_NAME ) ?></h1>

        <p><?php _e('Now you can use the shortcode <code>[piggly_views_collection limit="X" days="X" types="post, page, attachment"]</code> to get a collection of most viewed posts returning the default template.', PIGGLY_VIEWS_NAME );?></p>
        
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 58.42 19.27">
            <circle cx="31.4" cy="11.45" r="7.82" style="fill:#cbe00e"/><circle cx="50.6" cy="11.45" r="7.82" style="fill:#22adc2"/><polygon points="8.14 9.59 4.47 3.66 20 3.66 20 19.19 4.47 19.19 9.27 11.52 8.14 9.59" style="fill:#ff4e50"/><path d="M25.37,44.14H6.79l4.68-7.58a1.09,1.09,0,1,1,1.85,1.15L10.7,42H23.19V28.6H10.7l2.64,4.27A1.09,1.09,0,1,1,11.49,34l-4.7-7.6H25.37Z" transform="translate(-6.79 -26.37)" style="fill:#324454"/><path d="M37.51,43.94a1.08,1.08,0,0,1-1.06-.83,1.09,1.09,0,0,1,.8-1.32,6.71,6.71,0,1,0-3.14,0,1.09,1.09,0,1,1-.51,2.12,8.91,8.91,0,1,1,4.16,0Z" transform="translate(-6.79 -26.37)" style="fill:#324454"/><path d="M54.88,44.16A8.9,8.9,0,0,1,52.8,26.61a1.09,1.09,0,0,1,.51,2.12,6.71,6.71,0,1,0,3.14,0A1.09,1.09,0,1,1,57,26.61a8.9,8.9,0,0,1-2.08,17.55Z" transform="translate(-6.79 -26.37)" style="fill:#324454"/>
        </svg>

        <span class="caption">
            PIGGLY <strong>DEV</strong>
        </span>
    </div>
</div>