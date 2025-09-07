<?php
// Handle template download FIRST, before anything else!
if (isset($_GET['download_template'])) {
    $file = 'youth_data_template.csv';
    if (file_exists($file)) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="youth_data_template.csv"');
        readfile($file);
        exit;
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Template file not found.']);
        exit;
    }
}

session_start();
header('Content-Type: application/json');

// Error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('database.php'); // $conn = pg_connect(...)

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug: Output $_FILES and $_POST
    if (empty($_FILES)) {
        echo json_encode(['success' => false, 'message' => 'No files uploaded', 'debug' => $_FILES]);
        exit;
    }
    if (!isset($_FILES['csv_file'])) {
        echo json_encode(['success' => false, 'message' => 'csv_file not set', 'debug' => $_FILES]);
        exit;
    }
    if ($_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Upload error: ' . $_FILES['csv_file']['error'], 'debug' => $_FILES]);
        exit;
    }

    $file = $_FILES['csv_file']['tmp_name'];
    if (!file_exists($file)) {
        echo json_encode(['success' => false, 'message' => 'Uploaded file not found on server.']);
        exit;
    }

    $handle = fopen($file, 'r');
    if (!$handle) {
        echo json_encode(['success' => false, 'message' => 'Failed to open uploaded file.']);
        exit;
    }

    // Read header
    $header = fgetcsv($handle);
    $expectedHeader = [
        'First Name','Middle Name','Last Name','Suffix','Address','Contact Number 1','Contact Number 2','Email','Gender','Age','Date of Birth (YYYY-MM-DD)','PWD (Yes/No)','Nationality','Religion','Father Full Name','Mother Full Name','Contact Person','Contact Person Relationship','Contact Person Number','Contact Person Telephone','Contact Person Address'
    ];
    if (array_map('trim', $header) !== $expectedHeader) {
        fclose($handle);
        echo json_encode(['success' => false, 'message' => 'CSV header does not match template.', 'header' => $header]);
        exit;
    }

    $inserted = 0;
    $errors = [];
    while (($row = fgetcsv($handle)) !== false) {
        if (count($row) < count($expectedHeader)) {
            $errors[] = "Incomplete row: " . implode(", ", $row);
            continue;
        }

        // Map CSV columns to variables and handle empty values
        $first_name = trim($row[0]) ?: 'none'; // 1st column
        $middle_name = trim($row[1]) ?: 'none'; // 2nd column
        $last_name = trim($row[2]) ?: 'none'; // 3rd column
        $suffix = trim($row[3]) ?: 'none';
        $address = trim($row[4]) ?: 'none';
        $contact_num1 = trim($row[5]) ?: null; // Allow NULL for integers
        $contact_num2 = trim($row[6]) ?: null; // Allow NULL for integers
        $email = trim($row[7]) ?: 'none'; // 8th column
        $gender = trim($row[8]) ?: 'none';
        $age = trim($row[9]) ?: null; // Allow NULL for integers
        $dob = trim($row[10]) ?: 'none';
        $pwd = strtolower(trim($row[11])) === 'yes' ? 'Yes' : 'No';
        $nationality = trim($row[12]) ?: 'none';
        $religion = trim($row[13]) ?: 'none';
        $father_fullname = trim($row[14]) ?: 'none';
        $mother_fullname = trim($row[15]) ?: 'none';
        $contact_person = trim($row[16]) ?: 'none';
        $cp_relationship = trim($row[17]) ?: 'none';
        $cp_contactnum = trim($row[18]) ?: null; // Allow NULL for integers
        $cp_tel = trim($row[19]) ?: null; // Allow NULL for integers
        $cp_address = trim($row[20]) ?: 'none';

        // Check for duplicates in the database based only on first_name and last_name
        $check_sql = "SELECT COUNT(*) FROM skmembers_queue WHERE first_name = $1 AND last_name = $2";
        $check_params = [$first_name, $last_name];
        $check_result = pg_query_params($conn, $check_sql, $check_params);

        if ($check_result) {
            $count = pg_fetch_result($check_result, 0, 0);
            if ($count > 0) {
                $errors[] = "Duplicate record: " . htmlspecialchars($first_name . " " . $last_name);
                continue;
            }
        } else {
            $errors[] = "Failed to check duplicates for: " . htmlspecialchars($first_name . " " . $last_name) . " (" . pg_last_error($conn) . ")";
            continue;
        }

        // Insert into skmembers_queue (adjust columns as needed)
        $sql = "INSERT INTO skmembers_queue 
            (first_name, middle_name, last_name, suffix, address, contact_num1, contact_num2, email, gender, age, dob, pwd, nationality, religion, father_fullname, mother_fullname, contact_person, cp_relationship, cp_contactnum, cp_telephonenum, cp_address, status)
            VALUES
            ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17,$18,$19,$20,$21,0)";
        $params = [
            $first_name, $middle_name, $last_name, $suffix, $address, $contact_num1, $contact_num2, $email, $gender, $age, $dob, $pwd, $nationality, $religion, $father_fullname, $mother_fullname, $contact_person, $cp_relationship, $cp_contactnum, $cp_tel, $cp_address
        ];
        $result = pg_query_params($conn, $sql, $params);
        if ($result) {
            $inserted++;
        } else {
            $errors[] = "Failed to insert: " . htmlspecialchars($first_name . " " . $last_name) . " (" . pg_last_error($conn) . ")";
        }
    }
    fclose($handle);

    if ($inserted > 0) {
        echo json_encode(['success' => true, 'message' => "Successfully uploaded $inserted record(s)." . (count($errors) ? " Some rows failed." : "")]);
    } else {
        echo json_encode(['success' => false, 'message' => "No records uploaded. " . implode("; ", $errors)]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request.']);
exit;