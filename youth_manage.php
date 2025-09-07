<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// PHPMailer setup (move these lines here)
require __DIR__ . '/PHPMailer/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/PHPMailer-master/src/SMTP.php';
require __DIR__ . '/PHPMailer/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include('database.php');

// Validate database connection
if (!$conn) {
    die("Database connection error: " . pg_last_error());
}

// Fetch the next available res_id
$res_id_sql = "SELECT MAX(res_id) AS max_res_id FROM accepted_members";
$res_id_result = pg_query($conn, $res_id_sql);
if ($res_id_result) {
    $row = pg_fetch_assoc($res_id_result);
    $next_res_id = $row['max_res_id'] ? $row['max_res_id'] + 1 : 2025001;
} else {
    die("Error fetching next res_id: " . pg_last_error($conn));
}

// Handle actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = intval($_GET['id']);

    if ($action == 'accept') {
        // 1. Assign the next available Resident ID
        // 2. Move user from pending to accepted_members
        // 3. Update status in skmembers_queue
        // 4. Send email notification

        // Fetch user info for email
        $info_sql = "SELECT email, first_name FROM skmembers_queue WHERE id = $1";
        $info_result = pg_query_params($conn, $info_sql, [$id]);
        if ($info_row = pg_fetch_assoc($info_result)) {
            $user_email = $info_row['email'];
            $user_name = $info_row['first_name'];
            $res_id = $next_res_id; // The assigned Resident ID

            // Insert into accepted_members
            $insert_sql = "INSERT INTO accepted_members (members_id, res_id, archive) VALUES ($1, $2, 'No')";
            $insert_result = pg_query_params($conn, $insert_sql, [$id, $res_id]);

            // Update status in skmembers_queue
            $update_sql = "UPDATE skmembers_queue SET status = '1' WHERE id = $1";
            pg_query_params($conn, $update_sql, [$id]);

            // PHPMailer code (object creation and sending)
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'bmajck00@gmail.com';
                $mail->Password   = 'psrk suml kthe lxak'; // your app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('bmajck00@gmail.com', 'SK Barangay 252');
                $mail->addAddress($user_email, $user_name);
                $mail->isHTML(true);
                $mail->Subject = 'Your SK Barangay ID Request is Approved';
                $mail->Body    = "
                    <p>Dear $user_name,</p>
                    <p>Your request for an SK Barangay ID has been <b>approved</b>.</p>
                    <p>Your assigned Resident ID is: <b>$res_id</b></p>
                    <p>You may now use your Resident ID for SK Barangay services.</p>
                    <br>
                    <p>Thank you,<br>SK Barangay Admin</p>
                ";

                $mail->send();
            } catch (Exception $e) {
                // Optionally log or handle the error
            }

            // Redirect or show a success message
            header("Location: pending_reqID.php?success=1");
            exit();
        } else {
            // Handle error: user not found
            header("Location: pending_reqID.php?error=notfound");
            exit();
        }
    } elseif ($action == 'archive') {
        $update_archive_sql = "UPDATE accepted_members SET archive = 'Yes' WHERE members_id = $1";
        $archive_result = pg_query_params($conn, $update_archive_sql, [$id]);
        if ($archive_result) {
            $update_status_sql = "UPDATE skmembers_queue SET status = 1 WHERE id = $1";
            pg_query_params($conn, $update_status_sql, [$id]);
        } else {
            die("Error archiving member: " . pg_last_error($conn));
        }
        header("Location: youth_manage.php?archived=1");
        exit();
    } elseif ($action == 'delete') {
        $delete_accepted_sql = "DELETE FROM accepted_members WHERE members_id = $1";
        $delete_queue_sql = "DELETE FROM skmembers_queue WHERE id = $1";
        pg_query_params($conn, $delete_accepted_sql, [$id]);
        pg_query_params($conn, $delete_queue_sql, [$id]);
        header("Location: youth_manage.php");
        exit();
    }
}

// Auto-archive by age
$update_archive_sql = 
    "UPDATE accepted_members a
    SET archive = 'Yes'
    FROM skmembers_queue q
    WHERE a.members_id = q.id AND q.age >= 31";
pg_query($conn, $update_archive_sql);

// Fetch accepted, unarchived youth members
$accepted_sql = 
    "SELECT 
        a.res_id, 
        q.id,
        q.first_name, 
        q.middle_name,
        q.last_name, 
        q.gender, 
        q.age,
        q.address
    FROM accepted_members a
    JOIN skmembers_queue q ON a.members_id = q.id
    WHERE a.archive = 'No'
    AND q.age BETWEEN 1 AND 30";
$accepted_result = pg_query($conn, $accepted_sql);

