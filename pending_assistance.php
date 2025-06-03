<?php
// Start PHP processing at the top
include('database.php');
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Handle POST request to accept assistance
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accept_res_id'], $_POST['year_level'])) {
    $res_id = $_POST['accept_res_id'];
    $year_level = $_POST['year_level'];

    if (!empty($res_id) && !empty($year_level)) {
        $stmt = $conn->prepare("INSERT INTO accepted_for_assistance (res_id, year_level) VALUES (?, ?)");
        $stmt->bind_param("ss", $res_id, $year_level);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Database error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Invalid input.";
    }
    $conn->close();
    exit;
}

// Fetch data for the table
$sql = "SELECT ar.id, ar.res_id, sq.first_name, sq.last_name, ar.year_level, ar.ccog_filename, ar.cor_filename 
        FROM assistance_req ar
        JOIN accepted_members am ON ar.res_id = am.res_id
        JOIN skmembers_queue sq ON am.members_id = sq.id
        WHERE ar.res_id NOT IN (SELECT res_id FROM accepted_for_assistance)";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Assistance Requests</title>
    <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        button {
            padding: 8px 15px;
            margin: 5px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }
        .accept-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
        }
        iframe {
            width: 100%;
            height: 500px;
            display: none;
            border: none;
        }
        .search-container {
            margin-bottom: 15px;
            position: relative;
            width: 27%;
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
        }
    </style>
</head>

<body>
<?php include 'navbar.php'; ?>
<main>
    <div style="width: 90%; display: flex; justify-content: space-between; align-items: center;">
        <h1>Pending Educational Assistance Requests</h1>
        <div class="search-container">
            <i class="fa fa-magnifying-glass search-icon"></i>
            <input type="text" id="acceptedSearch" class="form-control text-center" placeholder="Search Resident Name or Year Level" style="height: 40px;">
        </div>
    </div>

    <table id="acceptedTable">
        <thead>
            <tr>
                <th>Resident Name</th>
                <th>Year Level</th>
                <th>Grades</th>
                <th>COR</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                <td><?= htmlspecialchars($row['year_level']) ?></td>
                <td>
                    <button class="btn btn-primary" onclick="showDocument('<?= htmlspecialchars($row['ccog_filename']) ?>')">Show Grades</button>
                </td>
                <td>
                    <button class="btn btn-primary" onclick="showDocument('<?= htmlspecialchars($row['cor_filename']) ?>')">Show COR</button>
                </td>
                <td>
                    <button class="accept-btn"
                        data-res-id="<?= htmlspecialchars($row['res_id']) ?>"
                        data-year-level="<?= htmlspecialchars($row['year_level']) ?>">
                        Accept
                    </button>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</main>

<iframe id="documentViewer"></iframe>

<script>
    function showDocument(filePath) {
        const viewer = document.getElementById('documentViewer');
        viewer.src = filePath;
        viewer.style.display = 'block';
    }

    document.querySelectorAll('.accept-btn').forEach(button => {
    button.addEventListener('click', function () {
        const resId = this.getAttribute('data-res-id');
        const yearLevel = this.getAttribute('data-year-level');

        Swal.fire({
            title: 'Accept Request?',
            text: "Are you sure you want to accept this request?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, accept it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `accept_res_id=${encodeURIComponent(resId)}&year_level=${encodeURIComponent(yearLevel)}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data.includes("success")) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Accepted!',
                            text: 'Assistance request has been accepted.',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = 'accepted_assistance.php';
                        });
                    } else {
                        throw new Error(data);
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong: ' + error.message
                    });
                });
            }
        });
    });
});
</script>

<?php include 'footer.php'; ?>
</body>
</html>
