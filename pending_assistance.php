<?php
// Include database connection and start session
include('database.php');
session_start();

// PHPMailer setup (must be at the top, outside any function or if-block)
require __DIR__ . '/PHPMailer/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/PHPMailer-master/src/SMTP.php';
require __DIR__ . '/PHPMailer/PHPMailer-master/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Redirect to login if the user is not authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Handle POST request to accept assistance
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accept_res_id'], $_POST['year_level'])) {
    $res_id = htmlspecialchars($_POST['accept_res_id']);
    $year_level = htmlspecialchars($_POST['year_level']);

    if (!empty($res_id) && !empty($year_level)) {
        // Fetch cor_filename and ccog_filename from assistance_req
        $fetch_files = "SELECT cor_filename, ccog_filename FROM assistance_req WHERE res_id = $1";
        $file_result = pg_query_params($conn, $fetch_files, [$res_id]);
        $file_row = pg_fetch_assoc($file_result);

        // Insert into accepted_for_assistance with cor and grades
        $query = "INSERT INTO accepted_for_assistance (res_id, year_level, cor_filename, ccog_filename) VALUES ($1, $2, $3, $4)";
        $result = pg_query_params($conn, $query, [
            $res_id,
            $year_level,
            $file_row['cor_filename'],
            $file_row['ccog_filename']
        ]);

        if ($result) {
            // Fetch user email and name
            $user_sql = "SELECT sq.email, sq.first_name
                         FROM skmembers_queue sq
                         JOIN accepted_members am ON am.members_id = sq.id
                         WHERE am.res_id = $1";
            $user_result = pg_query_params($conn, $user_sql, [$res_id]);
            if ($user_info = pg_fetch_assoc($user_result)) {
                $user_email = $user_info['email'];
                $user_name = $user_info['first_name'];

                $expiry_date = date('F j, Y', strtotime('+1 year'));

                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'bmajck00@gmail.com';
                    $mail->Password   = 'psrk suml kthe lxak'; // your app password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    $mail->setFrom('bmajck00@gmail.com', 'SK Barangay 252');
                    $mail->addAddress($user_email, $user_name);
                    $mail->isHTML(true);
                    $mail->Subject = 'Your Educational Assistance Request Was Approved';
                    $mail->Body    = "
                        <p>Dear $user_name,</p>
                        <p>Your educational assistance request has been <b>approved</b>.</p>
                        <p>Your assistance is valid until: <b>$expiry_date</b></p>
                        <br>
                        <p>Thank you,<br>SK Barangay Admin</p>
                    ";
                    $mail->send();
                } catch (Exception $e) {
                    error_log("Mailer Error: " . $mail->ErrorInfo);
                }
            }
            echo "success";
        } else {
            echo "Database error: " . pg_last_error($conn);
        }
    } else {
        echo "Invalid input.";
    }
    pg_close($conn);
    exit;
}

// Handle POST request to decline assistance
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['decline_res_id'])) {
    $res_id = $_POST['decline_res_id'];

    // Fetch year_level, ccog_filename, cor_filename from assistance_req
    $fetch_files = "SELECT year_level, ccog_filename, cor_filename FROM assistance_req WHERE res_id = $1";
    $file_result = pg_query_params($conn, $fetch_files, [$res_id]);
    $file_row = pg_fetch_assoc($file_result);

    // Insert into declined_assistance
    $insert_sql = "INSERT INTO declined_assistance (res_id, year_level, status, created_at, ccog_filename, cor_filename)
                   VALUES ($1, $2, TRUE, NOW(), $3, $4)";
    pg_query_params($conn, $insert_sql, [
        $res_id,
        $file_row['year_level'],
        $file_row['ccog_filename'],
        $file_row['cor_filename']
    ]);

    // Optionally, delete from assistance_req
    $delete_sql = "DELETE FROM assistance_req WHERE res_id = $1";
    pg_query_params($conn, $delete_sql, [$res_id]);

    // Fetch user email and name
    $user_sql = "SELECT sq.email, sq.first_name
                 FROM skmembers_queue sq
                 JOIN accepted_members am ON am.members_id = sq.id
                 WHERE am.res_id = $1";
    $user_result = pg_query_params($conn, $user_sql, [$res_id]);
    if ($user_info = pg_fetch_assoc($user_result)) {
        $user_email = $user_info['email'];
        $user_name = $user_info['first_name'];

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'bmajck00@gmail.com';
            $mail->Password   = 'psrk suml kthe lxak'; // your app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('bmajck00@gmail.com', 'SK Barangay 252');
            $mail->addAddress($user_email, $user_name);
            $mail->isHTML(true);
            $mail->Subject = 'Your Educational Assistance Request Was Declined';
            $mail->Body    = "
                <p>Dear $user_name,</p>
                <p>We regret to inform you that your educational assistance request has been <b>declined</b>.</p>
                <p>If you have questions or wish to re-apply, please contact the SK Barangay office.</p>
                <br>
                <p>Thank you,<br>SK Barangay Admin</p>
            ";
            $mail->send();
        } catch (Exception $e) {
            // Optionally log or handle the error
        }
    }

    echo "declined";
    exit;
}

