<?php
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();

// Fetch the count of various user statuses
$sql_pending = "SELECT count(*) as pending FROM users WHERE users.verified = 0";
$sql_jobseekers = "SELECT count(*) as jobseekers FROM users WHERE users.type = 2 AND users.verified = 1";
$sql_employers = "SELECT count(*) as employers FROM users WHERE users.type = 3 AND users.verified = 1";
$sql_ongoing = "SELECT count(*) as ongoing FROM job_requests WHERE job_requests.status = 0 AND is_accepted = 1";

// Fetch job occupations
$sql_job_occupations = "SELECT job_type, COUNT(*) as count FROM users WHERE job_type IS NOT NULL GROUP BY job_type";

$stmt1 = $conn->prepare($sql_pending);
$stmt1->execute();
$result1 = $stmt1->get_result() ?? null;
$stmt1->close();

$stmt2 = $conn->prepare($sql_jobseekers);
$stmt2->execute();
$result2 = $stmt2->get_result() ?? null;
$stmt2->close();

$stmt3 = $conn->prepare($sql_employers);
$stmt3->execute();
$result3 = $stmt3->get_result() ?? null;
$stmt3->close();

$stmt4 = $conn->prepare($sql_ongoing);
$stmt4->execute();
$result4 = $stmt4->get_result() ?? null;
$stmt4->close();

