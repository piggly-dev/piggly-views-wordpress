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
            
        <input type="checkbox" name="<?=PIGGLY_VIEWS_NAME.'_disable'?>" id="<?=PIGGLY_VIEWS_NAME.'_disable'?>" value="1" <?php if ( get_post_meta( get_the_ID(), PIGGLY_VIEWS_NAME.'_disable', true ) ) : echo 'checked'; endif; ?>>
        <label for="<?=PIGGLY_VIEWS_NAME.'_disable'?>"><?php _e( 'Disable views tracking for this post', PIGGLY_VIEWS_NAME ); ?></label>
        <br/><br/>
        
    </div>
</div>