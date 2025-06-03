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
            // Do NOT echo JS here anymore, we'll do it in the script below
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>
  <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
  />
  <!-- SweetAlert2 CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"
    rel="stylesheet"
  />

  <style>
    body {
      background: url('PICTURES/home_bg.jpg') no-repeat center center;
      background-size: cover;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      font-family: Arial, sans-serif;
      padding-top: 100px;
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
  </style>
</head>

<body>
  <!-- Navbar -->
  <?php include 'navbar.php'; ?>

  <main>
    <div class="card shadow">
      <h2 class="text-center">ADMIN | LOG IN</h2>

      <form method="POST" action="">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input
            type="text"
            class="form-control"
            id="username"
            name="username"
            required
          />
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input
            type="password"
            class="form-control"
            id="password"
            name="password"
            required
          />
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
    </div>
  </main>

  <?php include 'footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      <?php if (!empty($success)) : ?>
        Swal.fire({
          icon: "success",
          title: "Success",
          text: "<?php echo addslashes($success); ?>",
          timer: 1000,
          timerProgressBar: true,
          backdrop: `
            rgba(0,0,0,0.4)
            url('PICTURES/home_bg.jpg')
            no-repeat center
          `,
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
          showConfirmButton: false,
          didOpen: () => {
            const swalBackdrop = document.querySelector(".swal2-backdrop");
            if (swalBackdrop) {
              swalBackdrop.style.backdropFilter = "blur(8px)";
              swalBackdrop.style.webkitBackdropFilter = "blur(8px)";
              swalBackdrop.style.backgroundSize = "contain"; // <-- Prevent zoom in
            }
          },
          didClose: () => {
            window.location.href = "dashboard.php";
          },
        });
      <?php elseif (!empty($error)) : ?>
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "<?php echo addslashes($error); ?>",
          confirmButtonText: "OK",
        });
      <?php endif; ?>
    });
  </script>
</body>
</html>
