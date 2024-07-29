<?php 

require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;
$showModal = false;

if($_SERVER["REQUEST_METHOD"] == "GET") {
  // $id = $_GET['id'];
  $userid = $_SESSION['user_id'];
  $job_req_id = $_GET['id'];

  $sql = "SELECT job_requests.*, job_requests.id as job_req_id, job_requests.type as job_req_type, job_listings.*, job_offers.id as job_offer_id, job_offers.job as job_offer_job, 
  job_offers.date as job_offer_date, job_offers.time as job_offer_time, job_offers.type as job_offer_type, job_offers.salary_offer as job_offer_sal, job_offers.location as job_offer_loc, job_offers.responsibilities as job_offer_respo, job_offers.qualifications as job_offer_quali,
   users.id as userid ,users.firstname as user_fname, users.lastname as user_lname, users.email as user_email, users.home_address as user_address, ratings.reviews FROM job_requests
        LEFT JOIN job_listings ON job_requests.job_id = job_listings.id
        LEFT JOIN job_offers ON job_requests.job_id = job_offers.id
        LEFT JOIN users ON job_requests.user_id = users.id
        LEFT JOIN ratings ON job_requests.id = ratings.job_req_id
        WHERE job_requests.id = ? AND job_requests.status = 1";

  // echo $id . ' query: ' . $sql; die();
  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $job_req_id);
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
  <title>Employer Dashboard</title>
  <!-- Link Styles -->
  <link rel="icon" type="image/png" href="../HanapKITA.png">
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="styles.css"> <!-- Link to your custom stylesheet -->
  <style>
    body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0f2f5;
    color: #333;
    line-height: 1.6;
}

.container {
    display: flex;
    justify-content: center;
    padding: 40px 20px;
}

.content {
    width: 100%;
    max-width: 800px;
}

.header {
    font-size: 28px;
    font-weight: bold;
    color: #1d4ed8;
    margin-bottom: 20px;
    padding-bottom: 0px;
}

.card {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.job-title {
    font-size: 24px;
    font-weight: bold;
    color: #1d4ed8;
    margin-bottom: 10px;
}

.job-date {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 15px;
}

.status-button {
    background-color: #10b981;
    color: white;
    font-weight: bold;
    padding: 10px 20px;
    border-radius: 9999px;
    font-size: 16px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.status-button:hover {
    background-color: #059669;
}

.employed-worker {
    margin-top: 20px;
}

.worker-label {
    font-weight: bold;
    color: #4b5563;
}

.worker-name {
    color: #10b981;
}

.divider {
    margin: 25px 0;
    border: none;
    border-top: 1px solid #e5e7eb;
}

.section-title {
    font-size: 20px;
    font-weight: bold;
    color: #111827;
    margin-bottom: 15px;
}

.detail-item {
    display: flex;
    align-items: center;
    margin-top: 12px;
    color: #4b5563;
}

.icon {
    margin-right: 12px;
    font-size: 18px;
    color: #3b82f6;
}

.detail-text {
    font-size: 16px;
}



.description-list,
.qualification-section,
.qualification-list,
.review-section,
.review-text {
    font-size: 16px;
    color: #4b5563;
}

.description-list,
.qualification-list {
    list-style-type: none;
    padding-left: 0;
}

.description-list li,
.qualification-list li {
    position: relative;
    padding-left: 20px;
    margin-bottom: 10px;
}

.description-list li:before,
.qualification-list li:before {
    content: "•";
    position: absolute;
    left: 0;
    color: #3b82f6;
}
.job-details-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-left: 50px;
    margin-right: 50px;
    margin-top: -20px;
    margin-bottom: -30px;
}

.detail-item {
    display: flex;
    align-items: center;
}

.icon {
    margin-right: 10px;
}
  </style>
</head>
<body>
<?php 
  $data = [];
  if ($result != null)
    $data = $result->fetch_assoc();
  else 
    echo '';
?>
  <main class="container">
    <div class="content">
        <span class="header">JOB ORDER HISTORY</span>
        
        <div class="card">
        <button class="status-button">
                COMPLETED
            </button>
            <h1 class="job-title"><?php echo $data['job'] ?? $data['job_offer_job'];?></h1>
            <p class="job-date"><?php echo $data['date'] ?? $data['job_offer_date'];?></p>
            <div class="employed-worker">
                <p class="worker-label">Employed Worker: <span class="worker-name"><?php echo $data['user_fname']?> <?php echo $data['user_lname'];?></span></p>
            </div>

            <hr class="divider">
            <h2 class="section-title">Job details</h2>
            <div class="job-details-container">
                <div class="detail-item">
                    <i class="fas fa-briefcase icon"></i>
                    <?php $type = $data['type'] ?? $data['job_offer_type']; ?>
                    <p class="detail-text"><?php echo $type == 'parttime' ? 'Part-time' : 'Full-time';?></p>
                </div>
                <div class="detail-item">
                    <i class="fas fa-map-marker-alt icon"></i>
                    <p class="detail-text"><?php echo $data['location'] ?? $data['job_offer_loc'];?></p>
                </div>
                <div class="detail-item">
                    <i class="fas fa-peso-sign icon"></i>
                    <p class="detail-text">
                        <?php 
                            $salary = $data['salary_offer'] ?? $data['job_offer_sal'];
                            echo number_format($salary, 2, '.', ',');
                        ?>
                    </p>
                </div>
            </div>



            <hr class="divider">

            <h2 class="section-title">Responsibilities:</h2>
            <ul class="description-list">
                <?php echo nl2br($data['responsibilities'] ?? $data['job_offer_respo']);?>
            </ul>
            <div class="qualification-section">
                <h2 class="section-title">Qualifications:</h2>
                <ul class="qualification-list">
                    <?php echo nl2br($data['qualifications'] ?? $data['job_offer_quali']);?>
                </ul>
            </div>

            <div class="review-section">
                <h2 class="section-title">Review:</h2>
                <p class="review-text">
                    <?php echo $data['reviews'] ?? 'no reviews';?>
                </p>
            </div>
        </div>
    </div>
  </main>
  <script>
    document.getElementById('acceptButton').addEventListener('click', function() {
        document.getElementById('confirmationBox').classList.remove('hidden');
    });

    document.getElementById('confirmButton').addEventListener('click', function() {
        document.getElementById('confirmationBox').classList.add('hidden');
        window.location.href = "employergivingfeedback.html";
    });

    document.getElementById('cancelButton').addEventListener('click', function() {
        document.getElementById('confirmationBox').classList.add('hidden');
    });
  </script>
</body>
</html>
