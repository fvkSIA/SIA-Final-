<?php

session_start();
include '/xampp/htdocs/SIA-Final-/db/db_connection.php';
if (!isset($_SESSION['temp_user'])) {
    header("Location: login.html");
    exit();
}
ob_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_otp = $_POST['otp'];
    $stored_otp = $_SESSION['temp_user']['otp'];
    $user_id = $_SESSION['temp_user']['id'];

    $sql = "SELECT * FROM users WHERE id='$user_id' AND otp='$user_otp'";
    $query = mysqli_query($conn, $sql);
    $user = mysqli_fetch_array($query);

    if ($user) {
        $otp_expiry = strtotime($user['otp_expiry']);
        if ($otp_expiry >= time()) {
            $_SESSION['email'] = $user['email'];
            unset($_SESSION['temp_user']);
            header('Location:newpass.php');
        } else {
            ?>
                <script>
    alert("OTP has expired. Please try again.");
    function navigateToPage() {
        window.location.href = 'login.html';
    }
    window.onload = function() {
        navigateToPage();
    }
</script>
            <?php 
        }
    } else {
        ?>
          <script type="text/javascript">alert("Incorrect OTP, please try again.");</script>
          <?php 
}
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter OTP</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #b8c6db;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 400px;
            text-align: center;
        }
        h1 {
            color: #3a4a7b;
            font-size: 24px;
            margin-bottom: 10px;
        }
        p {
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
        }
        .code-inputs {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .code-input {
            width: 50px;
            height: 50px;
            border: none;
            border-radius: 25px;
            background-color: #e8eef9;
            font-size: 24px;
            text-align: center;
            font-family: 'Poppins', sans-serif;
        }
        button {
            padding: 10px 30px;
            background-color: white;
            color: #3a4a7b;
            border: 2px solid #3a4a7b;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            font-family: 'Poppins', sans-serif;
        }
        button:hover {
            background-color: #3a4a7b;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Enter Verification Code</h1>
        <p>Make sure the verification code is correct.</p>
        <form method="post" action="enterotp.php">
            <label style="font-weight: bold; font-size: 18px;" for="otp">Enter OTP Code:</label><br>
            <input type="number" name="otp" pattern="\d{6}" placeholder="Six-Digit OTP" required><br><br>
            <button type="submit">Verify OTP</button>
        </form>
        <button>CONFIRM</button>
    </div>
</body>
</html>