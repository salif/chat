<?php
$conn = new SQLite3(__DIR__ . "/db/main.db", SQLITE3_OPEN_READWRITE);
if (!$conn) {
    die("Connection failed: " . $conn->lastErrorMsg());
}
