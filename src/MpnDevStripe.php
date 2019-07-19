<?php
namespace MpnDev;

require '../vendor/autoload.php';
$path = $_SERVER['DOCUMENT_ROOT'];

include_once $path . '/wp-config.php';
include_once $path . '/wp-load.php';
include_once $path . '/wp-includes/wp-db.php';
include_once $path . '/wp-includes/pluggable.php';

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use MpnDev\MpnDevMail;

class MpnDevStripe {

	private $json;
	private $mail;

	public function __construct()
	{
		$this->json = json_decode(stripslashes($_POST['data']), true);
		$this->json['order']['image'] = "uploaded_images/" . basename($_FILES['image']['tmp_name'] . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
		$this->mail = new MpnDevMail(true);
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

	private function sendEmailToCustomer()
	{
		$this->mail->sendToCustomerOnOrder([
			'sender' => 'contact@windproofcurtains.co.uk',
			'to' => $this->json['order']['email'],
			'subject' => $this->getMailSubjectForCustomerOnCustomerMakeOrder(),
			'body' => $this->getMailContentForCustomerOnCustomerMakeOrder(),
			'alt_body' => strip_tags($this->getMailContentForCustomerOnCustomerMakeOrder())
		]);
		return $this->resetMail();
	}

	private function sendEmailToOwner()
	{
		$this->mail->sendOnCustomerMakeOrder([
			'sender' => 'contact@windproofcurtains.co.uk',
			'to' => 'contact@windproofcurtains.co.uk',
			'subject' => 'Клиент е наравил поръчка',
			'body' => $this->getMailContentForOwnerOnCustomerMakeOrder(),
			'alt_body' => strip_tags($this->getMailContentForOwnerOnCustomerMakeOrder())
		]);
		return $this->resetMail();
	}

	private function resetMail()
	{
		$this->mail = new MpnDevMail(true);
		return $this;
	}

	private function getMailSubjectForCustomerOnCustomerMakeOrder()
	{
		global $wpdb;
		$table_email_templates = $wpdb->prefix . "mpn_dev_plugin_email_templates";
		return $wpdb->get_results("SELECT * FROM `$table_email_templates`", ARRAY_A)[0]['subject'];
	}

	private function getMailContentForCustomerOnCustomerMakeOrder()
	{
		global $wpdb;
		$table_email_templates = $wpdb->prefix . "mpn_dev_plugin_email_templates";
		return $wpdb->get_results("SELECT * FROM `$table_email_templates`", ARRAY_A)[0]['body'];
	}

	private function getMailContentForOwnerOnCustomerMakeOrder()
	{
		return 'Клиент е направил поръчка и е <b>платил</b>';
	}

	public function store()
	{
		$this->validate()
			 ->calculateAmount()
			 ->saveImageOfThePlace()
			 ->saveOrderInDB()
			 ->sendEmailToOwner()
			 ->sendEmailToCustomer()
			 ->returnResponse();
		global $wpdb;
		$table_setup = $wpdb->prefix . "mpn_dev_plugin_setup";
		$stripe_secret_key = $wpdb->get_results("SELECT * FROM `$table_setup`", ARRAY_A)[0]['stripe_secret_key'];
		Stripe::setApiKey( $stripe_secret_key );/*secret key*/
		
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