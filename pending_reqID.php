<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include('database.php');
$sql = "SELECT id, first_name, middle_name, last_name, gender, age, email, status FROM skmembers_queue WHERE status = 0";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching data: " . mysqli_error($conn));
}

$members = [];
while ($row = mysqli_fetch_assoc($result)) {
    $members[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg" />
    <title>Pending Residents</title>
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
        footer {
            background-color: #00205b;
            color: white;
            text-align: center;
            padding: 15px 0;
            width: 100%;
            margin-top: auto;
        }
        h1 {
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
        .accept-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-container {
            position: relative;
            width: 250px;
        }
        .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2rem;
            color: gray;
        }
        .search-input {
            padding-left: 35px;
        }
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 10px;
            gap: 10px;
        }
        .page-btn {
            border: 1px solid #00205b;
            background-color: white;
            color: #00205b;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .page-btn.active {
            background-color: #00205b;
            color: white;
        }
        @media print {
            body *:not(#printArea):not(#printArea *) {
                visibility: hidden;
            }
            #printArea {
                position: absolute;
                left: 0;
                top: 0;
                visibility: visible;
                width: 100%;
            }
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<main>
    <div style="width: 90%; display: flex; justify-content: space-between; align-items: center;">
        <h1>Pending Request</h1>
        <div style="display: flex; align-items: center; gap: 10px;">
            <select id="rowsPerPage" class="form-select form-select-sm" style="width: 100px;">
                <option value="10">Show 10</option>
                <option value="25" selected>Show 25</option>
                <option value="50">Show 50</option>
                <option value="100">Show 100</option>
            </select>
            <div class="search-container">
                <i class="fa fa-search search-icon"></i>
                <input type="text" id="searchInput" class="form-control search-input" placeholder="Search name & email">
            </div>
        </div>
    </div>

    <div id="printArea" style="width: 100%;">
        <table id="pendingTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>
    </div>

    <div class="pagination-container" id="pagination"></div>

    <!-- Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Acceptance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Are you sure you want to accept this resident?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <a id="confirmAcceptBtn" class="btn btn-success">Yes, Accept</a>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    const members = <?= json_encode($members); ?>;
    const tableBody = document.getElementById("tableBody");
    const rowsPerPageSelect = document.getElementById("rowsPerPage");
    const searchInput = document.getElementById("searchInput");
    const pagination = document.getElementById("pagination");
    let currentPage = 1;

    function confirmAccept(id) {
        document.getElementById("confirmAcceptBtn").href = "youth_manage.php?action=accept&id=" + id;
        new bootstrap.Modal(document.getElementById("confirmModal")).show();
    }

    function renderTable() {
        const search = searchInput.value.toLowerCase();
        const rowsPerPage = parseInt(rowsPerPageSelect.value);
        const filtered = members.filter(m => (
            m.first_name.toLowerCase().includes(search) ||
            m.middle_name.toLowerCase().includes(search) ||
            m.last_name.toLowerCase().includes(search) ||
            m.email.toLowerCase().includes(search)
        ));

        const totalPages = Math.ceil(filtered.length / rowsPerPage);
        if (currentPage > totalPages) currentPage = 1;
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        const visibleRows = filtered.slice(start, end);
        tableBody.innerHTML = visibleRows.map((row, index) => `
            <tr>
                <td>${start + index + 1}</td>
                <td>${row.first_name}</td>
                <td>${row.middle_name}</td>
                <td>${row.last_name}</td>
                <td>${row.gender}</td>
                <td>${row.age}</td>
                <td>${row.email}</td>
                <td>
                    <button class="accept-btn" onclick="confirmAccept(${row.id})" title="Approve">
                        <i class="fas fa-check"></i>
                    </button>
                </td>
            </tr>
        `).join("");

        pagination.innerHTML = "";
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement("button");
            btn.textContent = i;
            btn.className = `page-btn ${i === currentPage ? "active" : ""}`;
            btn.onclick = () => {
                currentPage = i;
                renderTable();
            };
            pagination.appendChild(btn);
        }
    }

    rowsPerPageSelect.addEventListener("change", () => {
        currentPage = 1;
        renderTable();
    });
    searchInput.addEventListener("input", () => {
        currentPage = 1;
        renderTable();
    });

    window.addEventListener("load", renderTable);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>
