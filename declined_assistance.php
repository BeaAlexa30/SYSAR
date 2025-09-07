<?php
include('database.php');
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Fetch declined assistance data
$query = "SELECT da.id, da.res_id, sq.first_name, sq.last_name, da.year_level, da.created_at, da.ccog_filename, da.cor_filename
          FROM declined_assistance da
          JOIN accepted_members am ON da.res_id = am.res_id
          JOIN skmembers_queue sq ON am.members_id = sq.id
          ORDER BY da.created_at DESC";
$result = pg_query($conn, $query);

if (!$result) {
    die('Database error: ' . pg_last_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Declined Assistance Requests</title>
    <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 100px;
            margin-left: 200px;
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
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
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
<body>
<?php include 'navbar.php'; ?>
<main>
    <h1>Declined Educational Assistance Requests</h1>
    <table>
        <thead>
            <tr>
                <th>Resident Name</th>
                <th>Year Level</th>
                <th>COR</th>
                <th>Grades</th>
                <th>Date Declined</th>
            </tr>
        </thead>
        <tbody>
            <?php if (pg_num_rows($result) > 0): ?>
                <?php while ($row = pg_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                    <td><?= htmlspecialchars($row['year_level']) ?></td>
                    <td>
                        <?php
                        $corFile = $row['cor_filename'];
                        if (strpos($corFile, 'uploads/') === 0) {
                            $corFile = substr($corFile, strlen('uploads/'));
                        }
                        ?>
                        <?php if (!empty($corFile)): ?>
                            <a href="uploads/<?= htmlspecialchars($corFile) ?>" target="_blank">View COR</a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php
                        $gradesFile = $row['ccog_filename'];
                        if (strpos($gradesFile, 'uploads/') === 0) {
                            $gradesFile = substr($gradesFile, strlen('uploads/'));
                        }
                        ?>
                        <?php if (!empty($gradesFile)): ?>
                            <a href="uploads/<?= htmlspecialchars($gradesFile) ?>" target="_blank">View Grades</a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars(date('F j, Y', strtotime($row['created_at']))) ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center; color: #888;">No declined assistance requests found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
<?php include 'footer.php'; ?>
</body>
</html>