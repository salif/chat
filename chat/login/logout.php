<?php
session_start();
require "../conn.php";
require "../counter.php";
$conn->close();
unset($_SESSION['vlz']);
session_destroy();
header('location:index.php');
?>
