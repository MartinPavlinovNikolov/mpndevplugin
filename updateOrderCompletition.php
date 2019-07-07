<?php

$path = $_SERVER['DOCUMENT_ROOT'];

include_once $path . '/wp-config.php';
include_once $path . '/wp-load.php';
include_once $path . '/wp-includes/wp-db.php';
include_once $path . '/wp-includes/pluggable.php';

global $wpdb;
$time = time();
$wpdb->update(
	$wpdb->prefix . "mpn_dev_plugin_orders",
	["compleated_at" => $time],
	["id" => $_POST['order_id']]
);

echo date('d-m-Y h:m:s', $time);