<?php
namespace MpnDevStripe;

use Stripe\{Stripe, Customer, Charge};

require __DIR__ . '/vendor/autoload.php';
$path = $_SERVER['DOCUMENT_ROOT'];

include_once $path . '/wp-config.php';
include_once $path . '/wp-load.php';
include_once $path . '/wp-includes/wp-db.php';
include_once $path . '/wp-includes/pluggable.php';

class MpnDevStripe {

	private $json;

	public function __construct()
	{
		$this->json = json_decode(stripslashes($_POST['data']), true);
		$this->json['order']['image'] = "uploaded_images/" . basename($_FILES['image']['tmp_name'] . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
	}

	private function validate()
	{
		return $this;
	}

	private function returnResponse()
	{
		echo 'success';
	}

	private function calculateAmount()
	{
		return $this;
	}

	private function sendEmailToOwner()
	{
		//wp_mail('martin_nikolov.89@abv.bg', 'нова поръчка', 'здрасти'); todo: setup wp smtp
		return $this;
	}

	public function store()
	{
		$this->validate()
			 ->calculateAmount()
			 ->saveImageOfThePlace()
			 ->saveOrderInDB()
			 ->sendEmailToOwner()
			 ->returnResponse();

		Stripe::setApiKey('sk_test_vp02kYk98HLycQE9dOe6p62p00cxA91GvY');/*secret key*/
		
		$customer = Customer::create([
			'email' => $this->json['stripe']['email'],
			'source' => $this->json['stripe']['source']
		]);

		$charge = Charge::create([
			'customer' => $customer->id,
			'amount' => $this->json['order']['price'],
			'currency' => 'gbp'
		]);

	}

	private function saveImageOfThePlace()
	{
	    move_uploaded_file($_FILES['image']['tmp_name'], "./" . $this->json['order']['image']);
	    return $this;
	}

	private function saveOrderInDB()
	{
		global $wpdb;

		$payed = $this->json['order']['price'];
		$currency = 'gbp';//todo
		$ordered_at = time();
		$color = $this->json['order']['selected_color'];
		$measurment = $this->json['order']['measurment'];
		$image_of_the_place = $this->json['order']['image'];
		$gateway = 'stripe';

		$username = $this->json['order']['username'];
		$email = $this->json['order']['email'];
		$address = $this->json['order']['address'];
		$phone = $this->json['order']['phone'];

		$wpdb->insert( 
			$wpdb->prefix . "mpn_dev_plugin_orders",
			array(
				'payed' => $payed,
				'currency' => $currency,
				'ordered_at' => $ordered_at,
				'color' => $color,
				'measurment' => $measurment,
				'image_of_the_place' => $image_of_the_place,
				'gateway' => $gateway
			) 
		);
		$order_id = $wpdb->insert_id;

		$wpdb->insert( 
			$wpdb->prefix . "mpn_dev_plugin_customers",
			array(
				'order_id' => $order_id,
				'username' => $username,
				'email' => $email,
				'address' => $address,
				'phone' => $phone
			) 
		);

		foreach($this->json['order']['walls'] as $wall){

			$current_svg_name = ($wall['shape_id'] . '.svg');
			$door_starts_from = $wall['door_starts_from'] != 0 ? $wall['door_starts_from'] : null;
			$section_additional_information = $wall['additional_information'];

			$wpdb->insert( 
				$wpdb->prefix . "mpn_dev_plugin_walls",
				array(
					'order_id' => $order_id,
					'shape' => $current_svg_name,
					'door_starts_from' => $door_starts_from,
					'note' => $section_additional_information
				) 
			);
			$wall_id = $wpdb->insert_id;

			foreach($wall['shape_dimensions'] as $dimension){
				$letter = $dimension['letter'];
				$value = $dimension['value'];

				$wpdb->insert( 
					$wpdb->prefix . "mpn_dev_plugin_dimensions",
					array(
						'wall_id' => $wall_id,
						'letter' => $letter,
						'value' => $value
					) 
				);
			}
		}

		return $this;
	}
	
}

try {
	$MpnDevStripe = new MpnDevStripe();
	$MpnDevStripe->store();
} catch (Exception $e) {
	echo 'error';
}