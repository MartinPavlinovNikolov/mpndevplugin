<?php

$path = $_SERVER['DOCUMENT_ROOT'];

include_once $path . '/wp-config.php';
include_once $path . '/wp-load.php';
include_once $path . '/wp-includes/wp-db.php';
include_once $path . '/wp-includes/pluggable.php';

global $wpdb;
$newBody = $_POST['new_template'];
$id = $_POST['mail_template_id'];

$wpdb->update(
	$wpdb->prefix . "mpn_dev_plugin_email_templates",
	["body" => $newBody],
	["id" => $id]
);

echo "success";



