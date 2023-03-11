<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://faqs.dmimag.site
 * @since      1.0.0
 *
 * @package    Dmimag_Faqs
 * @subpackage Dmimag_Faqs/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Dmimag_Faqs
 * @subpackage Dmimag_Faqs/public
 * @author     dmimag <support.plugins@dmimag.site>
 */
class Dmimag_Faqs_Public {

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * Custom post type of the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $post_type
   */
  protected $post_type;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of the plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version, $post_type ) {

    $this->plugin_name = $plugin_name;

    $this->version = $version;

    $this->post_type = $post_type;

  }

  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {
    
    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dmimag-faqs-public.css', array(), $this->version, 'all' );

  }

  /**
   * Register the JavaScript for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {
    
    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dmimag-faqs-public.js', array( 'jquery' ), $this->version, 'in_footer' );
    
  }
  
  /**
   * DMI Mag FAQs Shortcode
   *
   * @since    1.0.0
   */  
  function dmimag_faqs_shortcode( $atts ) {
    ob_start();
    
    $faqs_row_class = 'dmi-faqs-row-accordion';
    
    if( isset( $atts['type'] ) && $atts['type'] == 'guide' ) {
      $faqs_title = array();
      $faqs_row_class = 'dmi-faqs-row-guid';
    }
    
    if( isset( $atts['faq'] ) && is_numeric( $atts['faq'] ) ) {
    
      $args = array(
        'post_type' => $this->post_type,
        'p' => $atts['faq'],
        'post_status' => 'publish'
      );

      $query = new WP_Query( $args );

      if ( $query->have_posts() ) {
?>
        <div class="dmi-faqs-row <?php echo $faqs_row_class; ?>">
          <div class="dmi-faqs-col dmi-faqs-col-content">
<?php
        $i = '';
        while ( $query->have_posts() ) {
          $query->the_post();
          
          $post_content = wp_unslash( json_decode( $query->post->post_content, true ) );
          
          if( ! empty( $post_content ) && is_array( $post_content ) ) {
          
            foreach( $post_content as $faq ) {
              $i++;
?>
            <div <?php if( isset( $atts['type'] ) && $atts['type'] == 'guide' ) { ?>id="faq-<?php echo $i; ?>" <?php } ?>class="dmi-faq">
              <div class="dmi-faq-title">
<?php
              if ( ! empty( $faq['faqtitle'] ) ) {
                echo esc_html( $faq['faqtitle'] );
          
                if( isset( $atts['type'] ) && $atts['type'] == 'guide' ) {
                  $faqs_title[$i] = $faq['faqtitle'];
                }
              }
?>
              </div>
              <div class="dmi-faq-content">
<?php
              if ( ! empty( $faq['faqcontent'] ) ) {
                echo apply_filters( 'the_content', wp_kses_post( $faq['faqcontent'] ) );
              }
?>
              </div>
            </div>
<?php
            }
          }
        } // end while
?>
          </div>
<?php
        if( isset( $atts['type'] ) && $atts['type'] == 'guide' && isset( $faqs_title ) ) {
?>
          <div class="dmi-faqs-col dmi-faqs-col-nav">
            <ul class="dmi-faq-nav">
<?php
            foreach( $faqs_title as $key => $faq_title ) {
              if( ! empty( $faq_title ) ) {
?>
              <li><a href="#faq-<?php echo $key; ?> "><?php echo esc_html( $faq_title ); ?></a></li>
<?php
              }
            }
?>
            </ul>
            
          </div>
<?php
        }
?>
        </div>
<?php
      }
      wp_reset_postdata();
      
    }
    return ob_get_clean();
  }
}
?>