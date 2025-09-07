<?php
session_start();
// Include the database connection file
include 'database.php';

// Include PHPMailer files
require __DIR__ . '/PHPMailer/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/PHPMailer-master/src/SMTP.php';
require __DIR__ . '/PHPMailer/PHPMailer-master/src/Exception.php';

// Declare PHPMailer namespaces
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Initialize $res_id to avoid undefined variable warnings
$res_id = null;

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $res_id = htmlspecialchars($_POST['sk_id']); // Sanitize input
    $year_level = htmlspecialchars($_POST['year_level']);
    $purpose = htmlspecialchars($_POST['purpose']);

    // File Upload
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $filename = basename($_FILES["docs_filename"]["name"]);
    $target_file = $target_dir . $filename;

    // Validate file type and size
    $allowed_types = ['pdf', 'doc', 'docx', 'jpg', 'png'];
    $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
    if (!in_array($file_type, $allowed_types)) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid file type. Allowed types: PDF, DOC, DOCX, JPG, PNG.'];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if ($_FILES["docs_filename"]["size"] > 2 * 1024 * 1024) { // Limit file size to 2MB
        $_SESSION['message'] = ['type' => 'error', 'text' => 'File size exceeds the 2MB limit.'];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Check if the file name already exists in the database
    $check_file_sql = "SELECT * FROM docreq_queue WHERE docs_filename = $1";
    $check_file_result = pg_query_params($conn, $check_file_sql, [$filename]);

    if (pg_num_rows($check_file_result) > 0) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'The file you are trying to upload has already been submitted. Please upload a different file.'];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if (move_uploaded_file($_FILES["docs_filename"]["tmp_name"], $target_file)) {
        // Check if res_id exists in accepted_members
        $check_sk_sql = "SELECT res_id FROM accepted_members WHERE res_id = $1";
        $check_sk_result = pg_query_params($conn, $check_sk_sql, [$res_id]);

        if (pg_num_rows($check_sk_result) > 0) {
            // Insert Data if res_id exists
            $insert_sql = "INSERT INTO docreq_queue (sk_id, year_level, purpose, docs_filename) 
                           VALUES ($1, $2, $3, $4)";
            $insert_result = pg_query_params($conn, $insert_sql, [$res_id, $year_level, $purpose, $filename]);

            if ($insert_result) {
                sendEmail($res_id, $conn); // Send email notification
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Document Request Submitted Successfully!'];
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Error: ' . pg_last_error($conn)];
            }
        } else {
            // Show error if res_id does not exist
            $_SESSION['message'] = ['type' => 'error', 'text' => 'The Resident ID you entered does not exist. Please make a request for a resident ID first. Thank you!'];
        }
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'File upload failed.'];
    }

    // Redirect to clear POST data and show message
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Function to send email notification
function sendEmail($res_id, $conn) {
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

            $mail->setFrom('bmajck00@gmail.com', 'SK Barangay');
            $mail->addAddress($user_email, $user_name);
            $mail->isHTML(true);
            $mail->Subject = 'Document Request Submitted';
            $mail->Body    = "
                <p>Dear $user_name,</p>
                <p>Your document request has been <b>successfully submitted</b>.</p>
                <p>Please wait for further notice regarding the status of your request.</p>
                <br>
                <p>Thank you,<br>SK Barangay Admin</p>
            ";
            $mail->send();
        } catch (Exception $e) {
            // Optionally log or handle the error
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Request to Print</title>
    <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: url('PICTURES/home_bg.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            padding-top: 40px;
        }

        .container {
            max-width: 600px;
            margin-top: 60px;
            background-color: rgba(248, 249, 250, 0.79);
            backdrop-filter: blur(3px);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            flex: 1; /* Ensures the container takes up available space */
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
        label {
            display: block;
            margin-bottom: 8px;
            color: rgb(63, 112, 234);
        }
        button {
            background-color: red;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            width: 85%;
        }
        .btn-primary {
            width: 85%;
            border-radius: 10px;
        }
        .button-container {
            display: flex;
            justify-content: center;
        }
        button:hover {
            background-color: #831903;
        }
    </style>
</head>

<?php include 'navbar.php'; ?>

<body>
<div class="container">
    <h2 class="text-center mb-4" style="color: red;"><b>Document to Print Request Form</b></h2>
    <form id="requestForm" action="" method="POST" enctype="multipart/form-data">
        <!-- Resident ID Input -->
        <div class="mb-3">
            <label for="sk_id"><span class="required">*</span>Resident ID</label>
            <input type="text" name="sk_id" id="sk_id" class="form-control" required />
        </div>

        <!-- Year Level -->
        <div class="mb-3">
            <label for="year_level"><span class="required">*</span>Year Level</label>
            <select name="year_level" id="year_level" class="form-select" required>
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
        </div>

        <!-- Purpose -->
        <div class="mb-3">
            <label for="purpose"><span class="required">*</span>Purpose</label>
            <textarea name="purpose" id="purpose" class="form-control" rows="3" required></textarea>
        </div>

        <!-- File Upload -->
        <div class="mb-3">
            <label for="docs_filename"><span class="required">*</span>Upload Document Here</label>
            <input type="file" name="docs_filename" id="docs_filename" class="form-control" required />
        </div>

        <div class="button-container">
            <button type="submit">Submit Request</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById('requestForm').addEventListener('submit', function(e) {
    e.preventDefault(); // stop form from submitting immediately

    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to submit this document request?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, submit it!'
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit(); // submit form if confirmed
        }
    });
});
</script>

<?php if (isset($_SESSION['message'])): ?>
<script>
    Swal.fire({
        icon: '<?php echo $_SESSION['message']['type']; ?>',
        title: '<?php echo ($_SESSION['message']['type'] == 'success') ? 'Success!' : 'Oops!'; ?>',
        text: '<?php echo $_SESSION['message']['text']; ?>',
        confirmButtonColor: '#3085d6',
    });
</script>
<?php unset($_SESSION['message']); endif; ?>

<?php include 'footer.php'; ?>
</body>
</html>
