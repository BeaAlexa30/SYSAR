<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

header('Content-Type: application/json');
include('database.php');

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'No member ID provided.']);
    exit;
}

$id = intval($_GET['id']);

$sql = "SELECT 
    a.res_id,
    q.first_name, q.middle_name, q.last_name, q.suffix,
    q.address, q.contact_num1, q.contact_num2, q.email,
    q.gender, q.age, q.blood_type, q.dob, q.religion, q.pwd,
    q.nationality, q.father_fullname, q.mother_fullname,
    q.contact_person, q.cp_relationship, q.cp_contactnum
FROM accepted_members a
JOIN skmembers_queue q ON a.members_id = q.id
WHERE q.id = $1";
$result = pg_query_params($conn, $sql, [$id]);
if ($row = pg_fetch_assoc($result)) {
    $row['PWD'] = $row['pwd'];
    unset($row['pwd']);
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Member not found.']);
}
