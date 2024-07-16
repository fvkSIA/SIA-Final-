<?php 


require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;

$id = $_SESSION['user_id'];

$sql = "SELECT job_offers.*, job_offers.type as jo_type, users.id as userid, users.firstname, users.middlename, users.lastname, job_requests.id as jr_id, job_requests.user_id, job_requests.job_id, job_requests.employer_id, job_requests.type, job_requests.status, job_requests.is_accepted  FROM job_offers 
  INNER JOIN users ON users.id = job_offers.employer_id 
  INNER JOIN job_requests ON job_offers.id = job_requests.job_id
  WHERE job_requests.user_id = ?
  AND job_requests.is_accepted = 0 
  AND job_requests.type = 1";

// echo $id . ' query: ' . $sql; die();
if ($stmt = $conn->prepare($sql)) {
  $stmt->bind_param("s", $id);
  $stmt->execute();
  $result = $stmt->get_result() ?? null;
  $stmt->close();
}

$conn->close();

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Job Offers</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    .job-offer-container {
      width: 100%;
      border-radius: 8px;
    }

    .job-offer-container h2 {
      font-size: 24px;
      color: #3D52A0;
      margin-bottom: 20px;
    }

    .job-offer {
      display: flex;
      flex-direction: column;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 8px;
      margin-bottom: 20px;
      background-color: #fff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      position: relative;
    }

    .job-details {
      display: flex;
      flex-direction: column;
    }

    .job-title {
      font-size: 38px;
      font-weight: bold;
      color: #3D52A0;
      margin-top: -80px;
      margin-left: -30px;
    }

    .job-location, .job-type, .job-salary {
      font-size: 17px;
      margin-top: -60px;
      margin-left: 8px;
      margin-bottom: 10px;
      color: #000;
    }

    .job-type, .job-salary {  
      margin-top: 10px;
      margin-left: 40px;
    }

    .job-type span, .job-salary span {
      background-color: #e9ecef;
      padding: 5px 10px;
      border-radius: 4px;
      font-size: 14px;
    }

    .employee-name {
      font-size: 18px;
      font-weight: bold;
      color: #333;
      position: absolute;
      top: 20px;
      right: 20px;
    }

    .view-offer {
      display: inline-block;
      padding: 10px 20px;
      background-color: #007bff;
      color: #fff;
      border-radius: 4px;
      text-decoration: none;
      font-size: 14px;
      font-weight: bold;
      position: absolute;
      bottom: 20px;
      right: 20px;
    }

    .view-offer:hover {
      background-color: #0056b3;
    }

    .job-title-wrapper {
      display: flex;
      align-items: center;
    }

    .job-title-wrapper .fas {
      font-size: 24px;
      color: #000;
      margin-top: 80px;
      margin-right: 10px;
    }
  </style>
</head>
<body>
  <div class="job-offer-container">
    <h2>Job Offers</h2>
    <?php 
      $data = [];
      if ($result != null)
        $data = $result->fetch_all(MYSQLI_ASSOC);
      else 
        echo 'no job offers';
    ?>

    <?php if($data):?>
      <?php foreach($data as $row): ?>
        <div class="job-offer">
          <div class="job-details">
            <div class="job-title-wrapper">
              <i class="fas fa-briefcase"></i>
              <div class="job-title"><?php echo $row['job']?></div>
            </div>
            <div class="job-location"><?php echo $row['location']?></div>
            <div class="job-type">
              <span>Job Type</span>
              <span><?php echo $row['jo_type']?></span>
            </div>
            <div class="job-salary">
              <span>Salary</span>
              <span><?php echo $row['salary_offer']?></span>
            </div>
          </div>
          <div class="employee-name"><?php echo $row['firstname'] . ' ' . $row['lastname']?></div>
          <a href="jobseekerofferdetails.php?id=<?php echo $row['id']?>&jrid=<?php echo $row['jr_id'];?>" class="view-offer">VIEW OFFER</a>
        </div>
      <?php endforeach;?>
    <?php else:?>
      <div class="job-offer">
          <div class="job-details">
            NO JOB OFFERS
          </div>
        </div>
    <?php endif;?>
    
  </div>
</body>
</html>
