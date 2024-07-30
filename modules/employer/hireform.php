<?php
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../login/PHPMailer/src/Exception.php';
require '../login/PHPMailer/src/PHPMailer.php';
require '../login/PHPMailer/src/SMTP.php';

$error = '';
$result = null;
$showModal = false;

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

$conn = new mysqli('localhost', 'root', '', 'hanapkita_db'); // Establish connection

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    
    $sql = "SELECT * FROM users WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result() ?? null;
        $stmt->close();
    } else {
        $error = "Error preparing GET statement: " . $conn->error;
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if required POST variables are set
    if (isset($_POST['emp_id'], $_POST['jobseeker_id'], $_POST['job'], $_POST['date'], $_POST['time'], $_POST['job_type'], $_POST['salary'], $_POST['location'], $_POST['job_responsibilities'], $_POST['job_qualifications'])) {
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

        // Insert into job_offers
        $sql = "INSERT INTO job_offers (job, date, time, type, salary_offer, location, responsibilities, qualifications, job_seeker_id, employer_id) VALUES (?,?,?,?,?,?,?,?,?,?)";
        $jr = "INSERT INTO job_requests (user_id, job_id, employer_id, type) VALUES (?,?,?,?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('ssssssssii', $job, $date, $time, $job_type, $salary, $location, $job_desc, $job_quali, $jobseeker_id, $emp_id);
            if ($stmt->execute()) {
                $last_id = $conn->insert_id; // Use mysqli object method to get last insert ID
                if ($job_req = $conn->prepare($jr)) {
                    $job_req->bind_param('siii', $jobseeker_id, $last_id, $emp_id, $type);
                    if ($job_req->execute()) {
                        $showModal = true;
                    // Fetch jobseeker's email
    $stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
    $stmt->bind_param("i", $jobseeker_id);
    $stmt->execute();
    $stmt->bind_result($jobseeker_email);
    $stmt->fetch();
    $stmt->close();

    // Send email
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'kjcruz0604@gmail.com'; // Your Gmail
        $mail->Password   = 'qvxhyivnmoiiexum'; // Your Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('kjcruz0604@gmail.com', 'HanapKITA Team');
        $mail->addAddress($jobseeker_email);

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Job Offer';
        $mail->Body    = "
            <h1>Congratulations! You've received a job offer.</h1>
            <p>Job: $job</p>
            <p>Date: $date</p>
            <p>Time: $time</p>
            <p>Job Type: $job_type</p>
            <p>Salary: $salary</p>
            <p>Location: $location</p>
            <h2>Responsibilities:</h2>
            <p>$job_desc</p>
            <h2>Qualifications:</h2>
            <p>$job_quali</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        $errorMessage = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    $errorMessage = "Error: " . $job_req->error;   
}
                } else {
                    $errorMessage = "Error preparing job_requests statement: " . $conn->error;
                }
            } else {
                $errorMessage = "Error: " . $stmt->error;
            }
        } else {
            $errorMessage = "Error preparing job_offers statement: " . $conn->error;
        }
    } else {
        $errorMessage = "Required form fields are missing.";
    }
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

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Offer</title>
    <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
    <link rel="icon" type="image/png" href="../HanapKITA.png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 95%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .title {
            font-size: 32px;
            color: #1E3B85;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
        }
        .post-box {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
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
        .button-container {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
        .post-button {
            background-color: #1E3B85;
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
        .form-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .form-group > div {
            flex: 1;
            min-width: 200px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">JOB OFFER</div>
        <?php 
            if ($result != null) {
                $user = $result->fetch_assoc();
            }
        ?>
        <form action="hireform.php" method="post">
            <div class="post-box">
                <div class="form-group">
                    <div>
                        <label for="job">Hiring for:</label>
                        <input type="hidden" name="emp_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
                        <input type="hidden" name="jobseeker_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                        <input type="hidden" name="job" value="<?php echo htmlspecialchars($user['job_type'] ?? ''); ?>">
                        <input type="text" value="<?php echo htmlspecialchars($user['job_type'] ?? ''); ?>" disabled required/>
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
                        <input style="color: #8D8D8E;" type="text" value="<?php echo htmlspecialchars($user_city);?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div>
                        <label for="salary">Salary Offer:</label>
                        <input type="text" id="salary" name="salary" required>
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
                <label for="job_responsibilities">Responsibilities:</label>
                <textarea id="job_responsibilities" name="job_responsibilities" required></textarea>
                <label for="job_qualifications">Qualifications:</label>
                <textarea id="job_qualifications" name="job_qualifications" required></textarea>
                <div class="button-container">
                    <button class="post-button" type="submit">SEND OFFER</button>
                </div>
            </div>
        </form>
    </div>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p>The Offer was successfully sent to the jobseeker!</p>
            <button class="modal-button" onclick="closeModalAndRedirect()">Continue</button>
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

        const modal = document.getElementById("myModal");
        <?php if ($showModal) : ?>
            document.addEventListener('DOMContentLoaded', function() {
                modal.style.display = 'block';
            });
        <?php endif; ?>

        function closeModal() {
            modal.style.display = "none";
        }

        function closeModalAndRedirect() {
            closeModal();
            window.location.href = 'employerskilledworker.php';
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
