<?php
session_start();
require_once "conn.php";
header("Content-Type: application/json; charset=utf-8");
$retr = ["msgs" => array(), "iserr" => 0, "err" => ""];

if (!isset($_SESSION["vlz"])) {
	$retr["iserr"] = 1;
	$retr["err"] = "not logged in!";
} elseif (!isset($_POST["command"]) || !isset($_POST["data"])) {
	$retr["iserr"] = 1;
	$retr["err"] = "not provided command or data!";
} else {
	$cmd = $_POST["command"];
	$data = $_POST["data"];
	$current_user = $_SESSION["vlz"];

	if ($cmd == "load") {
		$e_data = SQLite3::escapeString($data);
		$result = $conn->query("SELECT * FROM chat WHERE chat.id > $e_data");
		$msgs = array();
		while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
			if ($row["username"] === $current_user) {
				$row["username"] = "M";
			}
			array_push($msgs, $row);
		}
		$retr["msgs"] = $msgs;
	} elseif ($cmd == "send") {
		$e_user = SQLite3::escapeString($_SESSION["vlz"]);
		$e_text = SQLite3::escapeString(trim($data));
		if (!$conn->exec("INSERT INTO chat('text', 'username') VALUES ('$e_text', '$e_user')")) {
			$retr["iserr"] = 1;
			$retr["err"] = array($conn->lastErrorMsg());
		}
	} else {
		$retr["iserr"] = 1;
		$retr["err"] = "invalid command!";
	}
}
echo json_encode($retr);
$conn->close();
