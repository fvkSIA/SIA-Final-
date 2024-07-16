<?php 
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;
$showModal = false;

if($_SERVER["REQUEST_METHOD"] == "GET") {
  $id = $_GET['id'];
  $userid = $_SESSION['user_id'];

  $sql = "SELECT job_requests.*, job_requests.type as job_req_type, job_listings.*, job_offers.id as job_offer_id, job_offers.job as job_offer_job, job_offers.date as job_offer_date, job_offers.time as job_offer_time, job_offers.type as job_offer_type, job_offers.salary_offer as job_offer_sal, job_offers.location as job_offer_loc, job_offers.responsibilities as job_offer_respo, job_offers.qualifications as job_offer_quali, users.firstname as user_fname, users.lastname as user_lname, users.email as user_email, users.home_address as user_address FROM job_requests
        LEFT JOIN job_listings ON job_requests.job_id = job_listings.id
        LEFT JOIN job_offers ON job_requests.job_id = job_offers.id
        INNER JOIN users ON job_requests.user_id = users.id
        WHERE job_requests.id = ?
        AND job_requests.is_accepted = 1
        AND job_requests.employer_id = ?";

  // echo $id . ' query: ' . $sql; die();
  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ii", $id, $userid);
    $stmt->execute();
    $result = $stmt->get_result() ?? null;
    $stmt->close();
  }

} 

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Employer Dashboard</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
  <style>


body{
  font-family: 'Roboto', sans-serif;
  font-family: 'Poppins', sans-serif;
}

</style>
</head>
<body>
<?php 
    $data = [];
    if ($result != null) {
    $row = $result->fetch_assoc();
    } else {
    echo '';
    }

  ?>

  <main class=" justify-center">

    <div class=" mx-auto">
        <span class="text-blue-500" style="font-size: 25px; font-weight: bold;">JOB ORBER</span>
        
        <div class="bg-blue-100 p-6 rounded-lg" style="width: 100%;">
            
            <h1 class="text-lg font-bold text-blue-500"><?php echo $row['job_req_type'] == 1 ? $row['job_offer_job'] : $row['job']?></h1>
            <p class="text-sm text-gray-600"><?php echo $row['job_req_type'] == 1 ? $row['job_offer_date'] : $row['date']?></p>
            <button class="bg-green-500 text-white font-bold py-2 px-4 rounded-full text-lg" style="height: 28px; padding-top: 1px;">
                ONGOING
            </button>
            <div class="mt-4">
                <p class="font-bold text-gray-700">Employed Worker: <span class="text-green-500"><?php echo $row['user_fname'] . ' ' . $row['user_lname'];?></span></p>
            </div>

            <hr class="my-4">

            <h2 class="text-xl font-bold text-gray-900">Job details</h2>

            <div class="flex items-center mt-2 text-gray-600">
                <i class="fas fa-briefcase mr-2"></i>
                <p class="text-sm"><span class="text-green-500"><?php echo $row['job_req_type'] == 1 ? $row['job_offer_type'] : $row['type']?></p>
            </div>
            <div class="flex items-center mt-2 text-gray-600">
                <i class="fas fa-map-marker-alt mr-2"></i>
                <p class="text-sm"><span class="text-green-500"><?php echo $row['job_req_type'] == 1 ? $row['job_offer_loc'] : $row['location']?></p>
            </div>

            <div class="mt-4">
                <h3 class="text-lg font-bold text-gray-900">Salary</h3>
                <p class="text-sm bg-gray-100 px-2 py-1 rounded text-gray-700 font-semibold"><span class="text-green-500"><?php echo $row['job_req_type'] == 1 ? $row['job_offer_sal'] : $row['salary_offer']?></p>
            </div>

            <hr class="my-4">

            <h2 class="text-xl font-bold text-gray-900">Full Job description</h2>
            <!-- <p class="text-sm mt-2 text-gray-600">Responsibilities:</p> -->
            <ul class="list-disc list-inside text-sm text-gray-600 mt-2 mb-4">
                <?php echo $row['job_req_type'] == 1 ? $row['job_offer_respo'] : $row['responsibilities']?>
            </ul>
            <div class="mb-4">
                <h2 class="font-bold text-lg">Qualifications:</h2>
                <ul class="list-disc list-inside text-sm text-gray-600 mt-2 mb-4">
                    <?php echo $row['job_req_type'] == 1 ? $row['job_offer_quali'] : $row['qualifications']?>
                </ul>
            </div>
            <div class="text-center mt-4">
                <button id="acceptButton" class="bg-[#FFCC00] text-white font-bold py-2 px-6 rounded-full">COMPLETE JOB</button>
            </div>
            <div id="confirmationBox" class="hidden absolute inset-0 bg-opacity-50 flex justify-center items-center">
                <div class="bg-white p-6 rounded-lg" style="width: 30%; text-align: justify;">
                    <p class="text-lg font-bold mb-4">Are you sure you want to accept this worker?</p>
                    <p class="mb-6">Are you sure you want to complete the job order? This action cannot be undone.</p>
                    <div class="text-right">
                        <button id="confirmButton" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-full">Yes</button>
                        <button id="cancelButton" class="bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-full ml-2">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </main>
  <script>
    document.getElementById('acceptButton').addEventListener('click', function() {
        document.getElementById('confirmationBox').classList.remove('hidden');
    });

    document.getElementById('confirmButton').addEventListener('click', function() {
        document.getElementById('confirmationBox').classList.add('hidden');
        window.location.href = "employergivingfeedback.php";
    });

    document.getElementById('cancelButton').addEventListener('click', function() {
        document.getElementById('confirmationBox').classList.add('hidden');
    });
  </script>
</body>
</html>