if (!$accepted_result) {
    die("Error fetching accepted members: " . pg_last_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Add Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
        .swal2-card-popup {
            border-radius: 16px !important;
            box-shadow: 0 6px 24px rgba(0,32,91,0.15) !important;
            padding: 0 !important;
        }
        .swal2-card-popup .swal2-html-container {
            margin: 0 !important;
            padding: 0 0 10px 0 !important;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <main>
        <div style="width: 90%; display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <h1>Accepted Resident to be a Member</h1>
            <div style="display: flex; align-items: center; gap: 10px;">
                <!-- Add Upload Buttons -->
                <div class="btn-group">
                    <button type="button" class="btn btn-success" id="uploadBtn">
                        <i class="fa fa-upload"></i> Upload CSV
                    </button>
                    <button type="button" class="btn btn-info" id="downloadTemplateBtn">
                        <i class="fa fa-download"></i> Download Template
                    </button>
                </div>
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

        <!-- Add Upload Modal -->
        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Upload Youth Data CSV</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="uploadForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="csvFile" class="form-label">Select CSV File</label>
                                <input type="file" class="form-control" id="csvFile" name="csv_file" accept=".csv" required>
                            </div>
                            <div class="alert alert-info">
                                <small>
                                    <strong>Note:</strong> Please make sure your CSV file follows the template format.
                                    You can download the template using the "Download Template" button.
                                </small>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="submitUpload">Upload</button>
                    </div>
                </div>
            </div>
        </div>

        <table id="acceptedTable">
            <thead>
                <tr>
                    <th>Resident ID</th>
                    <th>Full Name</th>
                    <th>Address</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = pg_fetch_assoc($accepted_result)) : ?>
                    <tr class="resident-row">
                        <td><?= htmlspecialchars($row['res_id']) ?></td>
                        <td>
                            <a href="#" class="member-name-link" data-id="<?= $row['id'] ?>">
                                <?= htmlspecialchars($row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($row['address']) ?></td>
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
                            <h4 style="margin:0 0 10px 0;color:#00205b;">
                                ${data.first_name} ${data.middle_name} ${data.last_name} ${data.suffix || ''}
                            </h4>
                            <table class="table table-sm table-borderless" style="margin-bottom:0;">
                                <tbody>
                                    <tr><th>Resident ID:</th><td>${data.res_id || ''}</td></tr>
                                    <tr><th>Address:</th><td>${data.address}</td></tr>
                                    <tr><th>Contact 1:</th><td>${data.contact_num1}</td></tr>
                                    <tr><th>Contact 2:</th><td>${data.contact_num2}</td></tr>
                                    <tr><th>Email:</th><td>${data.email}</td></tr>
                                    <tr><th>Gender:</th><td>${data.gender}</td></tr>
                                    <tr><th>Age:</th><td>${data.age}</td></tr>
                                    <tr><th>Blood Type:</th><td>${data.blood_type}</td></tr>
                                    <tr><th>Date of Birth:</th><td>${data.dob}</td></tr>
                                    <tr><th>Religion:</th><td>${data.religion}</td></tr>
                                    <tr><th>PWD:</th><td>${data.PWD}</td></tr>
                                    <tr><th>Nationality:</th><td>${data.nationality}</td></tr>
                                    <tr><th>Father's Name:</th><td>${data.father_fullname}</td></tr>
                                    <tr><th>Mother's Name:</th><td>${data.mother_fullname}</td></tr>
                                    <tr><th>Contact Person:</th><td>${data.contact_person}</td></tr>
                                    <tr><th>Relationship:</th><td>${data.cp_relationship}</td></tr>
                                    <tr><th>Contact Person Number:</th><td>${data.cp_contactnum}</td></tr>
                                </tbody>
                            </table>
                        `;
                        Swal.fire({
                            title: 'Member Information',
                            html: htmlContent,
                            width: 650,
                            showCloseButton: true,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'swal2-card-popup'
                            }
                        });
                    });
            });
        });

        // Print button
        document.getElementById("printTable").addEventListener("click", () => {
            window.print();
        });

        // Upload button click handler
        document.getElementById('uploadBtn').addEventListener('click', function() {
            const uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
            uploadModal.show();
        });

        // Download template button click handler
        document.getElementById('downloadTemplateBtn').addEventListener('click', function() {
            window.location.href = 'upload_youth_data.php?download_template=1';
        });

        // Submit upload button click handler
        document.getElementById('submitUpload').addEventListener('click', function() {
            const form = document.getElementById('uploadForm');
            const formData = new FormData(form);
            const fileInput = document.getElementById('csvFile');

            if (!fileInput.files.length) {
                Swal.fire({
                    icon: 'error',
                    title: 'No File Selected',
                    text: 'Please select a CSV file to upload.',
                });
                return;
            }

            // Show loading state
            Swal.fire({
                title: 'Uploading...',
                text: 'Please wait while we process your file.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('upload_youth_data.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Close the upload modal
                const modalInstance = bootstrap.Modal.getInstance(document.getElementById('uploadModal'));
                modalInstance.hide();
                
                // Show result
                Swal.fire({
                    icon: data.success ? 'success' : 'error',
                    title: data.success ? 'Upload Complete' : 'Upload Failed',
                    text: data.message,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (data.success) {
                        // Reload the page to show new data
                        window.location.reload();
                    }
                });
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Failed',
                    text: 'An error occurred while uploading the file.',
                });
            });
        });

        // Reset form when modal is closed
        document.getElementById('uploadModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('uploadForm').reset();
        });
    </script>
</body>
</html>
