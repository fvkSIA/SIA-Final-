<?php 

require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;
$showModal = false;

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die('Unauthorized access.');
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Handle deletion
    if (isset($_GET['delete_id'])) {
        $delete_id = intval($_GET['delete_id']);
        $userid = $_SESSION['user_id'];

        // Prepare SQL statement to delete the request
        $sql = "DELETE FROM job_requests WHERE id = ? AND employer_id = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ii", $delete_id, $userid);
            if ($stmt->execute()) {
                header("Location: employer_history.php?message=Request deleted successfully");
                exit();
            } else {
                $error = 'Failed to delete request.';
            }
            $stmt->close();
        } else {
            $error = 'Failed to prepare SQL statement.';
        }
    }

    // Fetch job requests
    $userid = $_SESSION['user_id'];

    $sql = "SELECT job_requests.*, job_requests.id as job_req_id, job_requests.type as job_req_type, job_listings.*, job_offers.id as job_offer_id, job_offers.job as job_offer_job, 
    job_offers.date as job_offer_date, job_offers.time as job_offer_time, job_offers.type as job_offer_type, job_offers.salary_offer as job_offer_sal, job_offers.location as job_offer_loc, job_offers.responsibilities as job_offer_respo, job_offers.qualifications as job_offer_quali,
     users.id as userid, users.firstname as user_fname, users.lastname as user_lname, users.email as user_email, users.home_address as user_address FROM job_requests
          LEFT JOIN job_listings ON job_requests.job_id = job_listings.id
          LEFT JOIN job_offers ON job_requests.job_id = job_offers.id
          INNER JOIN users ON job_requests.user_id = users.id
          WHERE job_requests.status = 1
          AND job_requests.employer_id = ? 
          ORDER BY job_requests.created_at DESC";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $userid);
        $stmt->execute();
        $result = $stmt->get_result() ?? null;
        $stmt->close();
    } else {
        $error = 'Failed to prepare SQL statement for fetching requests.';
    }
}

$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard - History</title>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="icon" type="image/png" href="../HanapKITA.png">
    <style>
         @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .history-title {
            font-size: 28px;
            color: #6b0d0d;
            margin-bottom: 30px;
            border-bottom: 2px solid #6b0d0d;
            padding-bottom: 10px;
        }
        .history-item {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.2s ease-in-out;
        }
        .history-item:hover {
            transform: translateY(-5px);
        }
        .history-item h3 {
            margin: 0 0 10px 0;
            font-size: 20px;
            color: #2563eb;
        }
        .history-item p {
            margin: 5px 0;
            color: #4b5563;
            font-size: 14px;
        }
        .view-details {
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .view-details:hover {
            background-color: #2563eb;
        }
        .delete-button {
            background-color: #ef4444;
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s;
            margin-left: 10px;
        }
        .delete-button:hover {
            background-color: #dc2626;
        }
        .no-history {
            text-align: center;
            color: #6b7280;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="history-title">History</h1>
        
        <?php if ($error): ?>
            <p class="no-history"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if (isset($_GET['message'])): ?>
            <p class="no-history"><?php echo htmlspecialchars($_GET['message']); ?></p>
        <?php endif; ?>
        
        <?php
        $data = [];
        if ($result != null) {
            $data = $result->fetch_all(MYSQLI_ASSOC);
        }
        
        if (!empty($data)):
            foreach($data as $row):
        ?>
            <div class="history-item">
                <div>
                    <h3><?php echo htmlspecialchars($row['job_req_type'] == 1 ? $row['job_offer_job'] : $row['job']); ?></h3>
                    <p>Jobseeker: <?php echo htmlspecialchars($row['user_fname'] . ' ' . $row['user_lname']); ?></p>
                    <p>Date & Time: <?php echo htmlspecialchars($row['job_req_type'] == 1 ? $row['job_offer_date'] : $row['date']); ?> 
                       <?php echo htmlspecialchars($row['job_req_type'] == 1 ? $row['job_offer_time'] : $row['time']); ?></p>
                </div>
                <div>
                    <a href="view-details-history.php?id=<?php echo htmlspecialchars($row['job_req_id']); ?>" class="view-details">View Details</a>
                    <a href="?delete_id=<?php echo htmlspecialchars($row['job_req_id']); ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this request?');">Delete</a>
                </div>
            </div>
        <?php
            endforeach;
        else:
        ?>
            <p class="no-history">No job request history available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
