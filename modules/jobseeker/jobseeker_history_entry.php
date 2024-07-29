<?php 
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;

$id = $_SESSION['user_id'];
$sql = "SELECT ratings.*, job_requests.*, users.*, job_listings.*, job_offers.id as job_offer_id, 
        job_offers.job as job_offer_job, job_offers.date as job_offer_date, job_offers.time as job_offer_time, 
        job_offers.type as job_offer_type, job_offers.salary_offer as job_offer_sal, job_offers.location as job_offer_loc, 
        job_offers.responsibilities as job_offer_respo, job_offers.qualifications as job_offer_quali 
        FROM `ratings` 
        INNER JOIN job_requests ON ratings.job_req_id = job_requests.id
        INNER JOIN users ON job_requests.employer_id = users.id
        LEFT JOIN job_listings ON job_requests.job_id = job_listings.id
        LEFT JOIN job_offers ON job_requests.job_id = job_offers.id
        WHERE ratings.user_id = ?
        ORDER BY job_requests.created_at DESC";

if($stmt = $conn->prepare($sql)){
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result() ?? null;
    $stmt->close();
}

// Fetch the data from the result set
$data = [];
if ($result) {
    $data = $result->fetch_all(MYSQLI_ASSOC);
}

// Filter the data to keep only the first occurrence of each job ID
$unique_jobs = [];
$filtered_data = [];
foreach ($data as $row) {
    $job_id = $row['job_offer_id'] ?? $row['job_id'];
    if (!isset($unique_jobs[$job_id])) {
        $unique_jobs[$job_id] = true;
        $filtered_data[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>history</title>
    <style>
     @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 95%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        .history-title {
            font-size: 2rem;
            color: #1e3a8a;
            margin-bottom: 30px;
            border-bottom: 2px solid #3b82f6;
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
            flex-direction: column;
        }
        @media (min-width: 768px) {
            .history-item {
                flex-direction: row;
            }
        }
        .history-item:hover {
            transform: translateY(-5px);
        }
        .history-item h3 {
            margin: 0 0 10px 0;
            font-size: 1.25rem;
            color: #2563eb;
        }
        .history-item p {
            margin: 5px 0;
            color: #4b5563;
            font-size: 0.875rem;
        }
        .history-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            font-size: 0.9rem;
            color: #777;
        }
        .error {
            color: #e74c3c;
            text-align: center;
            margin-bottom: 20px;
        }
        .no-history {
            text-align: center;
            font-size: 1.2rem;
            color: #777;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="history-title">History</h1>
        <?php if($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if(!empty($filtered_data)): ?>
            <?php foreach($filtered_data as $row): ?>
                <div class="history-item">
                    <div>
                        <h3><?php echo $row['job_offer_job'] == '' ? $row['job'] : $row['job_offer_job']; ?></h3>
                        <p><?php echo $row['updated_at'] ?? ''; ?></p>
                        <div class="history-meta">
                            <span><?php echo $row['job_offer_loc'] == '' ? $row['location'] : $row['job_offer_loc']; ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-history">No history available at this time.</p>
        <?php endif; ?>
    </div>
</body>
</html>
