<?php
session_start();
require "conn.php";

$current_user="";

if(isset($_SESSION['vlz'])) {
	$current_user = $_SESSION['vlz'];
}

if(!isset($_POST["q"]) || !isset($_POST["data"])) {
	$conn->close();
	exit;
}

$q = $_POST["q"];
$data = $_POST["data"];
$retr = ["data"=>array(), "id"=>0];

if($q == "load") {
	$data = mysqli_real_escape_string($conn, $data);
	$sql = "SELECT * FROM `chat` WHERE `id` > $data";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$temparray = array();
		while($row = $result->fetch_assoc()) {
			$row["me"] = ($row["username"] === $current_user);
			array_push($temparray, $row);
			$data = $row["id"];
		}
		$retr["data"] = $temparray;
		$retr["id"] = $data;
	} else {
		$retr["data"] = array();
		$retr["id"] = $data;
	}
} elseif ($q == "send") {
	$chat_user = mysqli_real_escape_string($conn, $_SESSION['vlz']);
	$chat_text = mysqli_real_escape_string($conn, trim($data));
	$sql = "INSERT INTO `chat`(`text`, `username`) VALUES ('$chat_text', '$chat_user')";
	if($conn->query($sql)) {
		$retr["data"] = array('ok');
		$retr["id"] = 0;
	} else {
		$retr["data"] = array($conn->error);
		$retr["id"] = 0;
	}
}

$conn->close();

header("Content-Type: application/json");
echo json_encode($retr);

?>
