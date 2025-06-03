<?php
include 'database.php';

session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Pagination variables
$rowsPerPageOptions = [25, 50];
$defaultRowsPerPage = 25;

// Get rows per page from GET or use default
$rowsPerPage = isset($_GET['rows']) && in_array((int)$_GET['rows'], $rowsPerPageOptions) ? (int)$_GET['rows'] : $defaultRowsPerPage;

// Get current page from GET or default to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;

// Count total rows
$countQuery = "SELECT COUNT(*) AS total FROM docreq_queue dq
    JOIN accepted_members am ON dq.sk_id = am.res_id
    JOIN skmembers_queue sq ON am.members_id = sq.id";
$countResult = $conn->query($countQuery);
$totalRows = $countResult->fetch_assoc()['total'] ?? 0;

$totalPages = ceil($totalRows / $rowsPerPage);
if ($page > $totalPages && $totalPages > 0) {
    $page = $totalPages;
}

// Calculate offset for SQL LIMIT
$offset = ($page - 1) * $rowsPerPage;

// Fetch paginated results
$query = "SELECT dq.id, am.res_id, sq.first_name, sq.last_name, dq.docs_filename 
          FROM docreq_queue dq
          JOIN accepted_members am ON dq.sk_id = am.res_id
          JOIN skmembers_queue sq ON am.members_id = sq.id
          ORDER BY dq.id DESC
          LIMIT ?, ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $offset, $rowsPerPage);
$stmt->execute();
$pendingResult = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['done_id'])) {
    $done_id = $_POST['done_id'];

    $fetchQuery = "SELECT am.res_id, sq.first_name, sq.last_name, dq.docs_filename 
                   FROM docreq_queue dq
                   JOIN accepted_members am ON dq.sk_id = am.res_id
                   JOIN skmembers_queue sq ON am.members_id = sq.id
                   WHERE dq.id = ?";
    $stmtFetch = $conn->prepare($fetchQuery);
    $stmtFetch->bind_param("i", $done_id);
    $stmtFetch->execute();
    $result_fetch = $stmtFetch->get_result();
    $docData = $result_fetch->fetch_assoc();
    $stmtFetch->close();

    if ($docData) {
        $insertQuery = "INSERT INTO completed_doc_requests (res_id, first_name, last_name, docs_filename) 
                        VALUES (?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($insertQuery);
        $stmtInsert->bind_param("isss", $docData['res_id'], $docData['first_name'], $docData['last_name'], $docData['docs_filename']);
        $stmtInsert->execute();
        $stmtInsert->close();

        $deleteQuery = "DELETE FROM docreq_queue WHERE id = ?";
        $stmtDelete = $conn->prepare($deleteQuery);
        $stmtDelete->bind_param("i", $done_id);
        $stmtDelete->execute();
        $stmtDelete->close();
    }

    $conn->close();
    echo "<script>
        localStorage.setItem('doc_done', 'true');
        window.location.href = window.location.pathname + '?rows=$rowsPerPage&page=$page';
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pending Document Requests</title>
    <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
    />
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 100px;
            margin-left: 200px;
            transition: margin-left 0.3s;
            background: #f9f9f9;
        }
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }
        .header-container {
            width: 65%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        h1 {
            font-size: 24px;
            color: #00205b;
            margin: 0;
            user-select: none;
        }
        select#rowsPerPage {
            padding: 6px 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            background: white;
            font-size: 16px;
            cursor: pointer;
            user-select: none;
        }
        table {
            width: 65%;
            margin: 0 auto 30px;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        td form {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        th {
            background-color: #00205b;
            color: white;
        }
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .btn-custom {
            display: inline-flex;
            padding: 6px 12px;
            font-size: 14px;
            border: none;
            color: white;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        .icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }
        .icon-btn.view-btn {
            background-color: #e0f0ff;
            color: #0056b3;
        }
        .btn-view:hover {
            background-color: #5a32a3;
        }
        .btn-done {
            background-color: #28a745;
            border-radius: 20px;
        }
        .btn-done:hover {
            background-color: #218838;
        }
        /* Pagination Styles */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px auto;
        }
        .pagination a {
            padding: 8px 12px;
            background-color: #00205b;
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }
        .pagination a.active {
            background-color: #0056b3;
        }
        .pagination a:hover {
            background-color: #004494;
        }
    </style>
</head>

<?php include 'navbar.php'; ?>

<body>
    <main>
        <div class="header-container">
            <h1>Pending Documents to Print</h1>
            <div>
                <label for="rowsPerPage" class="form-label me-2" style="color:#00205b; font-weight:600;">Show:</label>
                <select id="rowsPerPage" name="rowsPerPage">
                    <?php foreach ($rowsPerPageOptions as $option): ?>
                        <option value="<?= $option ?>" <?= $option === $rowsPerPage ? 'selected' : '' ?>><?= $option ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

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
                <?php if ($pendingResult->num_rows === 0): ?>
                    <tr><td colspan="4">No pending document requests.</td></tr>
                <?php else: ?>
                    <?php while ($row = $pendingResult->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['res_id']) ?></td>
                            <td><?= htmlspecialchars($row['first_name'] . " " . $row['last_name']) ?></td>
                            <td>
                                <a href="uploads/<?= htmlspecialchars($row['docs_filename']) ?>" target="_blank" class="icon-btn view-btn" title="View Document">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                            </td>
                            <td>
                                <form method="post" class="done-form">
                                    <input type="hidden" name="done_id" value="<?= htmlspecialchars($row['id']) ?>">
                                    <button type="button" class="btn-custom btn-done done-confirm" title="Mark as Done">
                                        <i class="bi bi-check-circle-fill"></i> Done
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination Controls -->
        <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>&limit=<?= $limit ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
    </main>

<?php include 'footer.php'; ?>

<script>
    // SweetAlert2 confirmation for Done button
    document.querySelectorAll('.done-confirm').forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('form');
            Swal.fire({
                title: 'Are you sure?',
                text: "Mark this document request as done?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, mark as done!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Show success alert if redirected after marking done
    if (localStorage.getItem('doc_done') === 'true') {
        localStorage.removeItem('doc_done');
        Swal.fire({
            title: 'Success!',
            text: 'Document marked as done.',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'history_docs.php';
        });
    }

    // Change rows per page reload
    document.getElementById('rowsPerPage').addEventListener('change', function () {
        const selectedRows = this.value;
        // Reload with rows param, reset to page 1
        const url = new URL(window.location);
        url.searchParams.set('rows', selectedRows);
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    });

    // Pagination buttons logic
    document.querySelectorAll('.pagination button[data-page]').forEach(button => {
        button.addEventListener('click', () => {
            const page = button.getAttribute('data-page');
            const url = new URL(window.location);
            url.searchParams.set('page', page);
            // preserve current rows per page
            const rows = document.getElementById('rowsPerPage').value;
            url.searchParams.set('rows', rows);
            window.location.href = url.toString();
        });
    });
</script>
</body>
</html>
