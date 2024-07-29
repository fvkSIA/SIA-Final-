<?php 
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;
$showModal = false;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    
    $sql = "SELECT * FROM job_listings WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result() ?? null;
        $stmt->close();
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $jobid = $_POST['job_id'];
    $userid = $_POST['user_id'];
    $emp_id = $_POST['emp_id'];
    $type = 2;

    $sql = "INSERT INTO job_requests (user_id, job_id, type, employer_id) VALUES (?,?,?,?)";

    if ($stmt = $conn->prepare($sql)){
        $stmt->bind_param("iiii", $userid, $jobid, $type, $emp_id);
        
        if ($stmt->execute()){
            $showModal = true;
        } else {
            $error = 'Error encountered. Try again later';
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jobseeker Dashboard</title>
  <!-- Link Styles -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f9f9f9;
      color: #333;
    }
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }
    .job-details {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      padding: 20px;
    }
    .job-details h1 {
      font-size: 2rem;
      margin-bottom: 1rem;
      color: #1E3B85;
    }
    .job-details h2 {
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
      color: #333;
    }
    .job-details p {
      margin-bottom: 0.5rem;
    }
    .job-details .details {
      display: flex;
      justify-content: space-between;
      margin-bottom: 1rem;
    }
    .job-details .details div {
      background: #f0f0f0;
      padding: 10px;
      border-radius: 5px;
      flex: 1;
      margin: 0 5px;
    }
    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.4);
      align-items: center;
      justify-content: center;
    }
    .modal-content {
      background: #fff;
      border-radius: 8px;
      padding: 20px;
      max-width: 400px;
      text-align: center;
    }
    .modal-content p {
      font-size: 1.25rem;
      margin-bottom: 20px;
    }
    .modal-button {
      background-color: #1E3B85;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1rem;
    }
    .modal-button:hover {
      background-color: #1a2b6c;
    }
    .apply-button {
      background-color: #1E3B85;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1rem;
      margin-top: 20px;
    }
    .apply-button:hover {
      background-color: #1a2b6c;
    }
  </style>
</head>
<body>
  <section class="container">
    <?php if ($result != null && $result->num_rows > 0): ?>
      <?php $job = $result->fetch_assoc(); ?>
      <div class="job-details">
        <h1>Job Post</h1>
        <h2><?php echo htmlspecialchars($job['job']); ?></h2>
        <p><i class='bx bx-briefcase'></i> <?php echo htmlspecialchars($job['date']); ?> at <?php echo htmlspecialchars($job['time']); ?></p>
        <p><i class='bx bx-map'></i> <?php echo htmlspecialchars($job['location']); ?></p>

        <div class="details">
          <div>
            <p class="font-bold">Job Type</p>
            <p><?php echo htmlspecialchars($job['type']); ?></p>
          </div>
          <div>
            <p class="font-bold"><i class="bx bx-money"></i> Salary</p>
            <p><?php echo htmlspecialchars(number_format($job['salary_offer'], 2, '.', ',')); ?></p>
          </div>
        </div>

        <h3>Full Job Description</h3>
        <p><strong><i class='bx bx-list-ul pt-5'></i> Responsibilities:</strong></p>
        <ul>
          <?php echo nl2br(htmlspecialchars($job['responsibilities'])); ?>
        </ul>

        <p><strong><i class='bx bx-badge-check pt-5'></i> Qualifications:</strong></p>
        <ul>
          <?php echo nl2br(htmlspecialchars($job['qualifications'])); ?>
        </ul>

        <form id="applyForm" action="jobdetails.php" method="post">
          <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job['id']); ?>">
          <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
          <input type="hidden" name="emp_id" value="<?php echo htmlspecialchars($job['employer_id']); ?>">
          <button type="submit" class="apply-button">Apply</button>
        </form>
      </div>
    <?php endif; ?>

    <!-- The Modal -->
    <div id="myModal" class="modal">
      <div class="modal-content">
        <p>You have successfully applied for this job!</p>
        <button class="modal-button" onclick="redirect()">Continue</button>
      </div>
    </div>
  </section>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Show modal if necessary
      <?php if ($showModal) : ?>
        const modal = document.getElementById("myModal");
        modal.style.display = 'flex';
      <?php endif; ?>
      
      // Handle apply button click
      document.getElementById('applyForm')?.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission
        
        const formData = new FormData(this);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', this.action, true);
        xhr.onload = function () {
          if (this.status === 200) {
            // Show success modal
            document.getElementById('myModal').style.display = 'flex';
          } else {
            alert('Error applying for the job. Please try again.');
          }
        };
        xhr.send(formData);
      });
    });

    function closeModal() {
      const modal = document.getElementById("myModal");
      modal.style.display = "none";
    }

    function redirect() {
      location.href = "jobseekerhiring.php";
    }

    // Close the modal when clicking outside of the modal content
    window.onclick = function(event) {
      const modal = document.getElementById("myModal");
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
  </script>
</body>
</html>
