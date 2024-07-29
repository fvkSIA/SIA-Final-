<?php
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$showModal = false;

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'hanapkita_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user city
$stmt = $conn->prepare("SELECT city FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_city);
$user_city = '';

if ($stmt->fetch()) {
    $user_city = htmlspecialchars($user_city);
}
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emp_id = $_POST['emp_id'];
    $job = $_POST['job'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $job_type = $_POST['job_type'];
    $salary = $_POST['salary'];
    $location = $_POST['location']; // Use the submitted location
    $job_desc = $_POST['job_responsibilities'];
    $job_quali = $_POST['job_qualifications'];

    $sql = "INSERT INTO job_listings (job, date, time, type, salary_offer, location, responsibilities, qualifications, employer_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ssssssssi', $job, $date, $time, $job_type, $salary, $location, $job_desc, $job_quali, $emp_id);
        if ($stmt->execute()) {
            $showModal = true;
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Error preparing statement: " . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employer Dashboard</title>
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <link rel="icon" type="image/png" href="../HanapKITA.png">
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap");

body {
  font-family: "Poppins", sans-serif;
  margin: 0;
  padding: 0;
}

.container {
  width: 95%;
  margin: 0 auto;
  padding: 20px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.title {
  font-size: 32px;
  color: #6b0d0d;
  margin-bottom: 20px;
  font-weight: bold;
  text-align: center;
}

.post-box {
  background-color: #f9f9f9;
  padding: 20px;
  border-radius: 10px;
  margin-bottom: 10px;
  display: block;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.post-box h2 {
  font-size: 28px;
  color: #333;
  font-weight: bold;
  margin-bottom: 15px;
}

.post-box label {
  font-size: 16px;
  color: #333;
  font-weight: bold;
  display: block;
  margin-bottom: 5px;
}

.post-box input[type=text],
.post-box input[type=number],
.post-box input[type=date],
.post-box input[type=time],
.post-box select,
.post-box textarea {
  height: 40px;
  font-size: 16px;
  display: block;
  width: 100%;
  border: 1px solid #ccc;
  border-radius: 5px;
  margin-bottom: 15px;
  padding: 10px;
  box-sizing: border-box;
}

.post-box textarea {
  height: 120px;
  resize: vertical;
}

.form-group {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
}

.form-group > div {
  flex: 1;
  min-width: 200px;
}

.button-container {
  display: flex;
  justify-content: right;
  align-items: center;
}

.post-button {
  width: 20%;
  background-color: #6b0d0d;
  color: #fff;
  font-weight: bold;
  font-size: 18px;
  border: none;
  border-radius: 25px;
  padding: 10px 20px;
  cursor: pointer;
}

.post-button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
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
}

.modal-content {
  background-color: #fff;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
  max-width: 500px;
  border-radius: 10px;
  text-align: center;
}

.modal-content p {
  font-size: 18px;
}

.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

.modal-button {
  background-color: #1E3B85;
  color: #fff;
  font-weight: bold;
  font-size: 16px;
  border: none;
  border-radius: 5px;
  padding: 10px 20px;
  cursor: pointer;
  margin-top: 15px;
}

.modal-button:hover {
  background-color: #163a6b;
}

.back-button {
  position: absolute;
  top: 10px;
  left: 10px;
  background-color: #6b0d0d;
  color: white;
  padding: 10px 15px;
  border-radius: 5px;
  text-decoration: none;
}

.back-button a {
  color: white;
  text-decoration: none;
}

.back-button:hover {
  background-color: #8b1b0d;
}
  </style>
</head>
<body>
<div class="back-button">
  <a href="/SIA-Final-/modules/employer/employer_jobpost_history.php">Back to Job List</a>
</div>
<div class="container">
  <form id="jobForm" action="employerjobpost.php" method="post">
    <div class="title">POST A JOB</div>
    <div class="post-box">
      <div class="form-group">
        <div>
          <label for="job">Hiring for:</label>
          <input type="hidden" name="emp_id" value="<?php echo htmlspecialchars($_SESSION['user_id']);?>">
          <select id="job" name="job" required>
            <option value="" selected="selected">Choose job</option>
            <option value="Welder">Welder</option>
            <option value="Plumber">Plumber</option>
            <option value="Lineman">Lineman</option>
            <option value="Guard">Security Guard</option>
            <option value="Electrician">Electrician</option>
            <option value="Driver">Driver</option>
            <option value="Refservice">Refrigerator and Aircon Service</option>
            <option value="Foodservice">Food Service</option>
            <option value="Laundry">Laundry Staff</option>
            <option value="Factory">Factory Worker</option>
            <option value="Housekeeper">Housekeeper</option>
            <option value="Janitor">Janitor</option>
            <option value="Carpenter">Carpenter</option>
            <option value="Construction">Construction Worker</option>
          </select>
        </div>
        <div>
          <label for="job_type">Job Type:</label>
          <select id="job_type" name="job_type" required>
            <option value="" selected="selected">Choose Job Type</option>
            <option value="fulltime">Full Time</option>
            <option value="parttime">Part Time</option>
            <option value="onetime">One Time</option>
          </select>
        </div>
        <div>
          <label for="location">Location:</label>
          <input type="hidden" id="location" name="location" value="<?php echo htmlspecialchars($user_city);?>">
          <input style="color: #8D8D8E;" type="text" id="locationDisplay" value="<?php echo htmlspecialchars($user_city);?>" readonly>
        </div>
      </div>
      <div class="form-group">
        <div>
          <label for="salary">Salary Offer:</label>
          <input type="number" id="salary" name="salary" min="0" required>
        </div>
        <div>
          <label for="date">Date:</label>
          <input type="date" id="date" name="date" required>
        </div>
        <div>
          <label for="time">Time:</label>
          <input type="time" id="time" name="time" required>
        </div>
      </div>
      <div>
        <label for="job_responsibilities">Job Responsibilities:</label>
        <textarea id="job_responsibilities" name="job_responsibilities" required></textarea>
      </div>
      
      <div>
        <label for="job_qualifications">Job Qualifications:</label>
        <textarea id="job_qualifications" name="job_qualifications" required></textarea>
      </div>
      
      <div class="button-container">
        <button type="submit" class="post-button">Post Job</button>
      </div>
    </div>
  </form>
</div>

<!-- Modal -->
<div id="successModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <p>Job posted successfully!</p>
    <button class="modal-button" onclick="window.location.href='employer_jobpost_history.php'">View Job List</button>
  </div>
</div>

<script>
document.getElementById('job_responsibilities').addEventListener('input', function() {
  let lines = this.value.split('\n');
  let formattedLines = lines.map(line => line.startsWith('• ') ? line : '• ' + line);
  this.value = formattedLines.join('\n');
});
document.getElementById('job_qualifications').addEventListener('input', function() {
  let lines = this.value.split('\n');
  let formattedLines = lines.map(line => line.startsWith('• ') ? line : '• ' + line);
  this.value = formattedLines.join('\n');
});

document.getElementById('jobForm').addEventListener('submit', function(event) {
  const form = event.target;
  if (!form.checkValidity()) {
    event.preventDefault();
    event.stopPropagation();
    // Optionally show some error messages
  }
});

window.onload = function() {
  <?php if ($showModal): ?>
    const modal = document.getElementById('successModal');
    const closeModal = document.querySelector('.close');
    modal.style.display = 'block';

    closeModal.onclick = function() {
      modal.style.display = 'none';
    }

    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = 'none';
      }
    }
  <?php endif; ?>
};
</script>
</body>
</html>
