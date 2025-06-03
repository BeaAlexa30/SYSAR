<?php
include('database.php');
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$swalType = "";
$swalMessage = "";
$redirectUrl = ""; // empty by default

// Handle retrieval
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['retrieve_res_id'])) {
    $res_id = $_POST['retrieve_res_id'];
    $sqlRetrieve = "UPDATE accepted_for_assistance SET status = 1 WHERE res_id = '$res_id'";
    if ($conn->query($sqlRetrieve) === TRUE) {
        $swalType = "success";
        $swalMessage = "Resident retrieved successfully!";
        $redirectUrl = "accepted_assistance.php"; // redirect after success retrieve
    } else {
        $swalType = "error";
        $swalMessage = "Error retrieving resident: " . $conn->error;
    }
}

// Handle deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_res_id'])) {
    $res_id = $_POST['delete_res_id'];

    // Start transaction (optional but recommended)
    $conn->begin_transaction();

    try {
        // Delete from accepted_for_assistance
        $sqlDeleteAccepted = "DELETE FROM accepted_for_assistance WHERE res_id = '$res_id'";
        if (!$conn->query($sqlDeleteAccepted)) {
            throw new Exception("Error deleting from accepted_for_assistance: " . $conn->error);
        }

        // Delete from assistance_req
        $sqlDeleteAssistanceReq = "DELETE FROM assistance_req WHERE res_id = '$res_id'";
        if (!$conn->query($sqlDeleteAssistanceReq)) {
            throw new Exception("Error deleting from assistance_req: " . $conn->error);
        }

        // Commit transaction if both deletes succeed
        $conn->commit();

        $swalType = "success";
        $swalMessage = "Resident deleted successfully from both tables!";
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();

        $swalType = "error";
        $swalMessage = $e->getMessage();
    }
}

// Fetch archived residents
$sqlArchived = "SELECT aa.id AS accepted_id, aa.res_id, sq.first_name, sq.last_name, aa.year_level 
                FROM accepted_for_assistance aa
                JOIN accepted_members am ON aa.res_id = am.res_id
                JOIN skmembers_queue sq ON am.members_id = sq.id
                WHERE aa.status = 0";
$resultArchived = $conn->query($sqlArchived);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Archived Residents</title>
    <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        .search-icon {
            position: absolute;
            left: 10px;
            top: 35%;
            transform: translateY(-50%);
            font-size: 1rem;
            color: gray;
        }
        #searchArchived {
            padding-left: 35px;
        }
    </style>
</head>

<?php include 'navbar.php'; ?>

<body>
<main>
<div style="width: 80%; display: flex; justify-content: space-between; align-items: center;">
    <h1>Archived Residents</h1>
    <div class="search-container" style="position: relative; width: 30%; display: flex; align-items: center;">
        <i class="fa fa-magnifying-glass search-icon"></i>
        <input type="text" id="searchArchived" class="search-input form-control text-center" placeholder="Search Resident Name or Year Level">
    </div>
</div>

    <table id="archivedTable">
        <thead>
            <tr>
                <th>Accepted ID</th>
                <th>Resident Name</th>
                <th>Year Level</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultArchived->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['accepted_id']) ?></td>
                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                <td><?= htmlspecialchars($row['year_level']) ?></td>
                <td>
                    <!-- Retrieve Button -->
                    <button type="button" class="btn btn-warning btn-icon retrieve-btn" data-id="<?= htmlspecialchars($row['res_id']); ?>" title="Retrieve">
                        <i class="fas fa-undo-alt fa-lg"></i>
                    </button>

                    <!-- Delete Button -->
                    <button type="button" class="btn btn-danger btn-icon delete-btn" data-id="<?= htmlspecialchars($row['res_id']); ?>" title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Hidden Forms -->
    <form id="retrieveForm" method="POST" style="display: none;">
        <input type="hidden" name="retrieve_res_id" id="retrieve_res_id">
    </form>
    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="delete_res_id" id="delete_res_id">
    </form>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Handle delete confirmation
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function () {
                const resId = this.getAttribute("data-id");

                Swal.fire({
                    title: "Are you sure?",
                    text: "This will permanently delete the resident.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete_res_id').value = resId;
                        document.getElementById('deleteForm').submit();
                    }
                });
            });
        });

        // Handle retrieve confirmation
        document.querySelectorAll(".retrieve-btn").forEach(button => {
            button.addEventListener("click", function () {
                const resId = this.getAttribute("data-id");

                Swal.fire({
                    title: "Retrieve this resident?",
                    text: "They will be moved back to the active list.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#ffc107",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, retrieve"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('retrieve_res_id').value = resId;
                        document.getElementById('retrieveForm').submit();
                    }
                });
            });
        });

        // Show success or error alert if set from PHP
        <?php if (!empty($swalType) && !empty($swalMessage)): ?>
            Swal.fire({
                icon: '<?= $swalType ?>',
                title: '<?= $swalMessage ?>',
                confirmButtonColor: '#00205b'
            }).then(() => {
                <?php if ($redirectUrl): ?>
                    window.location.href = '<?= $redirectUrl ?>'; // Redirect after successful retrieve
                <?php else: ?>
                    // Reload current page for delete success or errors
                    window.location.href = window.location.href.split('?')[0];
                <?php endif; ?>
            });
        <?php endif; ?>

        // Client-side search/filter function
        const searchInput = document.getElementById('searchArchived');
        searchInput.addEventListener('keyup', function () {
            const filter = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll('#archivedTable tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    });
</script>
</body>

<?php include 'footer.php'; ?>

</html>
