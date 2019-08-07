<?php

$conn = new mysqli("localhost", "root", "", "chat");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
