<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include('database.php');

// Success alert flags
$showRetrieveSuccess = false;
$showDeleteSuccess = false;

// Handle "retrieve" or "delete" actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    if ($action == 'retrieve') {
        $update_retrieve_sql = "UPDATE accepted_members SET archive = 'No' WHERE members_id = '$id'";
        if (mysqli_query($conn, $update_retrieve_sql)) {
            $update_status_sql = "UPDATE skmembers_queue SET status = 1 WHERE id = '$id'";
            mysqli_query($conn, $update_status_sql);
            header("Location: archive-youth.php?action=retrieve_success");
            exit();
        }
    } elseif ($action == 'delete') {
        $delete_sql = "DELETE FROM accepted_members WHERE members_id = '$id'";
        if (mysqli_query($conn, $delete_sql)) {
            header("Location: archive-youth.php?action=delete_success");
            exit();
        }
    }
}

if (isset($_GET['action'])) {
    if ($_GET['action'] === 'retrieve_success') {
        $showRetrieveSuccess = true;
    } elseif ($_GET['action'] === 'delete_success') {
        $showDeleteSuccess = true;
    }
}

// Auto-archive members with age >= 31
$update_archive_sql = 
    "UPDATE accepted_members a
    JOIN skmembers_queue q ON a.members_id = q.id
    SET a.archive = 'Yes'
    WHERE q.age >= 31";
mysqli_query($conn, $update_archive_sql);

// Fetch archived members
$archived_sql = 
    "SELECT a.res_id, q.id, q.first_name, q.middle_name, q.last_name, q.gender, q.age
    FROM accepted_members a
    JOIN skmembers_queue q ON a.members_id = q.id
    WHERE a.archive = 'Yes'";
$archived_result = mysqli_query($conn, $archived_sql);

if (!$archived_result) {
    die("Error fetching archived members: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg" />
    <title>SK Member Dashboard</title>
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
        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-container {
            position: relative;
            width: 25%;
            display: flex;
            align-items: center;
        }
        .search-icon {
            position: absolute;
            left: 10px;
            font-size: 1.2rem;
            color: gray;
        }
        .search-input {
            padding-left: 35px;
            height: 40px;
        }
        #pagination button.active {
            background-color: #00205b;
            color: white;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main>
        <div style="width: 90%; margin: 0 auto;" class="d-flex justify-content-between align-items-center my-3">
            <h1 class="m-0" style="font-size: 24px; color: #00205b;">Accepted Resident to be a Member</h1>

            <div class="d-flex align-items-center gap-2">
                <select id="entriesPerPage" class="form-select form-select-sm" style="width: 100px;">
                    <option value="10">Show 10</option>
                    <option value="25" selected>Show 25</option>
                    <option value="50">Show 50</option>
                </select>

                <div class="position-relative" style="width: 220px;">
                    <i class="fa fa-search position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"></i>
                    <input
                        type="text"
                        id="acceptedSearch"
                        class="form-control form-control-sm ps-4 text-center"
                        placeholder="Search name & ID" />
                </div>
            </div>
        </div>
        
        <table id="acceptedTable">
            <thead>
                <tr>
                    <th>Resident ID</th>
                    <th>Full Name</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($archived_result)) : ?>
                <tr class="resident-row">
                    <td><?php echo htmlspecialchars($row['res_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['gender']); ?></td>
                    <td><?php echo htmlspecialchars($row['age']); ?></td>
                    <td class="action-container">
                        <a href="#" class="btn btn-warning btn-icon retrieve-btn" data-id="<?php echo $row['id']; ?>" title="Retrieve">
                            <i class="fa fa-undo"></i>
                        </a>
                        <a href="#" class="btn btn-danger btn-icon delete-btn" data-id="<?php echo $row['id']; ?>" title="Delete">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div id="pagination" class="d-flex justify-content-center mb-4 mt-2"></div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const entriesSelect = document.getElementById("entriesPerPage");
                const table = document.getElementById("acceptedTable");
                const tbody = table.querySelector("tbody");
                const rows = Array.from(tbody.querySelectorAll(".resident-row"));
                const pagination = document.getElementById("pagination");

                let currentPage = 1;
                let rowsPerPage = parseInt(entriesSelect.value);

                function displayTableRows() {
                    let start = (currentPage - 1) * rowsPerPage;
                    let end = start + rowsPerPage;

                    rows.forEach((row, index) => {
                        row.style.display = (index >= start && index < end) ? "" : "none";
                    });
                }

                function setupPagination() {
                    pagination.innerHTML = "";
                    let pageCount = Math.ceil(rows.length / rowsPerPage);

                    for (let i = 1; i <= pageCount; i++) {
                        let btn = document.createElement("button");
                        btn.textContent = i;
                        btn.className = "btn btn-outline-primary btn-sm mx-1" + (i === currentPage ? " active" : "");
                        btn.addEventListener("click", function () {
                            currentPage = i;
                            displayTableRows();
                            setupPagination();
                        });
                        pagination.appendChild(btn);
                    }
                }

                entriesSelect.addEventListener("change", function () {
                    rowsPerPage = parseInt(this.value);
                    currentPage = 1;
                    displayTableRows();
                    setupPagination();
                });

                displayTableRows();
                setupPagination();

                // Search
                document.getElementById("acceptedSearch").addEventListener("keyup", function () {
                    let filter = this.value.toUpperCase();
                    rows.forEach((row) => {
                        let text = row.innerText.toUpperCase();
                        row.style.display = text.includes(filter) ? "" : "none";
                    });
                });

                // Retrieve
                document.querySelectorAll(".retrieve-btn").forEach((button) => {
                    button.addEventListener("click", function (event) {
                        event.preventDefault();
                        let memberId = this.getAttribute("data-id");
                        Swal.fire({
                            title: "Are you sure?",
                            text: "Do you want to retrieve this member?",
                            icon: "question",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#aaa",
                            confirmButtonText: "Yes, retrieve",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `archive-youth.php?action=retrieve&id=${memberId}`;
                            }
                        });
                    });
                });

                // Delete
                document.querySelectorAll(".delete-btn").forEach((button) => {
                    button.addEventListener("click", function (event) {
                        event.preventDefault();
                        let memberId = this.getAttribute("data-id");
                        Swal.fire({
                            title: "Are you sure?",
                            text: "You won't be able to revert this!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#d33",
                            cancelButtonColor: "#3085d6",
                            confirmButtonText: "Yes, delete it!",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `archive-youth.php?action=delete&id=${memberId}`;
                            }
                        });
                    });
                });

                // Alerts
                <?php if ($showRetrieveSuccess): ?>
                Swal.fire({
                    icon: "success",
                    title: "Member Retrieved!",
                    text: "The member has been successfully retrieved.",
                    confirmButtonText: "OK",
                }).then(() => {
                    window.location.href = "archive-youth.php";
                });
                <?php endif; ?>

                <?php if ($showDeleteSuccess): ?>
                Swal.fire({
                    icon: "success",
                    title: "Member Deleted!",
                    text: "The member has been successfully deleted.",
                    confirmButtonText: "OK",
                }).then(() => {
                    window.location.href = "archive-youth.php";
                });
                <?php endif; ?>
            });
        </script>
    </main>
</body>

<?php include "footer.php"; ?>
</html>
