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
		$price_per_squear_methers = $instance['price_per_squere_meter'];
		$door_price = $instance['door_price'];
		$door_width = $instance['door_width'];
		$door_height = $instance['door_height'];
		$myUrl = plugin_dir_url(__DIR__).basename(__DIR__).'/MpnDevStripe.php';
		include( MPNDEV_PLUGIN_PATH . 'inc/demo.php' );
		include( MPNDEV_PLUGIN_PATH . 'inc/mpndevplugin.js.php' );
		echo $args['after_widget'];
	}
			
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'price_per_squere_meter' ] ) ) {
		$price_per_squere_meter = $instance[ 'price_per_squere_meter' ];
		}
		else {
		$price_per_squere_meter = __( '200', 'wpb_widget_domain' );
		}
		
		if ( isset( $instance[ 'door_price' ] ) ) {
		$door_price = $instance[ 'door_price' ];
		}
		else {
		$door_price = __( '300', 'wpb_widget_domain' );
		}
		
		if ( isset( $instance[ 'door_width' ] ) ) {
		$door_width = $instance[ 'door_width' ];
		}
		else {
		$door_width = __( '60', 'wpb_widget_domain' );
		}
		
		if ( isset( $instance[ 'door_height' ] ) ) {
		$door_height = $instance[ 'door_height' ];
		}
		else {
		$door_height = __( '200', 'wpb_widget_domain' );
		}
		// Widget admin form
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'price_per_squere_meter' ); ?>"><?php _e( 'Цена за квадратен метър в паундове(С включени данъци):' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'price_per_squere_meter' ); ?>" name="<?php echo $this->get_field_name( 'price_per_squere_meter' ); ?>" type="text" value="<?php echo (esc_attr( $price_per_squere_meter ) / 100); ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'door_price' ); ?>"><?php _e( 'Цена за стандартна врата в паундове(С включени данъци):' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'door_price' ); ?>" name="<?php echo $this->get_field_name( 'door_price' ); ?>" type="text" value="<?php echo (esc_attr( $door_price ) / 100); ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'door_width' ); ?>"><?php _e( 'Стандартна широчина на врата в сантиметри:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'door_width' ); ?>" name="<?php echo $this->get_field_name( 'door_width' ); ?>" type="text" value="<?php echo esc_attr( $door_width ); ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'door_height' ); ?>"><?php _e( 'Стандартна височина на врата в сантиметри:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'door_height' ); ?>" name="<?php echo $this->get_field_name( 'door_height' ); ?>" type="text" value="<?php echo esc_attr( $door_height ); ?>" />
		</p>
		<?php 
	}
		
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['price_per_squere_meter'] = ( ! empty( $new_instance['price_per_squere_meter'] ) ) ? strip_tags( ($new_instance['price_per_squere_meter'] * 100) ) : '';
		$instance['door_price'] = ( ! empty( $new_instance['door_price'] ) ) ? strip_tags( ($new_instance['door_price'] * 100) ) : '';
		$instance['door_width'] = ( ! empty( $new_instance['door_width'] ) ) ? strip_tags( $new_instance['door_width'] ) : '';
		$instance['door_height'] = ( ! empty( $new_instance['door_height'] ) ) ? strip_tags( $new_instance['door_height'] ) : '';
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