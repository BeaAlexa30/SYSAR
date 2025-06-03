<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include('database.php');

// Fetch the next available res_id
$res_id_sql = "SELECT MAX(res_id) AS max_res_id FROM accepted_members";
$res_id_result = mysqli_query($conn, $res_id_sql);
$row = mysqli_fetch_assoc($res_id_result);
$next_res_id = $row['max_res_id'] ? $row['max_res_id'] + 1 : 2025001;

// Handle actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    if ($action == 'accept') {
        $insert_sql = "INSERT INTO accepted_members (members_id, res_id) VALUES ('$id', '$next_res_id')";
        if (mysqli_query($conn, $insert_sql)) {
            $update_sql = "UPDATE skmembers_queue SET status = 1 WHERE id = '$id'";
            mysqli_query($conn, $update_sql);
        }
    } elseif ($action == 'archive') {
        $update_archive_sql = "UPDATE accepted_members SET archive = 'Yes' WHERE members_id = '$id'";
        if (mysqli_query($conn, $update_archive_sql)) {
            $update_status_sql = "UPDATE skmembers_queue SET status = 1 WHERE id = '$id'";
            mysqli_query($conn, $update_status_sql);
        }
        header("Location: youth_manage.php?archived=1");
        exit();
    } elseif ($action == 'delete') {
        $delete_accepted_sql = "DELETE FROM accepted_members WHERE members_id = '$id'";
        $delete_queue_sql = "DELETE FROM skmembers_queue WHERE id = '$id'";
        mysqli_query($conn, $delete_accepted_sql);
        mysqli_query($conn, $delete_queue_sql);
        mysqli_query($conn, "SET @num := 0");
        mysqli_query($conn, "UPDATE skmembers_queue SET id = @num := @num + 1 ORDER BY id");
        mysqli_query($conn, "ALTER TABLE skmembers_queue AUTO_INCREMENT = 1");
        mysqli_query($conn, "SET @num := 0");
        mysqli_query($conn, "UPDATE accepted_members SET id = @num := @num + 1, members_id = @num ORDER BY id");
        mysqli_query($conn, "ALTER TABLE accepted_members AUTO_INCREMENT = 1");
        header("Location: youth_manage.php");
        exit();
    }
}

// Auto-archive by age
$update_archive_sql = 
    "UPDATE accepted_members a
    JOIN skmembers_queue q ON a.members_id = q.id
    SET a.archive = 'Yes'
    WHERE q.age >= 31";
mysqli_query($conn, $update_archive_sql);

// Fetch accepted, unarchived youth members (without filename)
$accepted_sql = 
    "SELECT 
        a.res_id, 
        q.id,
        q.first_name, 
        q.middle_name,
        q.last_name, 
        q.gender, 
        q.age
    FROM accepted_members a
    JOIN skmembers_queue q ON a.members_id = q.id
    WHERE a.archive = 'No'
    AND q.age BETWEEN 1 AND 30";
$accepted_result = mysqli_query($conn, $accepted_sql);

