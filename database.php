<?php

$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "sk_barangaydb";

// Create connection
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


// Query for Youth Manage count
$youthCountQuery = "SELECT COUNT(*) AS count FROM accepted_members"; // Replace with your actual table name
$youthResult = $conn->query($youthCountQuery);
$youthCount = $youthResult->fetch_assoc()['count'];

// Query for Document to Print count
$documentCount = 0; // Set default to avoid errors
// Uncomment and modify the query below if needed
$documentCountQuery = "SELECT COUNT(*) AS count FROM docreq_queue"; 
$documentResult = $conn->query($documentCountQuery);
$documentCount = $documentResult->fetch_assoc()['count'];

// Query for Educational Assistance count
$educationCount = 0; // Set default to avoid errors
// Uncomment and modify the query below if needed
$educationCountQuery = "SELECT COUNT(*) AS count FROM accepted_for_assistance";
$educationResult = $conn->query($educationCountQuery);
$educationCount = $educationResult->fetch_assoc()['count'];

?>
