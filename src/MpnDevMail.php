<?php
namespace MpnDev;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MpnDevMail extends PHPMailer {

	public $mpn_host;
	public $mpn_my_username;
	public $mpn_password;
	public $mpn_port;

	public function __construct($auth)
	{
		parent::__construct($auth);
		$this->CharSet = 'UTF-8';
		global $wpdb;
		$table_setup = $wpdb->prefix . "mpn_dev_plugin_setup";
		$q = $wpdb->get_results("SELECT * FROM `$table_setup`", ARRAY_A);
		if(get_site_url() === "http://wordpress.local"){
			$this->mpn_host = 'smtp.mailtrap.io';
			$this->mpn_my_username = 'e7a37e0739cdf7';
			$this->mpn_password = '85c8e9259654b0';
			$this->mpn_port = 465;
		} else {
			$this->mpn_host = $q['mail_host'];
			$this->mpn_my_username = $q['mail_username'];;
			$this->mpn_password = $q['mail_password'];;
			$this->mpn_port = (int) $q['mail_port'];;
		}
	}

	public function sendOnCustomerMakeOrder($data)
	{
		try {
		    //Server settings
		    $this->isSMTP();// Set mailer to use SMTP
		    $this->Host       = $this->mpn_host;// Specify main and backup SMTP servers
		    $this->SMTPAuth   = true;// Enable SMTP authentication
		    $this->Username   = $this->mpn_my_username;// SMTP username
		    $this->Password   = $this->mpn_password;// SMTP password
		    $this->SMTPSecure = 'tls';// Enable TLS encryption, `ssl` also accepted
		    $this->Port       = $this->mpn_port;// TCP port to connect to

		    //Recipients
		    $this->setFrom($data['sender'], $this->mpn_my_username);
		    $this->addAddress($data['to']);// Add a recipient

		    // Content
		    $this->isHTML(true);// Set email format to HTML
		    $this->Subject = $data['subject'];
		    $this->Body    = $data['body'];
		    $this->AltBody = $data['alt_body'];

		    $this->send();
		    return true;
		} catch (Exception $e) {
		    return false;
		}
	}

	public function sendToCustomerOnOrder($data)
	{
		try {
		    //Server settings
		    $this->isSMTP();// Set mailer to use SMTP
		    $this->Host       = $this->mpn_host;// Specify main and backup SMTP servers
		    $this->SMTPAuth   = true;// Enable SMTP authentication
		    $this->Username   = $this->mpn_my_username;// SMTP username
		    $this->Password   = $this->mpn_password;// SMTP password
		    $this->SMTPSecure = 'tls';// Enable TLS encryption, `ssl` also accepted
		    $this->Port       = $this->mpn_port;// TCP port to connect to

		    //Recipients
		    $this->setFrom($data['sender']);
		    $this->addAddress($data['to']);// Add a recipient

		    // Content
		    $this->isHTML(true);// Set email format to HTML
		    $this->Subject = $data['subject'];
		    $this->Body    = $data['body'];
		    $this->AltBody = $data['alt_body'];

		    $this->send();
		    return true;
		} catch (Exception $e) {
		    return false;
		}
	}
}