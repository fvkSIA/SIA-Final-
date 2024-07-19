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
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['profile'] = $user['profile'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['lastname'] . ', ' . $user['firstname'];
            $_SESSION['phone'] = $user['phone_number'];
            $_SESSION['address'] = $user['home_address'];
            $_SESSION['type'] = $user['type'];
            unset($_SESSION['temp_user']);
            if ($user['type'] == 2) {
                // jobseeker dashboard
                header('location: ../jobseeker/jobseekernavbar.php');
            } else if ($user['type'] == 3){
                header('location: ../employer/employernavbar.php');
            }
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
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <style type="text/css">
        #container{
            border: 1px solid black;
            width: 400px;
            margin-left: 400px;
            margin-top: 50px;
            height: 330px;
        }
        form{
            margin-left: 50px;
        }
        p{
            margin-left: 50px;
        }
        h1{
            margin-left: 50px;
        }
        input[type=number]{
            width: 290px;
            padding: 10px;
            margin-top: 10px;

        }
        button{
            background-color: orange;
            border: 1px solid orange;
            width: 100px;
            padding: 9px;
            margin-left: 100px;
        }
        button:hover{
            cursor: pointer;
            opacity: .9;
        }
    </style>
</head>
<body>
    <div id="container">
        <h1>Two-Step Verification</h1> 
        <p>Enter the 6 Digit OTP Code that has been sent <br> to your email address: <?php echo $_SESSION['email']; ?></p>
        <form method="post" action="otp_verification.php">
            <label style="font-weight: bold; font-size: 18px;" for="otp">Enter OTP Code:</label><br>
            <input type="number" name="otp" pattern="\d{6}" placeholder="Six-Digit OTP" required><br><br>
            <button type="submit">Verify OTP</button>
        </form>
    </div>
</body>
</html>

