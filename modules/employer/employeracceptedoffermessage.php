<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Jobseeker Dashboard</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <link rel="icon" type="image/png" href="../HanapKITA.png">

  <style>
     @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .container {
      width: 90%;
      max-width: 800px;
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      padding: 20px;
      box-sizing: border-box;
    }
    .message-box {
      background-color: #d7dfee;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 20px;
      text-align: center;
    }
    .message-box h2 {
      font-size: 24px;
      color: #333;
      margin: 0;
    }
    .message-box p {
      font-size: 18px;
      color: #333;
      margin: 10px 0 0;
    }
    .greeting, .congratulations {
      font-size: 16px;
      color: #333;
      margin-bottom: 20px;
    }
    .button-group {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }
    .proceed-button, .close-button {
      text-decoration: none;
      border: none;
      border-radius: 8px;
      padding: 10px 15px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .proceed-button {
      background-color: #1E3B85;
      color: #ffffff;
    }
    .proceed-button:hover {
      background-color: #163e6e;
    }
    .close-button {
      background-color: #f3f3f3;
      color: #333;
      font-size: 20px;
    }
    .close-button:hover {
      background-color: #d0d0d0;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="message-box">
        <h2><?php echo $_GET['fname']?> <?php echo $_GET['lname'];?></h2>
        <br>
        <p>Your Job Offer has been accepted!</p>
    </div>
    <div class="greeting">Hi <?php echo $_SESSION['name'];?></div>
    <div class="congratulations">
        Congratulations! You're job offer to the jobseeker have been accepted. Communicate to each other and have a beautiful work outcome.    </div>
        <div class="button-group">
        <button class="close-button" onclick="closeContainer()">x</button>
    </div>
</div>



<script>
    function closeContainer() {
      window.location.href = "employerinbox.php";
    }
  </script>
  <!-- Scripts -->
  <script src="script.js"></script>
</body>
</html>
