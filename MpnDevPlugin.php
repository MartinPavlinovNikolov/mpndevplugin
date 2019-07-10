<?php
/*
Plugin Name: MpnDevPlugin
Description: MpnDevPlugin
*/
/* Start Adding Functions Below this Line */
	// Register and load the widget
function mpn_dev_plugin_db_table_install () {
   global $wpdb;

   $table_name = $wpdb->prefix . "mpn_dev_plugin_orders"; 
   $table_name1 = $wpdb->prefix . "mpn_dev_plugin_customers"; 
   $table_name2 = $wpdb->prefix . "mpn_dev_plugin_walls"; 
   $table_name3 = $wpdb->prefix . "mpn_dev_plugin_dimensions"; 

   $charset_collate = $wpdb->get_charset_collate();

	$orders = "CREATE TABLE IF NOT EXISTS $table_name (
		id INT NOT NULL AUTO_INCREMENT,
		payed INT,
		currency text(55) NOT NULL,
		ordered_at TEXT NOT NULL,
		compleated_at TEXT,
		color text(55) NOT NULL,
		measurment text(55) NOT NULL,
		image_of_the_place text(255) NOT NULL,
		gateway text(55) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	$customers = "CREATE TABLE IF NOT EXISTS $table_name1 (
		id INT NOT NULL AUTO_INCREMENT,
		order_id INT NOT NULL,
		username text(255) NOT NULL,
		email text(255) NOT NULL,
		address text(255) NOT NULL,
		phone text(255) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	$walls = "CREATE TABLE IF NOT EXISTS $table_name2 (
		id INT NOT NULL AUTO_INCREMENT,
		order_id INT NOT NULL,
		shape text(255) NOT NULL,
		door_starts_from INT,
		note text(255),
		PRIMARY KEY  (id)
	) $charset_collate;";

	$dimensions = "CREATE TABLE IF NOT EXISTS $table_name3 (
		id INT NOT NULL AUTO_INCREMENT,
		wall_id INT NOT NULL,
		letter TEXT NOT NULL,
		value INT NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $orders );
	dbDelta( $customers );
	dbDelta( $walls );
	dbDelta( $dimensions );
}

function wpb_load_widget() {
	register_widget( 'MpnDevPlugin' );
	mpn_dev_plugin_db_table_install ();
}
add_action( 'widgets_init', 'wpb_load_widget' );

define('MPNDEV_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Creating the widget 
class MpnDevPlugin extends WP_Widget {

	function __construct() {
		parent::__construct(
			// Base ID of your widget
			'MpnDevPlugin', 

			// Widget name will appear in UI
			__('MpnDevPlugin', 'wpb_widget_domain'), 

			// Widget description
			array( 'description' => __( 'MpnDevPlugin', 'MpnDevPlugin' ), )
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
		global $wpdb;

		$table_orders = $wpdb->prefix . "mpn_dev_plugin_orders";
		$table_customers = $wpdb->prefix . "mpn_dev_plugin_customers";
		$table_walls = $wpdb->prefix . "mpn_dev_plugin_walls";
		$table_dimensions = $wpdb->prefix . "mpn_dev_plugin_dimensions";

		$orders = [];
		$compleatedOrders = [];
		$incompleatedOrders = [];
		foreach($wpdb->get_results("SELECT * FROM `$table_orders` ORDER BY `ordered_at` DESC", ARRAY_A) as $o => $order){
			$order_id = $order['id'];
			$orders[$o]['id'] = $order['id'];
			$orders[$o]['payed'] = $order['payed'];
			$orders[$o]['currency'] = $order['currency'];
			$orders[$o]['ordered_at'] = $order['ordered_at'];
			$orders[$o]['compleated_at'] = $order['compleated_at'];
			$orders[$o]['color'] = $order['color'];
			$orders[$o]['measurment'] = $order['measurment'];
			$orders[$o]['image_of_the_place'] = $order['image_of_the_place'];
			$orders[$o]['gateway'] = $order['gateway'];

			$customer = $wpdb->get_results("SELECT * FROM `$table_customers` WHERE `order_id`='$order_id'", ARRAY_A)[0];
			$orders[$o]['username'] = $customer['username'];
			$orders[$o]['email'] = $customer['email'];
			$orders[$o]['address'] = $customer['address'];
			$orders[$o]['phone'] = $customer['phone'];

			foreach($wpdb->get_results("SELECT * FROM `$table_walls` WHERE `order_id` = '$order_id' ORDER BY `id` ASC", ARRAY_A) as $w => $wall){
				$wall_id = $wall['id'];
				$orders[$o]['walls'][$w]['id'] = $wall['id'];
				$orders[$o]['walls'][$w]['order_id'] = $wall['order_id'];
				$orders[$o]['walls'][$w]['shape'] = $wall['shape'];
				$orders[$o]['walls'][$w]['door_starts_from'] = $wall['door_starts_from'];
				$orders[$o]['walls'][$w]['note'] = $wall['note'];
				foreach($wpdb->get_results("SELECT * FROM `$table_dimensions` WHERE `wall_id` = '$wall_id'", ARRAY_A) as $d => $dimension){
					$orders[$o]['walls'][$w]['dimensions'][$d]['id'] = $dimension['id'];
					$orders[$o]['walls'][$w]['dimensions'][$d]['wall_id'] = $dimension['wall_id'];
					$orders[$o]['walls'][$w]['dimensions'][$d]['letter'] = $dimension['letter'];
					$orders[$o]['walls'][$w]['dimensions'][$d]['value'] = $dimension['value'];
					if($orders[$o]['compleated_at'] == null){
						$incompleatedOrders[$o] = $orders[$o];
					} else {
						$compleatedOrders[$o] = $orders[$o];
					}
				}
			}
		}
		add_thickbox();
		?>
		
		<div id="my-content-id" style="display:none;">
			<?php include( MPNDEV_PLUGIN_PATH . 'MpnDevOrders.php' ); ?>
		</div>

		<!-- Button trigger modal -->
		<a href="#TB_inline?width=780&height=540&inlineId=my-content-id" class="thickbox btn btn-primary mt-2 mb-2">
		  Моите поръчки
		</a>

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

} // Class MpnDevPlugin ends here
  
function wpse_admin_load_plugin_css(){
	$plugin_url = plugin_dir_url( __FILE__ );
	wp_register_style('bootstrap.min.css', $plugin_url . 'assets/css/bootstrap.min.css');
	wp_register_script('popper', $plugin_url . 'assets/js/popper.min.js', ['jquery']);
	wp_register_script('bootstrap', $plugin_url . 'assets/js/bootstrap.min.js', ['jquery', 'popper']);
	wp_register_script('vue', 'https://cdn.jsdelivr.net/npm/vue');

	wp_enqueue_style('bootstrap.min.css');
	wp_enqueue_script('pooper');
	wp_enqueue_script('bootstrap');
	wp_enqueue_script('vue');
}
  
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
add_action( 'admin_enqueue_scripts', 'wpse_admin_load_plugin_css' );
add_action( 'wp_enqueue_scripts', 'wpse_load_plugin_css' );
/* Stop Adding Functions Below this Line */
?>