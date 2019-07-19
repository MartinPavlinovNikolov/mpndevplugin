<?php
namespace MpnDev;

require '../vendor/autoload.php';
$path = $_SERVER['DOCUMENT_ROOT'];

include_once $path . '/wp-config.php';
include_once $path . '/wp-load.php';
include_once $path . '/wp-includes/wp-db.php';
include_once $path . '/wp-includes/pluggable.php';

use MpnDev\MpnDevMail;
use \Exception;

class Sender {

	private $email_body;
	private $email_subject;
	private $order_id;
	private $user_id;
	private $order;
	private $customer;

	public function __construct()
	{
		$this->email_body = $_POST["email_body"];
		$this->email_subject = $_POST["subject"];
		$this->order_id = $_POST["order_id"];
		$this->user_id = $_POST["user_id"];
	}

	private function setOrder()
	{
		global $wpdb;
		$table_orders = $wpdb->prefix . "mpn_dev_plugin_orders";
		$this->order = $wpdb->get_results("SELECT * FROM `$table_orders` WHERE `id`='".$this->order_id."'")[0];
		return $this;
	}

	private function setCustomer()
	{
		global $wpdb;
		$table_customers = $wpdb->prefix . "mpn_dev_plugin_customers";
		$this->customer = $wpdb->get_results("SELECT * FROM `$table_customers` WHERE `id`='".$this->user_id."'")[0];
		return $this;
	}

	private function replaceShortCodes()
	{
		$rules = [
			'[username]',
			'[phone]',
			'[address]',
			'[customer_email]',
			'[peyed_at]',
			'[peyed]',
			'[order]',
			'[ordered_at]',
			'[finished_at]'
		];
		$replacements = [
			$this->customer->username,
			$this->customer->phone,
			$this->customer->address,
			$this->customer->email,
			date('d-m-Y h:m:s', $this->order->ordered_at),
			($this->order->payed / 100) . $this->order->currency,
			$this->order->id,
			date('d-m-Y h:m:s', $this->order->ordered_at),
			date('d-m-Y h:m:s', $this->order->compleated_at)
		];
		foreach($rules as $index => $rule){
			$this->email_body = str_replace($rule, $replacements[$index], $this->email_body);
		}
		return $this;
	}

	private function sendEmail()
	{
		$successfullSended = (new MpnDevMail(true))->sendToCustomerOnOrder([
			'sender' => 'contact@windproofcurtains.co.uk',
			'to' => $this->customer->email,
			'subject' => $this->email_subject,
			'body' => $this->email_body,
			'alt_body' => strip_tags($this->email_body)
		]);
		if(!$successfullSended){
			throw new \Exception("Error Processing Request", 1);
		}
		return $this;
	}

	public function send()
	{
		try
		{
			$this->setOrder()
			->setCustomer()
			->replaceShortCodes()
			->sendEmail();
		}
		catch (\Exception $e)
		{
			echo "Нещо се обърка. Моля опитайте отново...";exit();
		}
		echo "success";
	}
}

(new Sender)->send();