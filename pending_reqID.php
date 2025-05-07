<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include('database.php');

// Fetch pending members (Not accepted)
$sql = "SELECT * FROM skmembers_queue WHERE status = 0";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching data: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg">
    <title>Pending Residents</title>
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
        footer {
            background-color: #00205b;
            color: white;
            text-align: center;
            padding: 15px 0;
            position: relative;
            width: 100%;
            margin-top: auto;
        }
        h1 {
            margin-left: 6px;
            font-size: 24px;
            color: #00205b;
        }
        table {
            width: 90%;
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
        td img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .accept-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 15px;
            margin: 5px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <br><br><br>
    
<main>
<div style="width: 90%; display: flex; justify-content: space-between; align-items: center;">
    <h1>Pending Request</h1>
    <div class="search-container" style="position: relative; width: 25%; display: flex; align-items: center;">
        <i class="fa fa-magnifying-glass search-icon" style="position: absolute; left: 10px; font-size: 1.2rem; color: gray;"></i>
        <input type="text" id="pendingSearch" class="search-input form-control text-center" placeholder="Search Full Name or Resident ID" style="padding-left: 35px; height: 40px;">
    </div>
</div>

<table id="pendingTable">
    <thead>
        <tr>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
            <th>Gender</th>
            <th>Age</th>
            <th>Email</th>
            <th>Picture</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr class="resident-row">
                <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                <td><?php echo htmlspecialchars($row['middle_name']); ?></td>
                <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                <td><?php echo htmlspecialchars($row['age']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                
                <td>
                    <?php 
                    $imagePath = "uploads/" . htmlspecialchars($row['filename']);
                    
                    if (!empty($row['filename']) && file_exists($imagePath)) {
                        echo '<img src="' . $imagePath . '" alt="Profile Picture">';
                    } else {
                        echo '<img src="uploads/default.jpg" alt="Default Picture">';
                    }
                    ?>
                </td>
                
                <td class="action-container">
                    <button class="accept-btn" onclick="confirmAccept(<?php echo htmlspecialchars($row['id']); ?>)">Accept</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Acceptance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to accept this resident?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <a id="confirmAcceptBtn" class="btn btn-success">Yes, Accept</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("pendingSearch").addEventListener("keyup", function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll("#pendingTable tbody .resident-row");

        rows.forEach(row => {
            let text = row.innerText.toUpperCase();
            row.style.display = text.includes(filter) ? "" : "none";
        });
    });

    function confirmAccept(id) {
        document.getElementById("confirmAcceptBtn").href = "youth_manage.php?action=accept&id=" + id;
        new bootstrap.Modal(document.getElementById("confirmModal")).show();
    }
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</main>

</body>

<?php include 'footer.php'; ?>

</html>
