<?php
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start(); // Only call session_start() once

// Fetch the count of various user statuses
$sql_pending = "SELECT count(*) as pending FROM users WHERE users.verified = 0";
$sql_jobseekers = "SELECT count(*) as jobseekers FROM users WHERE users.type = 2 AND users.verified = 1";
$sql_employers = "SELECT count(*) as employers FROM users WHERE users.type = 3 AND users.verified = 1";
$sql_ongoing = "SELECT count(*) as ongoing FROM job_requests WHERE job_requests.status = 0 AND is_accepted = 1";

// Fetch job occupations
$sql_job_occupations = "SELECT job_type, COUNT(*) as count FROM users WHERE job_type IS NOT NULL GROUP BY job_type";

try {
    // Prepare and execute statements
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

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    exit;
}

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <!-- Icon Link -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>

        .card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
        }

        .card-body {
            padding: 10px;
        }

        .card-title {
            font-size: 16px;
            color: #6c757d;
        }

        .card-text {
            font-size: 24px;
            font-weight: bold;
        }

        .card-link {
            text-decoration: none;
            color: #0d6efd;
        }

        .card-link:hover {
            text-decoration: underline;
        }

        .job-occupations, .top-jobs {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .occupation {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .occupation p {
            margin-left: 10px;
            font-size: 16px;
        }
        .container{
        width: 100%;
        padding-bottom: 50px;
        padding-top: 30px;
        }

        .framed-header {
        width: 100%;
        border: 2px solid #3498db;
        padding: 20px;
        border-radius: 10px;
        background: linear-gradient(to right, #00aaff, #dbe9f4);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .welcome-text {
        font-family: 'Arial', sans-serif;
        color: #2c3e50;
        margin-bottom: 15px;
    }

    .welcome-text h1 {
        font-size: 24px;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .welcome-text p {
        font-size: 14px;
        margin: 5px 0 0;
        color: #00235e;
    }

    .dashboard-text {
        font-family: 'Arial', sans-serif;
        color: #3498db;
        font-size: 20px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    @media screen and (min-width: 768px) {
        .framed-header {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }

        .welcome-text {
            margin-bottom: 0;
        }

        .welcome-text h1 {
            font-size: 28px;
        }

        .welcome-text p {
            font-size: 16px;
        }

        .dashboard-text {
            font-size: 24px;
        }
    }



        .occupation-bar-container {
            width: 1500px;
            background-color: #e0e0e0;
            height: 20px;
            border-radius: 4px;
            overflow: hidden;
        }

        .occupation-bar {
            height: 100%;
            background-color: #3498db;
        }

        .chart {
            display: flex;
            justify-content: space-around;
            align-items: flex-end;
            height: 200px;
            gap: 5px;
        }

        .chart .bar {
            width: 20px;
            background: #119aaf;
        }

        @media (max-width: 768px) {
            .card {
                margin-bottom: 20px;
            }
        }

        @media (max-width: 576px) {
            .card {
                margin-bottom: 10px;
            }

            .chart .bar {
                width: 15px;
            }
        }
    </style>
</head>
<body>  
<?php 
      $pending = $result1 ? $result1->fetch_assoc() : [];
      $jobseekers = $result2 ? $result2->fetch_assoc() : [];
      $employers = $result3 ? $result3->fetch_assoc() : [];
      $ongoing = $result4 ? $result4->fetch_assoc() : [];
    ?>

<div class="container">
<div class="container">
    <div class="framed-header">
        <div class="welcome-text">
        <h1 style="color: #00358d; font-family: Arial, sans-serif; text-align: center;">Admin</h1>
        <p>Welcome back</p>
        </div>
        <div class="dashboard-text">Dashboard</div>
    </div>
</div>

    <div class="row g-4">
        <div class="col-md-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pending Applicants</h5>
                    <p class="card-text"><?php echo sprintf("%02d", $pending['pending']); ?></p>
                    <a href="adminregistration.php" class="card-link">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ongoing Jobs</h5>
                    <p class="card-text"><?php echo sprintf("%02d", $ongoing['ongoing']); ?></p>
                    <a href="adminviewongoingjob.php" class="card-link">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Jobseekers</h5>
                    <p class="card-text"><?php echo sprintf("%02d", $jobseekers['jobseekers']); ?></p>
                    <a href="admdashboardjobseeker.php" class="card-link">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Employers</h5>
                    <p class="card-text"><?php echo sprintf("%02d", $employers['employers']); ?></p>
                    <a href="admdashboardemployer.php" class="card-link">View</a>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>