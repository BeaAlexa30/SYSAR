<?php
// Include database connection
include('database.php');

session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Handle accepting a request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accept_res_id']) && isset($_POST['year_level'])) {
    $res_id = $_POST['accept_res_id'];
    $year_level = $_POST['year_level'];

    // Check if the resident is already accepted
    $checkQuery = "SELECT * FROM accepted_for_assistance WHERE res_id = '$res_id'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        echo "<script>alert('Resident has already been accepted!'); window.location.href='accepted_assistance.php';</script>";
    } else {
        // Insert into accepted_for_assistance table
        $sqlInsert = "INSERT INTO accepted_for_assistance (res_id, year_level) VALUES ('$res_id', '$year_level')";
        if ($conn->query($sqlInsert) === TRUE) {
            echo "<script>alert('Resident accepted successfully!'); window.location.href='accepted_assistance.php';</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "'); window.location.href='accepted_assistance.php';</script>";
        }
    }
}

// Handle unaccepting a resident
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['unaccept_res_id'])) {
    $res_id = $_POST['unaccept_res_id'];

    // Remove from accepted_for_assistance
    $sqlDelete = "DELETE FROM accepted_for_assistance WHERE res_id = '$res_id'";
    if ($conn->query($sqlDelete) === TRUE) {
        echo "<script>alert('Resident unaccepted successfully!'); window.location.href='accepted_assistance.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "'); window.location.href='accepted_assistance.php';</script>";
    }
}

// Fetch accepted residents
$sqlAccepted = "SELECT aa.id AS accepted_id, aa.res_id, sq.first_name, sq.last_name, aa.year_level 
                FROM accepted_for_assistance aa
                JOIN accepted_members am ON aa.res_id = am.res_id
                JOIN skmembers_queue sq ON am.members_id = sq.id";
$resultAccepted = $conn->query($sqlAccepted);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Assistance Requests</title>
    <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Accepted Residents</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 100px;
            margin-left: 200px;
            transition: margin-left 0.3s;
        }
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 {
            margin-left: 5px;
            font-size: 24px;
            color: #00205b;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #00205b;
            color: white;
        }
        button {
            padding: 8px 15px;
            margin: 5px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .accept-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
        }
        .unaccept-btn {
            background-color: #ff9800;
            color: white;
            border: none;
        }
        iframe {
            width: 100%;
            height: 500px;
            display: none;
            border: none;
        }
        .search-box {
            margin-bottom: 15px;
        }
        .search-icon {
        position: absolute;
        left: 10px; /* Adjust position inside input */
        top: 35%;
        transform: translateY(-50%);
        font-size: 1rem;
        color: gray;
        }

        #searchPending {
            padding-left: 35px; /* Add space so text doesn't overlap the icon */
        }
    </style>
</head>

<?php include 'navbar.php'; ?>

<body>
<main>
<div style="width: 80%; display: flex; justify-content: space-between; align-items: center;">
<h1>Accepted Residents</h1>
    <div class="search-container" style="position: relative; width: 30%; display: flex; align-items: center;">
        <i class="fa fa-magnifying-glass search-icon" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); font-size: 1rem; color: gray;"></i>
        <input type="text" id="acceptedSearch" class="search-input form-control text-center" placeholder="Search Resident Name or Year Level" style="padding-left: 35px; height: 40px;">
    </div>
</div>

    <table id="acceptedTable">
        <thead>
            <tr>
                <th>Accepted ID</th>
                <th>Resident Name</th>
                <th>Year Level</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultAccepted->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['accepted_id']) ?></td>
                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                <td><?= htmlspecialchars($row['year_level']) ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="unaccept_res_id" value="<?= htmlspecialchars($row['res_id']); ?>">
                        <button class="btn btn-warning">Unaccept</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</main>
</body>

<?php include 'footer.php'; ?> 

</html>
