<?php 

require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;
$showModal = false;

$id = $_SESSION['user_id'];
$sql = "SELECT ratings.*, job_requests.*, users.*, job_listings.*, job_offers.id as job_offer_id, job_offers.job as job_offer_job, 
    job_offers.date as job_offer_date, job_offers.time as job_offer_time, job_offers.type as job_offer_type, job_offers.salary_offer as job_offer_sal, job_offers.location as job_offer_loc, job_offers.responsibilities as job_offer_respo, job_offers.qualifications as job_offer_quali FROM `ratings` 
    INNER JOIN job_requests ON ratings.job_req_id = job_requests.id
    INNER JOIN users ON job_requests.employer_id = users.id
    LEFT JOIN job_listings ON job_requests.job_id = job_listings.id
    LEFT JOIN job_offers ON job_requests.job_id = job_offers.id
    WHERE ratings.user_id = ?";

if($stmt = $conn->prepare($sql)){
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result() ?? null;
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
            margin: 0;
        }
        .feedback-container {
            max-width: 100%; /* Adjust max-width for responsiveness */
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px; /* Adjust padding for smaller screens */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .feedback-title {
            font-size: 2rem; /* Adjust font size for smaller screens */
            color: #3b5998;
            margin-top: 0;
            margin-bottom: 20px; /* Adjust spacing */
            text-align: center; /* Center align title */
        }
        .feedback-item {
            background-color: #D4D9E6;
            padding: 20px;
            border: 2px solid #d3d3d3;
            margin-bottom: 20px; /* Adjust spacing */
            display: flex;
            flex-direction: column; /* Stack items vertically */
        }
        .feedback-item h3 {
            font-size: 1.5rem; /* Adjust font size */
            color: #1E3B85;
            margin-top: 0;
        }
        .feedback-item p {
            margin: 10px 0; /* Adjust spacing */
            color: #4a4a4a;
            font-size: 14px;
        }
        .right-container {
            display: flex;
            justify-content: flex-end; /* Align items to the right */
        }
        .stars {
            color: #ffc107;
            font-size: 2rem; /* Adjust font size for stars */
        }
        .go-back {
            width: 50px; /* Adjust size of the go-back button */
            margin-top: 20px; /* Adjust spacing */
        }
        .go-back-container {
            margin-top: 20px; /* Adjust spacing */
            text-align: right; /* Align content to the right */
        }
        .view-details {
            color: #3b5998;
            text-decoration: none;
            font-size: 14px;
            position: absolute;
            bottom: 10px;
            right: 10px;
        }
        .view-details:hover {
            text-decoration: underline;
        }

        /* Media query for smaller screens */
        @media (max-width: 768px) {
            .feedback-container {
                padding: 10px; /* Adjust padding */
            }
            .feedback-title {
                font-size: 1.5rem; /* Adjust font size */
            }
            .feedback-item {
                padding: 15px; /* Adjust padding */
            }
            .feedback-item h3 {
                font-size: 1.2rem; /* Adjust font size */
            }
            .feedback-item p {
                font-size: 12px; /* Adjust font size */
            }
            .stars {
                font-size: 1.5rem; /* Adjust font size */
            }
            .go-back {
                width: 40px; /* Adjust size */
            }
        }
    </style>
</head>
<body>
    <?php 
      $data = [];
      if ($result != null)
        $data = $result->fetch_all(MYSQLI_ASSOC);
      else 
        echo '';
    ?>
    <div class="feedback-container">
        <div class="feedback-title">Feedback</div>
        <?php if($data):?>
            <?php foreach($data as $row): ?>
                <div class="feedback-item">
                    <h3><?php echo $row['job_offer_job'] ?? ''?> - <?php echo $row['job_offer_loc'] ?? ''?> - <?php echo $row['firstname'] ?? ''?> <?php echo $row['lastname'] ?? ''?></h3>
                    <p><?php echo $row['reviews'] ?? 'No review'?></p>
                    <!-- <div class="right-container">
                        <div class="stars">★★★★★</div>
                    </div> -->
                </div>
            <?php endforeach;?>
        <?php else:?>
        <?php endif;?>
        
        
    </div>
</body>
</html>
