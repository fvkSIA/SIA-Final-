<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp = $_POST['otp'];
    $new_password = $_POST['new_password'];

    if (isset($_SESSION['otp']) && $otp == $_SESSION['otp']) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update the password in the database
        $email = $_SESSION['email'];
        $conn = new mysqli('localhost', 'root', '', 'hanapkita_db');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "UPDATE users SET password='$hashed_password' WHERE email='$email'";
        if ($conn->query($sql) === TRUE) {
            // Show alert and redirect
            echo "<script>
                    alert('Password reset successfully, Please login again');
                    window.location.href = 'login.html';
                  </script>";
        } else {
            echo "Error updating password: " . $conn->error;
        }

        $conn->close();
        session_destroy();
    } else {
        echo "<script>
                alert('Invalid OTP!');
              </script>";
    }
}
?>
