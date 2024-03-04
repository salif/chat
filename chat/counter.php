<?php

function get_user_ip() {
	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
		$_SERVER["REMOTE_ADDR"] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		$_SERVER["HTTP_CLIENT_IP"] = $_SERVER["HTTP_CF_CONNECTING_IP"];
	}
	$client  = @$_SERVER["HTTP_CLIENT_IP"];
	$forward = @$_SERVER["HTTP_X_FORWARDED_FOR"];
	$remote  = $_SERVER["REMOTE_ADDR"];

	if (filter_var($client, FILTER_VALIDATE_IP)) {
		$ip = $client;
	} elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
		$ip = $forward;
	} else {
		$ip = $remote;
	}

	return $ip;
}

$ip_addr = get_user_ip();
$user_agent = SQLite3::escapeString($_SERVER["HTTP_USER_AGENT"]);
$thispage = SQLite3::escapeString($_SERVER["REQUEST_URI"]);

if (isset($_SESSION["vlz"])) {
	$username = SQLite3::escapeString($_SESSION["vlz"]);
} else {
	$username = "no";
}

$conn->exec("INSERT INTO counter('ip', 'agent', 'page', 'username') VALUES ('$ip_addr', '$user_agent', '$thispage', '$username')");
