<?php
// Include database connection
include('database.php');

session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Initialize variables for alert messaging
$alertType = '';
$alertMessage = '';

// Handle archiving a resident by updating status = 0
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['archive_res_id'])) {
    $res_id = $_POST['archive_res_id'];

    $sqlArchive = "UPDATE accepted_for_assistance SET status = 0 WHERE res_id = '$res_id'";
    if ($conn->query($sqlArchive) === TRUE) {
        $alertType = 'success';
        $alertMessage = 'Resident archived successfully!';
    } else {
        $alertType = 'error';
        $alertMessage = 'Error: ' . $conn->error;
    }
}

// Fetch only active accepted residents (status = 1)
$sqlAccepted = "SELECT aa.id AS accepted_id, aa.res_id, sq.first_name, sq.last_name, aa.year_level 
                FROM accepted_for_assistance aa
                JOIN accepted_members am ON aa.res_id = am.res_id
                JOIN skmembers_queue sq ON am.members_id = sq.id
                WHERE aa.status = 1";
$resultAccepted = $conn->query($sqlAccepted);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Accepted Residents</title>
    <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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
        .archive-btn {
            background-color: #ff9800;
            color: white;
            border: none;
        }
        .archive-btn i {
            margin-right: 5px;
        }
        .search-container {
            position: relative;
            width: 30%;
            display: flex;
            align-items: center;
        }
        .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
            color: gray;
        }
        #acceptedSearch {
            padding-left: 35px;
            height: 40px;
        }
    </style>
</head>

<?php include 'navbar.php'; ?>

<body>
<main>
    <div style="width: 80%; display: flex; justify-content: space-between; align-items: center;">
        <h1>Accepted Residents</h1>
        <div class="search-container">
            <i class="fa fa-search search-icon"></i>
            <input type="text" id="acceptedSearch" class="form-control text-center" placeholder="Search Resident Name or Year Level" />
        </div>
    </div>

    <table id="acceptedTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Resident Name</th>
                <th>Year Level</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $counter = 0; ?>
            <?php while ($row = $resultAccepted->fetch_assoc()): ?>
            <tr>
                <td><?= ++$counter ?></td>
                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                <td><?= htmlspecialchars($row['year_level']) ?></td>
                <td>
                    <form method="POST" class="archiveForm" action="">
                        <input type="hidden" name="archive_res_id" value="<?= htmlspecialchars($row['res_id']); ?>" />
                        <button type="submit" class="archive-btn">
                            <i class="fas fa-box-archive"></i> Archive
                        </button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    // Confirm archive on form submit
    document.querySelectorAll('.archiveForm').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to archive this resident?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff9800',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, archive it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // proceed with form submission
                }
            });
        });
    });

    <?php if ($alertType === 'success'): ?>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: <?= json_encode($alertMessage) ?>,
        confirmButtonColor: '#3085d6'
    }).then(() => {
        window.location.href = 'archive-assistance.php';
    });
    <?php elseif ($alertType === 'error'): ?>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: <?= json_encode($alertMessage) ?>,
        confirmButtonColor: '#d33'
    }).then(() => {
        window.location.href = 'archive-assistance.php';
    });
    <?php endif; ?>
</script>

</body>

<?php include 'footer.php'; ?>
</html>
