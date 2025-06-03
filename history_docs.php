<?php
include 'database.php';

session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM completed_doc_requests WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    echo "<script>
        localStorage.setItem('deleted', 'true');
        window.location.href = 'history_docs.php';
    </script>";
    exit;
}

// Pagination settings
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 25;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get total records
$totalQuery = "SELECT COUNT(*) as total FROM completed_doc_requests";
$totalResult = $conn->query($totalQuery)->fetch_assoc();
$totalRecords = $totalResult['total'];
$totalPages = ceil($totalRecords / $limit);

// Fetch paginated records
$stmt = $conn->prepare("SELECT * FROM completed_doc_requests LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$completedResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Completed Document Requests</title>
    <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
        }
        h1 {
            font-size: 24px;
            color: #00205b;
            margin-left: 2.5%;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 65%;
            margin: auto;
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
        .icon-btn.view-btn:hover {
            background-color: #c2e0ff;
        }
        .icon-btn.delete-btn {
            background-color: #ffe0e0;
            color: red;
        }
        .icon-btn.delete-btn:hover {
            background-color: #ffcccc;
        }
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
        select {
            padding: 5px 10px;
            border-radius: 6px;
        }
    </style>
</head>

<?php include 'navbar.php'; ?>

<body>
<main>
    <div class="top-bar">
        <h1>Completed Documents</h1>
        <form method="get" id="limitForm">
            <label for="limit" class="form-label me-2" style="color:#00205b; font-weight:600;">Show:</label>
            <select name="limit" id="limit" onchange="document.getElementById('limitForm').submit()">
                <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
                <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
            </select>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Resident ID</th>
                <th>Name</th>
                <th>Document</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $completedResult->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['res_id']) ?></td>
                    <td><?= htmlspecialchars($row['first_name'] . " " . $row['last_name']) ?></td>
                    <td>
                        <a href="uploads/<?= htmlspecialchars($row['docs_filename']) ?>" target="_blank" class="icon-btn view-btn" title="View Document">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                    </td>
                    <td>
                        <form method="post" class="delete-form">
                            <input type="hidden" name="delete_id" value="<?= htmlspecialchars($row['id']) ?>">
                            <button type="button" class="btn btn-danger btn-icon delete-btn" title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>&limit=<?= $limit ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
</main>

<script>
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('form');
            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the document record.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    if (localStorage.getItem('deleted') === 'true') {
        Swal.fire({
            icon: 'success',
            title: 'Deleted!',
            text: 'Document deleted successfully.'
        });
        localStorage.removeItem('deleted');
    }
</script>

<?php include 'footer.php'; ?>
</body>
</html>
