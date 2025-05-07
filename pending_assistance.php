<?php
// Include database connection
include('database.php');

session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Fetch pending assistance requests (Exclude accepted residents)
$sql = "SELECT ar.id, ar.res_id, sq.first_name, sq.last_name, ar.year_level, ar.ccog_filename, ar.cor_filename 
        FROM assistance_req ar
        JOIN accepted_members am ON ar.res_id = am.res_id
        JOIN skmembers_queue sq ON am.members_id = sq.id
        WHERE ar.res_id NOT IN (SELECT res_id FROM accepted_for_assistance)";
$result = $conn->query($sql);

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
<div style="width: 90%; display: flex; justify-content: space-between; align-items: center;">
    <h1>Pending Educational Assistance Requests</h1>
    <div class="search-container" style="position: relative; width: 27%; display: flex; align-items: center;">
        <i class="fa fa-magnifying-glass search-icon" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); font-size: 1rem; color: gray;"></i>
        <input type="text" id="acceptedSearch" class="search-input form-control text-center" placeholder="Search Resident Name or Year Level" style="padding-left: 35px; height: 40px;">
    </div>
</div>

    <table id="acceptedTable">
        <thead>
            <tr>
                <th>Resident Name</th>
                <th>Year Level</th>
                <th>Grades</th>
                <th>COR</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                <td><?php echo htmlspecialchars($row['year_level']); ?></td>
                <td>
                    <button class="btn btn-primary" onclick="showDocument('<?php echo htmlspecialchars($row['ccog_filename']); ?>')">Show Grades</button>
                </td>
                <td>
                    <button class="btn btn-primary" onclick="showDocument('<?php echo htmlspecialchars($row['cor_filename']); ?>')">Show COR</button>
                </td>
                <td>
                    <form method="POST" action="accepted_assistance.php">
                        <input type="hidden" name="accept_res_id" value="<?php echo htmlspecialchars($row['res_id']); ?>">
                        <input type="hidden" name="year_level" value="<?php echo htmlspecialchars($row['year_level']); ?>">
                        <button class="btn btn-success">Accept</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    </main>

    <iframe id="documentViewer" style="width: 100%; height: 500px; display: none; border: none;"></iframe>

<script>
    function showDocument(filePath) {
        const viewer = document.getElementById('documentViewer');
        viewer.src = filePath;
        viewer.style.display = 'block';
    }

    document.getElementById("searchPending").addEventListener("keyup", function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("#pendingTable tbody tr");

        rows.forEach(row => {
            let name = row.cells[0].textContent.toLowerCase();
            let year = row.cells[1].textContent.toLowerCase();
            row.style.display = (name.includes(filter) || year.includes(filter)) ? "" : "none";
        });
    });
</script>

<?php include 'footer.php'; ?>
</body>
</html>
