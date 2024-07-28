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
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
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

    .home-section {
      background-color: #ffffff;
      min-height: 100vh;
      padding: 40px 20px;
    }

    .title {
      font-size: 28px;
      font-weight: bold;
      color: #1d4ed8;
      margin-bottom: 20px;
    }

    .modal-button {
      background-color: #1E3B85;
      color: white;
      font-weight: bold;
      font-size: 18px;
      border: none;
      border-radius: 8px;
      padding: 10px 15px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .modal-button:hover {
      background-color: #3c5fa4;
    }

    .modal {
      display: none; /* Hidden by default */
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
      background-color: #fefefe;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 90%;
      max-width: 400px;
      border-radius: 8px;
      text-align: center;
    }

    .modal-content p {
      font-size: 18px;
      margin: 10px 0;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 24px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: #333;
      text-decoration: none;
      cursor: pointer;
    }

    .form-container {
      text-align: right;
      margin-top: 20px;
    }

    .job-details-container {
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      background-color: #ffffff;
      max-width: 800px;
    }

    .detail-item {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }

    .icon {
      margin-right: 10px;
      color: #1d4ed8;
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
      color: #1d4ed8;
    }

    @media (max-width: 768px) {
      .modal-content {
        width: 95%;
      }
    }
.detail-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px; 
}

.detail-item {
    display: flex;
    align-items: center;
    font-size: 1.2em; 
}

.icon {
    font-size: 1em;
    margin-right: 12px;
    color: #007bff; 
    margin-top: -15px;
    margin-bottom: -15px;
}

.detail-text {
    margin: 0;
    font-size: 1.2em;
    color: #333;
    margin-top: -15px;
    margin-bottom: -15px;
}

.icon b {
    font-size: 1.5em; 
    color: #28a745; 
}
.job-title {
            font-size: 34px;
            color: #333;
            font-family: Arial, sans-serif;
        }
        .job-title-sub {
            font-size: 20px;
            color: #333;
            font-family: Arial, sans-serif;
            margin: 10px 0;
            font-weight: bold;
        }
  </style>
</head>
<body>
  <section class="home-section">
    <?php if ($result != null && $result->num_rows > 0): ?>
      <?php $job = $result->fetch_assoc(); ?>
      <div class="job-details-container">
        <h2 class="job-title"><?php echo htmlspecialchars($job['job']); ?></h2>
        <p class="job-date"><?php echo htmlspecialchars($job['date']); ?></p>
        <br>
        <div class="detail-container">
            <div class="detail-item">
                <i class="fas fa-map-marker-alt icon"></i>
                <p class="detail-text"><?php echo htmlspecialchars($job['location']); ?></p>
            </div>

            <div class="detail-item">
                <i class="fas fa-briefcase icon"></i>
                <p class="detail-text"><?php echo htmlspecialchars($job['type']); ?></p>
            </div>
            
            <div class="detail-item">
                <span class="icon"><b>₱</b></span>
                <p class="detail-text"><?php echo htmlspecialchars(number_format($job['salary_offer'], 2, '.', ',')); ?></p>
            </div>
        </div>
        <hr class="mb-5">
        <h3 class="job-title-sub">Responsibilities:</h3>
        <ul class="description-list">
          <?php echo nl2br(htmlspecialchars($job['responsibilities'])); ?>
        </ul>
        <br>
        <h3 class="job-title-sub">Qualifications:</h3>
        <ul class="qualification-list">
          <?php echo nl2br(htmlspecialchars($job['qualifications'])); ?>
        </ul>

        <!-- Apply Button Form -->
        <div class="form-container">
          <form id="applyForm" action="" method="post">
            <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job['id']); ?>">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
            <input type="hidden" name="emp_id" value="<?php echo htmlspecialchars($job['employer_id']); ?>">
            <button type="submit" id="applyButton" class="modal-button">Apply</button>
          </form>
        </div>
      </div>
    <?php endif; ?>

    <!-- The Modal -->
    <div id="myModal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
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
        modal.style.display = 'block';
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
            document.getElementById('myModal').style.display = 'block';
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
