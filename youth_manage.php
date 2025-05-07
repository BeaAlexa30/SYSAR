<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include('database.php');

// Fetch the next available res_id (Auto-Increment Logic)
$res_id_sql = "SELECT MAX(res_id) AS max_res_id FROM accepted_members";
$res_id_result = mysqli_query($conn, $res_id_sql);
$row = mysqli_fetch_assoc($res_id_result);
$next_res_id = $row['max_res_id'] ? $row['max_res_id'] + 1 : 2025001;

// Handle "Accept" and "Unaccept" actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    if ($action == 'accept') {
        $insert_sql = "INSERT INTO accepted_members (members_id, res_id) VALUES ('$id', '$next_res_id')";
        if (mysqli_query($conn, $insert_sql)) {
            $update_sql = "UPDATE skmembers_queue SET status = 1 WHERE id = '$id'";
            mysqli_query($conn, $update_sql);
        }
    } elseif ($action == 'unaccept') {
        // Move to pending requests
        $delete_sql = "DELETE FROM accepted_members WHERE members_id = '$id'";
        if (mysqli_query($conn, $delete_sql)) {
            $update_sql = "UPDATE skmembers_queue SET status = 0 WHERE id = '$id'";
            mysqli_query($conn, $update_sql);
        }
        header("Location: pending_reqID.php");
        exit();
    } if ($action == 'delete') {
        // Delete from both tables
        $delete_accepted_sql = "DELETE FROM accepted_members WHERE members_id = '$id'";
        $delete_queue_sql = "DELETE FROM skmembers_queue WHERE id = '$id'";
     
        mysqli_query($conn, $delete_accepted_sql);
        mysqli_query($conn, $delete_queue_sql);
    
        // Reorder IDs in skmembers_queue
        mysqli_query($conn, "SET @num := 0");
        mysqli_query($conn, "UPDATE skmembers_queue SET id = @num := @num + 1 ORDER BY id");
        mysqli_query($conn, "ALTER TABLE skmembers_queue AUTO_INCREMENT = 1");
    
        // Reorder IDs in accepted_members
        mysqli_query($conn, "SET @num := 0");
        mysqli_query($conn, "UPDATE accepted_members SET id = @num := @num + 1, members_id = @num ORDER BY id");
        mysqli_query($conn, "ALTER TABLE accepted_members AUTO_INCREMENT = 1");
    
        // Redirect back
        header("Location: youth_manage.php");
        exit();
    }    
}

// Fetch accepted members
$accepted_sql = "
    SELECT 
        a.res_id, 
        q.id,
        q.first_name, 
        q.middle_name, 
        q.last_name, 
        q.gender, 
        q.age, 
        q.filename 
    FROM accepted_members a
    JOIN skmembers_queue q ON a.members_id = q.id
";
$accepted_result = mysqli_query($conn, $accepted_sql);

if (!$accepted_result) {
    die("Error fetching accepted members: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg">
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
        td img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px; /* Square image with slightly rounded corners */
        }
        .unaccept-btn {
            background-color: #ff9800;
            color: white;
            border: none;
            padding: 8px 15px;
            margin: 5px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s;
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
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <br><br><br>
    
    <main>
        <div style="width: 90%; display: flex; justify-content: space-between; align-items: center;">
            <h1>Accepted Resident to be a Member</h1>
            <div class="search-container">
                <i class="fa fa-magnifying-glass search-icon"></i>
                <input type="text" id="acceptedSearch" class="search-input form-control text-center" placeholder="Search Full Name or Resident ID">
            </div>
        </div>

        <table id="acceptedTable">
            <thead>
                <tr>
                    <th>Resident ID</th>
                    <th>Full Name</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Picture</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($accepted_result)) : ?>
                    <tr class="resident-row">
                        <td><?php echo htmlspecialchars($row['res_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['gender']); ?></td>
                        <td><?php echo htmlspecialchars($row['age']); ?></td>
                        <td>
                            <?php 
                            $imagePath = "uploads/" . htmlspecialchars($row['filename']);
                            if (!empty($row['filename']) && file_exists($imagePath)) {
                                echo '<img src="' . $imagePath . '" alt="Profile Picture">';
                            } else {
                                echo '<img src="uploads/default.jpg" alt="Default Picture">';
                            }
                            ?>
                        </td>
                        <td class="action-container">
                        <a href="youth_manage.php?action=unaccept&id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-warning btn-icon" title="Archive">
                                <i class="fa fa-archive"></i>
                            </a>
                            <a href="#" class="btn btn-danger btn-icon delete-btn" data-id="<?php echo htmlspecialchars($row['id']); ?>" title="Delete">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <script>
            document.getElementById("acceptedSearch").addEventListener("keyup", function() {
                let filter = this.value.toUpperCase();
                let rows = document.querySelectorAll("#acceptedTable tbody .resident-row");

                rows.forEach(row => {
                    let text = row.innerText.toUpperCase();
                    row.style.display = text.includes(filter) ? "" : "none";
                });
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                document.querySelectorAll(".delete-btn").forEach(button => {
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
                            confirmButtonText: "Yes, delete it!"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `youth_manage.php?action=delete&id=${memberId}`;
                            }
                        });
                    });
                });
            });
        </script>
    </main>

</body>

<?php include 'footer.php'; ?>

</html>