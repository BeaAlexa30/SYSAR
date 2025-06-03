<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include('database.php');

// Modify the phone number columns to VARCHAR
$alter_queries = [
    "ALTER TABLE skmembers_queue MODIFY contact_num1 VARCHAR(20)",
    "ALTER TABLE skmembers_queue MODIFY contact_num2 VARCHAR(20)",
    "ALTER TABLE skmembers_queue MODIFY cp_contactnum VARCHAR(20)",
    "ALTER TABLE skmembers_queue MODIFY cp_telephonenum VARCHAR(20)"
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
    echo "Success! The phone number columns have been modified to VARCHAR(20).";
} else {
    echo "Error occurred: " . implode("<br>", $messages);
}

$conn->close();
?> 