<?php
include('database.php');

if (isset($_GET['action']) && $_GET['action'] === 'archive' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "UPDATE skmembers_queue SET archive = 'Yes' WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "Resident successfully archived.";
            $_SESSION['alertType'] = "success";
        } else {
            $_SESSION['message'] = "Error archiving resident: " . mysqli_stmt_error($stmt);
            $_SESSION['alertType'] = "danger";
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['message'] = "Error preparing archive statement.";
        $_SESSION['alertType'] = "danger";
    }

    header("Location: youth_manage.php");
    exit();
}
?>
