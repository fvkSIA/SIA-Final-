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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Offer Details</title>
    <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
    <style>

@import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
            width: 100%;
            max-width: 800px;
        }

        h1, h2, h3, h4 {
            color: #333;
            margin-bottom: 15px;
        }

        .header {
            margin-bottom: 30px;
        }

        .header h2 {
            color: #007BFF;
        }

        .header p {
            color: #777;
        }

        .job-details {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .job-details div {
            flex: 1;
            min-width: 150px;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .job-details img {
            width: 30px;
            height: 30px;
            margin-bottom: 10px;
        }

        .job-details h4 {
            color: #007BFF;
            margin-bottom: 5px;
        }

        .full-description {
            margin-top: 30px;
        }

        .full-description ul {
            padding-left: 20px;
            margin-bottom: 20px;
        }

        .full-description li {
            margin-bottom: 10px;
            color: #555;
        }

        .buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .buttons button, .buttons .decline {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-align: center;
            text-decoration: none;
        }

        .buttons button {
            background-color: #007BFF;
            color: #fff;
        }

        .buttons button:hover {
            background-color: #0056b3;
        }

        .buttons .decline {
            background-color: #dc3545;
            color: #fff;
        }

        .buttons .decline:hover {
            background-color: #c82333;
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
            margin-bottom: 20px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .modal-button {
            background-color: #1E3B85;
            color: white;
            font-weight: bold;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .modal-button:hover {
            background-color: #3c5fa4;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            .job-details {
                flex-direction: column;
            }

            .buttons {
                flex-direction: column;
            }
        }
    </style>
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

                <h3>Job Details</h3>
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
                        <span><?php echo isset($job['salary_offer']) ? number_format($job['salary_offer'], 2) : 'Not specified'; ?></span>
                        </div>  
                    <div class="location">
                        <img src="../jobseeker/assets/images/location-icon.png" alt="Location">
                        <h4>Location</h4>
                        <span><?php echo $job['location'] ?? 'Not specified';?></span>
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
                <button class="accept" type="submit">Accept</button>
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
</body>
</html>