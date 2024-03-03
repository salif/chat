<?php
session_start();
require_once "../conn.php";
require_once "../counter.php";
require_once "../func.php";
if (isset($_SESSION["vlz"])) {
	$conn->close();
	header("location:user.php");
	exit;
}

$is_signup = false;

if (isset($_POST["type"])) {
	if ($_POST["type"] === "login") {
		if (isset($_POST["username"]) && isset($_POST["newpassword"])) {
			$username = trim($_POST["username"]);
			$password = $_POST["newpassword"];
			if (r_is_bad($username, 3, 32)) {
				$error = "the length of username should be between 3 and 32!";
			} elseif (r_is_bad($password, 3, 256)) {
				$error = "the length of password should be between 3 and 256!";
			} else {
				$e_username = SQLite3::escapeString($username);
				$e_hpassword = SQLite3::escapeString(hash_p($password));
				$result_user = $conn->querySingle("SELECT * FROM users WHERE users.password = '$e_hpassword' AND users.username = '$e_username'");
				if (!is_null($result_user)) {
					$_SESSION["vlz"] = $username;
					$conn->close();
					header("location:user.php");
					exit;
				} else {
					$error = "incorrect username or password!";
				}
			}
		} else {
			$error = "something is empty!";
		}
	} elseif ($_POST["type"] === "signup") {
		$is_signup = true;
		if (isset($_POST["username"]) && isset($_POST["displayname"]) && isset($_POST["newpassword"])) {
			$username = trim($_POST["username"]);
			$displayname = trim($_POST["displayname"]);
			$password = $_POST["newpassword"];
			if (r_is_bad($username, 3, 32)) {
				$error = "the length of username should be between 3 and 32!";
			} elseif (r_is_bad($displayname, 2, 32)) {
				$error = "the length of display name should be between 1 and 32!";
			} elseif (r_is_bad($password, 3, 256)) {
				$error = "the length of password should be between 3 and 256!";
			} else {
				$e_username = SQLite3::escapeString($username);
				$result_user = $conn->querySingle("SELECT * FROM users WHERE users.username = '$e_username' LIMIT 1");
				if (!is_null($result_user)) {
					$error = "sorry, username is taken!";
				} else {
					$password = hash_p($password);
					if ($conn->exec("INSERT INTO users('name', 'username', 'password') VALUES ('$displayname','$username','$password')")) {
						$_SESSION["vlz"] = $username;
						$conn->close();
						header("location:user.php");
						exit;
					} else {
						$error = "error!";
					}
				}
			}
		} else {
			$error = "something is empty!";
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
	<title>Login or Sign up</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" />
	<link rel="stylesheet" href="/chat/style.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</head>

<body class="bg-dark text-white">
	<div class="container" style="margin-top:30px">
		<?php if ($is_signup) : ?>
			<h2 align="center" id="chat-name">Sign up</h2>
		<?php else : ?>
			<h2 align="center" id="chat-name">Login</h2>
		<?php endif ?>
		<div class="chat-con bg-light">
			<?php if (isset($error)) : ?>
				<span style="color: red" id="errorm">Error: <?php echo $error; ?></span>
			<?php else : ?>
				<span class="d-none" id="errorm"></span>
			<?php endif ?>
			<form method="post" id="form_login" class="<?php if ($is_signup) : ?>d-none<?php endif ?>">
				<input type="text" name="username" class="form-control" placeholder="Username" required="required" />
				<input type="password" id="login_password" class="form-control" placeholder="Password" />
				<input type="password" id="login_new_password" name="newpassword" class="form-control d-none" readonly="readonly" required="required" />
				<input type="button" id="login_continue" value="continue" onclick="javascript:set_login_password();" class="btn btn-primary" style="width:100%;" />
				<input type="hidden" name="type" value="login" required="required" />
				<input type="submit" id="login_submit" value="Login" class="btn btn-primary d-none" style="width:100%;" />
				&nbsp;&nbsp;<a href="javascript:show_signup();" class="mylink">Sign up</a>
			</form>
			<form method="post" id="form_signup" class="<?php if (!$is_signup) : ?>d-none<?php endif ?>" autocomplete="off">
				<input type="text" name="username" class="form-control" placeholder="Username" required="required" />
				<input type="text" name="displayname" class="form-control" placeholder="Display name" required="required" />
				<input type="password" id="signup_password" class="form-control" placeholder="Password" />
				<input type="password" id="signup_c_password" class="form-control" placeholder="Repeat password" />
				<input type="password" id="signup_new_password" name="newpassword" class="form-control d-none" readonly="readonly" required="required" />
				<input type="button" id="signup_continue" value="continue" onclick="javascript:set_signup_password();" class="btn btn-primary" style="width:100%;" />
				<input type="hidden" name="type" value="signup" required="required" />
				<input type="submit" id="signup_submit" value="Sign up" class="btn btn-primary d-none" style="width:100%;" />
				&nbsp;&nbsp;<a href="javascript:show_login();" class="mylink">Login</a>
			</form>
		</div>
	</div>
	<script>
		async function hash_p(source) {
			const sourceBytes = new TextEncoder().encode(source + "fwyUPsTYoP7MQefX5ZUvoErVY3AWwVct");
			const digest = await crypto.subtle.digest("SHA-256", sourceBytes);
			const resultBytes = [...new Uint8Array(digest)];
			return resultBytes.map(x => x.toString(16).padStart(2, "0")).join("");
		}

		function show_signup() {
			var el_new_pass = document.querySelector("#login_new_password")
			el_new_pass.value = "";
			el_new_pass.classList.add("d-none");
			var el_pass = document.querySelector("#login_password")
			el_pass.value = "";
			el_pass.classList.remove("d-none");
			document.querySelector("#login_continue").classList.remove("d-none");
			document.querySelector("#login_submit").classList.add("d-none");
			document.querySelector("#form_login").classList.add("d-none");

			document.querySelector("#form_signup").classList.remove("d-none");
			document.querySelector("#errorm").classList.add("d-none");
			document.querySelector("#chat-name").textContent = "Sign up";
		}

		function show_login() {
			var el_new_pass = document.querySelector("#signup_new_password");
			el_new_pass.value = "";
			el_new_pass.classList.add("d-none");
			var el_pass = document.querySelector("#signup_password");
			el_pass.value = "";
			el_pass.classList.remove("d-none");
			var el_c_pass = document.querySelector("#signup_c_password");
			el_c_pass.value = "";
			el_c_pass.classList.remove("d-none");
			document.querySelector("#signup_continue").classList.remove("d-none");
			document.querySelector("#signup_submit").classList.add("d-none");
			document.querySelector("#form_signup").classList.add("d-none");

			document.querySelector("#form_login").classList.remove("d-none");
			document.querySelector("#errorm").classList.add("d-none");
			document.querySelector("#chat-name").textContent = "Login";
		}

		async function set_signup_password() {
			document.querySelector("#signup_new_password").classList.remove("d-none");
			var el_pass = document.querySelector("#signup_password")
			var el_c_pass = document.querySelector("#signup_c_password")
			el_pass.classList.add("d-none");
			el_c_pass.classList.add("d-none");
			document.querySelector("#signup_continue").classList.add("d-none");
			document.querySelector("#signup_new_password").value = await hash_p(el_pass.value);
			document.querySelector("#signup_submit").classList.remove("d-none");
		}
		async function set_login_password() {
			document.querySelector("#login_new_password").classList.remove("d-none");
			var el_pass = document.querySelector("#login_password")
			el_pass.classList.add("d-none");
			document.querySelector("#login_continue").classList.add("d-none");
			document.querySelector("#login_new_password").value = await hash_p(el_pass.value);
			document.querySelector("#login_submit").classList.remove("d-none");
		}
	</script>
</body>

</html>