$stmt5 = $conn->prepare($sql_job_occupations);
$stmt5->execute();
$result5 = $stmt5->get_result() ?? null;
$stmt5->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <!-- Icon Link -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        .dashboard {
            margin-left: 115px;
            padding: 20px;
            width: calc(100% - 115px);
            background: #f0f0f0;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e0e0;
        }

        .header h1 {
            font-size: 24px;
        }

        .filters {
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
        }

        .stats-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .stat-box {
            background: #ffffff;
            padding: 1px;
            border-radius: 8px;
            text-align: center;
            width: 20%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .stat-box a {
            text-decoration: none !important; /* Alisin ang underline */
            color: inherit; /* Panatilihing kulay ng text tulad ng default */
        }

        .stat-box .stat-circle {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 60px;
            height: 60px;
            margin: 0 auto 10px auto;
            border-radius: 50%;
            font-size: 20px;
        }

        .pendingapplicants { background: yellow; }
        .ongoing-jobs { background: green; }
        .jobseekers-available { background: blue; }
        .hired-workers { background: pink; }

        .total-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .total-box {
            background: #ffffff;
            border-radius: 8px;
            text-align: center;
            width: 47%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .job-occupations {
            margin-top: 20px;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%; /* Adjusted width */
        }

        .occupation {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .occupation p {
            margin-left: 10px;
        }

        .occupation-bar {
            height: 8px;
            border-radius: 4px;
            flex: 1;
        }

        .driver { background: yellow; }
        .labandera { background: pink; }
        .tubero { background: green; }
        .sales-assistant { background: purple; }

        .top-jobs {
            margin-top: 20px;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .chart {
            display: flex;
            justify-content: space-around;
            align-items: flex-end;
            height: 200px;
        }

        .chart .bar {
            width: 20px;
            background: #119aaf;
        }

        @media (max-width: 768px) {
            .stat-box, .total-box {
                width: 44%;
            }
        }

        @media (max-width: 480px) {
            .stat-box, .total-box {
                width: 100%;
            }

            .sidenav {
                width: 80px;
            }

            #click:checked ~ .sidenav {
                width: 200px;
            }

            .dashboard {
                margin-left: 80px;
                width: calc(100% - 80px);
            }

            #click:checked ~ .dashboard {
                margin-left: 200px;
                width: calc(100% - 200px);
            }

            .header h1 {
                font-size: 20px;
            }

            .header {
                padding: 10px;
            }

            .filters {
                width: 100%;
                margin-top: 10px;
            }
        }
        .stats-container a,
        .total-container a {
            text-decoration: none; /* Removes underline */
        }
        .stats-container {
            font-size: 16px;
        }
        .stat-box {
            display: inline-block;
            text-align: center;
            margin: 1px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
            color: inherit;
        }
        .stat-circle {
            display: inline-block;
            margin-bottom: 5px;
        }
        .total-container {
            margin-top: 20px;
        }
        .total-box {
            display: inline-block;
            text-align: center;
            margin: 2px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
            color: inherit;
        }
        .job-occupations {
            margin-top: 20px;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%; /* Adjusted width */
        }

        .occupation {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .occupation-bar-container {
            width: 100%; /* Set to 100% to use available space */
            background-color: #e0e0e0; /* Light grey background */
            height: 20px;
            border-radius: 4px;
            overflow: hidden;
        }

        .occupation-bar {
            height: 100%;
            background-color: #119aaf;
        }

        .occupation p {
            margin-left: 10px;
            min-width: 150px;
        }
    </style>
</head>
<body>
    
<?php 
      $pending = [];
      $jobseekers = [];
      $employers = [];
      $ongoing = [];

      $pending = $result1->fetch_assoc();
      $jobseekers = $result2->fetch_assoc();
      $employers = $result3->fetch_assoc();
      $ongoing = $result4->fetch_assoc();

    ?>

        <div class="container" style="color: black;">
            <div class="mt-5 mb-5">
                <h1>DASHBOARD</h1>
            </div>

            <div class="row">
                <div class="col-md-3 col-sm-12">
                    <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-secondary">PENDING APPLICANTS</h5>
                        <p class="card-text h2"><?php  $num_padded = sprintf("%02d", $pending['pending']); echo $num_padded;?></p>
                        <a href="adminregistration.php" class="card-link float-right">VIEW</a>
                    </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-secondary">ONGOING JOBS</h5>
                        <p class="card-text h2"><?php  $num_padded = sprintf("%02d", $ongoing['ongoing']); echo $num_padded;?></p>
                        <a href="adminviewongoingjob.php" class="card-link float-right">VIEW</a>
                    </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-secondary">TOTAL JOBSEEKERS</h5>
                        <p class="card-text h2"><?php  $num_padded = sprintf("%02d", $jobseekers['jobseekers']); echo $num_padded;?></p>
                        <a href="admdashboardjobseeker.php" class="card-link float-right">VIEW</a>
                    </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-secondary">TOTAL EMPLOYERS</h5>
                        <p class="card-text h2"><?php $num_padded = sprintf("%02d", $employers['employers']); echo $num_padded?></p>
                        <a href="admdashboardemployer.php" class="card-link float-right">VIEW</a>
                    </div>
                    </div>
                </div>
            </div>
            
            <div class="job-occupations">
                <h2>Job Occupations</h2>
                <?php
                $maxCount = 0;
                $jobData = [];

                if ($result5 && $result5->num_rows > 0) {
                    while ($row = $result5->fetch_assoc()) {
                        $jobData[] = $row;
                        if ($row['count'] > $maxCount) {
                            $maxCount = $row['count'];
                        }
                    }
                }

                foreach ($jobData as $job) {
                    $percentage = ($job['count'] / $maxCount) * 100;
                    echo "<div class='occupation'>";
                    echo "<div class='occupation-bar-container'>";
                    echo "<div class='occupation-bar' style='width: {$percentage}%;'></div>";
                    echo "</div>";
                    echo "<p>" . htmlspecialchars($job['job_type']) . " (" . $job['count'] . ")</p>";
                    echo "</div>";
                }

                if (empty($jobData)) {
                    echo "<p>No job occupations found.</p>";
                }
                ?>
            </div>

            <div class="top-jobs">
                <h2>Top Jobs Advertised</h2>
                <div class="chart">
                    <div class="bar" style="height: 60%;"></div>
                    <div class="bar" style="height: 40%;"></div>
                    <div class="bar" style="height: 50%;"></div>
                    <div class="bar" style="height: 30%;"></div>
                    <div class="bar" style="height: 70%;"></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
