<?php 

require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';

if($_SERVER["REQUEST_METHOD"] == "POST") {
   
    // username and password sent from form 
    $email = mysqli_real_escape_string($conn,$_POST['username']);
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
        if ($user['type'] == 1) {
            // jobseeker dashboard
            header('location: adminnavbar.php');
        } 
        exit;
    } else {
        $error =  "Invalid email or password.";
    }
    $stmt->close();
 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Admin Login</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f5;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .login-container {
        background: white;
        padding: 50px;
        border: 1px solid black;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        text-align: center;
        width: 300px;
    }

    .login-container h1 {
        margin-bottom: 30px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 38px;
        margin-top: -10px;
        color: #2f2f75;
    }

    label {
        display: block;
        text-align: left;
        margin-bottom: 8px;
        font-weight: bold;
    }

    .input-container {
        position: relative;
        margin-bottom: 15px;
    }

    .input-container input {
        width: 100%;
        padding: 10px;
        padding-left: 40px; /* Adjusted padding to make space for the icon */
        border: 1px solid black;
        border-radius: 5px;
        font-size: 16px;
        box-sizing: border-box;
    }

    .input-container .bx {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 20px;
    }

    button {
        width: 100%;
        padding: 10px;
        background-color: #2f2f75;
        border: none;
        border-radius: 5px;
        color: white;
        font-size: 16px;
        cursor: pointer;
    }

    button:hover {
        background-color: #1e1e5c;
    }
</style>
</head>
<body>
    <div class="login-container">
        <h1>ADMIN</h1>
        <form id="loginForm" action="index.php" method="POST">
            <label for="username">Email:</label>
            <div class="input-container">
                <input type="text" id="username" name="username" required>
                <i class='bx bx-user'></i> <!-- Boxicons user icon -->
            </div>
            <label for="password">Password:</label>
            <div class="input-container">
                <input type="password" id="password" name="password" required>
                <i class='bx bx-lock'></i> <!-- Boxicons lock icon -->
            </div>
            
            <button type="submit">Login</button>
            <?php if ($error != ''):?>
                <p><?php echo $error;?></p>
            <?php endif;?>
        </form>
    </div>

    <script>
        // document.getElementById('loginForm').addEventListener('submit', function(event) {
        //     event.preventDefault();

        //     const username = document.getElementById('username').value;
        //     const password = document.getElementById('password').value;

        //     // Replace these with a more secure approach for production!
        //     const validUsername = 'admin';a
        //     const validPassword = 'admin123';

        //     if (username === validUsername && password === validPassword) {
        //         alert('Login successful!');
        //         // Redirect to the admin dashboard
        //         window.location.href = 'admdashboard.html';
        //     } else {
        //         alert('Invalid username or password');
        //     }
        // });
    </script>
</body>
</html>
