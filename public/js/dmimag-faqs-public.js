(function( $ ) {
  'use strict';
  $('.dmi-faq-title').on('click', function(event) {
    event.preventDefault();
    
    $(this).toggleClass('dmi-faq-title-active');
    $(this).siblings( '.dmi-faq-content' ).fadeToggle( 800, 'linear' );
    
    return false;
  });
  
})( jQuery );