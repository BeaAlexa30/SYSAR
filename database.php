<?php

// Supabase PostgreSQL Session Pooler URI
$connection_uri = "postgresql://postgres.zflminlprlrmziqnowlq:WPjgzlSbeVQxkdIK@aws-1-ap-southeast-1.pooler.supabase.com:5432/postgres";

// Connect to Supabase PostgreSQL
$conn = pg_connect($connection_uri);

// Check connection
if (!$conn) {
    error_log("❌ Connection failed: " . pg_last_error());
    die("Connection failed. Check logs for details.");
} else {
    error_log("✅ Connection successful.");
}

try {
    // Initialize counts
    $youthCount = 0;
    $documentCount = 0;
    $educationCount = 0;

    // Query for Youth Manage count
    $youthCountQuery = "SELECT COUNT(*) AS count FROM accepted_members";
    $youthResult = pg_query($conn, $youthCountQuery);
    if ($youthResult) {
        $youthRow = pg_fetch_assoc($youthResult);
        $youthCount = $youthRow['count'];
    } else {
        throw new Exception("Error executing Youth Manage query: " . pg_last_error($conn));
    }

    // Query for Document to Print count
    $documentCountQuery = "SELECT COUNT(*) AS count FROM docreq_queue";
    $documentResult = pg_query($conn, $documentCountQuery);
    if ($documentResult) {
        $documentRow = pg_fetch_assoc($documentResult);
        $documentCount = $documentRow['count'];
    } else {
        throw new Exception("Error executing Document to Print query: " . pg_last_error($conn));
    }

    // Query for Educational Assistance count
    $educationCountQuery = "SELECT COUNT(*) AS count FROM accepted_for_assistance";
    $educationResult = pg_query($conn, $educationCountQuery);
    if ($educationResult) {
        $educationRow = pg_fetch_assoc($educationResult);
        $educationCount = $educationRow['count'];
    } else {
        throw new Exception("Error executing Educational Assistance query: " . pg_last_error($conn));
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    die("An error occurred while processing the queries.");
}
