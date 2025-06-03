<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Display login success message if available
if (isset($_SESSION['login_message'])) {
    echo "<script>alert('" . $_SESSION['login_message'] . "');</script>";
    unset($_SESSION['login_message']); // Clear the session message after showing
}

// Include the database connection file
include 'database.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="icon" type="image/jpg" href="SKFILES/Org_Chart_and_Logos/SK_LOGO.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add Highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <style>
        body {
            background: url('PICTURES/home_bg.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 100px;
            margin-left: 200px;
            transition: margin-left 0.3s;
        }
        .sidebar.closed + body {
            margin-left: 0;
        }
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 1rem 0;
            position: relative;
            bottom: 0;
            width: 100%;
        }
        .card-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px 0;
            width: 100%;
            max-width: 1200px;
        }
        .card {
            width: 300px;
            height: 150px;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            align-items: start;
            justify-content: center;
            padding-left: 20px;
            font-weight: bold;
            color: white;
            font-size: 18px;
            text-decoration: none;
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .youth { background-color: #d50000; } /* Red */
        .document { background-color: #008000; } /* Green */
        .education { background-color: #0033cc; } /* Blue */
        .card-number {
            font-size: 24px;
            font-weight: bold;
        }
        .charts-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
            width: 100%;
            max-width: 1200px;
        }
        .chart {
            width: 100%;
            max-width: 580px;
            height: 400px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main>
        <div class="container mt-5">
            <h1 class="text-center" style="color: black;"><b>Welcome, <?php echo $_SESSION['username']; ?>!</b></h1>

            <!-- Updated Cards for different options -->
            <div class="card-container">
                <a href="youth_manage.php" class="card youth">
                    <span class="card-number"><?php echo $youthCount; ?></span>
                    <span>YOUTH MANAGE</span>
                </a>

                <a href="doc_to_print.php" class="card document">
                    <span class="card-number"><?php echo $documentCount; ?></span>
                    <span>DOCUMENT TO PRINT</span>
                </a>

                <a href="accepted_assistance.php" class="card education">
                    <span class="card-number"><?php echo $educationCount; ?></span>
                    <span>EDUCATIONAL ASSISTANCE</span>
                </a>
            </div>

            <!-- Charts Container -->
            <div class="charts-container">
                <div id="ageDistributionChart" class="chart"></div>
                <div id="yearLevelChart" class="chart"></div>
                <div id="pwdDistributionChart" class="chart"></div>
            </div>
        </div>
    </main>

    <?php
    // Query for age distribution
    $age_sql = "SELECT age, COUNT(*) as count 
                FROM skmembers_queue q 
                JOIN accepted_members a ON q.id = a.members_id 
                WHERE a.archive = 'No' 
                GROUP BY age 
                ORDER BY age";
    $age_result = $conn->query($age_sql);
    $age_data = [];
    while ($row = $age_result->fetch_assoc()) {
        $age_data[] = [(int)$row['age'], (int)$row['count']];
    }

    // Query for year level distribution
    $year_sql = "SELECT year_level, COUNT(*) as count 
                 FROM accepted_for_assistance 
                 WHERE status = 1 
                 GROUP BY year_level 
                 ORDER BY CASE 
                    WHEN year_level LIKE 'Kinder%' THEN 1
                    WHEN year_level LIKE 'Grade%' THEN 2
                    WHEN year_level LIKE '%College%' THEN 3
                    ELSE 4
                 END, year_level";
    $year_result = $conn->query($year_sql);
    $year_data = [];
    while ($row = $year_result->fetch_assoc()) {
        $year_data[] = [$row['year_level'], (int)$row['count']];
    }

    // Query for PWD distribution
    $pwd_sql = "SELECT PWD, COUNT(*) as count 
                FROM skmembers_queue q 
                JOIN accepted_members a ON q.id = a.members_id 
                WHERE a.archive = 'No' 
                GROUP BY PWD";
    $pwd_result = $conn->query($pwd_sql);
    $pwd_data = [];
    while ($row = $pwd_result->fetch_assoc()) {
        $status = $row['PWD'] === 'Yes' ? 'PWD' : 'Non-PWD';
        $pwd_data[] = [$status, (int)$row['count']];
    }
    ?>

    <script>
        // Age Distribution Chart
        Highcharts.chart('ageDistributionChart', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Age Distribution of Youth Members'
            },
            xAxis: {
                title: {
                    text: 'Age'
                }
            },
            yAxis: {
                title: {
                    text: 'Number of Youth'
                }
            },
            plotOptions: {
                column: {
                    colorByPoint: true
                }
            },
            series: [{
                name: 'Youth Members',
                data: <?php echo json_encode($age_data); ?>,
                showInLegend: false
            }],
            exporting: {
                enabled: true,
                buttons: {
                    contextButton: {
                        menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadCSV']
                    }
                }
            }
        });

        // Year Level Distribution Chart
        Highcharts.chart('yearLevelChart', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Educational Level Distribution'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f}%'
                    }
                }
            },
            series: [{
                name: 'Students',
                colorByPoint: true,
                data: <?php echo json_encode($year_data); ?>
            }],
            exporting: {
                enabled: true,
                buttons: {
                    contextButton: {
                        menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadCSV']
                    }
                }
            }
        });

        // PWD Distribution Chart
        Highcharts.chart('pwdDistributionChart', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'PWD Distribution of Youth Members'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f}%'
                    }
                }
            },
            series: [{
                name: 'Youth Members',
                colorByPoint: true,
                data: <?php echo json_encode($pwd_data); ?>
            }]
        });
    </script>

    <?php include 'footer.php'; ?>

</body>
</html>