// Fetch data for the table
$query = "SELECT ar.id, ar.res_id, sq.first_name, sq.last_name, ar.year_level, ar.ccog_filename, ar.cor_filename 
          FROM assistance_req ar
          JOIN accepted_members am ON ar.res_id = am.res_id
          JOIN skmembers_queue sq ON am.members_id = sq.id
          WHERE ar.res_id NOT IN (SELECT res_id FROM accepted_for_assistance)";
$result = pg_query($conn, $query);

if (!$result) {
    die("Database error: " . pg_last_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Assistance Requests</title>
    <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Styles for the page */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 100px;
            margin-left: 200px;
        }
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 {
            margin-left: 5px;
            font-size: 24px;
            color: #00205b;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #00205b;
            color: white;
        }
        button {
            padding: 8px 15px;
            margin: 5px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }
        .accept-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
        }
        iframe {
            width: 90%; /* Match the table width */
            height: 600px; /* Adjust height for better visibility */
            margin: 20px auto; /* Center the iframe below the table */
            border: 1px solid #ddd; /* Add a border for better visibility */
            border-radius: 8px; /* Match the table's border radius */
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); /* Add a shadow for consistency */
            display: none;
        }
        .search-container {
            margin-bottom: 15px;
            position: relative;
            width: 27%;
        }
        .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
            color: gray;
        }
        #acceptedSearch {
            padding-left: 35px;
        }

        /* Center content inside the iframe */
        iframe {
            display: block;
            text-align: center; /* Center content horizontally */
        }

        /* Ensure the image inside the iframe is centered */
        iframe img {
            display: block;
            margin: auto; /* Center the image inside the iframe */
        }
    </style>
</head>

<body>
<?php include 'navbar.php'; ?>
<main>
    <div style="width: 90%; display: flex; justify-content: space-between; align-items: center;">
        <h1>Pending Educational Assistance Requests</h1>
        <div class="search-container">
            <i class="fa fa-magnifying-glass search-icon"></i>
            <input type="text" id="acceptedSearch" class="form-control text-center" placeholder="Search Resident Name or Year Level" style="height: 40px;">
        </div>
    </div>

    <table id="acceptedTable">
        <thead>
            <tr>
                <th>Resident Name</th>
                <th>Year Level</th>
                <th>Grades</th>
                <th>COR</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (pg_num_rows($result) > 0) { ?>
                <?php while ($row = pg_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                    <td><?= htmlspecialchars($row['year_level']) ?></td>
                    <td>
                        <button class="btn btn-primary" onclick="showDocument('<?= htmlspecialchars($row['ccog_filename']) ?>')">Show Grades</button>
                    </td>
                    <td>
                        <button class="btn btn-primary" onclick="showDocument('<?= htmlspecialchars($row['cor_filename']) ?>')">Show COR</button>
                    </td>
                    <td>
                        <button class="accept-btn"
                            data-res-id="<?= htmlspecialchars($row['res_id']) ?>"
                            data-year-level="<?= htmlspecialchars($row['year_level']) ?>">
                            Accept
                        </button>
                        <button class="decline-btn btn btn-danger"
                            data-res-id="<?= htmlspecialchars($row['res_id']) ?>">
                            Decline
                        </button>
                    </td>
                </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="5" style="text-align:center; color: #888;">No pending assistance requests found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</main>

<iframe id="documentViewer" style="display: none;"></iframe>

<script>
    function showDocument(filePath) {
        const viewer = document.getElementById('documentViewer');
        viewer.src = filePath;
        viewer.style.display = 'block'; // Show the iframe
        viewer.scrollIntoView({ behavior: 'smooth' }); // Scroll to the iframe
    }

    document.querySelectorAll('.accept-btn').forEach(button => {
        button.addEventListener('click', function () {
            const resId = this.getAttribute('data-res-id');
            const yearLevel = this.getAttribute('data-year-level');

            Swal.fire({
                title: 'Accept Request?',
                text: "Are you sure you want to accept this request?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, accept it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `accept_res_id=${encodeURIComponent(resId)}&year_level=${encodeURIComponent(yearLevel)}`
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data.includes("success")) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Accepted!',
                                text: 'Assistance request has been accepted.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = 'accepted_assistance.php';
                            });
                        } else {
                            throw new Error(data);
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong: ' + error.message
                        });
                    });
                }
            });
        });
    });

    document.querySelectorAll('.decline-btn').forEach(button => {
        button.addEventListener('click', function () {
            const resId = this.getAttribute('data-res-id');

            Swal.fire({
                title: 'Decline Request?',
                text: "Are you sure you want to decline this request?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, decline it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `decline_res_id=${encodeURIComponent(resId)}`
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data.includes("declined")) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Declined!',
                                text: 'Assistance request has been declined.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            throw new Error(data);
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong: ' + error.message
                        });
                    });
                }
            });
        });
    });
</script>

<?php include 'footer.php'; ?>
</body>
</html>
