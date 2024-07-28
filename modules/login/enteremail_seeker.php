<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
include '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';

if( isset($_POST['email'])) {
   
    // username and password sent from form 
    $email = mysqli_real_escape_string($conn,$_POST['email']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($_POST['email']== $user['email']) {

        $otp = rand(100000, 999999);
        $otp_expiry = date("Y-m-d H:i:s", strtotime("+3 minute"));
        $subject= "Your OTP for Password Reset";
        $message="Your OTP is: $otp";
        $f_name = $row['firstname'];
        
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPDebug = 2;
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kjcruz0604@gmail.com'; //host email 
        $mail->Password = 'qvxhyivnmoiiexum'; // app password of your host email nncd fuyn vlzl xrbv
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->isHTML(true);
        $mail->setFrom('kjcruz0604@gmail.com', 'HanapKITA Team');//Sender's Email & Name
        $mail->addAddress($email,$f_name); //Receiver's Email and Name
        $mail->Subject = ("$subject");
        $mail->Body = $message;
        $mail->send();

        $sql1 = "UPDATE users SET otp='$otp', otp_expiry='$otp_expiry' WHERE id=".$user['id'];
        $query1 = mysqli_query($conn, $sql1);

        $_SESSION['email']=$user['email'];
        $_SESSION['temp_user'] = ['id' => $user['id'], 'otp' => $otp];
        header('Location:enterotp_seeker.php');
        }else{
          ?>
            <script type="text/javascript">alert("Incorrect email or password, please try again.");</script>
            <?php 
        }
      }else{
        ?>
          <script type="text/javascript">alert("Incorrect email or password, please try again.");</script>
          <?php 
        }
?>