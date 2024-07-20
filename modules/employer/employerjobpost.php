<?php 

require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;
$showModal = false;


if ($_SERVER["REQUEST_METHOD"] == "POST"){
  // print_r($_POST);
  // die();
  $emp_id = $_POST['emp_id'];
  // $jobseeker_id = $_POST['jobseeker_id'];
  $job = $_POST['job'];
  $date = $_POST['date'];
  $time = $_POST['time'];
  $job_type = $_POST['job_type'];
  $salary = $_POST['salary'];
  $location = $_POST['location'];
  $job_desc = $_POST['job_responsibilities'];
  $job_quali = $_POST['job_qualifications'];

  $sql = "INSERT INTO job_listings (job, date, time, type, salary_offer, location, responsibilities, qualifications, employer_id) VALUES (?,?,?,?,?,?,?,?,?)";

  if ($stmt = $conn->prepare($sql)){
    $stmt->bind_param('ssssssssi', $job, $date, $time, $job_type, $salary, $location, $job_desc, $job_quali, $emp_id);
    if ($stmt->execute()){
      $showModal = true;
    } else {
        $errorMessage = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    $stmt->close();
  }
  // $showModal = true;
  // die();
}

$conn->close();


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Employer Dashboard</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <style>
      @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap");

    body {
      font-family: "Poppins", sans-serif;
    }

    .container {
      max-width: 100%;
      border-radius: 10px;
    }

    .title {
      font-size: 32px;
      color: #1E3B85;
      margin-bottom: 20px;
      font-weight: bold;
      text-align: left;
    }

    .post-box {
      background-color: #d7dfee00;
      width: 85%;
      padding-left: 30px;
      padding-top: 2%;
      padding-right: 10%;
      padding-bottom: 10%;
      border-radius: 10px;
      margin-bottom: 20px;
      display: inline-block;
    }

    .post-box h2 {
      font-size: 30px;
      color: #333333;
      font-weight: bold;
    }

    .post-box label {
      font-size: 20px;
      color: #333333;
      font-weight: bold;
      text-align: left;
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
      border-radius: 20px;
      box-sizing: border-box;
      margin-bottom: 10px;
      padding: 10px;
    }

    .post-box textarea {
      height: 100px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .textarea-container {
      display: flex;
      align-items: flex-start;
      margin-bottom: 10px;
    }

    textarea {
      flex: 1;
      height: 100px;
      border: 1px solid #ccc;
      border-radius: 4px;
      padding: 10px;
      font-size: 14px;
      margin-right: 10px;
    }

    .delete-button {
      background-color: transparent;
      color: #ff4d4d;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 20px;
      align-self: center;
      display: none; /* Hide delete button by default */
    }

    .delete-button:hover {
      color: #ff1a1a;
    }

    .add-other {
      color: #007bff;
      text-decoration: none;
      font-size: 14px;
    }

    .add-other:hover {
      text-decoration: underline;
    }

    .post-button {
      background-color: #d7dfee;
      color: black;
      font-weight: bold;
      font-size: 18px;
      border: none;
      border-radius: 25px;
      padding: 10px 60px;
      cursor: pointer;
      float: right; /* Align to the right */
      margin-top: 20px;
    }

    /* Modal Styles */
    .modal {
      display: none; /* Hidden by default */
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgb(0,0,0);
      background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
      background-color: #fefefe;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      max-width: 500px;
      border-radius: 10px;
      text-align: center;
    }

    .modal-content p {
      font-size: 20px;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }

    .modal-button {
      background-color: #1E3B85;
      color: white;
      font-weight: bold;
      font-size: 20px;
      border: none;
      border-radius: 10px;
      padding: 10px 20px;
      cursor: pointer;
      margin-top: 20px;
    }

    .modal-button:hover {
      background-color: #3c5fa4;
    }

  </style>
</head>
<body>
<div class="container">
  <form action="employerjobpost.php" method="post">
    <div class="title">POST A JOB</div>
    <div class="post-box">
      <label>Hiring for:</label>
      <input type="hidden" name="emp_id" value="<?php echo $_SESSION['user_id'];?>">
      <select id="selectJob" onchange="stateSelected();" class="selector" name="job">
        <option value="" selected="selected">Choose job</option>
        <option value="Welder">Welder</option>
        <option value="Plumber">Plumber</option>
        <option value="Lineman">Lineman</option>
        <option value="Guard">Security Guard</option>
        <option value="Electrician">Electrician</option>
        <option value="Driver">Driver</option>
        <option value="Refservice">Refrigarator and Aircon Service</option>
        <option value="Foodservice">Food Service</option>
        <option value="Laundry">Laundry Staff</option>
        <option value="Factory">Factory Worker</option>
        <option value="Hosekeeper">Housekeeper</option>
        <option value="Janitor">Janitor</option>
        <option value="Construction">Construction Worker</option>

      </select><br>

      <label>Date:</label>
      <input type="date" id="date" name="date"><br>

      <label>Time:</label>
      <input type="time" id="time" name="time"><br>

      <label>Job Type:</label>
      <select id="selectJobType" onchange="stateSelected();" class="selector" name="job_type">
        <option value="" selected="selected">Choose Job Type</option>
        <option value="fulltime">Full Time</option>
        <option value="parttime">Part Time</option>
        <option value="onetime">One Time</option>
      </select><br>

      <label>Salary Offer:</label>
      <input type="text" id="salary" name="salary"><br>

      <label>Location:</label>
      <select id="selectLocation" onchange="stateSelected();" class="selector" name="location">
        <option value="" selected="selected">Choose location</option>
        <!-- <option value="">Location</option> -->
        <option value="manila">Manila</option>
        <option value="caloocan">Caloocan</option>
        <option value="valenzuela">Valenzuela</option>
        <option value="pasay">Pasay</option>
        <option value="makati">Makati</option>
        <option value="quezon_city">Quezon City</option>
        <option value="navotas">Navotas</option>
        <option value="las_piñas">Las Piñas</option>
        <option value="malabon">Malabon</option>
        <option value="mandaluyong">Mandaluyong</option>
        <option value="marikina">Marikina</option>
        <option value="muntinlupa">Muntinlupa</option>
        <option value="parañaque">Parañaque</option>
        <option value="pasig">Pasig</option>
        <option value="san_juan">San Juan</option>
        <option value="taguig">Taguig</option>
        <option value="valenzuela">Valenzuela</option>
        <option value="pateros">Pateros</option>
      </select><br>

      <div class="form-group">
        <label for="job-description">Responsibilities:</label>
        <div class="textarea-container">
          <textarea id="job-description" name="job_responsibilities"></textarea>
          <button class="delete-button" onclick="deleteTextarea(this)"><i class="bx bx-x"></i></button>
        </div>
        <a href="#" class="add-other" onclick="addOther('job-description')">Add "Other"</a>
      </div>

      <div class="form-group">
        <label for="job-qualifications">Qualifications:</label>
        <div class="textarea-container">
          <textarea id="job-qualifications" name="job_qualifications"></textarea>
          <button class="delete-button" onclick="deleteTextarea(this)"><i class="bx bx-x"></i></button>
        </div>
        <a href="#" class="add-other" onclick="addOther('job-qualifications')">Add "Other"</a>
      </div>

      <button class="post-button" type="submit">POST</button>
    </div>

  </form>
  
</div>

<!-- The Modal -->
<div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <p>The job post you posted has been successfully posted!</p>
    <button class="modal-button" onclick="closeModal()">Continue</button>
  </div>
</div>

<script>
  const modal = document.getElementById("myModal");
   <?php if ($showModal) : ?>
        document.addEventListener('DOMContentLoaded', function() {
            modal.style.display = 'block';
        });
  <?php endif; ?>

  function addOther(name) {
    const formContainer = document.querySelector(`textarea[name="${name}"]`).parentNode.parentNode;

    // Create a new container for the textarea and delete button
    const newContainer = document.createElement('div');
    newContainer.className = 'textarea-container';

    // Create a new textarea element
    const newTextarea = document.createElement('textarea');
    newTextarea.name = name;

    // Create a delete button
    const deleteButton = document.createElement('button');
    deleteButton.className = 'delete-button';
    deleteButton.innerHTML = '<i class="bx bx-x"></i>';
    deleteButton.onclick = function() {
        formContainer.removeChild(newContainer);
    };

    // Append the textarea and delete button to the new container
    newContainer.appendChild(newTextarea);
    newContainer.appendChild(deleteButton);

    // Insert the new container into the form container before the "add Other" link
    formContainer.insertBefore(newContainer, formContainer.querySelector('.add-other'));

    // Show the delete button
    deleteButton.style.display = 'inline-block';
  }

  function deleteTextarea(button) {
    const container = button.parentNode;
    container.parentNode.removeChild(container);
  }

  // function showModal() {
  //   const modal = document.getElementById("myModal");
  //   modal.style.display = "block";
  // }

  function closeModal() {
    const modal = document.getElementById("myModal");
    modal.style.display = "none";
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
