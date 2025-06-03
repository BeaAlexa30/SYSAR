<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include('database.php');

// Add the missing columns if they don't exist
$alter_queries = [
    "ALTER TABLE skmembers_queue ADD COLUMN IF NOT EXISTS religion VARCHAR(50) DEFAULT NULL AFTER nationality",
    "ALTER TABLE skmembers_queue ADD COLUMN IF NOT EXISTS cp_telephone VARCHAR(20) DEFAULT NULL AFTER cp_contactnum"
];

$success = true;
$messages = [];

foreach ($alter_queries as $query) {
    if (!$conn->query($query)) {
        $success = false;
        $messages[] = "Error executing query: " . $conn->error;
    }
}

if ($success) {
    echo "Success! The required columns have been added to the database.";
} else {
    echo "Error occurred: " . implode("<br>", $messages);
}

$conn->close();
?> 