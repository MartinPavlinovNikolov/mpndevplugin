<?php
namespace MpnDevStripe;

require __DIR__ . '/vendor/autoload.php';

use Stripe\{Stripe, Customer, Charge};

class MpnDevStripe {

	private $json;

	public function __construct()
	{
		$this->json = json_decode(file_get_contents('php://input'), true);
	}

	public function validateWalls()
	{
		return true;
	}

	public function returnResponse()
	{
		echo 'success';
	}

	public function calculateAmount()
	{
		return 1500;
	}

	public function store()
	{
		Stripe::setApiKey('sk_test_vp02kYk98HLycQE9dOe6p62p00cxA91GvY');/*secret key*/
		
		$customer = Customer::create([
			'email' => $this->json['stripe']['email'],
			'source' => $this->json['stripe']['source']
		]);

		$charge = Charge::create([
			'customer' => $customer->id,
			'amount' => $this->calculateAmount(),
			'currency' => 'gbp'
		]);

		$this->returnResponse();
	}
	
}

try {
	$MpnDevStripe = new MpnDevStripe();
	$MpnDevStripe->store();
} catch (Exception $e) {
	echo 'error';
}