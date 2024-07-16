<?php 


session_start();

$id = $_GET['id'];
$jrid = $_GET['jrid'];

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Jobseeker Dashboard</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <style>
    .container {
      width: 100%;
      height: 100vh; /* Full viewport height */
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      border-radius: 10px;
      background-color: #f0f0f0; /* Optional: a background for the container */
    }
    .title {
      font-size: 32px;
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      color: #1E3B85;
      margin-bottom: 20px;
      text-align: left;
      width: 80%;
      margin-top: 1%;
    }
    .message-box {
      background-color: #d7dfee;
      width: 80%;
      border-radius: 10px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 5px; /* Optional: padding for inner spacing */
    }
    .message-box h2 {
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      font-size: 30px;
      color: #333333;
      font-weight: bold;
    }
    .message-box p {
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      font-size: 40px;
      color: #333333;
      font-weight: bold;
    }
    .greeting {
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      font-size: 16px;
      margin: 25px 0 15px;
      text-align: left;
      width: 80%;
    }
    .congratulations {
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      font-size: 16px;
      width: 80%;
      text-align: left;
    }
    .see-more {
      color: #1E3B85;
      text-decoration: none;
      font-size: 14px;
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      margin-bottom: 20px;
    }
    .see-more:hover {
      text-decoration: underline;
    }
    .button-group {
      display: flex;
      justify-content: flex-end;
      width: 80%;
      margin-top: auto;
      margin-bottom: 20px;
    }
    .proceed-button {
      background-color: #1E3B85;
      color: #ffffff;
      border: none;
      border-radius: 20px;
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
      margin-right: 10px;
      text-decoration: none;
    }
    .close-button {
      background-color: transparent;
      color: #333333;
      border: none;
      font-size: 20px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="title">Job Offer</div>
    <div class="message-box">
      <h2><?php echo $_SESSION['name'];?></h2>
      <p>We've got new Job offer for you!</p>
    </div>
    <div class="greeting">Hi <?php echo $_SESSION['name'];?>!</div>
    <div class="congratulations">
      Exciting Opportunity: Join our team as a company driver! If you have a clean driving record and a passion for safe, reliable transportation, we want to hear from you. Apply now and become an essential part of our team, ensuring the smooth movement of people and goods every day.
    </div>
    <div class="button-group">
      <a class="proceed-button" href="jobseekerofferdetails.php?id=<?php echo $id;?>&jrid=<?php echo $jrid;?>">Proceed</button>
      <a class="close-button" href="jobseekerinbox.php">x</a>
    </div>
  </div>
  
  <script>
    function closeContainer() {
      window.location.href = "jobseekerinbox.php";
    }
    function proceed() {
      window.location.href = "jobseekerofferdetails.php";
    }
  </script>
  <!-- Scripts -->
  <script src="script.js"></script>
</body>
</html>
