<?php
session_start();
include('database.php');

// PHPMailer setup (at the top)
require __DIR__ . '/PHPMailer/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/PHPMailer-master/src/SMTP.php';
require __DIR__ . '/PHPMailer/PHPMailer-master/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $res_id = $_POST['res_id'];
    $year_level = $_POST['year_level'];

    // Check if res_id exists in accepted_members table
    $check_sql = "SELECT * FROM accepted_members WHERE res_id = $1";
    $check_result = pg_query_params($conn, $check_sql, [$res_id]);

    if (pg_num_rows($check_result) > 0) {
        // Check for duplicate entries in assistance_req table
        $duplicate_check_sql = "SELECT * FROM assistance_req WHERE res_id = $1";
        $duplicate_check_result = pg_query_params($conn, $duplicate_check_sql, [$res_id]);

        if (pg_num_rows($duplicate_check_result) > 0) {
            $_SESSION['alert'] = [
                'type' => 'error',
                'message' => 'It seems you have already submitted an assistance request. Please wait for the current request to be processed before submitting a new one.'
            ];
        } else {
            // Proceed with file upload
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $ccog_filename = $target_dir . basename($_FILES["ccog_filename"]["name"]);
            $cor_filename = $target_dir . basename($_FILES["cor_filename"]["name"]);

            if (move_uploaded_file($_FILES["ccog_filename"]["tmp_name"], $ccog_filename) &&
                move_uploaded_file($_FILES["cor_filename"]["tmp_name"], $cor_filename)) {

                $sql = "INSERT INTO assistance_req (res_id, year_level, ccog_filename, cor_filename) VALUES ($1, $2, $3, $4)";
                $insert_result = pg_query_params($conn, $sql, [$res_id, $year_level, $ccog_filename, $cor_filename]);

                if ($insert_result) {
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
                            $mail->Subject = 'Assistance Request Submitted';
                            $mail->Body    = "
                                <p>Dear $user_name,</p>
                                <p>Your educational assistance request has been <b>successfully submitted</b>.</p>
                                <p>Please wait for further notice regarding the status of your request.</p>
                                <br>
                                <p>Thank you,<br>SK Barangay Admin</p>
                            ";
                            $mail->send();
                        } catch (Exception $e) {
                            // Optionally log or handle the error
                        }
                    }
                    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Record added successfully!'];
                } else {
                    $_SESSION['alert'] = ['type' => 'error', 'message' => 'Error: ' . pg_last_error($conn)];
                }
            } else {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'File upload failed. Please ensure your files meet the requirements and try again.'];
            }
        }
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'The Resident ID you entered does not exist. Please make a request for a resident ID first. Thank you!'];
    }

    // Redirect to avoid form resubmission on reload
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

pg_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Assistance Request Form</title>
    <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: url('PICTURES/home_bg.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            padding-top: 100px;
        }

        .main-content {
            flex: 1; /* Ensures the main content takes up available space */
        }

        footer {
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            text-align: center;
            padding: 10px 0;
            width: 100%;
        }

        .required {
            color: red;
            font-weight: bold;
            margin-right: 4px;
        }
        .form-container {
            background-color: rgba(248, 249, 250, 0.79);
            backdrop-filter: blur(3px);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: rgb(63, 112, 234);
        }
        input,
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: red;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }
        button:hover {
            background: #831903;
        }
    </style>
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
</head>

<?php include 'navbar.php'; ?>

<body>
    <div class="main-content">
        <div class="container d-flex justify-content-center align-items-center">
            <div class="form-container">
                <h2 class="text-center" style="color: red;"><b>Assistance Request</b></h2>
                <form id="assistanceForm" method="POST" enctype="multipart/form-data">
                    <label for="res_id"><span class="required">*</span>Resident ID</label>
                    <input type="text" id="res_id" name="res_id" class="form-control" required />

                    <label for="year_level"><span class="required">*</span>Year Level</label>
                    <select id="year_level" name="year_level" class="form-control" required>
                        <option value="" disabled selected>Select Year Level</option>
                        <option value="Kinder">Kinder</option>
                        <option value="Grade 1">Grade 1</option>
                        <option value="Grade 2">Grade 2</option>
                        <option value="Grade 3">Grade 3</option>
                        <option value="Grade 4">Grade 4</option>
                        <option value="Grade 5">Grade 5</option>
                        <option value="Grade 6">Grade 6</option>
                        <option value="Grade 7">Grade 7</option>
                        <option value="Grade 8">Grade 8</option>
                        <option value="Grade 9">Grade 9</option>
                        <option value="Grade 10">Grade 10</option>
                        <option value="Grade 11">Grade 11</option>
                        <option value="Grade 12">Grade 12</option>
                        <option value="1st Year College">1st Year College</option>
                        <option value="2nd Year College">2nd Year College</option>
                        <option value="3rd Year College">3rd Year College</option>
                        <option value="4th Year College">4th Year College</option>
                    </select>

                    <label for="ccog_filename"><span class="required">*</span>Certified True Copy of Grades File</label>
                    <input type="file" id="ccog_filename" name="ccog_filename" class="form-control" required />

                    <label for="cor_filename"><span class="required">*</span>Certificate of Registration File</label>
                    <input type="file" id="cor_filename" name="cor_filename" class="form-control" required />

                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const form = document.getElementById('assistanceForm');

        form.addEventListener('submit', function (e) {
            e.preventDefault(); // prevent normal form submission

            Swal.fire({
                title: 'Confirm Submission',
                text: "Are you sure you want to submit the assistance request?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, submit it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // submit form after confirmation
                }
            });
        });

        <?php
        if (isset($_SESSION['alert'])) {
            $alert = $_SESSION['alert'];
            echo "Swal.fire(" . json_encode([
                'icon' => $alert['type'],
                'title' => $alert['type'] === 'success' ? 'Success!' : 'Oops!',
                'text' => $alert['message'],
                'confirmButtonColor' => '#3085d6'
            ]) . ");";
            unset($_SESSION['alert']);
        }
        ?>
    </script>
</body>
</html>
