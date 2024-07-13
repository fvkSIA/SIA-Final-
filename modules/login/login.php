<?php 

require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';

if($_SERVER["REQUEST_METHOD"] == "POST") {
   
    // username and password sent from form 
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $pass = mysqli_real_escape_string($conn,$_POST['password']); 

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['profile'] = $user['profile'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['lastname'] . ', ' . $user['firstname'];
        $_SESSION['phone'] = $user['phone_number'];
        $_SESSION['address'] = $user['home_address'];
        if ($user['type'] == 2) {
            // jobseeker dashboard
            header('location: ../jobseeker/jobseekernavbar.php');
        } else if ($user['type'] == 3){
            header('location: ../employer/employernavbar.php');
        }
        
        exit;
    } else {
        echo "Invalid email or password.";
    }
    $stmt->close();
 }
?>