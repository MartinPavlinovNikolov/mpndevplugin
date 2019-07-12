<?php
namespace MpnDevMail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MpnDevMail extends PHPMailer {

	public $mpn_host = MPNDEV_MAIL_HOST;
	public $mpn_my_username = MPNDEV_MAIL_USERNAME;
	public $mpn_password = MPNDEV_MAIL_PASSWORD;
	public $mpn_port = TESTING_MPNDEV_MAIL_PORT;

	public function __construct($auth)
	{
		parent::__construct($auth);
		$this->CharSet = 'UTF-8';
		
		if(get_site_url() === "http://wordpress.local"){
			$this->mpn_host = TESTING_MPNDEV_MAIL_HOST;
			$this->mpn_my_username = TESTING_MPNDEV_MAIL_USERNAME;
			$this->mpn_password = TESTING_MPNDEV_MAIL_PASSWORD;
			$this->mpn_port = TESTING_MPNDEV_MAIL_PORT;
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
		    $this->setFrom($data['sender'], MPNDEV_MAIL_USERNAME);
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