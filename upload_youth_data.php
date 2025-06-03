<?php
session_start();
include('database.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file'];
    $response = ['success' => false, 'message' => '', 'errors' => []];

    // Check file type
    $file_type = pathinfo($file['name'], PATHINFO_EXTENSION);
    if ($file_type !== 'csv') {
        $response['message'] = 'Please upload a CSV file.';
        echo json_encode($response);
        exit;
    }

    // Read CSV file
    if (($handle = fopen($file['tmp_name'], "r")) !== FALSE) {
        // Skip header row
        $header = fgetcsv($handle);
        
        $success_count = 0;
        $error_count = 0;
        $row_number = 1;
        $error_details = [];

        while (($data = fgetcsv($handle)) !== FALSE) {
            $row_number++;
            $row_errors = [];
            
            // Map CSV columns to database fields
            if (count($data) < 21) {
                $row_errors[] = "Row $row_number: Expected 21 columns, got " . count($data) . " columns";
                $error_count++;
                $error_details[] = $row_errors;
                continue;
            }

            $first_name = trim($data[0]);
            $middle_name = trim($data[1]);
            $last_name = trim($data[2]);
            $suffix = trim($data[3]);
            $address = trim($data[4]);
            $contact_num1 = trim($data[5]);
            $contact_num2 = trim($data[6]);
            $email = trim($data[7]);
            $gender = trim($data[8]);
            $age = trim($data[9]);
            $dob = trim($data[10]);
            $PWD = trim($data[11]);
            $nationality = trim($data[12]);
            $religion = trim($data[13]);
            $father_fullname = trim($data[14]);
            $mother_fullname = trim($data[15]);
            $contact_person = trim($data[16]);
            $cp_relationship = trim($data[17]);
            $cp_contactnum = trim($data[18]);
            $cp_telephone = trim($data[19]);
            $cp_address = trim($data[20]);

            // Validate required fields
            $required_fields = [
                'First Name' => $first_name,
                'Middle Name' => $middle_name,
                'Last Name' => $last_name,
                'Address' => $address,
                'Contact Number 1' => $contact_num1,
                'Email' => $email,
                'Gender' => $gender,
                'Date of Birth' => $dob,
                'Age' => $age,
                'Nationality' => $nationality,
                'PWD Status' => $PWD,
                'Religion' => $religion,
                'Father Full Name' => $father_fullname,
                'Mother Full Name' => $mother_fullname
            ];

            foreach ($required_fields as $field => $value) {
                if (empty($value)) {
                    $row_errors[] = "Row $row_number: $field is required";
                }
            }

            // Validate email
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $row_errors[] = "Row $row_number: Invalid email format";
            }

            // Validate age
            if (!empty($age) && (!is_numeric($age) || $age <= 0)) {
                $row_errors[] = "Row $row_number: Age must be a positive number";
            }

            // Validate contact numbers
            if (!empty($contact_num1)) {
                $contact_num1 = preg_replace('/[^0-9]/', '', $contact_num1);
                if (strlen($contact_num1) < 10) {
                    $row_errors[] = "Row $row_number: Contact Number 1 must have at least 10 digits";
                }
            }

            if (!empty($contact_num2)) {
                $contact_num2 = preg_replace('/[^0-9]/', '', $contact_num2);
            }

            if (!empty($cp_contactnum)) {
                $cp_contactnum = preg_replace('/[^0-9]/', '', $cp_contactnum);
                if (strlen($cp_contactnum) < 10) {
                    $row_errors[] = "Row $row_number: Contact Person Number must have at least 10 digits";
                }
            }

            if (!empty($cp_telephone)) {
                $cp_telephone = preg_replace('/[^0-9]/', '', $cp_telephone);
            }

            // If there are any errors for this row, skip it
            if (!empty($row_errors)) {
                $error_count++;
                $error_details = array_merge($error_details, $row_errors);
                continue;
            }

            // Insert data into database
            $sql = "INSERT INTO skmembers_queue (
                        first_name, middle_name, last_name, suffix, address, contact_num1, contact_num2, 
                        email, gender, age, dob, PWD, nationality, religion, father_fullname, mother_fullname, 
                        contact_person, cp_relationship, cp_contactnum, cp_telephonenum, cp_address, status
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";

            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param(
                    $stmt,
                    "sssssssssssssssssssss",
                    $first_name, $middle_name, $last_name, $suffix, $address, $contact_num1, $contact_num2,
                    $email, $gender, $age, $dob, $PWD, $nationality, $religion, $father_fullname, $mother_fullname,
                    $contact_person, $cp_relationship, $cp_contactnum, $cp_telephone, $cp_address
                );

                if (mysqli_stmt_execute($stmt)) {
                    $success_count++;
                } else {
                    $error_count++;
                    $row_errors[] = "Row $row_number: Database error - " . mysqli_stmt_error($stmt);
                    $error_details = array_merge($error_details, $row_errors);
                }
                mysqli_stmt_close($stmt);
            } else {
                $error_count++;
                $row_errors[] = "Row $row_number: Failed to prepare statement - " . mysqli_error($conn);
                $error_details = array_merge($error_details, $row_errors);
            }
        }
        fclose($handle);

        $response['success'] = $success_count > 0;
        $response['message'] = "Upload complete. Successfully imported $success_count records. Failed to import $error_count records.";
        if (!empty($error_details)) {
            $response['message'] .= "\n\nError Details:\n" . implode("\n", $error_details);
        }
    } else {
        $response['message'] = 'Error reading CSV file.';
    }

    echo json_encode($response);
    exit;
}

// Return template CSV
if (isset($_GET['download_template'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="youth_data_template.csv"');
    
    $output = fopen('php://output', 'w');
    
    // Write headers
    fputcsv($output, [
        'First Name', 'Middle Name', 'Last Name', 'Suffix', 'Address', 
        'Contact Number 1', 'Contact Number 2', 'Email', 'Gender', 'Age',
        'Date of Birth (YYYY-MM-DD)', 'PWD (Yes/No)', 'Nationality', 'Religion',
        'Father Full Name', 'Mother Full Name', 'Contact Person',
        'Contact Person Relationship', 'Contact Person Number', 'Contact Person Telephone', 'Contact Person Address'
    ]);
    
    // Write sample data
    fputcsv($output, [
        'Juan', 'Santos', 'Dela Cruz', 'Jr', '123 Main St, Manila',
        '9123456789', '9987654321', 'juan@email.com', 'Male', '18',
        '2006-01-01', 'No', 'Filipino', 'Catholic',
        'Pedro Dela Cruz', 'Maria Dela Cruz', 'Pedro Dela Cruz',
        'Father', '9123456789', '8123456', '123 Main St, Manila'
    ]);
    
    fclose($output);
    exit;
}
?> 