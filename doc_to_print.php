<?php
include 'database.php';

session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$query = "SELECT dq.id, am.res_id, sq.first_name, sq.last_name, dq.docs_filename 
          FROM docreq_queue dq
          JOIN accepted_members am ON dq.sk_id = am.res_id
          JOIN skmembers_queue sq ON am.members_id = sq.id";
$pendingResult = $conn->query($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['done_id'])) {
    $done_id = $_POST['done_id'];

    $fetchQuery = "SELECT am.res_id, sq.first_name, sq.last_name, dq.docs_filename 
                   FROM docreq_queue dq
                   JOIN accepted_members am ON dq.sk_id = am.res_id
                   JOIN skmembers_queue sq ON am.members_id = sq.id
                   WHERE dq.id = ?";
    $stmt = $conn->prepare($fetchQuery);
    $stmt->bind_param("i", $done_id);
    $stmt->execute();
    $result_fetch = $stmt->get_result();
    $docData = $result_fetch->fetch_assoc();
    $stmt->close();

    if ($docData) {
        $insertQuery = "INSERT INTO completed_doc_requests (res_id, first_name, last_name, docs_filename) 
                        VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("isss", $docData['res_id'], $docData['first_name'], $docData['last_name'], $docData['docs_filename']);
        $stmt->execute();
        $stmt->close();

        $deleteQuery = "DELETE FROM docreq_queue WHERE id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $done_id);
        $stmt->execute();
        $stmt->close();
    }

    $conn->close();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Document Requests</title>
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
        .done-btn { 
            background-color: #4CAF50; 
            color: white; 
            border: none; 
            padding: 8px 15px; 
            border-radius: 5px; 
            cursor: pointer; }
    </style>
</head>

<?php include 'navbar.php'; ?>

<body>
    <main>
    <div style="width: 95%; align-items: center;">
        <h1>Pending Documents to Print</h1>
        <table class="acceptedTable">
            <thead>
                <tr>
                    <th>Resident ID</th>
                    <th>Full Name</th>
                    <th>Document</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $pendingResult->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['res_id']) ?></td>
                        <td><?= htmlspecialchars($row['first_name'] . " " . $row['last_name']) ?></td>
                        <td>
                            <a href="uploads/<?= htmlspecialchars($row['docs_filename']) ?>" target="_blank" class="btn btn-primary" style="background-color: #9804c4; border: none;">
                                View Document
                            </a>
                        </td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="done_id" value="<?= htmlspecialchars($row['id']) ?>">
                                <button class="done-btn">Done</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    </main>

    <?php include 'footer.php'; ?>
</body>

</html>
