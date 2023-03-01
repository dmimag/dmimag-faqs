<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://faqs.dmimag.site
 * @since      1.0.0
 *
 * @package    Dmimag_Faqs
 * @subpackage Dmimag_Faqs/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dmimag_Faqs
 * @subpackage Dmimag_Faqs/admin
 * @author     dmimag <support.plugins@dmimag.site>
 */
class Dmimag_Faqs_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $post_type ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
    $this->post_type = $post_type;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dmimag_Faqs_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dmimag_Faqs_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dmimag-faqs-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dmimag_Faqs_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dmimag_Faqs_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dmimag-faqs-admin.js', array( 'jquery', 'jquery-ui-sortable' ), $this->version, 'in_footer' );

	}
  
  /**
	 * Add shortcode columns FAQs posts.
	 *
	 * @since    1.1.1
	 */
  public function dmimag_faqs_manage_dmimag_faqs_posts_columns( $columns ) { //add_filter( 'manage_post_posts_columns',
    
    $dmimag_faqs_columns = array(
      'cb' => $columns['cb'],
      'title' => $columns['title'],
      'shortcode' => __( 'Shortcode', $this->plugin_name ),
      'date' => $columns['date']
    );
    
    return $dmimag_faqs_columns;
  }
  
  /**
	 * Render shortcode text FAQs posts.
	 *
	 * @since    1.1.1
	 */
  public function dmimag_faqs_manage_dmimag_faqs_posts_custom_column( $column, $post_id ) { //add_action( 'manage_post_posts_custom_column', 
    if ( $column === 'shortcode' ) {
?>
      <input type="text" class="dmimag-faqs-shortcode" value="[dmimag-faqs faq=<?php echo $post_id; ?> type=accordion]" readonly><span title="<?php _e( 'Copy to Clipboard', $this->plugin_name ); ?>" class="dmimag-faqs-copy-to-clipboard"></span>
      <input type="text" class="dmimag-faqs-shortcode" value="[dmimag-faqs faq=<?php echo $post_id; ?> type=guide]" readonly><span title="<?php _e( 'Copy to Clipboard', $this->plugin_name ); ?>" class="dmimag-faqs-copy-to-clipboard"></span>
<?php
    }
  }
  
  /**
	 * Add shortcode column FAQs.
	 *
	 * @since    1.0.0
	 */
  function dmimag_faqs_manage_edit_taxonomy_columns( $columns ) {
    
    $columns['dmimag_faqs_group_shortcode'] = '<span>' . __( 'Shortcode', $this->plugin_name ) . '</span>';
    return $columns;
    
  }
  
  /**
	 * Return shortcode text FAQs Group.
	 *
	 * @since    1.0.0
	 */
  function dmimag_faqs_manage_taxonomy_custom_column( $string, $column_name, $term_id ) {
    if( $column_name == 'dmimag_faqs_group_shortcode' ) {
      return '
      <input type="text" class="dmimag-faqs-shortcode" value="[dmimag-faqs faq=' . $term_id . ' type=accordion]" readonly><span title="' . __( 'Copy to Clipboard', $this->plugin_name ) . '" class="dmimag-faqs-copy-to-clipboard"></span>
      <input type="text" class="dmimag-faqs-shortcode" value="[dmimag-faqs faq=' . $term_id . ' type=guide]" readonly><span title="' . __( 'Copy to Clipboard', $this->plugin_name ) . '" class="dmimag-faqs-copy-to-clipboard"></span>
      ';
    }
  }

}
?>