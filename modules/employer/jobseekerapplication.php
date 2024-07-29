<?php 
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $jrid = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
  
    $sql = "SELECT job_requests.id as jr_id, job_requests.user_id as jr_uid, job_requests.job_id as jr_jobid, job_requests.employer_id as jr_empid, job_requests.type as jr_type, job_requests.status as jr_comp, job_requests.is_accepted, users.id as user_id, users.firstname, users.lastname, job_listings.*, job_offers.id as job_offer_id, job_offers.job as job_offer_job, job_offers.date as job_offer_date, job_offers.time as job_offer_time, job_offers.type as job_offer_type, job_offers.salary_offer as job_offer_sal, job_offers.location as job_offer_loc, job_offers.responsibilities as job_offer_respo, job_offers.qualifications as job_offer_quali, a.firstname as job_seek_fname, a.lastname as job_seek_lname, a.middlename as job_seek_mname, a.email as job_seek_email, a.phone_number as job_seek_phone FROM job_requests
            LEFT JOIN users ON job_requests.employer_id = users.id
            LEFT JOIN job_listings ON job_requests.job_id = job_listings.id
            LEFT JOIN users as a ON job_requests.user_id = a.id
            LEFT JOIN job_offers ON job_requests.job_id = job_offers.id
            WHERE job_requests.id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $jrid);
        $stmt->execute();
        $result = $stmt->get_result() ?? null;
        $stmt->close();
    }

} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = filter_input(INPUT_POST, 'job_id', FILTER_SANITIZE_NUMBER_INT);
    $job_req_id = filter_input(INPUT_POST, 'job_req_id', FILTER_SANITIZE_NUMBER_INT);

    $sql = "UPDATE job_listings SET accepted = 1 WHERE id = ?";
    $sql2 = "UPDATE job_requests SET is_accepted = 1 WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            if ($stmt2 = $conn->prepare($sql2)) {
                $stmt2->bind_param("i", $job_req_id);
                if ($stmt2->execute()) {
                    $stmt->close();
                    $stmt2->close();
                    header('Location: employerongoing.php');
                    exit();
                } else {
                    $error = 'Error encountered while updating job request. Try again later.';
                }
            } else {
                $error = 'Error preparing statement for updating job request.';
            }
        } else {
            $error = 'Error encountered while updating job listing. Try again later.';
        }
    } else {
        $error = 'Error preparing statement for updating job listing.';
    }
}
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employer Dashboard - Job Post</title>
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <link rel="icon" type="image/png" href="../HanapKITA.png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #1a73e8;
      --secondary-color: #5f6368;
      --background-color: #f0f2f5;
      --card-background: #ffffff;
      --text-color: #333333;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--background-color);
      color: var(--text-color);
      line-height: 1.6;
    }

    main {
      max-width: 800px;
      margin: 40px auto;
      padding: 30px;
      background-color: var(--card-background);
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .header {
      font-size: 28px;
      font-weight: 600;
      color: var(--primary-color);
      margin-bottom: 20px;
      text-align: center;
    }

    .job-post {
      background-color: #f8f9fa;
      padding: 25px;
      border-radius: 10px;
      margin-top: 20px;
      transition: all 0.3s ease;
    }

    .job-post:hover {
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .job-post h1 {
      font-size: 24px;
      color: var(--primary-color);
      margin-bottom: 10px;
    }

    .job-post p {
      font-size: 16px;
      color: var(--secondary-color);
    }

    .employee-info {
      background-color: #e8f0fe;
      padding: 15px;
      border-radius: 8px;
      margin: 20px 0;
      display: flex;
      align-items: center;
    }

    .employee-info i {
      font-size: 24px;
      margin-right: 10px;
      color: var(--primary-color);
    }

    .employee-info p {
      font-weight: 500;
      margin: 0;
    }

    .employee-info span {
      color: var(--primary-color);
      font-weight: 600;
    }

    .job-details {
      margin-top: 25px;
    }

    .job-details h2 {
      font-size: 20px;
      color: var(--primary-color);
      margin-bottom: 15px;
      border-bottom: 2px solid var(--primary-color);
      padding-bottom: 5px;
    }

    .job-details p {
      font-size: 16px;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
    }

    .job-details i {
      margin-right: 10px;
      color: var(--secondary-color);
      font-size: 20px;
    }

    .salary, .responsibilities, .qualifications {
      margin-top: 20px;
      background-color: #f8f9fa;
      padding: 15px;
      border-radius: 8px;
    }

    .salary h3, .responsibilities h3, .qualifications h3 {
      font-size: 18px;
      color: var(--primary-color);
      margin-bottom: 10px;
      display: flex;
      align-items: center;
    }

    .salary h3 i, .responsibilities h3 i, .qualifications h3 i {
      margin-right: 10px;
    }

    ul {
      padding-left: 20px;
    }

    li {
      margin-bottom: 5px;
    }

    .accept-button {
      background-color: var(--primary-color);
      color: white;
      font-weight: 500;
      font-size: 16px;
      padding: 12px 24px;
      border-radius: 25px;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s ease;
      margin-top: 25px;
      display: block;
      width: 100%;
    }

    .accept-button:hover {
      background-color: #1557b0;
    }

    .confirmation-box {
      display: none;
      position: fixed;
      inset: 0;
      background-color: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .confirmation-box .box {
      background-color: var(--card-background);
      padding: 30px;
      border-radius: 12px;
      width: 90%;
      max-width: 400px;
      text-align: center;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .confirmation-box .box p {
      font-size: 18px;
      margin-bottom: 20px;
    }

    .confirmation-box .box button {
      padding: 10px 20px;
      border-radius: 20px;
      border: none;
      cursor: pointer;
      font-size: 16px;
      font-weight: 500;
      transition: background-color 0.3s ease;
    }

    .confirmation-box .box button#confirmButton {
      background-color: var(--primary-color);
      color: white;
      margin-right: 10px;
    }

    .confirmation-box .box button#confirmButton:hover {
      background-color: #1557b0;
    }

    .confirmation-box .box button#cancelButton {
      background-color: #f1f3f4;
      color: var(--secondary-color);
    }

    .confirmation-box .box button#cancelButton:hover {
      background-color: #e8eaed;
    }

    @media (max-width: 768px) {
      main {
        padding: 20px;
        margin: 20px;
      }

      .job-post {
        padding: 20px;
      }

      .job-post h1 {
        font-size: 22px;
      }

      .job-details h2 {
        font-size: 18px;
      }

      .employee-info {
        flex-direction: column;
        text-align: center;
      }

      .employee-info i {
        margin-right: 0;
        margin-bottom: 10px;
      }
    }
  </style>
