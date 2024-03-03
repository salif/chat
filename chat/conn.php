<?php
$conn = new SQLite3(__DIR__ . '/db/main.db');
if (!$conn) {
    die("Connection failed: " . $conn->lastErrorMsg());
}
