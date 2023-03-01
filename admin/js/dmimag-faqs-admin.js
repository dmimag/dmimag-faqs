(function( $ ) {
	'use strict';
  
  $(document).ready( function() {
    $('#dmimag-faqs .inside').sortable();
  });
  
  $('.dmimag-faqs-copy-to-clipboard').on('click', function(event) { // touchstart
    event.preventDefault();
    $(this).prev( '.dmimag-faqs-shortcode' ).select();
    document.execCommand( 'copy' );
    return false;
	});

  
  $(document).on( 'click touchstart', '.dmimag-faqs-button-remove', function( event ) {
    event.preventDefault();
    
    var dmimag_faqs = $( this ).parents( '.dmimag-faqs' );

    if ( dmimag_faqs.siblings( '.dmimag-faqs' ).length != '0' ) {
      
      dmimag_faqs.remove();
      //wp.editor.remove( id_content );  
      
    } else {
      dmimag_faqs.find( '.dmi-field input' ).val('');
      dmimag_faqs.find( '.dmi-field textarea' ).val('');
    }
    
    return false; 
    
  });
  
  $(document).on( 'click touchstart', '.dmimag-faqs-button-add', function() {

    var dmimag_faqs = $(this).parent( '.postbox-container' ).find( '#dmimag-faqs .inside .dmimag-faqs:last-child()' );
    
    var dmimag_faqs_count = $('#dmimag-faqs .inside').find( '.dmimag-faqs' ).length;
    
    var dmimag_faqs_data_count = dmimag_faqs.data('faqs');
    
    if( dmimag_faqs_count < dmimag_faqs_data_count ) {
      var faqs_count = dmimag_faqs_data_count + 1;
      console.log( '1: ' + faqs_count );
    } else if( dmimag_faqs_count == dmimag_faqs_data_count ) {
      var faqs_count = dmimag_faqs_data_count + 1;
      console.log( '2: ' + faqs_count );
    } else {
      var faqs_count = dmimag_faqs_count;
      console.log( '3: ' + faqs_count );
    }
    
    var data = {
      'action': 'dmimag_faqs_add_postbox',
      'c': faqs_count
    }

    $.ajax({
      type: 'POST',
      url: ajaxurl,
      data: data,
      success: function( response ) {
        dmimag_faqs.after( response );
      
        wp.editor.initialize( $( response ).find('.faqcontent-editor').attr('id'), {
          tinymce: {
            wpautop: true,
            autoresize_min_height: 200,
            wp_autoresize_on: true,
            statusbar: false,
            plugins : 'charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor wordpress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview',
            toolbar1: 'bold italic underline strikethrough | bullist numlist | blockquote hr wp_more | alignleft aligncenter alignright | link unlink | fullscreen | wp_adv',
            toolbar2: 'formatselect alignjustify forecolor | pastetext removeformat charmap | outdent indent | undo redo | wp_help'
          },
          quicktags: true,
          mediaButtons: true,
        });
      }
    });

  });

  
	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );