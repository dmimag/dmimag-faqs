(function( $ ) {
  'use strict';
  
  function dmimag_faqs_wp_editor( editor_id ) {
    wp.editor.initialize( editor_id, {
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
  };
  
  $(document).on( 'click touchstart', '.dmi-grid-metabox-up', function( event ) { 
    event.preventDefault();
    
    var faq = $(this).parents('.dmimag-faqs');
    
    var editor_id = faq.find('.faqcontent-editor').attr('id');

    wp.editor.remove( editor_id );
    
    faq.insertBefore( faq.prev() );
    
    dmimag_faqs_wp_editor( editor_id );

    return false;
	});

  $(document).on( 'click touchstart', '.dmi-grid-metabox-down', function( event ) {
    event.preventDefault();
    
    var faq = $(this).parents('.dmimag-faqs');

    var editor_id = faq.find('.faqcontent-editor').attr('id');

    wp.editor.remove( editor_id );
    
    faq.insertAfter( faq.next() );
    
    dmimag_faqs_wp_editor( editor_id );  
    
    return false;
	});
  
  
  $('.dmimag-faqs-copy-to-clipboard').on('click', function( event ) {
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
        
        dmimag_faqs_wp_editor( $( response ).find('.faqcontent-editor').attr('id') );
      }
    });

  });
})( jQuery );