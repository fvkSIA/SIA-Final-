<?php 
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;

if($_SERVER["REQUEST_METHOD"] == "GET") {
  $id = $_GET['id'];
  
  $sql = "SELECT  * FROM users where id = ?";

  // echo $id . ' query: ' . $sql; die();
  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result() ?? null;
    $stmt->close();
  }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Jobseeker Dashboard</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <style>
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #f0f0f0;
    }

    .card {
      background-color: #fff;
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      width: 700px;
      padding: 50px;
      display: flex;
      border: 1px solid black;
      align-items: center;
      font-family: Arial, sans-serif;
      position: relative;
      justify-content: center;
    }

    .card img {
      border-radius: 50%;
      width: 120px;
      height: 120px;
      margin-right: 20px;
    }

    .card .info {
      display: flex;
      flex-direction: column;
    }

    .card .info h2 {
      margin: 0;
      margin-left: 25px;
      font-size: 34px;
      font-family: Verdana, Geneva, Tahoma, sans-serif;
      color: #1a3e6e;
      margin-bottom: 10px;
    }

    .card .info p {
      margin: 5px 0;
      font-size: 14px;
      color: #666;
    }

    .card .info .icon {
      margin-left: 100px;
      margin-right: 15px;
    }

    .card .info .details {
      display: flex;
      margin-top: 20px;
      align-items: center;
    }

    .back-arrow {
      position: absolute;
      top: 20px;
      left: 20px;
      font-size: 24px;
      color: #1a3e6e;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <div class="card">
    <a href="jobseekerhiring.php">
      <div class="back-arrow">
          <i class="fas fa-arrow-left"></i>
      </div>
  </a>
  <?php 
          $data = [];
          if ($result != null) {
            $user = $result->fetch_assoc();
          } else {
            echo '';
          }

        ?>
    <img src="../employer/assets/images/<?php echo $user['profile'] ?? 'no-image.png'?>" alt="Profile Picture">
    <div class="info">
        <h2><?php echo $user['lastname'] . ', ' . $user['firstname']; ?></h2>
        <div class="details">
            <i class="fas fa-phone icon"></i>
            <p><?php echo $user['phone_number']; ?></p>
        </div>
        <div class="details">
            <i class="fas fa-map-marker-alt icon"></i>
            <p><?php echo $user['home_address']; ?></p>
        </div>
        <div class="details">
            <i class="fas fa-envelope icon"></i>
            <p><?php echo $user['email']; ?></p>
        </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="script.js"></script>
</body>
</html>
