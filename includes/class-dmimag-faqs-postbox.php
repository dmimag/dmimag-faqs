<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://faqs.dmimag.site
 * @since      1.0.1
 *
 * @package    Dmimag_Faqs
 * @subpackage Dmimag_Faqs/includes
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dmimag_Faqs
 * @subpackage Dmimag_Faqs/includes
 * @author     dmimag <support.plugins@dmimag.site>
 */

class Dmimag_Faqs_Postbox {
  
  /**
	 * The ID of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
  
  /**
	 * Custom post type of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $post_type
	 */
	private $post_type;

  /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.1
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
  
	public function __construct( $plugin_name, $version, $post_type ) {

    $this->plugin_name = $plugin_name;
		$this->version = $version;
    $this->post_type = $post_type;
    
	}
  
  /**
	 * Create postbox
	 *
	 * @since    1.0.1
	 * @param      
	 * @param      
	 */
  public function dmimag_faqs_create_postbox() {
    
    $options = array();
    add_meta_box( $this->plugin_name, __( 'FAQ', $this->plugin_name ), array( $this, 'dmimag_faqs_render_postbox' ), array( $this->post_type ), 'normal', 'high', $options ); 
    
  }

  /**
	 * Render postbox
	 *
	 * @since    1.1.1
	 * @param      
	 * @param      
	 */
  public function dmimag_faqs_render_postbox( $post, $options ) {
 
    if ( ! in_array( get_current_screen()->id, array( $this->post_type ) ) ) return;
    if ( ! current_user_can( 'edit_post', $post->ID ) ) return;
      
    wp_nonce_field( 'faqs_nonce', 'faq_wpnonce', false, true );
        
    $post_content = wp_unslash( json_decode( $post->post_content, true ) );
    
    $c = 0;

    if( ! empty( $post_content ) ) {

      foreach( $post_content as $faq ) {
        $title = '';
        $content = '';
        
        if( ! empty( $faq['faqtitle'] ) ) {
          $title = $faq['faqtitle'];
        }
        
        if( ! empty( $faq['faqcontent'] ) ) {        
          $content = $faq['faqcontent'];
        }
        
        $this->dmimag_faqs_render_postbox_html( $title, $content, $c ); 
        
        $c++;
      }
         
    } else {

      $this->dmimag_faqs_render_postbox_html( $title = '', $content = '', $c );
      
    }
  }
   
  /**
   * Render postbox input, textarea
   * 
   * @since    1.0.1
	 * @param      
	 * @param 
   */
  public function dmimag_faqs_render_postbox_html( $title = '' , $content = '', $c = '' ) {
    
    if( ! empty( $content ) ) { $content = $content; } else { $content = ''; }
    
    if( isset( $_POST['c'] ) && ! empty( $_POST['c'] ) && is_numeric( $_POST['c'] ) ) {
      $c = intval( $_POST['c'] );
    } elseif( isset( $c ) && is_numeric( $c ) ) {
      $c = intval( $c );
    } else {
      $c = rand( 55, 999 );
    }
?>
    <div class="dmimag-faqs 
                dmi-grid-metabox 
                dmi-grid-row 
                dmi-grid-metabox-faq 
                dmi-grid-metabox-pos-normal" data-faqs="<?php echo esc_attr( $c ); ?>">
      <div class="dmi-grid-col">
        <div class="dmi-metabox dmi-grid-row">
          <div class="dmi-grid-col">
            <div class="dmi-grid-row dmi-justify-content-end dmi-align-items-center">
              <div class="dmi-grid-col dmi-grid-col-auto"><?php _e( 'Sort FAQ', $this->plugin_name ); ?></div><div class="dmi-grid-col dmi-grid-col-auto dmi-grid-metabox-up"></div><div class="dmi-grid-col dmi-grid-col-auto dmi-grid-metabox-down"></div>
            </div>
          </div>
        </div>
        <div class="dmi-metabox dmi-metabox-text dmi-grid-row">
          <div class="dmi-grid-col">

            <div class="dmi-metabox-description dmi-grid-row">
              <h4 for="faqtitle"><?php _e( 'FAQ Title', $this->plugin_name ); ?></h4>
              <span class="description"><?php _e( 'FAQ title text', $this->plugin_name ); ?></span>
            </div>


            <div class="dmi-metabox-field dmi-grid-row">
              <div class="dmi-field dmi-grid-col">
                <input name="faq[<?php echo esc_attr( $c ); ?>][faqtitle]" type="text" id="faqtitle" value="<?php if( ! empty( $title ) ) { echo esc_attr( $title ); } ?>">
              </div>
            </div>
          </div>
        </div>

        <div class="dmi-metabox dmi-metabox-wp_editor dmi-grid-row">
          <div class="dmi-grid-col">
            <div class="dmi-metabox-description dmi-grid-row">
              <h4 for="faqcontent"><?php _e( 'FAQ Content', $this->plugin_name ); ?></h4>
              <span class="description"><?php _e( 'FAQ content text', $this->plugin_name ); ?></span>
            </div>
            <div class="dmi-metabox-field dmi-grid-row">
              <div class="dmi-field dmi-grid-col">
                <?php
                  $r = str_shuffle( 'abcdefgjqwertyu' );
                  $wp_editor_id = 'faqcontent' . $r;
                ?>
                <textarea name="faq[<?php echo esc_attr( $c ); ?>][faqcontent]" id="<?php echo esc_attr( $wp_editor_id ); ?>" class="large-text faqcontent-editor"><?php if( ! empty( $content ) ) { echo esc_textarea( $content ); } ?></textarea>
              </div>
            </div>
          </div>
        </div>

        <div class="dmi-metabox dmi-metabox-button dmi-grid-row">
          <div class="dmi-grid-col">
            <div class="dmi-metabox-field dmi-grid-row">
              <div class="dmi-field dmi-grid-col">
                <button type="button" class="button button-primary dmimag-faqs-button-remove"><?php _e( 'Remove FAQ', $this->plugin_name ); ?></button>
              </div>
            </div>
          </div>
        </div>
      </div> <!-- end .dmimag-core.dmi-grid-row .dmi-grid-col -->
    </div> <!-- end .dmimag-core.dmi-grid-row -->

<?php 
    if (! empty( $_POST) ) {
      wp_die();
    }
  }
  
