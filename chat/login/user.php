<?php
session_start();
require "../conn.php";
require "../counter.php";
if(!isset($_SESSION['vlz'])){
	header('location:index.php');
	exit;
}

$current_user = $_SESSION['vlz'];

$sql_current_user = mysqli_real_escape_string($conn, $current_user);
$sql = "select * from `users` where `username` = '$sql_current_user' limit 1";
$result = $conn->query($sql);
$user_row = $result->fetch_assoc();

if(isset($_POST["type"])) {
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
		<h2 align="center" id="chat-name">Hello, <?php echo $current_user; ?></h2>
		<div class="chat-con bg-light">
			<a href="/chat/" class="" target="_blank">Go to public chat</a>
		</div>
		<div class="chat-con bg-light text-primary">
			<?php
			if(isset($error)) {
				echo "<span style='color: red;'>error: ".$error."</span>";
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