if (!$accepted_result) {
    die("Error fetching accepted members: " . mysqli_error($conn));
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
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <title>SK Member Dashboard</title>
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
        .search-container {
            position: relative;
            width: 50%;
            display: flex;
            align-items: center;
            gap: 0.75rem;
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
            flex: 1;
        }
        .rows-per-page {
            width: 80px;
            height: 40px;
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 0 8px;
            font-size: 1rem;
        }
        .btn-print {
            height: 40px;
            border-radius: 5px;
            border: none;
            background-color: #00205b;
            color: white;
            padding: 0 15px;
            font-size: 1.2rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background-color 0.3s ease;
        }
        .btn-print:hover {
            background-color: #004080;
        }
        /* Pagination styles */
        .pagination {
            margin: 10px auto 40px;
            display: flex;
            justify-content: center;
            gap: 5px;
            user-select: none;
        }
        .pagination button {
            border: 1px solid #00205b;
            background-color: white;
            color: #00205b;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .pagination button.active,
        .pagination button:hover {
            background-color: #00205b;
            color: white;
        }
        /* For print: only print the table */
        @media print {
            body * {
                visibility: hidden;
            }
            #acceptedTable, #acceptedTable * {
                visibility: visible;
            }
            #acceptedTable {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            /* Hide the last column (Action) */
            #acceptedTable th:last-child,
            #acceptedTable td:last-child {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <main>
        <div style="width: 90%; display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <h1>Accepted Resident to be a Member</h1>
            <div style="display: flex; align-items: center; gap: 10px;">
                <select id="rowsPerPage" class="form-select form-select-sm" style="width: 100px;">
                    <option value="10">Show 10</option>
                    <option value="25" selected>Show 25</option>
                    <option value="50">Show 50</option>
                    <option value="100">Show 100</option>
                </select>
                <div class="search-container">
                    <i class="fa fa-search search-icon"></i>
                    <input type="text" id="searchInput" class="form-control search-input" placeholder="Search name & ID">
                </div>
                <button id="printTable" class="btn btn-outline-secondary btn-sm" title="Print Table">
                    <i class="fa fa-print"></i>
                </button>
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
                <?php while ($row = mysqli_fetch_assoc($accepted_result)) : ?>
                    <tr class="resident-row">
                        <td><?= htmlspecialchars($row['res_id']) ?></td>
                        <td>
                            <a href="#" class="member-name-link" data-id="<?= $row['id'] ?>">
                                <?= htmlspecialchars($row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($row['gender']) ?></td>
                        <td><?= htmlspecialchars($row['age']) ?></td>
                        <td>
                            <button class="btn btn-warning archive-btn" data-id="<?= $row['id'] ?>" title="Archive">
                                <i class="fa fa-archive"></i>
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Pagination container -->
        <div id="pagination" class="pagination"></div>
    </main>

    <script>
        const searchInput = document.getElementById("searchInput");
        const rowsPerPageSelect = document.getElementById("rowsPerPage");
        const acceptedTable = document.getElementById("acceptedTable");
        const tableBody = acceptedTable.querySelector("tbody");
        const paginationContainer = document.getElementById("pagination");

        let currentPage = 1;

        // Get all rows initially
        const allRows = Array.from(tableBody.querySelectorAll("tr.resident-row"));

        // Filter rows by search term
        function filterRows() {
            const filter = searchInput.value.toUpperCase();
            return allRows.filter(row => {
                const text = row.innerText.toUpperCase();
                return text.includes(filter);
            });
        }

        // Render rows for current page and filtered data
        function renderTable() {
            const filteredRows = filterRows();
            const rowsPerPage = parseInt(rowsPerPageSelect.value);
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);

            // Clamp currentPage if out of range
            if (currentPage > totalPages) currentPage = totalPages > 0 ? totalPages : 1;
            if (currentPage < 1) currentPage = 1;

            // Calculate start/end index
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            // Clear tbody and add filtered, paginated rows
            tableBody.innerHTML = "";
            filteredRows.slice(start, end).forEach(row => {
                tableBody.appendChild(row);
                row.style.display = "";
            });

            renderPagination(totalPages);
        }

        // Render pagination buttons
        function renderPagination(totalPages) {
            paginationContainer.innerHTML = "";

            if (totalPages <= 1) return; // No pagination needed

            // Previous button
            const prevBtn = document.createElement("button");
            prevBtn.textContent = "Previous";
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => {
                currentPage--;
                renderTable();
            };
            paginationContainer.appendChild(prevBtn);

            // Page buttons (show up to 7 pages max with current in center if possible)
            const maxPageButtons = 7;
            let startPage = Math.max(1, currentPage - Math.floor(maxPageButtons / 2));
            let endPage = startPage + maxPageButtons - 1;
            if (endPage > totalPages) {
                endPage = totalPages;
                startPage = Math.max(1, endPage - maxPageButtons + 1);
            }

            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = document.createElement("button");
                pageBtn.textContent = i;
                if (i === currentPage) pageBtn.classList.add("active");
                pageBtn.onclick = () => {
                    currentPage = i;
                    renderTable();
                };
                paginationContainer.appendChild(pageBtn);
            }

            // Next button
            const nextBtn = document.createElement("button");
            nextBtn.textContent = "Next";
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => {
                currentPage++;
                renderTable();
            };
            paginationContainer.appendChild(nextBtn);
        }

        // Event listeners
        searchInput.addEventListener("input", () => {
            currentPage = 1;
            renderTable();
        });

        rowsPerPageSelect.addEventListener("change", () => {
            currentPage = 1;
            renderTable();
        });

        // Initial table render
        renderTable();

        // Archive buttons
        document.querySelectorAll(".archive-btn").forEach(button => {
            button.addEventListener("click", function () {
                let memberId = this.getAttribute("data-id");
                Swal.fire({
                    title: "Are you sure?",
                    text: "This will archive the resident.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#ffc107",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, archive"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `youth_manage.php?action=archive&id=${memberId}`;
                    }
                });
            });
        });

        // SweetAlert success on archived=1 query param
        if (window.location.search.includes("archived=1")) {
            Swal.fire({
                title: "Archived!",
                text: "Resident has been archived successfully.",
                icon: "success",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            }).then(() => {
                window.location.href = "archive-youth.php";
            });
            if (history.replaceState) {
                const cleanUrl = window.location.href.split("?")[0];
                history.replaceState(null, null, cleanUrl);
            }
        }

        // Member info modal
        document.querySelectorAll(".member-name-link").forEach(link => {
            link.addEventListener("click", function(event) {
                event.preventDefault();
                let memberId = this.getAttribute("data-id");

                fetch(`view_member.php?id=${memberId}`)
                    .then(response => response.json())
                    .then(data => {
                        if(data.error) {
                            Swal.fire('Error', data.error, 'error');
                            return;
                        }

                        let htmlContent = `
                            <div style="text-align:left;">
                            <strong>Full Name:</strong> ${data.first_name} ${data.middle_name} ${data.last_name} ${data.suffix || ''}<br>
                            <strong>Address:</strong> ${data.address}<br>
                            <strong>Contact 1:</strong> ${data.contact_num1}<br>
                            <strong>Contact 2:</strong> ${data.contact_num2}<br>
                            <strong>Email:</strong> ${data.email}<br>
                            <strong>Gender:</strong> ${data.gender}<br>
                            <strong>Age:</strong> ${data.age}<br>
                            <strong>Blood Type:</strong> ${data.blood_type}<br>
                            <strong>Date of Birth:</strong> ${data.dob}<br>
                            <strong>Religion:</strong> ${data.religion}<br>
                            <strong>PWD:</strong> ${data.PWD}<br>
                            <strong>Nationality:</strong> ${data.nationality}<br>
                            <strong>Father's Name:</strong> ${data.father_fullname}<br>
                            <strong>Mother's Name:</strong> ${data.mother_fullname}<br>
                            <strong>Contact Person:</strong> ${data.contact_person}<br>
                            <strong>Relationship:</strong> ${data.cp_relationship}<br>
                            <strong>Contact Person Number:</strong> ${data.cp_contactnum}<br>
                            </div>
                        `;
                        Swal.fire({
                            title: 'Member Information',
                            html: htmlContent,
                            width: 600
                        });
                    });
            });
        });

        // Print button
        document.getElementById("printTable").addEventListener("click", () => {
            window.print();
        });
    </script>
</body>
</html>
