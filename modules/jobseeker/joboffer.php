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
      padding: 20px;
      background-color: #f0f2f5;
    }

    .job-offer-container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .job-offer-container h2 {
      font-size: 2rem;
      color: #3D52A0;
      margin-bottom: 30px;
      border-bottom: 2px solid #3b82f6;
      padding-bottom: 10px;
    }

    .job-offer {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
      padding: 20px;
      position: relative;
      transition: transform 0.2s;
    }

    .job-offer:hover {
      transform: translateY(-5px);
    }

    .job-details {
      display: flex;
      flex-direction: column;
    }

    .job-title-wrapper {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
    }

    .job-title-wrapper .fas {
      font-size: 24px;
      color: #3D52A0;
      margin-right: 10px;
    }

    .job-title {
      font-size: 1.5rem;
      font-weight: bold;
      color: #3D52A0;
    }

    .job-location, .job-type, .job-salary {
      font-size: 1rem;
      margin-bottom: 10px;
      color: #333;
    }

    .job-type span, .job-salary span {
      background-color: #e9ecef;
      padding: 5px 10px;
      border-radius: 4px;
      font-size: 0.9rem;
      margin-right: 5px;
    }

    .employee-name {
      font-size: 1rem;
      font-weight: bold;
      color: #333;
      position: absolute;
      top: 20px;
      right: 20px;
    }

    .view-offer {
      display: inline-block;
      padding: 10px 20px;
      background-color: #3D52A0;
      color: #fff;
      border-radius: 4px;
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: bold;
      position: absolute;
      bottom: 20px;
      right: 20px;
      transition: background-color 0.2s;
    }

    .view-offer:hover {
      background-color: #2C3E7F;
    }

    @media (max-width: 768px) {
      .job-offer {
        padding: 15px;
      }

      .job-title {
        font-size: 1.2rem;
      }

      .job-location, .job-type, .job-salary {
        font-size: 0.9rem;
      }

      .employee-name {
        position: static;
        margin-bottom: 10px;
      }

      .view-offer {
        position: static;
        display: block;
        text-align: center;
        margin-top: 15px;
      }
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
            <div class="job-location"><i class="fas fa-map-marker-alt"></i> <?php echo $row['location']?></div>
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