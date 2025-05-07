<?php
include 'database.php';

session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$completedQuery = "SELECT * FROM completed_doc_requests";
$completedResult = $conn->query($completedQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completed Document Requests</title>
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
            text-align: center;
            font-size: 24px;
            color: #00205b;
        }
        table {
            width: 65%;
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
    </style>
</head>

<?php include 'navbar.php'; ?>

<body>
    <main>
    <div style="width: 95%; align-items: center;">
    <h1>Completed Documents</h1>
    <table class="acceptedTable">
        <thead>
            <tr>
                <th>Resident ID</th>
                <th>Name</th>
                <th>Document</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $completedResult->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['res_id']) ?></td>
                    <td><?= htmlspecialchars($row['first_name'] . " " . $row['last_name']) ?></td>
                    <td>
                        <a href="uploads/<?= htmlspecialchars($row['docs_filename']) ?>" target="_blank" class="btn btn-secondary" style="border: none;">
                            View Document
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
 </main>
</body>

<?php include 'footer.php'; ?>

</html>
