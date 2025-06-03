<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include('database.php');

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'No ID specified']);
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Query all the detailed fields from skmembers_queue for this member id
$sql = "SELECT 
    id,
    first_name,
    middle_name,
    last_name,
    suffix,
    address,
    contact_num1,
    contact_num2,
    email,
    gender,
    age,
    blood_type,
    dob,
    religion,
    PWD,
    nationality,
    father_fullname,
    mother_fullname,
    contact_person,
    cp_relationship,
    cp_contactnum,
    cp_telephonenum,
    cp_address,
    status
FROM skmembers_queue
WHERE id = '$id'
LIMIT 1";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo json_encode(['error' => 'Member not found']);
    exit;
}

$data = mysqli_fetch_assoc($result);

echo json_encode($data);