</head>
<body>
  <?php 
    $data = [];
    if ($result != null) {
      $user = $result->fetch_assoc();
    } else {
      echo '<p>No data found.</p>';
    }

    function formatMoney($amount) {
      return '₱ ' . number_format($amount, 2, '.', ',');
    }
  ?>

  <main>
    <h1 class="header">Job Post Details</h1>
    <div class="job-post">
      <h1><?php echo htmlspecialchars($user['job']) ?></h1>
      <p><i class='bx bx-calendar'></i> <?php echo htmlspecialchars($user['date']) ?> at <?php echo htmlspecialchars($user['time']) ?></p>
      
      <div class="employee-info">
        <i class='bx bx-user-circle'></i>
        <p>Employed Worker: <span><?php echo htmlspecialchars($user['job_seek_fname']) ?> <?php echo htmlspecialchars($user['job_seek_lname']) ?></span></p>
      </div>

      <div class="job-details">
        <h2>Job Details</h2>
        <p><i class='bx bx-briefcase'></i> <?php echo htmlspecialchars($user['type']) ?></p>
        <p><i class='bx bx-map'></i> <?php echo htmlspecialchars($user['location']) ?></p>
        
        <div class="salary">
          <h3><i class='bx bx-money'></i> Salary</h3>
          <p><?php echo formatMoney($user['salary_offer']) ?></p>
        </div>
        
        <div class="responsibilities">
  <h3><i class='bx bx-list-ul'></i> Responsibilities</h3>
  <div>
    <?php 
      $responsibilities = explode("\n", $user['responsibilities']);
      foreach ($responsibilities as $responsibility) {
        $trimmedResponsibility = htmlspecialchars(trim($responsibility));
        if ($trimmedResponsibility) {
          echo "$trimmedResponsibility<br>";
        }
      }
    ?>
  </div>
</div>

<div class="qualifications">
  <h3><i class='bx bx-badge-check'></i> Qualifications</h3>
  <div>
    <?php 
      $qualifications = explode("\n", $user['qualifications']);
      foreach ($qualifications as $qualification) {
        $trimmedQualification = htmlspecialchars(trim($qualification));
        if ($trimmedQualification) {
          echo "$trimmedQualification<br>";
        }
      }
    ?>
  </div>
</div>

        
        <button id="acceptButton" class="accept-button">ACCEPT WORKER</button>
      </div>
    </div>
    
    <div id="confirmationBox" class="confirmation-box">
      <div class="box">
        <p>Are you sure you want to accept this worker?</p>
        <form id="jobseeker_apply_form" method="POST">
          <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($user['job_offer_id']); ?>">
          <input type="hidden" name="job_req_id" value="<?php echo htmlspecialchars($user['jr_id']); ?>">
          <button type="submit" id="confirmButton">Yes, Accept</button>
        </form>
        <button id="cancelButton">Cancel</button>
      </div>
    </div>
  </main>

  <script>
    document.getElementById('acceptButton').addEventListener('click', function() {
      document.getElementById('confirmationBox').style.display = 'flex';
    });

    document.getElementById('confirmButton').addEventListener('click', function() {
      document.getElementById('jobseeker_apply_form').submit();
    });

    document.getElementById('cancelButton').addEventListener('click', function() {
      document.getElementById('confirmationBox').style.display = 'none';
    });
  </script>
</body>
</html>
