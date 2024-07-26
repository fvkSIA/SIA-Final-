<?php 
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;
$showModal = false;

if($_SERVER["REQUEST_METHOD"] == "GET") {
  $id = $_GET['id'];
  
  $sql = "SELECT  * FROM job_listings where id = ?";

  // echo $id . ' query: ' . $sql; die();
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
      // success
      $showModal = true;

    } else {
      $error = 'Error encountered. Try again later';
    }
  }

}
function formatSalary($salary) {
  return number_format($salary, 2);
}
$conn->close();
?>


<!-- RESPONSIVE -->
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Jobseeker Dashboard</title>
  <!-- Link Styles -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
  />
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap"
    rel="stylesheet"
  />
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <style>
    .home-section {
      position: relative;
      background-color: var(--color-body);
      min-height: 100vh;
      top: 0;
      left: 50px;
      width: calc(100% - 78px);
      transition: all .5s ease;
      z-index: 2;
      padding-bottom: 5%;
    }

    .home-section .text {
      display: inline-block;
      color: var(--color-default);
      font-size: 25px;
      font-weight: 500;
      margin: 18px;
    }

    #myButton {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 99;
      padding: 10px 20px;
      background-color: #9399b6;
      color: black;
      border: none;
      border-radius: 50px;
      cursor: pointer;
      font-weight: bold;
      width: 150px;
    }

    #myButton:hover {
      background-color: white;
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
<?php 
    $data = [];
    if ($result != null) {
      $job = $result->fetch_assoc();
    } else {
      echo '';
    }

  ?>
<section class="home-section">
  <h1 class="title text-2xl mb-3" style="padding-top: 15px; font-size: 2em; padding-top: 50px; margin-bottom: 25px;">Job Post</h1>
  <div class="max-w-4xl" style="max-width: 100%; margin: 0 auto; padding-left: 50px;">
    <h2 class="title text-lg mb-1" style="font-size: 1.5em;"><?php echo $job['job'] ?? '';?></h2>
    <p class="location mb-6" style="margin-bottom: 25px;"><?php echo $job['date'] ?? '';?></p>
    <p class="location mb-6" style="margin-bottom: 25px;"><?php echo $job['location'] ?? '';?></p>

    <div class="mb-6" style="margin-bottom: 15px;">
      <h3 class="details mb-2" style="font-size: 1.25em; margin-bottom: 10px;">Job details</h3>
      <div class="flex justify-between" style="display: flex; flex-wrap: wrap; justify-content: space-between;">
        <div style="flex: 1 1 45%; margin-bottom: 15px;">
          <p class="font-bold text-gray" style="font-weight: bold; color: rgb(71, 71, 71);">Job type</p>
          <p class="bgcolor" style="background-color: #f0f0f0; padding: 5px;"><?php echo $job['type'] ?? '';?></p>
        </div>
        <div style="flex: 1 1 45%; margin-bottom: 15px;">
          <p class="font-bold text-gray" style="font-weight: bold; color: rgb(71, 71, 71);">Salary</p>
          <p class="bgcolor" style="background-color: #f0f0f0; padding: 5px;"><?php echo $job['salary_offer'] ?? '';?></p>
        </div>
      </div>
    </div>

    <div class="mb-6" style="margin-bottom: 25px;">
      <h3 class="section-title mb-2" style="font-size: 1.25em; margin-bottom: 10px;">Full Job description</h3>
      <p class="mb-4" style="margin-bottom: 15px;">Responsibilities:</p>
      <ul class="list-disc pl-5 mb-6" style="list-style-type: disc; padding-left: 20px; margin-bottom: 25px;">
        <?php echo $job['responsibilities'] ?? '';?>
      </ul>

      <p class="mb-4" style="margin-bottom: 15px;">Qualifications:</p>
      <ul class="list-disc pl-5 mb-6" style="list-style-type: disc; padding-left: 20px; margin-bottom: 25px;">
        <?php echo $job['qualifications'] ?? '';?>
      </ul>
    </div>
  </div>

  <!-- Button -->
   <form action="applydetails.php" method="post">
    <input type="hidden" name="job_id" value="<?php echo $job['id'];?>">
    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'];?>">
    <input type="hidden" name="emp_id" value="<?php echo $job['employer_id'];?>">
    <button id="myButton" type="submit">Apply</button>
   </form>
   

   <!-- The Modal -->
<div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <p>You have successfully applied for this job!</p>
    <button class="modal-button" onclick="redirect()">Continue</button>
  </div>
</div>

  <!-- Scripts -->
  <script>
    const modal = document.getElementById("myModal");
    <?php if ($showModal) : ?>
          document.addEventListener('DOMContentLoaded', function() {
              modal.style.display = 'block';
          });
    <?php endif; ?>
    document.getElementById('myButton').addEventListener('click', function() {
      // window.location.href = 'fillupapplyform.html';
    });

    function closeModal() {
      const modal = document.getElementById("myModal");
      modal.style.display = "none";
    }

    function redirect(){
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
