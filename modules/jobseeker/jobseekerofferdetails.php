<?php 
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;
$showModal = false;

$jr_id = isset($_GET['jrid']) ? $_GET['jrid'] : '';

if($_SERVER["REQUEST_METHOD"] == "GET") {
  $id = isset($_GET['id']) ? $_GET['id'] : '';

  $sql = "SELECT * FROM job_offers WHERE id = ?";

  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result() ?? null;
    $stmt->close();
  }

} else if ($_SERVER['REQUEST_METHOD'] == "POST"){
    $id = isset($_POST['job_id']) ? $_POST['job_id'] : '';
    $job_req_id = isset($_POST['job_req_id']) ? $_POST['job_req_id'] : '';

    $sql = "UPDATE job_offers SET accepted = 1 WHERE id = ?";
    $sql2 = "UPDATE job_requests SET is_accepted = 1 WHERE id = ?";
    if ($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i", $id);
        if ($stmt->execute()){
            if($stmt2 = $conn->prepare($sql2)){
                $stmt2->bind_param("i", $job_req_id);
                if ($stmt2->execute()){
                    $showModal = true;
                }
            }
        } else {    
            $error = 'Error encountered. Try again later';
        }
    } else{
        $error = 'Error encountered. Try again later';
    }
}

$job = [];
if ($result != null) {
    $job = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Jobseeker Dashboard</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <style>
body {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 80%;
            max-width: 1200px; /* Optional: To limit maximum width */
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            padding: 20px;
            overflow-y: auto;
        }

        .job-offer {
            text-align: left;
            flex-grow: 1;
        }

        .job-offer h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .header {
            margin-bottom: 20px;
        }

        .header h2 {
            font-size: 20px;
            color: #007BFF;
        }

        .header p {
            font-size: 14px;
            color: #777;
        }

        .details h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .job-details {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .job-details div {
            display: flex;
            align-items: center;
            flex-direction: column;
            text-align: center;
        }

        .job-details img {
            width: 24px;
            height: 24px;
            margin-bottom: 5px;
        }

        .job-details h4 {
            font-size: 16px;
            color: #007BFF;
            margin-bottom: 5px;
        }

        .job-details span {
            font-size: 14px;
            color: #333;
        }

        .full-description {
            margin-top: 20px;
            font-size: 14px;
        }

        .full-description h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
        }

        .full-description h4 {
            font-size: 16px;
            color: #007BFF;
            margin-bottom: 10px;
        }

        .full-description ul {
            list-style-type: disc;
            padding-left: 20px;
            margin-bottom: 20px;
        }

        .full-description ul li {
            margin-bottom: 5px;
            color: #555;
            line-height: 1.4;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            flex-shrink: 0;
        }

        .buttons button {
            width: 48%;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
        }

        .buttons button:hover {
            background-color: #0056b3;
        }

        .buttons .decline {
            background-color: #dc3545;
            width: 48%;
            padding: 10px;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        .buttons .decline:hover {
            background-color: #c82333;
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
        text-decoration: none;
        }

        .modal-button:hover {
        background-color: #3c5fa4;
        }  </style>
</head>
<body>
  <div class="container">
    <form action="jobseekerofferdetails.php" method="post">
        <input type="hidden" name="job_id" value="<?php echo $job['id'] ?? '';?>">
        <input type="hidden" name="job_req_id" value="<?php echo $jr_id;?>">
        <div class="job-offer">
            <h1>Job Offer</h1>
            <div class="header">
                <h2><?php echo $job['job'] ?? 'Job Title';?></h2>
                <p><?php echo $job['date'] ?? 'Date';?> at <?php echo $job['time'] ?? 'Time';?></p>
            </div>

            <div class="details">
                <h1>Job details</h1>
                <div class="job-details">
                    <div class="job-type">
                        <img src="../jobseeker/assets/images/job-icon.png" alt="Job Type">
                        <h4>Job Type</h4>
                        <?php if (isset($job['type'])): ?>
                            <?php if ($job['type'] == 'parttime'): ?>
                                <span>Part-time</span>
                            <?php elseif ($job['type'] == 'fulltime'): ?>
                                <span>Full-time</span>
                            <?php elseif ($job['type'] == 'onetime'): ?>
                                <span>One-time</span>
                            <?php else: ?>
                                <span>Not specified</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span>Not specified</span>
                        <?php endif; ?>
                    </div>
                    <div class="salary">
                        <img src="../jobseeker/assets/images/salary-icon.png" alt="Salary">
                        <h4>Salary</h4>
                        <span><?php echo $job['salary_offer'] ?? 'Not specified';?></span>
                    </div>  
                    <div class="location">
                        <img src="../jobseeker/assets/images/location-icon.png" alt="Location">
                        <h4>Location</h4>
                        <span><?php echo $job['location'] ?? 'Not specified';?></span>
                    </div>
                </div>
            </div>

            <div class="full-description">
                <h3>Full Job Description</h3>
                <h4>Responsibilities:</h4>
                <ul>
                    <?php echo isset($job['responsibilities']) ? nl2br(htmlspecialchars($job['responsibilities'])) : '<li>No responsibilities listed.</li>';?>
                </ul>
                <h4>Qualifications:</h4>
                <ul>
                    <?php echo isset($job['qualifications']) ? nl2br(htmlspecialchars($job['qualifications'])) : '<li>No qualifications listed.</li>';?>
                </ul>
            </div>
        </div>
        <div class="buttons">
            <button class="accept" id="" type="submit">Accept</button>
            <a class="decline" href="javascript:history.back()">Decline</a>
        </div>
    </form>
  </div>
  <div id="myModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <p>Congratulations on your new job!</p>
      <a class="modal-button" href="jobseekerinbox.php">Continue</a>
    </div>
  </div>

  <script>
      const modal = document.getElementById("myModal");
      <?php if ($showModal) : ?>
            document.addEventListener('DOMContentLoaded', function() {
                modal.style.display = 'block';
            });
      <?php endif; ?>

      function closeModal() {
        modal.style.display = "none";
      }
  </script>
  
  <!-- Scripts -->
  <script src="script.js"></script>
</body>
</html>
