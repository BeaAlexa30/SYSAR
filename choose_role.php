<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['role'] === 'admin') {
        unset($_SESSION['role']); // Remove any previous role
        header('Location: login.php'); // Go to login page
        exit;
    } elseif ($_POST['role'] === 'youth') {
        $_SESSION['role'] = 'youth';
        header('Location: home.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Choose Role</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('PICTURES/home_bg.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            padding: 60px 40px;
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            background: rgba(248, 249, 250, 0.85);
            backdrop-filter: blur(4px);
            max-width: 600px;
            width: 100%;
        }
        .btn {
            font-size: 1.3rem;
            padding: 18px 0;
        }
        h2 {
            font-size: 2.2rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="card shadow">
        <h2 class="mb-4 text-center">Choose Your Role</h2>
        <form method="post">
            <button type="submit" name="role" value="admin" class="btn btn-primary w-100 mb-4">Super Admin</button>
            <button type="submit" name="role" value="youth" class="btn btn-success w-100">Youth</button>
        </form>
    </div>
</body>
</html>