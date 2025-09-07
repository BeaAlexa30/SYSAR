<?php
// Include database connection
include('database.php');

session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// PHPMailer setup (must be here, before any use)
require __DIR__ . '/PHPMailer/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/PHPMailer-master/src/SMTP.php';
require __DIR__ . '/PHPMailer/PHPMailer-master/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Initialize variables for alert messaging
$alertType = '';
$alertMessage = '';

// Handle archiving a resident by updating status = FALSE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['archive_res_id'])) {
    $res_id = $_POST['archive_res_id'];

    $sqlArchive = "UPDATE accepted_for_assistance SET status = FALSE WHERE res_id = $1";
    $archiveResult = pg_query_params($conn, $sqlArchive, [$res_id]);

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
            $mail->Subject = 'Your SK Barangay Assistance Has Been Archived';
            $mail->Body    = "
                <p>Dear $user_name,</p>
                <p>Your SK Barangay assistance has been <b>archived</b> as of " . date('F j, Y') . ".</p>
                <p>If you wish to apply again, please contact the SK Barangay office.</p>
                <br>
                <p>Thank you,<br>SK Barangay Admin</p>
            ";
            $mail->send();
        } catch (Exception $e) {
            error_log("Mailer Error: " . $mail->ErrorInfo);
        }
    }

    if ($archiveResult) {
        $alertType = 'success';
        $alertMessage = 'Resident archived successfully!';
    } else {
        $alertType = 'error';
        $alertMessage = 'Error: ' . pg_last_error($conn);
    }
}

// Fetch only active accepted residents (status = TRUE)
$sqlAccepted = "SELECT aa.id AS accepted_id, aa.res_id, sq.first_name, sq.last_name, aa.year_level, aa.cor_filename, aa.ccog_filename
                FROM accepted_for_assistance aa
                JOIN accepted_members am ON aa.res_id = am.res_id
                JOIN skmembers_queue sq ON am.members_id = sq.id
                WHERE aa.status = TRUE";
$resultAccepted = pg_query($conn, $sqlAccepted);

if (!$resultAccepted) {
    die("Error fetching accepted residents: " . pg_last_error($conn));
}

// Auto-archive expired assistance (created_at older than 1 year)
$expire_sql = "UPDATE accepted_for_assistance 
               SET status = FALSE 
               WHERE status = TRUE AND created_at IS NOT NULL AND created_at + INTERVAL '1 year' <= NOW()
               RETURNING res_id";
$expire_result = pg_query($conn, $expire_sql);

// Email users whose assistance just expired
if ($expire_result && pg_num_rows($expire_result) > 0) {
    while ($expired_row = pg_fetch_assoc($expire_result)) {
        $expired_res_id = $expired_row['res_id'];
        // Fetch user email and name
        $user_sql = "SELECT sq.email, sq.first_name 
                     FROM skmembers_queue sq
                     JOIN accepted_members am ON am.members_id = sq.id
                     WHERE am.res_id = $1";
        $user_result = pg_query_params($conn, $user_sql, [$expired_res_id]);
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
                $mail->Subject = 'Your SK Barangay Assistance Has Expired';
                $mail->Body    = "
                    <p>Dear $user_name,</p>
                    <p>Your SK Barangay assistance has <b>expired</b> after 1 year.</p>
                    <p>Please apply again if you wish to continue receiving assistance.</p>
                    <br>
                    <p>Thank you,<br>SK Barangay Admin</p>
                ";
                $mail->send();
            } catch (Exception $e) {
                error_log("Mailer Error: " . $mail->ErrorInfo);
                // Optionally, display for debugging (remove in production)
                echo "Mailer Error: " . $mail->ErrorInfo;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Accepted Residents</title>
    <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 100px;
            margin-left: 200px;
            transition: margin-left 0.3s;
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
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
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
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .archive-btn {
            background-color: #ff9800;
            color: white;
            border: none;
        }
        .archive-btn i {
            margin-right: 5px;
        }
        .search-container {
            position: relative;
            width: 30%;
            display: flex;
            align-items: center;
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
            height: 40px;
        }
    </style>
</head>

<?php include 'navbar.php'; ?>

<body>
<main>
    <div style="width: 80%; display: flex; justify-content: space-between; align-items: center;">
        <h1>Accepted Residents</h1>
        <div class="search-container">
            <i class="fa fa-search search-icon"></i>
            <input type="text" id="acceptedSearch" class="form-control text-center" placeholder="Search Resident Name or Year Level" />
        </div>
    </div>

    <table id="acceptedTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Resident Name</th>
                <th>Year Level</th>
                <th>COR</th>
                <th>Grades</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $counter = 0;
            if (pg_num_rows($resultAccepted) === 0): ?>
                <tr>
                    <td colspan="6" style="text-align:center; color: #888;">
                        No accepted residents found.
                    </td>
                </tr>
            <?php else:
                while ($row = pg_fetch_assoc($resultAccepted)): ?>
                <tr>
                    <td><?= ++$counter ?></td>
                    <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                    <td><?= htmlspecialchars($row['year_level']) ?></td>
                    <td>
                        <?php
                        $corFile = $row['cor_filename'];
                        if (strpos($corFile, 'uploads/') === 0) {
                            $corFile = substr($corFile, strlen('uploads/'));
                        }
                        ?>
                        <?php if (!empty($corFile)): ?>
                            <a href="uploads/<?= htmlspecialchars($corFile) ?>" target="_blank">View COR</a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php
                        $gradesFile = $row['ccog_filename'];
                        if (strpos($gradesFile, 'uploads/') === 0) {
                            $gradesFile = substr($gradesFile, strlen('uploads/'));
                        }
                        ?>
                        <?php if (!empty($gradesFile)): ?>
                            <a href="uploads/<?= htmlspecialchars($gradesFile) ?>" target="_blank">View Grades</a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="POST" class="archiveForm" action="">
                            <input type="hidden" name="archive_res_id" value="<?= htmlspecialchars($row['res_id']); ?>" />
                            <button type="submit" class="archive-btn">
                                <i class="fas fa-box-archive"></i> Archive
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; endif; ?>
        </tbody>
    </table>
</main>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    // Confirm archive on form submit
    document.querySelectorAll('.archiveForm').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to archive this resident?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff9800',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, archive it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // proceed with form submission
                }
            });
        });
    });

    <?php if ($alertType === 'success'): ?>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: <?= json_encode($alertMessage) ?>,
        confirmButtonColor: '#3085d6'
    }).then(() => {
        window.location.href = 'archive-assistance.php';
    });
    <?php elseif ($alertType === 'error'): ?>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: <?= json_encode($alertMessage) ?>,
        confirmButtonColor: '#d33'
    }).then(() => {
        window.location.href = 'archive-assistance.php';
    });
    <?php endif; ?>
</script>

</body>

<?php include 'footer.php'; ?>
</html>
