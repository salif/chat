<?php
session_start();
require_once "conn.php";
require_once "counter.php";
if (!isset($_SESSION["vlz"])) {
	$conn->close();
	header('location:login/');
	exit;
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Chat</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="author" content="Salif Mehmed" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" />
	<link rel="stylesheet" href="/chat/style.css" />
</head>

<body class="bg-dark text-white">
	<div class="container" style="margin-top:30px">
		<h2 align="center" id="chat-name"></h2>
		<div class="chat-con bg-light text-dark">
			<div id="chat-loader"></div>
			<ul id="chat-ul"></ul>
			<div class="chat-ig input-group">
				<input class="form-control" placeholder="message..." id="chat-input" />
				<div class="input-group-append">
					<input type="button" value="send" class="btn btn-primary" id="chat-button">
				</div>
			</div>
		</div>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="./script.js"></script>
</body>

</html>