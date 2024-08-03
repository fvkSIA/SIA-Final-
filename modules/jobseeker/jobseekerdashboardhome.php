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
  <title>Employer Dashboard</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
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
        
        function loadJobDetails(id) {
            const container = document.querySelector('.container2');
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'jobdetails.php?id=' + id, true);
            xhr.onload = function () {
                console.log('Status:', this.status); // Log the status
                console.log('Response:', this.responseText); // Log the response
                if (this.status === 200) {
                    container.innerHTML = this.responseText;
                } else {
                    container.innerHTML = '<p>Error loading job details.</p>';
                }
            };
            xhr.onerror = function() {
                container.innerHTML = '<p>Request error.</p>';
            };
            xhr.send();
        }
    </script>
  <style>

@import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');



    .home-section {
      position: relative;
      background-color: var(--color-body);
      min-height: 100vh;
      top: 0;
      width: calc(100% - 78px);
      transition: all .5s ease;
      z-index: 2;
      margin: 0 auto;
    }
    

.home-section .text{
  display: inline-block;
  color:var(--color-default);
  font-size: 25px;
  font-weight: 500;
  margin: 10px;
}




.text1 {
  display: inline-block;
  margin: 0;
  color: #3D52A0;
  font-size: 120px;
  font-family: 'Poppins', sans-serif;
  position: absolute;
  top: -600px;
  left: 70px; /* Adjusted value to position the title relative to the logo */
  }
  .text2 {
  display: inline-block;
  margin: 0;
  color: #3D52A0;
  font-size: 15px;
  font-family: 'Poppins', sans-serif;
  font-style: italic;
  position: absolute;
  top: -295px;
  left: 90px; /* Adjusted value to position the title relative to the logo */
  }
  .landpic {
  position: absolute;
  top: -680px; /* Adjust as needed */
  left: 670px; /* Adjust to move it to the right */
  }
  .pic1 {
  height: 650px; /* Adjust height as needed */
  width: 580px; /* Adjust width as needed */
  }

  @media (max-width: 768px) {
    /* Adjustments for smaller screens (tablets and phones) */
    .text1 {
        font-size: 2em; /* Decrease font size */
    }

    .text2 {
        font-size: 0.8em; /* Decrease font size */
    }
}
  .right-half {
  position: absolute;
  top: 0px;
  right: 0;
  width: 40%; /* Adjust as needed */
  height: 100%;
  background-color: #EDE8F5; /* Set background color */
  }
  .search-bar {
  background-color: #7091E6; /* Medium blue background */
  padding: 20px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  position: absolute;
  top: -180px; /* Adjust this value to position the search bar */
  left: 120px; /* Adjust this value to position the search bar */
  gap: 15px;
  }
  .search-bar input, .search-bar select, .search-bar button {
  border: none;
  border-radius: 10px;
  padding: 13px;
  margin-right: 25px;
  font-size: 20px;
  }
  .search-bar input:focus, .search-bar select:focus, .search-bar button:focus {
  outline: none;
  }
  .search-bar input {
  flex: 1;
  }
  .search-bar select {
  flex: 1.5; /* Adjust flex value to expand the select box */
  }
  .search-bar button {
  background-color: #3D52A0; /* Dark blue button */
  color: white;
  cursor: pointer;
  }
  .search-bar button:hover {
  background-color: #2d3d82; /* Slightly darker blue on hover */
  }

  .container {
    display: flex;
    background-color: rgba(255, 255, 255);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
    margin-top: 0px; /* Adjusted margin to move the content further down */
    width: 100%; /* Make the container span the entire width */
    margin: 0 auto; /* Center align horizontally */
    flex-wrap: nowrap; /* Prevent wrapping to keep items in a row */
}

  .top-job-seekers {
    background-color: rgba(255, 255, 255, 0);
    padding: 10px;
    border-radius: 20px;
    width: 600px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 25px auto; /* Center horizontally with auto margins */
    text-align: center; /* Center text content inside the div */
    height: 320px; /* Adjusted height */
}


        .top-job-seekers h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #3D52A0;
        }
        .job-seeker {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #ADBBDA;
            border-radius: 20px;
            padding: 15px 20px;
            margin-bottom: 10px;
        }
        .job-seeker img {
            border-radius: 50%;
            width: 60px;
            height: 70px;
        }
        .job-seeker-info {
            flex: 1;
            margin-left: 15px;
            text-align: left;
        }
        .job-seeker-info h3 {
            margin: 0;
            font-size: 16px;
        }
        .job-seeker-info p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }
        .job-seeker-info .ratings {
            color: #FFD700;
        }
        .rank {
            font-size: 18px;
            color: #3D52A0;
            text-align: right;
        }
        .rank span {
            display: block;
            font-size: 14px;
            color: #555;
        }
        .navigation {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
        .navigation span {
            width: 10px;
            height: 10px;
            margin: 0 5px;
            background-color: #ccc;
            border-radius: 50%;
            display: inline-block;
        }
        .navigation .active {
            background-color: #3D52A0;
        }
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
            position: -webkit-sticky; /* For Safari */
            position: sticky;
            top: 0; /* Stick to the top */
            width: 100%;
            height: 100%; /* Adjust height as needed */
            overflow-y: auto; /* Adds vertical scrolling */
            overflow-x: hidden; /* Prevents horizontal scrolling */
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            z-index: 1000; /* Ensure it stays on top of other content */
        }
        .content {
            height: 2000px; /* Adjust height to create enough scrolling space */
            padding-top: 50px; /* To ensure the sticky element does not overlap the content */
        }

    .slider-container2 {
      position: relative;
      width: 55%; /* Full width for responsiveness */
      height: auto; /* Adjust this height according to your design */
      overflow: hidden;
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
    margin-top: 30%;
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
    .button-container2 button:hover {
      background-color: #0056b3;
    }
    .custom-label {
    font-family: 'Poppins', sans-serif;
    font-size: 16px;
    color: #4A4A4A;
    font-weight: 600;
    margin: 2px 0;
    line-height: 1.5;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
    padding: 10px;
    border-left: 4px solid #007BFF;
    background-color: #F9F9F9;
    border-radius: 5px;
    display: block;
    width: 100%;
  }

  .custom-select {
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    background-color: #fff;
    width: 100%;
    max-width: 300px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .custom-button {
    padding: 10px 20px;
    background-color: #007BFF;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
    margin-left: 10px;
  }

  .custom-button:hover {
    background-color: #0056b3;
  }

  .form-container {
    background-color: #F0F4F8;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: 0 auto;
  }

  .form-group {
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
  }

  .form-group label {
    margin-bottom: 5px;
  }
  .description {
    border-radius: 8px;
    padding: 40px;
    margin: 20px 0;
    font-family: Arial, sans-serif;
    text-align: justify;
}

.description h2 {
    color: #333;
    font-size: 24px;
    margin-bottom: 10px;
    text-align: center;
}

.description p {
    color: #555;
    line-height: 1.6;
    margin-bottom: 15px;
}

.apply-button {
    display: inline-block;
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    text-decoration: none;
    font-size: 16px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.apply-button:hover {
    background-color: #0056b3;
}

  </style>
</head>
<body>

  <section class="home-section">
    
    <div style="display: flex; flex-wrap: wrap; background-color: rgba(255, 255, 255, 0.8); border: 1px solid #ccc; border-radius: 10px;  padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin: 10px;">
    <div style="flex: 1 1 60%; box-sizing: border-box; text-align: center;">
        <p style="font-family: 'Poppins', sans-serif; font-size: 2.5em; font-weight: bold; color: #004f83; margin: 0;">
            Search, Find, and Apply!
        </p>
    </div>
    </div>
    <main class="flex-1 p-8">
    <div class="bg-blue-100 p-6 rounded-lg mb-2 flex justify-center">
    <form action="jobseekerdashboardhome.php" method="post">
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
            <button class="py-2 px-6 bg-blue-900 text-white rounded hover:bg-blue-800" type="submit">Find</button>
        </div>
    </form>
    
    </div>
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
            <?php
                $jobs_per_page = 15;
                $total_jobs = count($data); 
                $total_pages = ceil($total_jobs / $jobs_per_page); 

                $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $current_page = max(min($current_page, $total_pages), 1);

                $offset = ($current_page - 1) * $jobs_per_page;
                $jobs_to_display = array_slice($data, $offset, $jobs_per_page);
                ?>
                <div class='slider-container2'>
                    <div class='slider1'>
                        <?php foreach ($jobs_to_display as $row): ?>
                            <?php $formatted_salary_offer = formatSalary($row["salary_offer"]); ?>
                            <a href='#' data-id='<?= $row['id'] ?>' class='job-box1' onclick='loadJobDetails(<?= $row['id'] ?>)'>
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
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <div class='button-container2'>
                        <button class='prev1' onclick='navigate(-1)'>Prev</button>
                        <button class='next1' onclick='navigate(1)'>Next</button>
                    </div>
                </div>

            <div class="container2"  id="scrollContainer">
            <div class="description">
    <div style="flex: 1 1 60%; box-sizing: border-box; text-align: center;">
        <p style="font-family: 'Poppins', sans-serif; font-size: 2.5em; font-weight: bold; color: #004f83; margin: 0;">
            Search, Find, and Apply!
        </p>
    </div>
            <h2>Apply Now and Transform Your Future!</h2>
    <p>
        Are you ready to take the next step in your career? Don’t miss the opportunity to be part of an innovative and dynamic team. Our application process is designed to be straightforward and efficient, ensuring that you can showcase your skills and qualifications with ease. By applying now, you open doors to exciting career prospects, professional growth, and the chance to work in an environment that fosters creativity and success.
    </p>
    <p>
        We are looking for passionate individuals who are eager to contribute their talents and drive to our organization. Whether you are seeking a new challenge or aiming to advance in your field, applying today can set you on the path to achieving your career goals. Our team values dedication, innovation, and a proactive approach, and we are excited to discover what you can bring to our company.
    </p>

            </div>
            </div>
<script>
    let currentPage = <?= $current_page ?>;
const totalPages = <?= $total_pages ?>;

function navigate(direction) {
    let newPage = currentPage + direction;
    if (newPage < 1) newPage = 1;
    if (newPage > totalPages) newPage = totalPages;

    if (newPage !== currentPage) {
        currentPage = newPage;
        window.location.href = `?page=${currentPage}`;
    }
}

    
</script>

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
    
    <br>
    <div class="container">
        <?php include '../metromanila.php' ?>
    </div>

          <?php include '../employer/em_footer.html'; ?>


  </section>
  <!-- Scripts -->
  <script src="script.js"></script>
</body>
</html>