  /**
   * Enqueue wp.editor scripts
   * 
   * @since    1.1.1
	 * @param      
	 * @param 
   */  
  public function dmimag_faqs_wp_enqueue_editor() {

    if ( function_exists( 'wp_enqueue_editor' ) ) {
      
      wp_enqueue_editor();
      wp_enqueue_media();

    }

  }
  
  /**
   * Enqueue wp.editor.initialize script
   * 
   * @since    1.1.1
	 * @param      
	 * @param 
   */
  public function dmimag_faqs_wp_editor_initialize() {
?>
	<script>
    (function( $ ) {
      $('.dmimag-faqs .faqcontent-editor').each(function (index, element) {

        var id_content = $(this).attr('id');
        wp.editor.initialize( id_content, {
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

      });
    })( jQuery );
	</script>
<?php 
   }  
  
  /**
	 * Remove default meta box
	 *
	 * @since    1.1.1
	 * @param      
	 * @param      
	 */  
  public function dmimag_faqs_remove_default_meta_box() {
    remove_meta_box( 'slugdiv', $this->post_type, 'normal' ); 
  }
  
  /**
   * Render button
   * 
   * @since    1.1.1
	 * @param      
	 * @param 
   */
  public function dmimag_faqs_render_button_add( $post ) {
?>
    <button type="button" class="button button-primary dmimag-faqs-button-add"><?php _e( '+ Add FAQ', $this->plugin_name ); ?></button>
<?php
  }
  
  /**
   * Render text shortcode
   * 
   * @link https://wp-kama.ru/hook-cat/posts-edit
   *
   * @since    1.1.1
	 * @param      
	 * @param 
   */  
  public function dmimag_faqs_render_text_shortcode( $post ) {
		if ( $this->post_type !== $post->post_type ) {
			return;
		}
?>
		<div class="inside">
			<strong style="padding: 0 10px;"><?php esc_html_e( 'Shortcode:',  $this->plugin_name ); ?></strong>
      <p>
			  <input type="text" class="dmimag-faqs-shortcode" value='[dmimag-faqs faq=<?php echo intval( $post->ID ); ?> type=accordion]' readonly><span title="<?php _e( 'Copy to Clipboard', $this->plugin_name ); ?>" class="dmimag-faqs-copy-to-clipboard"></span> <!-- onclick="this.select()" -->
      </p>
      <p>
        <input type="text" class="dmimag-faqs-shortcode" value="[dmimag-faqs faq=<?php echo intval( $post->ID ); ?> type=guide]" readonly><span title="<?php _e( 'Copy to Clipboard', $this->plugin_name ); ?>" class="dmimag-faqs-copy-to-clipboard"></span>
      </p>
		</div>
<?php
	}
  
  /**
   * Save postbox
   * 
   * @since    1.1.1
	 * @param      
	 * @param 
   */
  public function dmimag_faqs_save_postbox( $data, $postarr ) {
    
    if ( ! current_user_can( 'edit_post', $postarr['ID'] ) ) return $data;

    if ( $this->post_type !== $data['post_type'] ) return $data;      

    if ( ! isset( $_POST['faq_wpnonce'] ) || 
        ! wp_verify_nonce( $_POST['faq_wpnonce'], 'faqs_nonce' ) ) return $data;

    if ( isset( $_POST['faq'] ) && ! empty( $_POST['faq'] ) && is_array( $_POST['faq'] ) ) {
      
      $data_faq = array();
      
      $data['post_content'] = '';
      
      $c = 0;
      
      foreach( $_POST['faq'] as $faq ) {
        
        if( isset( $faq['faqtitle'] ) && !empty( $faq['faqtitle'] ) ) {
          $data_faq[$c]['faqtitle'] = sanitize_text_field( $faq['faqtitle'] );
        }
        
        if( isset( $faq['faqcontent'] ) && !empty( $faq['faqcontent'] ) ) {
          $data_faq[$c]['faqcontent'] = sanitize_post( $faq['faqcontent'] );  //
        }
        
        $c++;
      }
      
      if( ! empty( $data_faq ) ) {
        $data['post_content'] = wp_slash( wp_json_encode( $data_faq ) );
      }
    }

    return $data;
  }
}
?>