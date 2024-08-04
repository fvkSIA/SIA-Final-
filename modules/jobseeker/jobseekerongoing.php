<?php 
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;
$data = []; // Initialize $data as an empty array

$id = $_SESSION['user_id'];

// SQL Query to fetch ongoing jobs with status 0 and is_accepted 1
$sql = "SELECT jr.*, j.job, j.location, j.salary_offer, j.date, j.time, e.firstname AS employer_firstname, e.lastname AS employer_lastname 
        FROM job_requests jr
        JOIN job_listings j ON jr.job_id = j.id
        JOIN users u ON jr.user_id = u.id
        JOIN users e ON jr.employer_id = e.id
        WHERE jr.status = 0 AND jr.is_accepted = 1 AND (jr.type = 1 OR jr.type = 2) AND u.id = ?";

if ($stmt = $conn->prepare($sql)){
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
    }
    
    $stmt->close();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ongoing Job</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
    body {
      font-family: 'Poppins', sans-serif;
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
      text-align: center;
      font-family: 'Poppins', sans-serif;
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

    .job-title-wrapper {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
    }

    .job-title-wrapper .fas {
      font-size: 24px;
      color: #000;
      margin-right: 10px;
    }

    .job-title,
    .job-location-salary span,
    .job-datetime span {
      font-weight: bold;
    }

    .job-location-salary {
      font-size: 1rem;
      margin-bottom: 10px;
      color: #333;
      display: flex;
      align-items: center;
    }

    .job-location-salary .separator {
      margin: 0 10px;
      color: #3D52A0;
    }

    .job-type span {
      background-color: #e9ecef;
      padding: 5px 10px;
      border-radius: 4px;
      font-size: 0.9rem;
      margin-right: 5px;
    }

    .job-salary span {
      font-size: 0.9rem;
      margin-right: 5px;
    }

    .job-datetime {
      font-size: 1rem;
      margin-bottom: 10px;
      color: #333;
      display: flex;
      align-items: center;
    }

    .job-datetime .separator {
      margin: 0 10px;
      color: #3D52A0;
    }

    .employee-name {
      font-size: 20px;
      font-weight: bold;
      color: #333;
      position: absolute;
      top: 60px;
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

      .job-location-salary {
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
    <h2>Ongoing Job</h2>
 

    <?php if($data):?>
      <?php foreach($data as $row): ?>
        <div class="job-offer">
          <div class="job-details">
            <div class="job-title-wrapper">
              <i class="fas fa-briefcase"></i>
              <div class="job-title"><?php echo htmlspecialchars($row['job']); ?></div>
            </div>
            <div class="job-location-salary">
              <i class="fas fa-map-marker-alt">&nbsp;</i> 
              <span><?php echo htmlspecialchars($row['location']); ?></span>
              <span class="separator">|</span>
              <span><i class="fas fa-dollar-sign">&nbsp;</i> <?php echo htmlspecialchars($row['salary_offer']); ?></span>
            </div>
            <div class="job-datetime">
              <i class="fas fa-calendar-alt">&nbsp;&nbsp;</i> 
              <span><?php echo htmlspecialchars($row['date']);?></span>
              <span class="separator">|</span>
              <i class="fas fa-clock">&nbsp;&nbsp;</i> 
              <span><?php echo htmlspecialchars($row['time']); ?></span>
            </div>
          </div>
          <div class="employee-name"><?php echo htmlspecialchars($row['employer_firstname'] . ' ' . $row['employer_lastname']); ?></div>
        </div>
      <?php endforeach;?>
    <?php else:?>
      
    <?php endif;?>
  </div>
</body>
</html>

<?php 
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
$error = '';
$result = null;

$id = $_SESSION['user_id'];

$sql = "
SELECT 
    jl.id as id,
    jl.job as job,
    jl.date as date,
    jl.time as time,
    jl.type as job_type,
    jl.salary_offer as salary_offer,
    jl.location as location,
    jl.responsibilities as responsibilities,
    jl.qualifications as qualifications,
    jl.employer_id as employer_id,
    u.firstname as firstname,
    u.lastname as lastname,
    jl.accepted as is_accepted
FROM 
    job_listings jl
LEFT JOIN users u ON jl.employer_id = u.id
LEFT JOIN job_requests jr ON jl.id = jr.job_id AND jr.user_id = ?
WHERE 
    jl.job_seeker_id = ?
    AND jl.accepted = 1

UNION ALL

SELECT 
    jo.id as id,
    jo.job as job,
    jo.date as date,
    jo.time as time,
    jo.type as job_type,
    jo.salary_offer as salary_offer,
    jo.location as location,
    jo.responsibilities as responsibilities,
    jo.qualifications as qualifications,
    jo.employer_id as employer_id,
    u.firstname as firstname,
    u.lastname as lastname,
    jr.is_accepted as is_accepted
FROM 
    job_offers jo
LEFT JOIN users u ON jo.employer_id = u.id
LEFT JOIN job_requests jr ON jo.id = jr.job_id AND jr.user_id = ?
WHERE 
    jr.is_accepted = 1
    AND jr.user_id = ?
    AND jr.type = 1
    AND jr.status != 1
";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ssss", $id, $id, $id, $id);
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
  <title>Ongoing Job</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
    body {
      font-family: 'Poppins', sans-serif;
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
      text-align: center;
      font-family: 'Poppins', sans-serif;
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

    .job-title-wrapper {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
    }

    .job-title-wrapper .fas {
      font-size: 24px;
      color: #000;
      margin-right: 10px;
    }

    .job-title,
    .job-location-salary span,
    .job-datetime span {
      font-weight: bold;
    }

    .job-location-salary {
      font-size: 1rem;
      margin-bottom: 10px;
      color: #333;
      display: flex;
      align-items: center;
    }

    .job-location-salary .separator {
      margin: 0 10px;
      color: #3D52A0;
    }

    .job-type span {
      background-color: #e9ecef;
      padding: 5px 10px;
      border-radius: 4px;
      font-size: 0.9rem;
      margin-right: 5px;
    }

    .job-salary span {
      font-size: 0.9rem;
      margin-right: 5px;
    }

    .job-datetime {
      font-size: 1rem;
      margin-bottom: 10px;
      color: #333;
      display: flex;
      align-items: center;
    }

    .job-datetime .separator {
      margin: 0 10px;
      color: #3D52A0;
    }

    .employee-name {
      font-size: 20px;
      font-weight: bold;
      color: #333;
      position: absolute;
      top: 60px;
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

      .job-location-salary {
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

    <?php 
      $data = [];
      if ($result != null)
        $data = $result->fetch_all(MYSQLI_ASSOC);
      else 
        echo 'no ongoing job';
    ?>

    <?php if($data):?>
      <?php foreach($data as $row): ?>
        <div class="job-offer">
          <div class="job-details">
            <div class="job-title-wrapper">
              <i class="fas fa-briefcase"></i>
              <div class="job-title"><?php echo htmlspecialchars($row['job']); ?></div>
            </div>
            <div class="job-location-salary">
              <i class="fas fa-map-marker-alt">&nbsp;</i> 
              <span><?php echo htmlspecialchars($row['location']); ?></span>
              <span class="separator">|</span>
              <span><i class="fas fa-dollar-sign">&nbsp;</i> <?php echo htmlspecialchars($row['salary_offer']); ?></span>
            </div>
            <div class="job-datetime">
              <i class="fas fa-calendar-alt">&nbsp;&nbsp;</i> 
              <span><?php echo htmlspecialchars($row['date']);?></span>
              <span class="separator">|</span>
              <i class="fas fa-clock">&nbsp;&nbsp;</i> 
              <span><?php echo htmlspecialchars($row['time']); ?></span>
            </div>
          </div>
          <div class="employee-name"><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></div>
        </div>
      <?php endforeach;?>
    <?php else:?>
   
    <?php endif;?>
  </div>
</body>
</html>
