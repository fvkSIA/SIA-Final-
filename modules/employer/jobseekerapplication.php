<?php 
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;
$showModal = false;

if($_SERVER["REQUEST_METHOD"] == "GET") {
  $jrid = $_GET['id'];
  
  $sql = "SELECT job_requests.id as jr_id, job_requests.user_id as jr_uid, job_requests.job_id as jr_jobid, job_requests.employer_id as jr_empid, job_requests.type as jr_type, job_requests.status as jr_comp, job_requests.is_accepted, users.id as user_id, users.firstname, users.lastname, job_listings.*, job_offers.id as job_offer_id, job_offers.job as job_offer_job, job_offers.date as job_offer_date, job_offers.time as job_offer_time, job_offers.type as job_offer_type, job_offers.salary_offer as job_offer_sal, job_offers.location as job_offer_loc, job_offers.responsibilities as job_offer_respo, job_offers.qualifications as job_offer_quali, a.firstname as job_seek_fname, a.lastname as job_seek_lname, a.middlename as job_seek_mname, a.email as job_seek_email, a.phone_number as job_seek_phone FROM job_requests
        LEFT JOIN users ON job_requests.employer_id = users.id
        LEFT JOIN job_listings ON job_requests.job_id = job_listings.id
        LEFT JOIN users as a ON job_requests.user_id = a.id
        LEFT JOIN job_offers ON job_requests.job_id = job_offers.id
        WHERE job_requests.id = ?";

  // echo $id . ' query: ' . $sql; die();
  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $jrid);
    $stmt->execute();
    $result = $stmt->get_result() ?? null;

    // print_r($result);
    // die();
    $stmt->close();
  }

} else if($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['job_id'];
    $job_req_id = $_POST['job_req_id'];

    $sql = "UPDATE job_listings SET accepted = 1 WHERE id = ?";
    $sql2 = "UPDATE job_requests SET is_accepted = 1 WHERE id = ?";
    if ($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i", $id);
        if ($stmt->execute()){
            if($stmt2 = $conn->prepare($sql2)){
                $stmt2->bind_param("i", $job_req_id);
                if ($stmt2->execute()){
                  $stmt->close();
                  $stmt2->close();
                  header('Location: employerongoing.php');
                  
                }
            }
            
        } else {    
            $error = 'Error encountered. Try again later';
        }
    } else{
        $error = 'Error encountered. Try again later';
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
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

    :root {
      --color-default: #004f83;
      --color-second: #0067ac;
      --color-white: #fff;
      --color-body: #e4e9f7;
      --color-light: #e0e0e0;
    }

    * {
      padding: 0%;
      margin: 0%;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      min-height: 100vh;
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #7cbeea;
      padding: 20px;
    }

    main {
      width: 100%;
      max-width: 800px;
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .search-bar {
      background-color: #7091E6;
      padding: 20px;
      border-radius: 20px;
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .search-bar input, .search-bar select, .search-bar button {
      border: none;
      border-radius: 10px;
      padding: 13px;
      font-size: 20px;
    }

    .search-bar input:focus, .search-bar select:focus, .search-bar button:focus {
      outline: none;
    }

    .search-bar input {
      flex: 1;
    }

    .search-bar select {
      flex: 1.5;
    }

    .search-bar button {
      background-color: #3D52A0;
      color: white;
      cursor: pointer;
    }

    .search-bar button:hover {
      background-color: #2d3d82;
    }
  </style>
</head>
<body>
<?php 
    $data = [];
    if ($result != null) {
      $user = $result->fetch_assoc();
    } else {
      echo '';
    }

  ?>
  <main class="p-10">
  <form action="jobseekerapplication.php" method="post" id="jobseeker_apply_form">
        <input type="hidden" name="job_id" value="<?php echo $user['jr_jobid'] ?? '';?>">
        <input type="hidden" name="job_req_id" value="<?php echo $jrid ?? '';?>">
  </form>
    <span class="text-blue-500" style="font-size: 25px;">JOB POST</span>
    <div class="bg-blue-100 p-6 rounded-lg mt-4">
      <h1 class="text-lg font-bold text-blue-500">Looking for <?php echo $user['job']?></h1>
      <p class="text-sm text-gray-600"><?php echo $user['date']?> at <?php echo $user['time']?></p>
      <div class="mt-4">
        <p class="font-bold text-gray-700">Employed Worker: <span class="text-green-500"><?php echo $user['job_seek_fname']?> <?php echo $user['job_seek_lname']?></span></p>
      </div>
      <hr class="my-4">
      <h2 class="text-xl font-bold text-gray-900">Job details</h2>
      <div class="flex items-center mt-2 text-gray-600">
        <i class="fas fa-briefcase mr-2"></i>
        <p class="text-sm"><?php echo $user['type']?></p>
      </div>
      <div class="flex items-center mt-2 text-gray-600">
        <i class="fas fa-map-marker-alt mr-2"></i>
        <p class="text-sm"><?php echo $user['location']?></p>
      </div>
      <div class="mt-4">
        <h3 class="text-lg font-bold text-gray-900">Salary</h3>
        <p class="text-sm bg-gray-100 px-2 py-1 rounded text-gray-700 font-semibold"><?php echo $user['salary_offer']?></p>
      </div>
      <hr class="my-4">
      <h2 class="text-xl font-bold text-gray-900">Full Job description</h2>
      <p class="text-sm mt-2 text-gray-600">Responsibilities:</p>
      <ul class="list-disc list-inside text-sm text-gray-600 mt-2 mb-4">
        <?php echo $user['responsibilities']?>
      </ul>
      <div class="mb-4">
        <h2 class="font-bold text-lg">Qualifications:</h2>
        <ul class="list-disc list-inside text-sm text-gray-600 mt-2 mb-4">
          <?php echo $user['qualifications']?>
        </ul>
      </div>
      <div class="text-center mt-4">
        <button id="acceptButton" class="bg-[#FFCC00] text-white font-bold py-2 px-6 rounded-full">ACCEPT WORKER</button>
      </div>
      <div id="confirmationBox" class="hidden absolute inset-0 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg" style="width: 30%;">
          <p class="text-lg font-bold mb-4">Are you sure you want to accept this worker?</p>
          <div class="flex justify-center">
            <button id="confirmButton" class="bg-blue-500 text-white py-2 px-6 rounded-full mr-2">Yes</button>
            <button id="cancelButton" class="bg-gray-300 py-2 px-6 rounded-full">No</button>
          </div>
        </div>
      </div>
    </div>
  </main>
  <!-- JS Scripts -->
  <script>
    document.getElementById('acceptButton').addEventListener('click', function() {
      document.getElementById('confirmationBox').classList.remove('hidden');
    });
    document.getElementById('confirmButton').addEventListener('click', function() {
      // Redirect to jc.html
      // window.location.href = 'employerongoing.html';
      var form = document.getElementById('jobseeker_apply_form');
      form.submit();
    });
    document.getElementById('cancelButton').addEventListener('click', function() {
      document.getElementById('confirmationBox').classList.add('hidden');
    });
  </script>
</body>
</html>
