<?php 
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;
$data = []; // Initialize $data as an empty array

$id = $_SESSION['user_id'];

$sql = "SELECT job_requests.id as job_req_id, job_requests.user_id as job_req_userid, job_requests.employer_id as job_req_empid, job_requests.type as job_req_type, job_requests.status as job_req_stat, job_requests.is_accepted as job_req_acc, job_listings.id as job_list_id, job_listings.job as job_list_job, job_listings.date as job_list_data, job_listings.time as job_list_time, job_listings.time as job_list_type, job_listings.salary_offer as job_list_sal, job_listings.location as job_list_loc, job_offers.location as job_list_loca, job_listings.responsibilities as job_list_respo, job_listings.qualifications as job_list_quali, job_listings.accepted as job_list_accept, job_offers.* FROM job_requests
        LEFT JOIN job_listings ON job_requests.job_id = job_listings.id
        LEFT JOIN job_offers ON job_requests.job_id = job_offers.id 
        WHERE job_requests.status = 0 
        AND job_requests.is_accepted = 1
        AND job_requests.employer_id = ?";

if ($stmt = $conn->prepare($sql)){
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
    }
    
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Employer Dashboard - Ongoing Jobs</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f0f4f8;
    }
    .container {
      max-width: 1000px;
      margin: 0 auto;
      padding: 40px 20px;
    }
    .ongoing-title {
      font-size: 32px;
      color: #6b0d0d; 
      margin-bottom: 30px;
      border-bottom: 3px solid #6b0d0d;
      padding-bottom: 10px;
      text-align: center;
    }
    .job-card {
      transition: all 0.3s ease;
    }
    .job-card:hover {
      transform: translateY(-5px);
    }
  </style>
</head>
<body class="bg-gray-100">
  <div class="container">
    <h1 class="ongoing-title">Ongoing Jobs</h1>

    <?php if ($data): ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($data as $row): ?>
          <div class="job-card bg-white p-6 rounded-lg shadow-md hover:shadow-xl">
            <h3 class="font-bold text-xl text-gray-800 mb-3">
              <?php echo $row['job'] ?? $row['job_list_job']; ?>
            </h3>
            <p class="text-gray-600 mb-3">
              <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
              <?php echo $row['job_list_loc']; ?><?php echo $row['job_list_loca']; ?>
            </p>
            <?php if ($row['job_req_stat'] == 0): ?>
              <span class="inline-block bg-green-500 text-white font-semibold py-1 px-3 rounded-full text-sm mb-4">
                ONGOING
              </span>
            <?php endif; ?>
            <a href="employerongoingdetails.php?id=<?php echo $row['job_req_id']; ?>" 
               class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition-colors duration-300 w-full text-center">
              View Details
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="bg-white p-8 rounded-lg shadow-md text-center">
        <i class="fas fa-briefcase text-5xl text-gray-400 mb-4"></i>
        <p class="text-xl text-gray-700">No ongoing jobs at the moment.</p>
      </div>
    <?php endif; ?>
  </div>

  <script src="script.js"></script>
</body>
</html>