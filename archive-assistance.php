<?php
// Include database connection
include('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $res_id = $_POST['res_id'];
    $year_level = $_POST['year_level'];

    // Check if res_id exists in accepted_members table
    $check_sql = "SELECT * FROM accepted_members WHERE res_id = '$res_id'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        // Proceed with file upload if res_id exists
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $ccog_filename = $target_dir . basename($_FILES["ccog_filename"]["name"]);
        $cor_filename = $target_dir . basename($_FILES["cor_filename"]["name"]);

        if (move_uploaded_file($_FILES["ccog_filename"]["tmp_name"], $ccog_filename) &&
            move_uploaded_file($_FILES["cor_filename"]["tmp_name"], $cor_filename)) {
            
            $sql = "INSERT INTO assistance_req (res_id, year_level, ccog_filename, cor_filename) 
                    VALUES ('$res_id', '$year_level', '$ccog_filename', '$cor_filename')";
            
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Record added successfully');</script>";
            } else {
                echo "<script>alert('Error: " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('File upload failed.');</script>";
        }
    } else {
        // Show alert if res_id doesn't exist
        echo "<script>alert('The Resident ID you entered does not exist. Please make a request for a resident ID first. Thank you!');</script>";
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assistance Request Form</title>
    <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        input, select {
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
</head>

<?php include 'navbar.php'; ?>

<body>
    <div class="container d-flex justify-content-center align-items-center">
        <div class="form-container">
            <h2 class="text-center" style = "color: red;"><b>Assistance Request</b></h2>
            <form method="POST" enctype="multipart/form-data">
                <label for="res_id"><span class="required">*</span>Resident ID</label>
                <input type="text" name="res_id" class="form-control" required>
                
                <label for="year_level"><span class="required">*</span>Year Level</label>
                <select name="year_level" class="form-control" required>
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
                    <option value="Grade 10">Grade 11</option>
                    <option value="Grade 10">Grade 12</option>
                    <option value="1st Year College">1st Year College</option>
                    <option value="2nd Year College">2nd Year College</option>
                    <option value="3rd Year College">3rd Year College</option>
                    <option value="4th Year College">4th Year College</option>
                </select>
                
                <label for="ccog_filename"><span class="required">*</span>Certified True Copy of Grades File</label>
                <input type="file" name="ccog_filename" class="form-control" required>
                
                <label for="cor_filename"><span class="required">*</span>Certificate of Registration File</label>
                <input type="file" name="cor_filename" class="form-control" required>
                
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<!-- HTML clear. Cleanse the javascript alert -->
