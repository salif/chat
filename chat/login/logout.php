<?php
session_start();
require_once "../conn.php";
require_once "../counter.php";
unset($_SESSION["vlz"]);
session_destroy();
$conn->close();
header("location:index.php");
exit;
