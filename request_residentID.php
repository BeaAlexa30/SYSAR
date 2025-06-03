<?php
session_start();
include('database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name']);
    $last_name = trim($_POST['last_name']);
    $suffix = trim($_POST['suffix']);
    $address = trim($_POST['address']);
    $contact_num1 = trim($_POST['contact_num1']);
    $contact_num2 = trim($_POST['contact_num2']);
    $email = trim($_POST['email']);
    $gender = trim($_POST['gender']);
    $age = trim($_POST['age']);
    $dob = trim($_POST['dob']);
    $PWD = trim($_POST['PWD']);
    $nationality = trim($_POST['nationality']);
    $father_fullname = trim($_POST['father_fullname']);
    $mother_fullname = trim($_POST['mother_fullname']);
    $contact_person = trim($_POST['contact_person']);
    $cp_relationship = trim($_POST['cp_relationship']);
    $cp_contactnum = trim($_POST['cp_contactnum']);
    $cp_address = trim($_POST['cp_address']);

    if (
        empty($first_name) || empty($middle_name) || empty($last_name) || empty($address) || 
        empty($contact_num1) || empty($email) || empty($gender) || empty($dob) || empty($age) || 
        empty($nationality) || empty($PWD) || empty($father_fullname) || empty($mother_fullname)
    ) {
        $_SESSION['message'] = "All required fields must be filled out.";
        $_SESSION['alertType'] = "danger";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Invalid email format.";
        $_SESSION['alertType'] = "danger";
    } elseif (!is_numeric($age) || $age <= 0) {
        $_SESSION['message'] = "Age must be a valid number.";
        $_SESSION['alertType'] = "danger";
    } elseif (!is_numeric($contact_num1) || strlen($contact_num1) < 10) {
        $_SESSION['message'] = "Invalid contact number format.";
        $_SESSION['alertType'] = "danger";
    } elseif (!empty($cp_contactnum) && (!is_numeric($cp_contactnum) || strlen($cp_contactnum) < 10)) {
        $_SESSION['message'] = "Invalid emergency contact number format.";
        $_SESSION['alertType'] = "danger";
    } else {
        $sql = "INSERT INTO skmembers_queue (
                    first_name, middle_name, last_name, suffix, address, contact_num1, contact_num2, 
                    email, gender, age, dob, PWD, nationality, father_fullname, mother_fullname, 
                    contact_person, cp_relationship, cp_contactnum, cp_address, status
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param(
                $stmt,
                "sssssssssssssssssss",
                $first_name, $middle_name, $last_name, $suffix, $address, $contact_num1, $contact_num2,
                $email, $gender, $age, $dob, $PWD, $nationality, $father_fullname, $mother_fullname,
                $contact_person, $cp_relationship, $cp_contactnum, $cp_address
            );

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['message'] = "Your request has been submitted successfully!";
                $_SESSION['alertType'] = "success";
            } else {
                $_SESSION['message'] = "Error inserting data: " . mysqli_stmt_error($stmt);
                $_SESSION['alertType'] = "danger";
            }
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['message'] = "Database error: Unable to prepare statement.";
            $_SESSION['alertType'] = "danger";
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SK Member Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg">
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
        .required {
            color: red;
            font-weight: bold;
            margin-right: 4px;
        }
        form {
            width: 80%;
            margin: 0 auto;
            background-color: rgba(248, 249, 250, 0.79);
            backdrop-filter: blur(3px);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: rgb(63, 112, 234);
        }
        input[type="text"],
        input[type="email"],
        input[type="number"],
        input[type="file"],
        input[type="date"],
        select {
            width: 100%;
            padding: 8px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
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
        .button-container {
        display: flex;
        justify-content: center;
        }
        button:hover {
            background-color: #831903;
        }
        .form-group {
        display: flex;
        gap: 15px; /* Adds spacing between fields */
        flex-wrap: wrap; /* Allows wrapping for smaller screens */
         }
        .form-group div {
            flex: 1; /* Makes each div take equal space */
            min-width: 200px; /* Ensures proper responsiveness */
        }
        input[type="text"],
        input[type="email"],
        input[type="number"],
        input[type="file"],
        select {
            width: 100%; /* Ensures all input fields take full width */
            padding: 12px;
            font-size: 16px; /* Increases text size */
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            .form-group {
                flex-direction: column; /* Stack fields on smaller screens */
            }
        }
        .modal-content {
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0px 0px 10px rgba(0,0,0,0.3);
        font-weight: bold;
    }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<form action="" method="POST" enctype="multipart/form-data">
<h2 class = "text-center" style = "color: red;">REQUEST RESIDENT ID FORM</h2>
<br><h4 style="color: rgb(63, 112, 234);">Personal Details</h4><br>
    <div class="form-group">
        <!-- Make askterisk red -->
        <div>
            <label for="first_name"><span class="required">*</span>First Name</label>
            <input type="text" id="first_name" name="first_name" class="form-control" required>
        </div>
        <div>
            <label for="middle_name"><span class="required">*</span>Middle Name</label>
            <input type="text" id="middle_name" name="middle_name" class="form-control" required>
        </div> 
        <div>
            <label for="last_name"><span class="required">*</span>Last Name</label>
            <input type="text" id="last_name" name="last_name" class="form-control" required>
        </div>
        <div>
            <label for="suffix">Suffix</label>
            <input type="text" id="suffix" name="suffix" class="form-control">
        </div>
    </div>

    <div class="form-group" style="width: 100%;">
        <div>
            <label for="address"><span class="required">*</span>Address</label>
            <input type="text" id="address" name="address" class="form-control" style="width: 100%;" class="form-control" required>
        </div>
    </div>

    <div class="form-group">
        <div>
            <label for="contact_num1"><span class="required">*</span>Contact Number 1</label>
            <input type="text" id="contact_num1" name="contact_num1" class="form-control" required>
        </div>
        <div>
            <label for="contact_num2">Contact Number 2</label>
            <input type="text" id="contact_num2" name="contact_num2" class="form-control">
        </div>
        <div>
            <label for="email"><span class="required">*</span>Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
    </div>

    <div class="form-group">
        <div>
            <label for="gender"><span class="required">*</span>Gender</label>
            <select id="gender" name="gender" class="form-control" required>
                <option value="" disabled selected>Select Gender</option>
                <option value="female">Female</option>
                <option value="male">Male</option>
            </select>
        </div>
        <div>
            <label for="dob"><span style="color: red;">*</span>Date of Birth</label>
            <input type="date" id="dob" name="dob" class="form-control" required>
        </div>

        <script>
            // Set max date to today (prevents future dates from being selected)
            document.getElementById('dob').max = new Date().toISOString().split("T")[0];
        </script>

        <div>
            <label for="age"><span style="color: red;">*</span>Age</label>
            <input type="number" id="age" name="age" class="form-control" required>
        </div>

        <script>
            document.getElementById('dob').addEventListener('change', function() {
                let dob = new Date(this.value);
                let today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                let monthDiff = today.getMonth() - dob.getMonth();
                let dayDiff = today.getDate() - dob.getDate();

                // Adjust age if birthday hasn't occurred yet this year
                if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
                    age--;
                }

                document.getElementById('age').value = age >= 0 ? age : ''; // Prevent negative age
            });
        </script>

        <div>
            <label for="blood_type">Blood Type</label>
            <input type="text" id="blood_type" name="blood_type" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <div>
            <label for="religion"><span style="color: red;">*</span>Religion</label>
            <input type="text" id="religion" name="religion" class="form-control" required>
        </div>
        <div>
            <label for="nationality"><span style="color: red;">*</span>Nationality</label>
            <input type="text" id="nationality" name="nationality" class="form-control" required>
        </div>
        <div>
            <label for="PWD"><span style="color: red;">*</span>PWD</label>
            <select id="PWD" name="PWD" class="form-control" required>
                <option value="" disabled selected>Select Yes or No</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
        </div>
    </div>

    <br><h4 style="color: rgb(63, 112, 234);">Parents Information</h4><br>
    <div class="form-group">
        <div>
            <label for="father_fullname"><span style="color: red;">*</span>Father's Full Name</label>
            <input type="text" id="father_fullname" name="father_fullname" class="form-control" required>
        </div>
        <div>
            <label for="mother_fullname"><span style="color: red;">*</span>Mother's Full Name</label>
            <input type="text" id="mother_fullname" name="mother_fullname" class="form-control" required>
        </div>
    </div>

    <br><h4 style="color: rgb(63, 112, 234);">Emergency Contact Details</h4><br>
    <div class="form-group">
        <div>
            <label for="contact_person"><span style="color: red;">*</span>Contact Person</label>
            <input type="text" id="contact_person" name="contact_person" class="form-control" required>
        </div>
        <div>
            <label for="cp_relationship"><span style="color: red;">*</span>Relationship</label>
            <input type="text" id="cp_relationship" name="cp_relationship" class="form-control" required>
        </div>
    </div>
 
    <div class="form-group">
        <div>
            <label for="cp_contactnum"><span style="color: red;">*</span>Mobile Number</label>
            <input type="text" id="cp_contactnum" name="cp_contactnum" class="form-control" required>
        </div>
        <div>
            <label for="cp_telephonenum">Telephone Number</label> <!-- not sure to exclude -->
            <input type="text" id="cp_telephonenum" name="cp_telephonenum" class="form-control">
        </div>
        <div>
            <label for="cp_address"><span style="color: red;">*</span>Address</label>
            <input type="text" id="cp_address" name="cp_address" class="form-control" required>
        </div>
    </div>

    <div class="button-container">
    <button type="submit">Submit</button>
    </div>
