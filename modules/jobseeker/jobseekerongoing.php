<?php 
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;

$id = $_SESSION['user_id'];

$sql = "SELECT 
    COALESCE(jo.id, jl.id) as id,
    COALESCE(jo.job, jl.job) as job,
    COALESCE(jo.date, jl.date) as date,
    COALESCE(jo.time, jl.time) as time,
    COALESCE(jo.type, jl.type) as job_type,
    COALESCE(jo.salary_offer, jl.salary_offer) as salary_offer,
    COALESCE(jo.location, jl.location) as location,
    COALESCE(jo.responsibilities, jl.responsibilities) as responsibilities,
    COALESCE(jo.qualifications, jl.qualifications) as qualifications,
    COALESCE(jo.employer_id, jl.employer_id) as employer_id,
    u.firstname,
    u.lastname,
    CASE 
        WHEN jo.id IS NOT NULL THEN jr.is_accepted
        WHEN jl.id IS NOT NULL THEN jl.accepted
    END as is_accepted
FROM 
    (SELECT * FROM job_offers 
     UNION ALL 
     SELECT * FROM job_listings) as combined_jobs
LEFT JOIN job_offers jo ON combined_jobs.id = jo.id
LEFT JOIN job_listings jl ON combined_jobs.id = jl.id
LEFT JOIN users u ON COALESCE(jo.employer_id, jl.employer_id) = u.id
LEFT JOIN job_requests jr ON jo.id = jr.job_id
WHERE 
    (jr.user_id = ? OR jl.job_seeker_id = ?)
    AND (jr.is_accepted = 1 OR jl.accepted = 1)
    AND (jr.type = 1 OR jl.id IS NOT NULL)
    AND (
        (jr.id IS NOT NULL AND jr.status != 1) -- Check status only for job requests
        OR (jl.id IS NOT NULL) -- Include all job listings
    )";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ss", $id, $id);
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
    <h2>Ongoing Job</h2>
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
        <div class="job-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($row['location']); ?></div>
        <div class="job-type">
          <span>Job Type</span>
          <span><?php echo htmlspecialchars($row['job_type']); ?></span>
        </div>
        <div class="job-salary">
          <span>Salary</span>
          <span><?php echo htmlspecialchars($row['salary_offer']); ?></span>
        </div>
        <div class="job-datetime">
          <span>Date and Time</span>
          <span><?php echo htmlspecialchars($row['date'] . ' ' . $row['time']); ?></span>
        </div>
      </div>
      <div class="employee-name"><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></div>
    </div>
  <?php endforeach;?>
<?php else:?>
  <div class="job-offer">
    <div class="job-details">
      No Ongoing Jobs
    </div>
  </div>
<?php endif;?>
    
  </div>
</body>
</html>