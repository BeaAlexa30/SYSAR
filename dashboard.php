<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Display login success message if available
if (isset($_SESSION['login_message'])) {
    echo "<script>alert('" . $_SESSION['login_message'] . "');</script>";
    unset($_SESSION['login_message']); // Clear the session message after showing
}

// Include the database connection file
include 'database.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('PICTURES/home_bg.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 80px; /* Adjust this for your navbar height */
            margin-left: 200px; /* Sidebar width when open */
            transition: margin-left 0.3s;
        }
        .sidebar.closed + body {
            margin-left: 0;
        }
        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 1rem 0;
            position: relative;
            bottom: 0;
            width: 100%;
        }
        .card-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }
        .card {
            width: 300px;
            height: 150px;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            align-items: start;
            justify-content: center;
            padding-left: 20px;
            font-weight: bold;
            color: white;
            font-size: 18px;
            text-decoration: none;
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .youth { background-color: #d50000; } /* Red */
        .document { background-color: #008000; } /* Green */
        .education { background-color: #0033cc; } /* Blue */
        .card-number {
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main>
        <div class="container mt-5">
            <h1 class="text-center" style="color: black;"><b>Welcome, <?php echo $_SESSION['username']; ?>!</b></h1>

            <!-- Updated Cards for different options -->
            <div class="card-container">
                <a href="youth_manage.php" class="card youth">
                    <span class="card-number"><?php echo $youthCount; ?></span>
                    <span>YOUTH MANAGE</span>
                </a>

                <a href="doc_to_print.php" class="card document">
                    <span class="card-number"><?php echo $documentCount; ?></span>
                    <span>DOCUMENT TO PRINT</span>
                </a>

                <a href="accepted_assistance.php" class="card education">
                    <span class="card-number"><?php echo $educationCount; ?></span>
                    <span>EDUCATIONAL ASSISTANCE</span>
                </a>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

</body>
</html>

