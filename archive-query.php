<?php
include('database.php');

// Validate database connection
if (!$conn) {
    die("Database connection error: " . pg_last_error());
}

if (isset($_GET['action']) && $_GET['action'] === 'archive' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "UPDATE skmembers_queue SET archive = 'Yes' WHERE id = $1";
    $result = pg_query_params($conn, $sql, [$id]);

    if ($result) {
        $_SESSION['message'] = "Resident successfully archived.";
        $_SESSION['alertType'] = "success";
    } else {
        $_SESSION['message'] = "Error archiving resident: " . pg_last_error($conn);
        $_SESSION['alertType'] = "danger";
    }

    header("Location: youth_manage.php");
    exit();
}
?>
