<?php
session_start();
require_once "../conn.php";
require_once "../counter.php";
if (!isset($_SESSION["vlz"])) {
	$conn->close();
	header("location:index.php");
	exit;
}

$current_user = $_SESSION["vlz"];

$e_current_user = SQLite3::escapeString($current_user);
$user = $conn->querySingle("SELECT * FROM users WHERE users.username = '$e_current_user' LIMIT 1", true);
if (isset($_POST["type"])) {
	$error = "not supported yet!";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Hello</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" />
	<link rel="stylesheet" href="/chat/style.css" />
</head>

<body class="bg-dark text-white">
	<div class="container" style="margin-top:30px">
		<h2 align="center" id="chat-name">Hello, <?php echo $user["name"]; ?></h2>
		<div class="chat-con bg-light">
			<a href="/chat/" class="" target="_blank">Go to public chat</a>
		</div>
		<div class="chat-con bg-light text-primary">
			<?php
			if (isset($error)) {
				echo "<span style='color: red;'>error: " . $error . "</span>";
			}
			?>
			<form method="post" autocomplete="off">
				<div class="input-group">
					<input type="text" name="username" placeholder="username" class="form-control" required="required" />
					<div class="input-group-append">
						<input type="hidden" name="type" value="username" />
						<input type="submit" value="chat" class="btn btn-primary" />
					</div>
				</div>
			</form>
		</div>
		<div class="chat-con bg-light">
			<a href="logout.php">Logout</a>
		</div>
		<div class="chat-con bg-light">
			<a href="delete.php">Delete Profile</a>
		</div>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>

</html>