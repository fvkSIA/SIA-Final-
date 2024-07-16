<?php 

require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;
$showModal = false;

if($_SERVER["REQUEST_METHOD"] == "GET") {
  // $id = $_GET['id'];
  $userid = $_SESSION['user_id'];

  $sql = "SELECT job_requests.*, job_requests.id as job_req_id, job_requests.type as job_req_type, job_listings.*, job_offers.id as job_offer_id, job_offers.job as job_offer_job, 
  job_offers.date as job_offer_date, job_offers.time as job_offer_time, job_offers.type as job_offer_type, job_offers.salary_offer as job_offer_sal, job_offers.location as job_offer_loc, job_offers.responsibilities as job_offer_respo, job_offers.qualifications as job_offer_quali,
   users.id as userid ,users.firstname as user_fname, users.lastname as user_lname, users.email as user_email, users.home_address as user_address FROM job_requests
        LEFT JOIN job_listings ON job_requests.job_id = job_listings.id
        LEFT JOIN job_offers ON job_requests.job_id = job_offers.id
        INNER JOIN users ON job_requests.user_id = users.id
        AND job_requests.status = 1
        AND job_requests.employer_id = ? 
        ORDER BY job_requests.created_at DESC";

  // echo $id . ' query: ' . $sql; die();
  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result() ?? null;
    $stmt->close();
  }

}

$conn->close();

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employer Dashboard</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
  <style>
    body {
      min-height: 100vh;
      font-family: Arial, sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      margin: 0;
      background-color: #7cbeea00;
    }

    .history-container {
      width: 100%;
      padding: 20px;
      box-sizing: border-box;
    }

    .history-title {
      font-size: 30px;
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      color: #5a83de;
      padding-bottom: 50px;
      text-align: left;
      font-weight: bold; 
    }

    .history-item {
      background-color: #e7e4f8;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
    }

    .history-item h3 {
      margin: 0;
      font-size: 25px;
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      color: #4299e1;
    }

    .history-item p {
      margin: 5px 0;
      color: #4a4a4a;
      font-size: 14px;
    }

    .right-container {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      justify-content: space-between;
      margin-top: 10px;
    }

    .stars {
      color: #ffc107;
      font-size: 30px;
    }

    .view-details {
      color: #3b5998;
      text-decoration: none;
      font-size: 14px;
      margin-top: 10px;
    }

    .view-details:hover {
      text-decoration: underline;
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
  <div class="history-container">
    <span class="history-title">History</span>
    <?php if($data):?>
      <?php foreach($data as $row): ?>
        <div class="history-item">
          <div>
              <h3><?php echo $row['job_req_type'] == 1 ? $row['job_offer_job'] : $row['job'];?></h3>
              <p><?php echo $row['user_fname'] . ' ' . $row['user_lname'];?></p>
              <p><?php echo $row['job_req_type'] == 1 ? $row['job_offer_date'] : $row['date'];?></p>
          </div>
          <div class="right-container">
              <a href="view-details-history.php" class="view-details">View Details</a>
          </div>
      </div>
        <?php endforeach;?>
    <?php else:?>
    <?php endif;?>
    
    
  </div>

  <script src="script.js"></script>
</body>
</html>