</form>

<!-- Pop-up Alert Modal -->
<div id="customAlert" class="modal fade show" tabindex="-1" style="display: none; background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4" id="alertBox" style="border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            <div class="modal-body">
                <i id="alertIcon" class="fas fa-check-circle" style="font-size: 3rem; color: #28a745;"></i>
                <h5 id="alertMessage" class="mt-3">Your request has been submitted successfully!</h5>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-center">
                <button class="btn btn-success" id="closeAlert">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var message = "<?php echo isset($_SESSION['message']) ? $_SESSION['message'] : ''; ?>";
        var alertType = "<?php echo isset($_SESSION['alertType']) ? $_SESSION['alertType'] : ''; ?>";

        if (message) {
            document.getElementById("alertMessage").innerText = message;
            let alertModal = document.getElementById("customAlert");
            let alertBox = document.getElementById("alertBox");
            let alertIcon = document.getElementById("alertIcon");

            // Apply styles based on alert type
            if (alertType === "success") {
                alertBox.style.backgroundColor = "#d4edda"; // Light green
                alertBox.style.color = "#155724"; // Dark green text
                alertIcon.className = "fas fa-check-circle";
                alertIcon.style.color = "#28a745";
            } else if (alertType === "danger") {
                alertBox.style.backgroundColor = "#f8d7da"; // Light red
                alertBox.style.color = "#721c24";
                alertIcon.className = "fas fa-times-circle";
                alertIcon.style.color = "#dc3545";
            }

            alertModal.style.display = "block";

            // Close modal on button click
            document.getElementById("closeAlert").addEventListener("click", function () {
                alertModal.style.display = "none";
            });

            <?php unset($_SESSION['message']); unset($_SESSION['alertType']); ?> // Clear message after displaying
        }
    });
</script>

<!-- Add FontAwesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>