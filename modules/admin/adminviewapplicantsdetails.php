<?php

require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Invalid ID");
}

$users = "SELECT users.*, user_types.id as user_type_id, user_types.description as user_type_desc FROM users
    INNER JOIN user_types ON users.type = user_types.id
    WHERE users.id = ?";

if ($stmt = $conn->prepare($users)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result() ?? null;
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $verify = $_POST['verify'];

    $sql = "UPDATE users SET verified=? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $verify, $user_id);
        if ($stmt->execute()) {
            // Fetch user email and other details to send in the email
            $userQuery = "SELECT email, password FROM users WHERE id=?";
            if ($userStmt = $conn->prepare($userQuery)) {
                $userStmt->bind_param("i", $user_id);
                $userStmt->execute();
                $userResult = $userStmt->get_result();
                $userData = $userResult->fetch_assoc();
                $userStmt->close();

                // Send verification email
                $mail = new PHPMailer(true);

                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = 'kjcruz0604@gmail.com'; // Replace with your SMTP username
                    $mail->Password = 'qvxhyivnmoiiexum'; // Replace with your SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Recipients
                    $mail->setFrom('kjcruz0604@gmail.com', 'HanapKITA Team');
                    $mail->addAddress($userData['email']); // Add a recipient

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Account Verification';
                    $mail->Body    = "Your account has been verified. Please use your username and password that you made during registration.<br>Email: {$userData['email']}<br>Password: {$userData['password']}";

                    $mail->send();
                    header('location: adminregistration.php');
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }
        } else {
            echo "ERROR APPROVING USER";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap');
        .form-control:focus {
            box-shadow: none;
            border-color: #BA68C8;
        }
        .profile-button {
            background: rgb(99, 39, 120);
            box-shadow: none;
            border: none;
        }
        .profile-button:hover {
            background: #682773;
        }
        .profile-button:focus {
            background: #682773;
            box-shadow: none;
        }
        .profile-button:active {
            background: #682773;
            box-shadow: none;
        }
        .back:hover {
            color: #682773;
            cursor: pointer;
        }
        .labels {
            font-size: 11px;
        }
        .add-experience:hover {
            background: #BA68C8;
            color: #fff;
            cursor: pointer;
            border: solid 1px #BA68C8;
        }
    </style>
    <script>
        function showAlert() {
            alert("Account verification message sent");
        }
    </script>
</head>
<body>
<?php 
      $data = [];
      if ($result != null)
        $data = $result->fetch_assoc();
      else 
        echo '';
    ?>
    <nav aria-label="breadcrumb" class="m-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="adminregistration.php">Users</a></li>
            <li class="breadcrumb-item active" aria-current="page">Profile</li>
        </ol>
    </nav>
<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                <img class="rounded-circle mt-5" width="150px" height="150" src="<?php echo $data['type'] == 3 ? '../employer/assets/images/' : '../jobseeker/assets/images/'?><?php echo $data['profile']?>">
                <span class="font-weight-bold"><?php echo $data['firstname'];?></span>
                <span class="text-black-50"><?php echo $data['email'];?></span>
                <span> 

                </span>
            </div>
        </div>
        <div class="col-md-5 border-right">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Profile</h4>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6"><label class="labels">First name</label><input type="text" class="form-control" placeholder="first name" value="<?php echo $data['firstname'];?>" disabled></div>
                    <div class="col-md-6"><label class="labels">Last name</label><input type="text" class="form-control" value="<?php echo $data['lastname'];?>" placeholder="last name" disabled></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Phone Number</label><input type="text" class="form-control" placeholder="enter phone number" value="<?php echo $data['phone_number'];?>" disabled></div>
                    <div class="col-md-12"><label class="labels">Home Address</label><input type="text" class="form-control" placeholder="enter home address" value="<?php echo $data['home_address'];?>" disabled></div>
                    <div class="col-md-12"><label class="labels">City</label><input type="text" class="form-control" placeholder="enter city" value="<?php echo $data['city'];?>" disabled></div>
                    <div class="col-md-12"><label class="labels">Birthdate</label><input type="text" class="form-control" placeholder="enter birthdate" value="<?php $date=date_create($data['birthdate']); echo date_format($date,"M d, Y");?>" disabled></div>
                    <div class="col-md-12"><label class="labels">Gender</label><input type="text" class="form-control" placeholder="enter gender" value="<?php echo $data['gender'];?>" disabled></div>
                    <div class="col-md-12"><label class="labels">Type</label><input type="text" class="form-control" placeholder="enter email id" value="<?php echo $data['user_type_desc'];?>" disabled></div>
                    <!-- <div class="col-md-12"><label class="labels">Education</label><input type="text" class="form-control" placeholder="education" value=""></div> -->
                </div>
                <div class="row mt-3">
                    <!-- <div class="col-md-6"><label class="labels">Country</label><input type="text" class="form-control" placeholder="country" value=""></div>
                    <div class="col-md-6"><label class="labels">State/Region</label><input type="text" class="form-control" value="" placeholder="state"></div> -->
                </div>
                <div class="mt-5 text-center">
                    <form action="adminviewapplicantsdetails.php?id=1" method="POST" id="approval_form" onsubmit="showAlert()">
                        <input type="hidden" name="user_id" value="<?php echo $id;?>">
                        <input type="hidden" name="verify" value="1">

                        <button class="btn btn-success" type="submit">Approve</button>
                        <a href="adminregistration.php" class="btn btn-danger mx-2" type="button">Cancel</a>
                    </form>
                    
                </div>
            </div>
        </div>
        <div class="col-md-4">
    <div class="p-3 py-5">
        <div class="d-flex justify-content-between align-items-center experience"><span>Files</span></div><br>
        <?php if($data['type'] == 2): // Assuming 2 is the type for jobseekers ?>
            <?php if(!empty($data['resume'])): ?>
                <div class="col-md-12">
                    <label class="labels">Resume</label><br>
                    <a href="<?php echo $data['resume']; ?>" target="_blank">View Resume</a>
                </div>
            <?php endif; ?>
            <br>
            <?php if(!empty($data['valid_ids'])): ?>
                <div class="col-md-12">
                    <label class="labels">Valid IDs / Birth Certificate</label><br>
                    <a href="<?php echo $data['valid_ids']; ?>" target="_blank">View Valid IDs/Birth Certificate</a>
                </div>
            <?php endif; ?>
            <br>
            <?php if(!empty($data['recent_job_experience'])): ?>
                <div class="col-md-12">
                    <label class="labels">Recent Job Experience</label><br>
                    <a href="<?php echo $data['recent_job_experience']; ?>" target="_blank">View Recent Job Experience</a>
                </div>
            <?php endif; ?>
        <?php elseif($data['type'] == 3): // Assuming 3 is the type for employers ?>
            <?php if(!empty($data['valid_id_path'])): ?>
                <div class="col-md-12">
                    <label class="labels">Valid ID / Birth Certificate</label><br>
                    <a href="<?php echo $data['valid_id_path']; ?>" target="_blank">View Valid ID/Birth Certificate</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
    </div>
</div>
</div>
    <script>
        function closeDiv() {
            window.location.href = 'adminregistration.php';
        }

        function approve() {
            // Handle approve button action
            console.log('Approved');
        }

        function decline() {
            // Handle decline button action
            console.log('Declined');
        }
    </script>
</body>
</html>
