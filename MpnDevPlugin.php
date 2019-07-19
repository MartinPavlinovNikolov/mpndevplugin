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
   $table_name4 = $wpdb->prefix . "mpn_dev_plugin_setup";
   $table_name5 = $wpdb->prefix . "mpn_dev_plugin_email_templates";

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

	$setup = "CREATE TABLE IF NOT EXISTS $table_name4 (
		id INT NOT NULL AUTO_INCREMENT,
		stripe_public_key TEXT NOT NULL,
		stripe_secret_key TEXT NOT NULL,
		paypal_client_id TEXT NOT NULL,
		paypal_secret TEXT NOT NULL,
		mail_host TEXT NOT NULL,
		mail_username TEXT NOT NULL,
		mail_password TEXT NOT NULL,
		mail_port TEXT NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	$email_templates = "CREATE TABLE IF NOT EXISTS $table_name5 (
		id INT NOT NULL AUTO_INCREMENT,
		title TEXT NOT NULL,
		subject TEXT NOT NULL,
		body TEXT NOT NULL,
		slug TEXT NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $orders );
	dbDelta( $customers );
	dbDelta( $walls );
	dbDelta( $dimensions );
	dbDelta( $setup );
	dbDelta( $email_templates );
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
		$stripe_public_key = $instance['stripe_public_key'];
		$paypal_client_id = $instance['paypal_client_id'];
		$stripeUrl = plugin_dir_url(__DIR__).basename(__DIR__).'/src/MpnDevStripe.php';
		$paypalUrl = plugin_dir_url(__DIR__).basename(__DIR__).'/src/MpnDevPaypal.php';
		include( MPNDEV_PLUGIN_PATH . 'src/inc/demo.php' );
		include( MPNDEV_PLUGIN_PATH . 'src/inc/mpndevplugin.js.php' );
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
		$door_width = __( '90', 'wpb_widget_domain' );
		}
		
		if ( isset( $instance[ 'door_height' ] ) ) {
		$door_height = $instance[ 'door_height' ];
		}
		else {
		$door_height = __( '200', 'wpb_widget_domain' );
		}
		
		if ( isset( $instance[ 'stripe_public_key' ] ) ) {
			$stripe_public_key = $instance[ 'stripe_public_key' ];
		}
		else {
			$stripe_public_key = 'pk_test_lVZX6Oqmev8YxYF9Ub1a4TNp00tlWJ7s94';
		}
		if ( isset( $instance[ 'stripe_secret_key' ] ) ) {
			$stripe_secret_key = $instance[ 'stripe_secret_key' ];
		}
		else {
			$stripe_secret_key = 'sk_test_vp02kYk98HLycQE9dOe6p62p00cxA91GvY';
		}
		if ( isset( $instance[ 'paypal_client_id' ] ) ) {
			$paypal_client_id = $instance[ 'paypal_client_id' ];
		}
		else {
			$paypal_client_id = 'AcJcCA-CNQxsWNU5a1coBsL6nVS0vBVW1UUsxphGyF_2hi-YxsqBXS6uMNChpPXGl0qeuO9c_UmF8USG';
		}
		if ( isset( $instance[ 'paypal_secret' ] ) ) {
			$paypal_secret = $instance[ 'paypal_secret' ];
		}
		else {
			$paypal_secret = 'ED92OKReYpmG1dBwecryqj3ul0k3G5HEhEQucUlRm-gj0KAJ6fP2U-Y7xC-YNyZ8l8AqiKER85q4Waql';
		}
		if ( isset( $instance[ 'mail_host' ] ) ) {
			$mail_host = $instance[ 'mail_host' ];
		}
		else {
			$mail_host = 'mail.windproofcurtains.co.uk';
		}
		if ( isset( $instance[ 'mail_username' ] ) ) {
			$mail_username = $instance[ 'mail_username' ];
		}
		else {
			$mail_username = 'contact@windproofcurtains.co.uk';
		}
		if ( isset( $instance[ 'mail_password' ] ) ) {
			$mail_password = $instance[ 'mail_password' ];
		}
		else {
			$mail_password = '[]=EDfCxcAku';
		}
		if ( isset( $instance[ 'mail_port' ] ) ) {
			$mail_port = $instance[ 'mail_port' ];
		}
		else {
			$mail_port = '465';
		}
		
		global $wpdb;

		$table_email_templates = $wpdb->prefix . "mpn_dev_plugin_email_templates";
		$email_templates = $wpdb->get_results("SELECT * FROM `$table_email_templates`", ARRAY_A);
		if($email_templates === null || count($email_templates) < 1){
			$wpdb->insert($table_email_templates, [
				'title' => 'Поръчката e получена',
				'subject' => 'new order.',
				'body' => 'new order',
				'slug' => 'new_order'
			]);
			$wpdb->insert($table_email_templates, [
				'title' => 'Поръчката се обработва',
				'subject' => 'the order is under review.',
				'body' => 'processing',
				'slug' => 'processing'
			]);
			$wpdb->insert($table_email_templates, [
				'title' => 'Продуктът се произвежда',
				'subject' => 'the product is in the process of being manufactured.',
				'body' => 'manifacturing',
				'slug' => 'manifacturing'
			]);
			$wpdb->insert($table_email_templates, [
				'title' => 'Поръчката е подадена към куриер',
				'subject' => 'order was send to curier.',
				'body' => 'send to curier',
				'slug' => 'send_to_curier'
			]);
			$wpdb->insert($table_email_templates, [
				'title' => 'Поръчката е доставена',
				'subject' => 'order was delivered.',
				'body' => 'delivered',
				'slug' => 'delivered'
			]);
			$wpdb->insert($table_email_templates, [
				'title' => 'Поръчката е неуспешно доставена',
				'subject' => 'delivering the order was failed.',
				'body' => 'delivered fail',
				'slug' => 'delivered_fail'
			]);
			$wpdb->insert($table_email_templates, [
				'title' => 'Поръчката е отказана',
				'subject' => 'order was canceled.',
				'body' => 'canceled',
				'slug' => 'canceled'
			]);
			$email_templates = $wpdb->get_results("SELECT * FROM `$table_email_templates`", ARRAY_A);
		}

		$email_template_new_order = $email_templates[0]['body'];

		$table_setup = $wpdb->prefix . "mpn_dev_plugin_setup";
		$setup = $wpdb->get_results("SELECT * FROM `$table_setup`", ARRAY_A);
		if($setup === null || count($setup) < 1){
			$wpdb->insert($table_setup, [
				'stripe_public_key' => 'pk_test_lVZX6Oqmev8YxYF9Ub1a4TNp00tlWJ7s94',
				'stripe_secret_key' => 'sk_test_vp02kYk98HLycQE9dOe6p62p00cxA91GvY',
				'paypal_client_id' => 'AcJcCA-CNQxsWNU5a1coBsL6nVS0vBVW1UUsxphGyF_2hi-YxsqBXS6uMNChpPXGl0qeuO9c_UmF8USG',
				'paypal_secret' => 'ED92OKReYpmG1dBwecryqj3ul0k3G5HEhEQucUlRm-gj0KAJ6fP2U-Y7xC-YNyZ8l8AqiKER85q4Waql',
				'mail_host' => 'mail.windproofcurtains.co.uk',
				'mail_username' => 'contact@windproofcurtains.co.uk',
				'mail_password' => '[]=EDfCxcAku',
				'mail_port' => '465'
			]);
		}

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
			$orders[$o]['user_id'] = $customer['id'];
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
			<?php include( MPNDEV_PLUGIN_PATH . 'src/MpnDevOrders.php' ); ?>
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

		<p>
		<label for="<?php echo $this->get_field_id( 'email_template_new_order' ); ?>"><?php _e( 'Шаблон за имейл, когато клиент направи поръчка:' ); ?></label> 
		<textarea class="widefat" id="<?php echo $this->get_field_id( 'email_template_new_order' ); ?>" name="<?php echo $this->get_field_name( 'email_template_new_order' ); ?>"><?php echo esc_attr( $email_template_new_order ); ?></textarea>
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'stripe_public_key' ); ?>"><?php _e( 'Stripe публичен ключ:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'stripe_public_key' ); ?>" name="<?php echo $this->get_field_name( 'stripe_public_key' ); ?>" type="text" value="<?php echo esc_attr( $stripe_public_key ); ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'stripe_secret_key' ); ?>"><?php _e( 'Stripe таен ключ:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'stripe_secret_key' ); ?>" name="<?php echo $this->get_field_name( 'stripe_secret_key' ); ?>" type="text" value="<?php echo esc_attr( $stripe_secret_key ); ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'paypal_client_id' ); ?>"><?php _e( 'Peypal ClientID:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'paypal_client_id' ); ?>" name="<?php echo $this->get_field_name( 'paypal_client_id' ); ?>" type="text" value="<?php echo esc_attr( $paypal_client_id ); ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'paypal_secret' ); ?>"><?php _e( 'Peypal Secret:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'paypal_secret' ); ?>" name="<?php echo $this->get_field_name( 'paypal_secret' ); ?>" type="text" value="<?php echo esc_attr( $paypal_secret ); ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'mail_host' ); ?>"><?php _e( 'Mail хост:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'mail_host' ); ?>" name="<?php echo $this->get_field_name( 'mail_host' ); ?>" type="text" value="<?php echo esc_attr( $mail_host ); ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'mail_username' ); ?>"><?php _e( 'Mail потребител:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'mail_username' ); ?>" name="<?php echo $this->get_field_name( 'mail_username' ); ?>" type="text" value="<?php echo esc_attr( $mail_username ); ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'mail_password' ); ?>"><?php _e( 'Mail парола:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'mail_password' ); ?>" name="<?php echo $this->get_field_name( 'mail_password' ); ?>" type="text" value="<?php echo esc_attr( $mail_password ); ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'mail_port' ); ?>"><?php _e( 'Mail порт:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'mail_port' ); ?>" name="<?php echo $this->get_field_name( 'mail_port' ); ?>" type="text" value="<?php echo esc_attr( $mail_port ); ?>" />
		</p>
		<?php 
	}
		
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		global $wpdb;
		$table_setup = $wpdb->prefix . "mpn_dev_plugin_setup";
		$wpdb->update($table_setup, [
			'stripe_public_key' => $new_instance['stripe_public_key'],
			'stripe_secret_key' => $new_instance['stripe_secret_key'],
			'paypal_client_id' => $new_instance['paypal_client_id'],
			'paypal_secret' => $new_instance['paypal_secret'],
			'mail_host' => $new_instance['mail_host'],
			'mail_username' => $new_instance['mail_username'],
			'mail_password' => $new_instance['mail_password'],
			'mail_port' => $new_instance['mail_port']
		], [
			'id' => 1
		]);
		$table_email_templates = $wpdb->prefix . "mpn_dev_plugin_email_templates";
		$wpdb->update($table_email_templates, [
			'body' => $new_instance['email_template_new_order']
		], [
			'id' => 1
		]);
		
		$instance = array();
		$instance['price_per_squere_meter'] = ( ! empty( $new_instance['price_per_squere_meter'] ) ) ? strip_tags( ($new_instance['price_per_squere_meter'] * 100) ) : '';
		$instance['door_price'] = ( ! empty( $new_instance['door_price'] ) ) ? strip_tags( ($new_instance['door_price'] * 100) ) : '';
		$instance['door_width'] = ( ! empty( $new_instance['door_width'] ) ) ? strip_tags( $new_instance['door_width'] ) : '';
		$instance['door_height'] = ( ! empty( $new_instance['door_height'] ) ) ? strip_tags( $new_instance['door_height'] ) : '';
		$instance['email_template_new_order'] = ( ! empty( $new_instance['email_template_new_order'] ) ) ? strip_tags( $new_instance['email_template_new_order'] ) : '';
		$instance['stripe_public_key'] = ( ! empty( $new_instance['stripe_public_key'] ) ) ? strip_tags( $new_instance['stripe_public_key'] ) : '';
		$instance['stripe_secret_key'] = ( ! empty( $new_instance['stripe_secret_key'] ) ) ? strip_tags( $new_instance['stripe_secret_key'] ) : '';
		$instance['paypal_client_id'] = ( ! empty( $new_instance['paypal_client_id'] ) ) ? strip_tags( $new_instance['paypal_client_id'] ) : '';
		$instance['paypal_secret'] = ( ! empty( $new_instance['paypal_secret'] ) ) ? strip_tags( $new_instance['paypal_secret'] ) : '';
		$instance['mail_host'] = ( ! empty( $new_instance['mail_host'] ) ) ? strip_tags( $new_instance['mail_host'] ) : '';
		$instance['mail_username'] = ( ! empty( $new_instance['mail_username'] ) ) ? strip_tags( $new_instance['mail_username'] ) : '';
		$instance['mail_password'] = ( ! empty( $new_instance['mail_password'] ) ) ? strip_tags( $new_instance['mail_password'] ) : '';
		$instance['mail_port'] = ( ! empty( $new_instance['mail_port'] ) ) ? strip_tags( $new_instance['mail_port'] ) : '';
		return $instance;
	}

} // Class MpnDevPlugin ends here
  
function wpse_admin_load_plugin_css(){
	$plugin_url = plugin_dir_url( __FILE__ );
	wp_register_style('bootstrap.min.css', $plugin_url . 'src/assets/css/bootstrap.min.css');
	wp_register_script('popper', $plugin_url . 'src/assets/js/popper.min.js', ['jquery']);
	wp_register_script('bootstrap', $plugin_url . 'src/assets/js/bootstrap.min.js', ['jquery', 'popper']);
	wp_register_script('vue', 'https://cdn.jsdelivr.net/npm/vue');

	wp_enqueue_style('bootstrap.min.css');
	wp_enqueue_script('pooper');
	wp_enqueue_script('bootstrap');
	wp_enqueue_script('vue');
}
  
function wpse_load_plugin_css(){
	$plugin_url = plugin_dir_url( __FILE__ );
	wp_register_style('font-awesome.min.css', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
	wp_register_style('mpndevplugin.css', $plugin_url . 'src/assets/css/mpndevplugin.css');
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