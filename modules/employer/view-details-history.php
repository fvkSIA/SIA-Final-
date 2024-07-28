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
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css"> <!-- Link to your custom stylesheet -->
  <style>
    body{
      font-family: 'Poppins', sans-serif;
    }
    .stars {
      color: #ffc107;
      font-size: 30px;
    }
    body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    }

    .container {
    display: flex;
    justify-content: center;
    padding: 20px;
    }

    .content {
    width: 100%;
    max-width: 800px;
    }

    .header {
    font-size: 25px;
    font-weight: bold;
    color: #1d4ed8; /* Blue-500 */
    display: block;
    }

    .card {
    background-color: #ebf8ff; /* Blue-100 */
    padding: 24px;
    border-radius: 8px;
    }

    .job-title {
    font-size: 1.125rem; /* Text-lg */
    font-weight: bold;
    color: #1d4ed8; /* Blue-500 */
    }

    .job-date {
    font-size: 0.875rem; /* Text-sm */
    color: #4b5563; /* Gray-600 */
    }

    .status-button {
    background-color: #f59e0b; /* Yellow-500 */
    color: white;
    font-weight: bold;
    padding: 8px 16px;
    border-radius: 9999px; /* Rounded-full */
    font-size: 1.125rem; /* Text-lg */
    border: none;
    cursor: pointer;
    }

    .employed-worker {
    margin-top: 16px;
    }

    .worker-label {
    font-weight: bold;
    color: #4b5563; /* Gray-700 */
    }

    .worker-name {
    color: #10b981; /* Green-500 */
    }

    .divider {
    margin: 16px 0;
    }

    .section-title {
    font-size: 1.25rem; /* Text-xl */
    font-weight: bold;
    color: #111827; /* Gray-900 */
    }

    .detail-item {
    display: flex;
    align-items: center;
    margin-top: 8px;
    color: #4b5563; /* Gray-600 */
    }

    .icon {
    margin-right: 8px;
    }

    .detail-text {
    font-size: 0.875rem; /* Text-sm */
    }

    .salary-section {
    margin-top: 16px;
    }

    .salary-text {
    font-size: 0.875rem; /* Text-sm */
    background-color: #f3f4f6; /* Gray-100 */
    padding: 4px 8px;
    border-radius: 4px;
    color: #374151; /* Gray-700 */
    font-weight: 600;
    }

    .description-label,
    .description-list,
    .qualification-section,
    .qualification-list,
    .review-section,
    .review-text {
    font-size: 0.875rem; /* Text-sm */
    color: #4b5563; /* Gray-600 */
    }

    .description-list,
    .qualification-list {
    list-style-type: disc;
    padding-left: 20px;
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
            <h1 class="job-title"><?php echo $data['job'] ?? $data['job_offer_job'];?></h1>
            <p class="job-date"><?php echo $data['date'] ?? $data['job_offer_date'];?></p>
            <button class="status-button">
                COMPLETED
            </button>
            <div class="employed-worker">
                <p class="worker-label">Employed Worker: <span class="worker-name"><?php echo $data['user_fname']?> <?php echo $data['user_lname'];?></span></p>
            </div>

            <hr class="divider">

            <h2 class="section-title">Job details</h2>

            <div class="detail-item">
                <i class="fas fa-briefcase icon"></i>
                <?php $type = $data['type'] ?? $data['job_offer_type']; ?>
                <p class="detail-text"><?php echo $type == 'parttime' ? 'Part-time' : 'Full-time';?></p>
            </div>
            <div class="detail-item">
                <i class="fas fa-map-marker-alt icon"></i>
                <p class="detail-text"><?php echo $data['location'] ?? $data['job_offer_loc'];?></p>
            </div>

            <div class="salary-section">
                <h3 class="section-title">Salary</h3>
                <p class="salary-text"><?php echo $data['salary_offer'] ?? $data['job_offer_sal']?></p>
            </div>

            <hr class="divider">

            <h2 class="section-title">Full Job description</h2>
            <p class="description-label">Responsibilities:</p>
            <ul class="description-list">
                <?php echo $data['responsibilities'] ?? $data['job_offer_respo'];?>
            </ul>
            <div class="qualification-section">
                <h2 class="section-title">Qualifications:</h2>
                <ul class="qualification-list">
                    <?php echo $data['qualifications'] ?? $data['job_offer_quali'];?>
                </ul>
            </div>

            <div class="review-section">
                <h2 class="section-title">Review:</h2>
                <!-- <div class="stars">
                    ★★★★☆
                </div> -->
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
