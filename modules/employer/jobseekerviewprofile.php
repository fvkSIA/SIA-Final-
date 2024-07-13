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
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      background-color: #f1eaf7; /* Optional: Match the background color to the container */
    }

    .button-group {
      position: absolute;
      top: 20px;
      right: 20px;
    }

    .close-button {
      background-color: transparent;
      color: #333333;
      border: none;
      font-size: 20px;
      cursor: pointer;
    }

    #myButton {
      position: relative;
      border-radius: 50px;
      cursor: pointer;
      width: 100px;
      background-color: white;
      color: #000000;
      font-weight: 500;
      padding: 0.5rem 1rem;
      border: 1px solid #000000;
      align-items: center;
      margin-left: auto;
      margin-right: auto;
      font-size: 16px;
      transition: transform 0.3s;
    }

    #myButton:hover {
      background-color: rgb(235, 235, 235);
    }

    @media screen and (max-width: 768px) {
      #myButton {
        transform: scale(0.9);
      }
    }

    @media screen and (max-width: 640px) {
      #myButton {
        transform: scale(0.8);
      }
    }

    .rounded-lg {
      max-width: 100%;
      overflow: hidden;
      word-wrap: break-word;
      background-color: #ffffff; /* Ensures container background is white */
    }
  </style>
</head>
<body>
  <div class="rounded-lg shadow-lg p-6" style="background-color: #f1eaf7; width: 40%;">
    <div class="flex items-center mb-4">
      <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTGcXaN1eANzJpV2p02f3Up6BqJ8I46Zc4BonvdyCvldGnrDLoAZ3E9lHH7ZGFr_-0F0LQ&usqp=CAU" class="w-20 h-20 rounded-full mr-4" alt="Profile picture of driver">
      <div>
        <?php 
          $data = [];
          if ($result != null) {
            $user = $result->fetch_assoc();
          } else {
            echo '';
          }

        ?>
        <h1 class="text-xl font-bold mb-2" style="letter-spacing: 5px;"><?php echo $user['lastname'] . ', ' . $user['firstname']; ?></h1>
        <div class="flex items-center text-sm text-gray-600 mb-2">
          <span class="mr-2 font-bold w-1/3">RANKING:</span>
          <a href="#" class="text-yellow-500 font-semibold underline">TOP 1 - DRIVER</a>
        </div>
        <div class="flex items-center mb-2">
          <span class="text-sm text-gray-600 mr-2 font-bold w-1/3">RATING:</span>
          <div class="flex">
            <i class="fas fa-star text-yellow-500"></i>
            <i class="fas fa-star text-yellow-500"></i>
            <i class="fas fa-star text-yellow-500"></i>
            <i class="fas fa-star text-yellow-500"></i>
            <i class="fas fa-star text-yellow-500"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="space-y-2 text-sm text-gray-600">
      <div class="flex">
        <span class="font-semibold w-1/3">PHONE NUMBER:</span>
        <span><?php echo $user['phone_number']; ?></span>
      </div>
      <div class="flex">
        <span class="font-semibold w-1/3">EMAIL ADDRESS:</span>
        <span><?php echo $user['email']; ?></span>
      </div>
      <div class="flex">
        <span class="font-semibold w-1/3">GENDER:</span>
        <span><?php echo $user['gender']; ?></span>
      </div>
      <div class="flex">
        <span class="font-semibold w-1/3">LOCATION:</span>
        <span><?php echo $user['city'];?></span>
      </div>
    </div>
    <div class="mt-4 p-4 border-t border-gray-300"></div>
    <a id="myButton" href='hireform.php?id=<?php echo $user['id'];?>'>HIRE</a>
  </div>
  </div>
  

  <div class="button-group">
    <button class="close-button" onclick="closeContainer()">x</button>
  </div>
  <script>
    function closeContainer() {
      window.location.href = "findworkers.php";
    }
  </script>
  <!-- Scripts -->
  <script src="script.js"></script>
</body>
</html>