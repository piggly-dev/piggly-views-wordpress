(function( $ ) {
    'use strict';

    jQuery.ajax
    ({
        url: pigglyCore.ajaxurl,
        type: "POST",
        data: { action: 'piggly_views_counter', post_id: pigglyCore.post_id, enabled: pigglyCore.enabled  },
        cache:!1
    });

})( jQuery );
