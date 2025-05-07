<?php
session_start();
include 'database.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (!empty($username) && !empty($password)) {
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) === 1) {
            $_SESSION['username'] = $username;
            $success = "You logged in successfully.";
            echo "<script>
                setTimeout(() => document.getElementById('whiteOverlay').classList.add('fade-in'), 500); 
                setTimeout(() => showAlert('$success', 'alert-success', true), 500); 
                setTimeout(() => window.location.href = 'dashboard.php', 2000); 
            </script>";
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background: url('PICTURES/home_bg.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            padding-top: 100px
        }
        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        .card {
            max-width: 400px;
            width: 100%;
            background-color: rgba(248, 249, 250, 0.95);
            backdrop-filter: blur(5px);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
        }
        /* Floating Alert */
        .alert-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            display: none;
            width: 350px;
            text-align: center;
        }
        .alert {
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border-radius: 10px;
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3);
            opacity: 0;
            transform: translateY(-20px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 5px solid #28a745;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 5px solid #dc3545;
        }
        .show {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
        
        /* White Overlay */
        #whiteOverlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0);
            z-index: 9999;
            opacity: 0;
            backdrop-filter: blur(10px);
            transition: opacity 1s ease-in-out, backdrop-filter 1s ease-in-out;
        }
        #whiteOverlay.fade-in {
            display: block;
            opacity: 1;
            backdrop-filter: blur(60px);
        }
    </style>
</head>

<body>

<!-- Navbar -->
<?php include 'navbar.php'; ?>

<main>
    <!-- White Overlay -->
    <div id="whiteOverlay"></div>

    <!-- Floating Alert -->
    <div id="alertContainer" class="alert-container">
        <div id="alertMessage" class="alert">
            <i id="alertIcon" class=""></i>
            <span id="alertText"></span>
        </div>
    </div>

        <div class="card shadow">
            <h2 class="text-center">ADMIN | LOG IN</h2>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </main>
    
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let alertContainer = document.getElementById("alertContainer");
            let alertMessage = document.getElementById("alertMessage");
            let alertText = document.getElementById("alertText");
            let alertIcon = document.getElementById("alertIcon");
            let whiteOverlay = document.getElementById("whiteOverlay");

            function showAlert(message, type, isWhiteBackground = false) {
                alertContainer.style.display = "block";
                alertMessage.className = "alert " + type;
                alertText.innerHTML = message;
                alertIcon.className = type === "alert-success" ? "bi bi-check-circle-fill" : "bi bi-x-circle-fill";

                if (isWhiteBackground) {
                    whiteOverlay.classList.add("fade-in");
                }

                setTimeout(() => {
                    alertMessage.classList.add("show");
                }, 300);

                setTimeout(() => {
                    alertMessage.classList.remove("show");
                    setTimeout(() => alertContainer.style.display = "none", 300);
                }, 2500);
            }

            <?php if (!empty($success)) : ?>
                setTimeout(() => showAlert("<?php echo $success; ?>", "alert-success", true), 500);
            <?php endif; ?>

            <?php if (!empty($error)) : ?>
                showAlert("<?php echo $error; ?>", "alert-danger");
            <?php endif; ?>
        });
    </script>
</body>
</html>
