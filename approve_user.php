<?php
// Include the database connection file
include 'db_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

function sendApprovalEmail($email, $password, $type) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth   = true;
        $mail->Username   = 'josephcarlobersoto@gmail.com'; // SMTP username
        $mail->Password   = 'xspo gtlu alar ahsf'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('josephcarlobersoto@gmail.com', 'Mailer');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your account has been approved';
        $mail->Body    = "Your account has been approved. Your username is: $email and password is: (password you input on your registration))";

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Update approval status and send email
if (isset($_POST['id']) && isset($_POST['type'])) {
    $id = intval($_POST['id']);
    $type = $_POST['type']; // 'jobseeker' or 'employer'

    $table = $type === 'jobseeker' ? 'jobseekers' : 'employers';

    $result = $mysqli->query("SELECT email, password FROM $table WHERE id = $id AND is_approved = 0");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];
        $password = $row['password'];

        if ($mysqli->query("UPDATE $table SET is_approved = 1 WHERE id = $id")) {
            sendApprovalEmail($email, $password, $type);
        } else {
            echo "Error updating record: " . $mysqli->error;
        }
    } else {
        echo "No unapproved user found with id $id";
    }
}

$mysqli->close();
?>

