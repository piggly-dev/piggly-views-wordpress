(function( $ ) {
    'use strict';
    
    $(document).ready( function ()
    {
        $(document).on( 'click' , '[data-slug="piggly-view"] a.delete', function ()
        {
            event.preventDefault();
            var deleteURL = $(this).attr('href');
            
            tb_show( 'Piggly View', pigglyCore.confirmationUrl + '&url_redirect=' + deleteURL );
        });
    });

})( jQuery );
