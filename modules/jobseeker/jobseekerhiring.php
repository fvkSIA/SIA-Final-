<?php
session_start();
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';

// Kunin ang user information
$user_job_type = '';
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_query = "SELECT job_type FROM users WHERE id = ?";
    $stmt = $conn->prepare($user_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        $user_job_type = $user['job_type'];
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $location = $_POST['location'] ?? '';

  $sql = "SELECT job_listings.*, users.id as user_id, users.profile, users.firstname, users.lastname, users.middlename, users.email 
          FROM job_listings 
          INNER JOIN users ON users.id = job_listings.employer_id
          WHERE users.type = 3 AND job_listings.job = ? AND job_listings.accepted = 0";

  $types = "s";
  $params = array($user_job_type);

  // Add location condition if specified
  if (!empty($location) && $location != "Select location") {
      $sql .= " AND job_listings.location = ?";
      $types .= "s";
      $params[] = $location;
  }

  // Prepare and execute the SQL statement
  if ($stmt = $conn->prepare($sql)) {
      $stmt->bind_param($types, ...$params);
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();

      // Check if there are no results
      if ($result->num_rows === 0) {
          $noJobsMessage = 'No job posts available matching your job type and criteria.';
      }
  } else {
      $noJobsMessage = 'Error preparing the SQL query: ' . $conn->error;
  }
} else {
  // If it's not a POST request, show all jobs matching the user's job type
  $sql = "SELECT job_listings.*, users.id as user_id, users.profile, users.firstname, users.lastname, users.middlename, users.email 
          FROM job_listings 
          INNER JOIN users ON users.id = job_listings.employer_id
          WHERE users.type = 3 AND job_listings.job = ? AND job_listings.accepted = 0";
  
  if ($stmt = $conn->prepare($sql)) {
      $stmt->bind_param("s", $user_job_type);
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();

      if ($result->num_rows === 0) {
          $noJobsMessage = 'No job posts available matching your job type.';
      }
  } else {
      $noJobsMessage = 'Error preparing the SQL query: ' . $conn->error;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Jobseeker Dashboard</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
  <script>
        // Check if PHP has set a noJobsMessage and alert it
        <?php if (!empty($noJobsMessage)): ?>
            window.onload = function() {
                alert('<?php echo addslashes($noJobsMessage); ?>');
            };
        <?php endif; ?>
    </script>
  <style>
    .apply-now-container {
      position: relative;
      height: 100%;
    }
    .apply-now-button {
      position: absolute;
      bottom: 10px;
      right: 10px;
      padding: 10px 20px;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-family: 'Poppins', sans-serif;
      text-align: center;
      text-decoration: none; /* Ensure the text isn't underlined */
      display: inline-block;
    }
    .apply-now-button:hover {
      background-color: #1E3A8A; /* Tailwind's blue-800 */
    }
    .container1 {
      position: relative;
      width: 100%;
      max-width: 1200px; /* Max width for larger screens */
      height: 1100px; /* Adjust height to fit 10 job boxes */
      overflow: hidden;
      border: 1px solid #ccc;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .slider-container {
      position: relative;
      width: 60%; /* Full width for responsiveness */
      height: auto; /* Adjust this height according to your design */
      overflow: hidden;
      border: 1px solid #ccc;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .slider {
      display: flex;
      flex-direction: column;
      transition: transform 0.5s ease-in-out;
    }
    .job-box {
      border: 1px solid #ccc;
      padding: 20px;
      border-radius: 10px;
      width: 95%;
      margin: 10px auto; /* Center the job box horizontally */
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      flex: 0 0 auto;
      display: block;
      border: 2px solid transparent; /* Default border color */
      transition: border-color 0.3s ease; /* Smooth transition for border color */
    }
    .job-box:hover {
      border-color: #007bff; /* Border color on hover */
    }
    .job-box:focus {
      border-color: #28a745; /* Border color on focus (if the element can be focused) */
    }
    /* Optional: Style the border when clicked or selected (depends on user interactions) */
    .job-box:active {
      border-color: #dc3545; /* Border color on active (when clicked) */
    }
    .job-title {
      font-size: 18px;
      font-weight: bold;
      text-decoration: none;
      color: #333;
    }
    .responsibilities {
      font-size: 14px;
      color: #555;
    }
    .row {
      display: flex;
      justify-content: space-between;
      margin-top: 10px;
    }
    .column {
      flex: 1;
      text-align: center;
    }
    .column h2 {
      font-size: 14px;
      color: #333;
      margin-bottom: 5px;
    }
    .column p {
      font-size: 14px;
      color: #555;
    }
    .icon {
      margin-right: 5px;
    }
    .piso-sign {
      font-family: 'Poppins', sans-serif;
    }
    button.prev, button.next {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      background-color: #333;
      color: white;
      border: none;
      padding: 10px;
      cursor: pointer;
      width: 80%; 
      max-width: 90%; 
      box-sizing: border-box;
      text-align: center;
      font-size: 16px; 
    }
    button.prev {
      bottom: 20px; 
    }
    button.next {
      bottom: 70px; 
    }
    @media (max-width: 600px) {
      .slider-container {
        width: 90%; 
      }
      button.prev, button.next {
        width: 100%;
        font-size: 14px;
      }
    }
    .apply-button {
      background-color: #4CAF50; /* Green background */
      border: none; /* Remove border */
      color: white; /* White text */
      padding: 10px 20px; /* Some padding */
      text-align: center; /* Center text */
      text-decoration: none; /* Remove underline */
      display: inline-block; /* Inline block */
      font-size: 16px; /* Increase font size */
      margin: 10px 0; /* Margin around the button */
      cursor: pointer; /* Pointer cursor on hover */
      border-radius: 5px; /* Rounded corners */
    }
    .apply-button:hover {
      background-color: #45a049; /* Darker green on hover */
    }
    .button-container {
    margin-top: 65%;
      display: flex;
      justify-content: space-between; /* Ensures the buttons are at opposite ends */
      align-items: center; /* Aligns buttons vertically in the center */
      width: 100%; /* Adjust width as needed */
      padding: 10px; /* Adds padding around the container */
      box-sizing: border-box; /* Ensures padding is included in the width */
    }
    .button-container button {
      background-color: #007bff; /* Button background color */
      color: white; /* Text color */
      border: none; /* Removes border */
      border-radius: 5px; /* Rounds corners */
      padding: 10px 20px; /* Adds padding inside the button */
      cursor: pointer; /* Changes cursor to pointer on hover */
      font-size: 16px; /* Sets font size */
    }
    .button-container .prev {
      /* Additional styles for 'Prev' button if needed */
    }
    .button-container .next {
      /* Additional styles for 'Next' button if needed */
    }
    .button-container button:hover {
      background-color: #0056b3; /* Changes background on hover */
    }



    .container2 {
      position: relative;
      width: 100%;
      max-width: 1200px; /* Max width for larger screens */
      height: 1100px; /* Adjust height to fit 10 job boxes */
      overflow: hidden;
      border: 1px solid #ccc;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .slider-container2 {
      position: relative;
      width: 50%; /* Full width for responsiveness */
      height: auto; /* Adjust this height according to your design */
      overflow: hidden;
      border: 1px solid #ccc;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .slider1 {
      display: flex;
      flex-direction: column;
      transition: transform 0.5s ease-in-out;
    }
    .job-box1 {
      border: 1px solid #ccc;
      padding: 20px;
      border-radius: 10px;
      width: 95%;
      margin: 10px auto; /* Center the job box horizontally */
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      flex: 0 0 auto;
      display: block;
      border: 2px solid transparent; /* Default border color */
      transition: border-color 0.3s ease; /* Smooth transition for border color */
    }
    .job-box1:hover {
      border-color: #007bff; 
    }
    .job-box1:focus {
      border-color: #28a745; /* Border color on focus (if the element can be focused) */
    }
    /* Optional: Style the border when clicked or selected (depends on user interactions) */
    .job-box1:active {
      border-color: #dc3545; /* Border color on active (when clicked) */
    }
    .job-title1 {
      font-size: 18px;
      font-weight: bold;
      text-decoration: none;
      color: #333;
    }
    .responsibilities1 {
      font-size: 14px;
      color: #555;
    }
    .row1 {
      display: flex;
      justify-content: space-between;
      margin-top: 10px;
    }
    .column1 {
      flex: 1;
      text-align: center;
    }
    .column1 h2 {
      font-size: 14px;
      color: #333;
      margin-bottom: 5px;
    }
    .column1 p {
      font-size: 14px;
      color: #555;
    }
    .icon1 {
      margin-right: 5px;
    }
    .piso-sign1 {
      font-family: 'Poppins', sans-serif;
    }
    button.prev1, button.next1 {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      background-color: #333;
      color: white;
      border: none;
      padding: 10px;
      cursor: pointer;
      width: 80%; /* Adjust width to fit within the container */
      max-width: 90%; /* Ensure the buttons stay within the slider-container */
      box-sizing: border-box;
      text-align: center;
      font-size: 16px; /* Adjust font size for better readability */
    }
    button.prev1 {
      bottom: 20px; /* Adjust spacing from the bottom */
    }
    button.next1 {
      bottom: 70px; /* Adjust spacing from the bottom to separate the buttons */
    }
    /* Additional media query for smaller screens */
    @media (max-width: 600px) {
      .slider-container2 {
        width: 90%; /* Make the slider container wider on smaller screens */
      }
      button.prev1, button.next1 {
        width: 100%; /* Increase button width for smaller screens */
        font-size: 14px; /* Adjust font size for smaller screens */
      }
    }
    .apply-button1 {
      background-color: #4CAF50; /* Green background */
      border: none; /* Remove border */
      color: white; /* White text */
      padding: 10px 20px; /* Some padding */
      text-align: center; /* Center text */
      text-decoration: none; /* Remove underline */
      display: inline-block; /* Inline block */
      font-size: 16px; /* Increase font size */
      margin: 10px 0; /* Margin around the button */
      cursor: pointer; /* Pointer cursor on hover */
      border-radius: 5px; /* Rounded corners */
    }
    .apply-button1:hover {
      background-color: #45a049; /* Darker green on hover */
    }
    .button-container2 {
    margin-top: 65%;
      display: flex;
      justify-content: space-between; /* Ensures the buttons are at opposite ends */
      align-items: center; /* Aligns buttons vertically in the center */
      width: 100%; /* Adjust width as needed */
      padding: 10px; /* Adds padding around the container */
      box-sizing: border-box; /* Ensures padding is included in the width */
    }
    .button-container2 button {
      background-color: #007bff; /* Button background color */
      color: white; /* Text color */
      border: none; /* Removes border */
      border-radius: 5px; /* Rounds corners */
      padding: 10px 20px; /* Adds padding inside the button */
      cursor: pointer; /* Changes cursor to pointer on hover */
      font-size: 16px; /* Sets font size */
    }
    .button-container2 .prev1 {
    }
    .button-container2 .next1 {
    }
    .button-container2 button:hover {
      background-color: #0056b3;
    }
    .custom-label {
      font-family: 'Poppins', sans-serif;
  font-size: 18px;
  color: #4A4A4A;
  font-weight: 600;
  margin: 15px 0;
  line-height: 1.5;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
  padding: 5px 10px;
  border-left: 4px solid #007BFF;
  background-color: #F9F9F9;
  border-radius: 5px;
}

  </style>
</head>
<body>
<main class="flex-1 p-8">
    <div class="bg-blue-100 p-6 rounded-lg mb-2 flex justify-center">
    <form action="jobseekerhiring.php" method="post">
    <div class="flex space-x-4 items-center">
        <label class="custom-label">Here are the job post for <?php echo htmlspecialchars($user_job_type); ?></label>
        <select class="py-2 px-4 rounded bg-white border border-gray-300" name="location">
            <option value="">All Cities</option>
            <option value="Manila">Manila</option>
            <option value="Caloocan">Caloocan</option>
            <option value="Valenzuela">Valenzuela</option>
            <option value="Pasay">Pasay</option>
            <option value="Makati">Makati</option>
            <option value="Quezon City">Quezon City</option>
            <option value="Navotas">Navotas</option>
            <option value="Las Piñas">Las Piñas</option>
            <option value="Malabon">Malabon</option>
            <option value="Mandaluyong">Mandaluyong</option>
            <option value="Marikina">Marikina</option>
            <option value="Muntinlupa">Muntinlupa</option>
            <option value="Parañaque">Parañaque</option>
            <option value="Pasig">Pasig</option>
            <option value="San Juan">San Juan</option>
            <option value="Taguig">Taguig</option>
            <option value="Pateros">Pateros</option>
        </select>
            <button class="py-2 px-6 bg-blue-900 text-white rounded hover:bg-blue-800" type="submit">Find Now!</button>
        </div>
    </form>
    
    </div>
    <div class="mx-auto mt-8 p-4 border-t-4 border-indigo-200 shadow-lg rounded-lg bg-white">
        <div class="flex items-top justify-between">
        <?php 
$data = [];
if ($result != null) {
    $data = $result->fetch_all(MYSQLI_ASSOC);
    
    // Sort the data array by created_at in descending order
    usort($data, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
}

// Function to format salary
function formatSalary($salary) {
    return number_format($salary, 2);
}
?>

<?php if ($data): ?>
    <div class='slider-container2'>
        <div class='slider1'>
            <?php foreach ($data as $row): ?>
                <?php $formatted_salary_offer = formatSalary($row["salary_offer"]); ?>
                <a href='#' data-id='<?= $row['id'] ?>' class='job-box1' onclick='loadJobDetails(<?= $row['id'] ?>)'>
                    <div class='job-box-content1'>
                        <h3 class='job-title1'><?= htmlspecialchars($row["job"], ENT_QUOTES, 'UTF-8') ?></h3>
                        <div class='row1'>
                            <div class='column1'>
                                <h4>Type</h4>
                                <p class='icon1 fas fa-briefcase'> <?= htmlspecialchars($row["type"], ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            <div class='column1'>
                                <h4>Location</h4>
                                <p class='icon1 fas fa-map-marker-alt'> <?= htmlspecialchars($row["location"], ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            <div class='column1'>
                                <h4>Salary Offer</h4>
                                <p><span class='piso-sign1'>&#8369;</span> <?= htmlspecialchars($formatted_salary_offer, ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <div class='button-container2'>
            <button class='prev1' onclick='slide(-1)'>Prev</button>
            <button class='next1' onclick='slide(1)'>Next</button>
        </div>
    </div>
    <script>
        function loadJobDetails(id) {
            const container = document.querySelector('.container2');
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'jobdetails.php?id=' + id, true);
            xhr.onload = function () {
                if (this.status === 200) {
                    container.innerHTML = this.responseText;
                } else {
                    container.innerHTML = '<p>Error loading job details.</p>';
                }
            };
            xhr.send();
        }
    </script>
    <div class="container2"></div>
<?php else: ?>
  <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hanapkita_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Kunin ang job_type ng user
$user_job_type = '';
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_query = "SELECT job_type FROM users WHERE id = ?";
    $stmt = $conn->prepare($user_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    if ($user = $user_result->fetch_assoc()) {
        $user_job_type = $user['job_type'];
    }
    $stmt->close();
}

// Ipakita ang lahat ng job posts na hindi pa tinanggap
$sql = "SELECT job_listings.*, users.id as user_id, users.profile, users.firstname, users.lastname, users.middlename, users.email 
        FROM job_listings 
        INNER JOIN users ON users.id = job_listings.employer_id
        WHERE users.type = 3 AND job_listings.job = ? AND job_listings.accepted = 0";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_job_type);
$stmt->execute();
$result = $stmt->get_result();

$jobs = [];
if ($result->num_rows > 0) {
    $jobs = $result->fetch_all(MYSQLI_ASSOC);
}

$stmt->close();
$conn->close();
?>
<h2 class="text-xl font-bold mb-4">
  There are no <?php foreach ($jobs as $job): ?>
    <?php echo htmlspecialchars($job['job']); ?>
    <?php endforeach; ?> Jobs in that City</h2>
<?php endif; ?>
    </div>
</main>
</body>
</html>
