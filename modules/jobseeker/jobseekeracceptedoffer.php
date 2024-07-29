<?php 
session_start();

$id = $_GET['id'];
$jrid = $_GET['jrid'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jobseeker Dashboard</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <style>
    body {
      margin: 0;
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
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
      <h2><?php echo htmlspecialchars(ucfirst($_SESSION['name'])); ?></h2>
      <p>We've got a new job offer for you!</p>

    </div>
    <div class="greeting">Hi <?php echo htmlspecialchars(ucfirst($_SESSION['name'])); ?>!</div>
    <div class="congratulations">
      Exciting Opportunity: Join our team as a company driver! If you have a clean driving record and a passion for safe, reliable transportation, we want to hear from you. Apply now and become an essential part of our team, ensuring the smooth movement of people and goods every day.
    </div>
    <div class="button-group">
      <a class="proceed-button" href="jobseekerofferdetails.php?id=<?php echo urlencode($id); ?>&jrid=<?php echo urlencode($jrid); ?>">Proceed</a>
      <a class="close-button" href="jobseekerinbox.php">x</a>
    </div>

  </div>
</body>
</html>
