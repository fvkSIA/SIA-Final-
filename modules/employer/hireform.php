<?php 

require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;
$showModal = false;

if($_SERVER["REQUEST_METHOD"] == "GET") {
  $id = $_GET['id'];
  
  $sql = "SELECT  * FROM users where id = ?";

  // echo $id . ' query: ' . $sql; die();
  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result() ?? null;
    $stmt->close();
  }
} else if ($_SERVER["REQUEST_METHOD"] == "POST"){
  // print_r($_POST);
  // die();
  $emp_id = $_POST['emp_id'];
  $jobseeker_id = $_POST['jobseeker_id'];
  $job = $_POST['job'];
  $date = $_POST['date'];
  $time = $_POST['time'];
  $job_type = $_POST['job_type'];
  $salary = $_POST['salary'];
  $location = $_POST['location'];
  $job_desc = $_POST['job_responsibilities'];
  $job_quali = $_POST['job_qualifications'];
  $type = 1;

  $sql = "INSERT INTO job_offers (job, date, time, type, salary_offer, location, responsibilities, qualifications, job_seeker_id, employer_id) VALUES (?,?,?,?,?,?,?,?,?,?)";
  $jr = "INSERT INTO job_requests (user_id, job_id, employer_id, type) VALUES (?,?,?,?)";
  if ($stmt = $conn->prepare($sql)){
    $stmt->bind_param('ssssssssii', $job, $date, $time, $job_type, $salary, $location, $job_desc, $job_quali, $jobseeker_id, $emp_id);
    if ($stmt->execute()){
      $last_id = mysqli_insert_id($conn);
      if ($job_req = $conn->prepare($jr)){
        $job_req->bind_param('ssss', $jobseeker_id, $last_id, $emp_id, $type);
        if ($job_req->execute()){
          $showModal = true;
        } else {
          $errorMessage = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
      }
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
    body {
      font-family: Arial, sans-serif;
    }

    .container {
      max-width: 100%;
      border-radius: 10px;
    }

    .title {
      font-size: 32px;
      color: #1E3B85;
      margin-bottom: 20px;
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
      font-size: 25px;
      border: none;
      border-radius: 25px;
      padding: 10px 20px;
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
  <div class="title">JOB OFFER</div>
  <?php 
          $data = [];
          if ($result != null) {
            $user = $result->fetch_assoc();
          } else {
            echo '';
          }

  ?>
  <form action="hireform.php" method="post">
    <div class="post-box">
      <label>Hiring for:</label>
      <input type="hidden" name="emp_id" value="<?php echo $_SESSION['user_id'];?>">
      <input type="hidden" name="jobseeker_id" value="<?php echo $user['id'];?>">
      <input type="hidden" name="job" value="<?php echo $user['job_type'] ?? '';?>">
      <input type="text" value='<?php echo $user['job_type'] ?? '';?>' disabled required/>
      <!-- <select id="selectJob" onchange="stateSelected();" class="selector">
        <option value="" selected="selected">Choose job</option>
        <option value="jobWelder">Welder</option>
        <option value="jobPlumber">Plumber</option>
        <option value="jobLineman">Lineman</option>
        <option value="jobGuard">Security Guard</option>
        <option value="jobElectrician">Electrician</option>
        <option value="jobDriver">Driver</option>
        <option value="jobRefservice">Refrigarator and Aircon Service</option>
        <option value="jobFoodservice">Food Service</option>
        <option value="jobLaundry">Laundry Staff</option>
        <option value="jobFactory">Factory Worker</option>
        <option value="jobHosekeeper">Housekeeper</option>
        <option value="jobJanitor">Janitor</option>
        <option value="jobConstruction">Construction Worker</option>

      </select> -->
      <br>

      <label>Date:</label>
      <input type="date" id="date" name="date"><br>

      <label>Time:</label>
      <input type="time" id="time" name="time"><br>

      <label>Job Type:</label>
      <select id="selectJobType" name="job_type" onchange="stateSelected();" class="selector">
        <option value="" selected="selected">Choose Job Type</option>
        <option value="fulltime">Full Time</option>
        <option value="parttime">Part Time</option>
        <option value="onetime">One Time</option>
      </select><br>

      <label>Salary Offer:</label>
      <input type="text" id="salary" name="salary"><br>

      <label>Location:</label>
      <select name="location" id="selectLocation" onchange="stateSelected();" class="selector">
        <option value="" selected="selected">Choose location</option>
        <option value="pateros">Municipality of Pateros</option>
        <option value="caloocan">Caloocan City</option>
        <option value="marikina">Marikina City</option>
        <option value="mandaluyong">Mandaluyong</option>
        <option value="muntinlupa">Muntinlupa City</option>
        <option value="cityofmanila">City of Manila</option>
        <option value="navotas">Navotas City</option>
        <option value="cityofmalabon">City of Malabon</option>
        <option value="navotas">Navotas City</option>
        <option value="valenzuela">Valenzuela City</option>
        <option value="pasay">Pasay City</option>
        <option value="parañaque">Parañaque City</option>
        <option value="sanjuan">City of San Juan</option>
        <option value="laspiñas">Las Piñas City</option>
        <option value="taguig">Taguig City</option>
        <option value="qc">Quezon City</option>
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

      <button class="post-button" type="submit">SEND OFFER</button>
    </div>

  </form>
  
</div>

<!-- The Modal -->
<div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <p>The Offer successfully sent to jobseeker!</p>
    <button class="modal-button" onclick="closeModalAndRedirect()">Continue</button>
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
  // Show the modal if the PHP variable indicates success
 

  function closeModal() {
    const modal = document.getElementById("myModal");
    modal.style.display = "none";
  }

  function closeModalAndRedirect() {
    closeModal();
    window.location.href = 'findworkers.php';
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
