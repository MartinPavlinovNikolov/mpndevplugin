<?php
/*
Plugin Name: MpnDevPlugin
Description: MpnDevPlugin
*/
/* Start Adding Functions Below this Line */
	// Register and load the widget
function wpb_load_widget() {
	register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );
define('MPNDEV_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Creating the widget 
class wpb_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			// Base ID of your widget
			'wpb_widget', 

			// Widget name will appear in UI
			__('MpnDevCalculator', 'wpb_widget_domain'), 

			// Widget description
			array( 'description' => __( 'MpnDevCalculator', 'MpnDevCalculator' ), )
		);
	}

	// Creating widget front-end

	public function widget( $args, $instance ) {
		// This is where you run the code and display the output
		$myUrl = plugin_dir_url(__DIR__).basename(__DIR__).'/MpnDevStripe.php';
		include( MPNDEV_PLUGIN_PATH . 'inc/demo.php' );
		include( MPNDEV_PLUGIN_PATH . 'inc/mpndevplugin.js.php' );
		echo $args['after_widget'];
	}
			
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
		$title = $instance[ 'title' ];
		}
		else {
		$title = __( 'New title', 'wpb_widget_domain' );
		}
		// Widget admin form
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}
		
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}

} // Class wpb_widget ends here
  
function wpse_load_plugin_css(){
	$plugin_url = plugin_dir_url( __FILE__ );
	wp_register_style('font-awesome.min.css', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
	wp_register_style('mpndevplugin.css', $plugin_url . 'assets/css/mpndevplugin.css');
	wp_register_script('jquery', 'https://code.jquery.com/jquery-3.4.1.min.js');
	wp_register_script('stripe', 'https://js.stripe.com/v3/', ['jquery']);
	wp_register_script('checkout', 'https://checkout.stripe.com/checkout.js');
	wp_register_script('vue', 'https://cdn.jsdelivr.net/npm/vue', ['jquery', 'stripe', 'checkout']);
	wp_register_script('axios', 'https://unpkg.com/axios/dist/axios.min.js', ['jquery', 'stripe', 'checkout', 'vue']);

	wp_enqueue_style('font-awesome.min.css');
	wp_enqueue_style('mpndevplugin.css');
	wp_enqueue_script('jquery');
	wp_enqueue_script('stripe');
	wp_enqueue_script('checkout');
	wp_enqueue_script('vue');
	wp_enqueue_script('axios');
}
add_action( 'wp_enqueue_scripts', 'wpse_load_plugin_css' );
/* Stop Adding Functions Below this Line */
?>