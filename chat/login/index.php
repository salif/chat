<?php
session_start();
require "../conn.php";
require "../counter.php";
if(isset($_SESSION['vlz'])){
	header('location:user.php');
	exit;
}

if(isset($_POST["type"])) {
	if($_POST["type"] === "login") {
		if(isset($_POST["username"]) && isset($_POST["password"])) {
			$username = trim($_POST["username"]);
			$password = $_POST["password"];
			if(strlen($username) < 3 || strlen($username) > 32) {
				$error = "the length of username should be between 3 and 32!";
			} elseif(strlen($password) < 8 || strlen($password) > 128) {
				$error = "the length of password should be between 8 and 128!";
			} else {
				$username = mysqli_real_escape_string($conn, $username);
				$password = hash('sha256', "Simple0Salt4".$password."End4Salt");
				$login_sql = "select * from `users` where `password` = '$password' and `username` = '$username' limit 1";
				$result = $conn->query($login_sql);
				if($result->num_rows == 1) {
					$_SESSION['vlz']=$username;
					$conn->close();
					header("location:user.php");
				} else {
					$error = "incorrect username or password!";
				}
			}
		} else {
			$error = "some field is empty!";
		}
	} elseif($_POST["type"] === "signup") {
		if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["repassword"])) {
			$username = trim($_POST["username"]);
			$password = $_POST["password"];
			$repassword = $_POST["repassword"];
			if(strlen($username) < 3 || strlen($username) > 32) {
				$error = "the length of username should be between 3 and 32!";
			} elseif(strlen($password) < 8 || strlen($password) > 128) {
				$error = "the length of password should be between 8 and 128!";
			} elseif($password !== $repassword) {
				$error = "the passwords don't match!";
			} else {
				$username = mysqli_real_escape_string($conn, $username);
				$checkforexists_sql = "select * from `users` where `username` = '$username'";
				$result = $conn->query($checkforexists_sql);
				if($result->num_rows > 0) {
					$error = "sorry, username is taken!";
				} else {
					$password = hash('sha256', "Simple0Salt4".$password."End4Salt");
					$signup_sql = "insert into `users`(`username`, `password`, `perm`) VALUES ('$username','$password',100)";
					if($conn->query($signup_sql)) {
						$_SESSION['vlz']=$username;
						$conn->close();
						header("location:user.php");
					} else {
						$error = "error!";
					}
				}
			}
		} else {
			$error = "some field is empty!";
		}
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
	<title>Login</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" />
	<link rel="stylesheet" href="/chat/style.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</head>
<body class="bg-dark text-white">
	<div class="container" style="margin-top:30px">
		<h2 align="center" id="chat-name">Login</h2>
		<div class="chat-con bg-light">
			<?php 
			if(isset($error)) {
				echo "<span style='color: red;'>error: ".$error."</span>";
			}
			?>
			<form method="post" id="loginform">
				<input type="text" name="username" class="form-control" placeholder="username" required="required" />
				<input type="password" name="password" class="form-control" placeholder="password" required="required" />
				<input type="hidden" name="type" value="login" />
				<input type="submit" value="login" class="btn btn-primary" style="width:100%;" />
				&nbsp;&nbsp;<a href="javascript:signup();" class="mylink">sign up</a>
			</form>
			<form method="post" id="signupform" style="display:none;" autocomplete="off">
				<input type="text" name="username" class="form-control" placeholder="username" required="required" autocomplete="new-password" />
				<input type="password" name="password" class="form-control" placeholder="password" required="required" autocomplete="new-password" />
				<input type="password" name="repassword" class="form-control" placeholder="repeat password" required="required" autocomplete="new-password" />
				<input type="hidden" name="type" value="signup" />
				<input type="submit" value="signup" class="btn btn-primary" style="width:100%;" />
				&nbsp;&nbsp;<a href="javascript:login();" class="mylink">login</a>
			</form>
		</div>
	</div>
	<script>
		function signup() {
			$("#loginform").hide();
			$("#signupform").show();
			$("#chat-name").text("Sign up");
		}

		function login() {
			$("#signupform").hide();
			$("#loginform").show();
			$("#chat-name").text("Login");
		}
	</script>
</body>
</html>
