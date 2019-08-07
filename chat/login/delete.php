<?php
session_start();
require "../conn.php";
require "../counter.php";

if(!isset($_SESSION['vlz'])){
	header('location:index.php');
	exit;
}

$current_user = $_SESSION['vlz'];

if(isset($_POST["yesorno"])) {
	$yn = $_POST["yesorno"];
	if($yn === "yes") {
		$sql_current_user = mysqli_real_escape_string($conn, $current_user);
		$sql_delete_chat = "DELETE FROM `chat` WHERE `username` = '$sql_current_user'";
		$conn->query($sql_delete_chat);
		$sql_delete_user = "DELETE FROM `users` WHERE `username` = '$sql_current_user'";
		$conn->query($sql_delete_user);
		$conn->close();
		header('location:logout.php');
	} else {
		header('location:user.php');
	}
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Delete profile?</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" />
	<link rel="stylesheet" href="/chat/style.css" />
</head>
<body class="bg-dark text-white">
	<div class="container" style="margin-top:30px">
		<h2 align="center" id="chat-name">Delete profile?</h2>
		<div class="chat-con bg-light text-dark">
			<form method="post">
				If you delete your profile all your mesages will be deleted, this can't be undone!
				<br />
				<b>Are you sure:</b>
				<br />
				<input type="submit" name="yesorno" value="no" class="btn btn-primary" />
				<input type="submit" name="yesorno" value="yes" class="btn btn-danger" />
			</form>
		</div>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>
</html>
