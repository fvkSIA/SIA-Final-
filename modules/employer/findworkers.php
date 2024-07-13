<?php 

require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;

if($_SERVER["REQUEST_METHOD"] == "POST") {
  $job_type = $_POST['job_type'];
  $location = $_POST['location'] ?? '';
  
  $sql = "SELECT  * FROM users where type = 2 AND job_type = ? AND city = ?";

  // echo $job_type . " " . $location . ' query: ' . $sql; die();
  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ss", $job_type, $location);
    $stmt->execute();
    $result = $stmt->get_result() ?? null;
    
    $stmt->close();
  }
}
$conn->close();
?>


<html>
  <head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"></link>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Poppins:wght@400&display=swap" rel="stylesheet">
    <style>
      .topnav {
  overflow: hidden;
  background-color: #DADADA;
  justify-items: end;
}

.topnav a {
  float: right;
  color: #333;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.topnav a:hover {
  /* background-color: #ddd; */
  color: #007bff;
}

.topnav a.active {
  background-color: #04AA6D;
  color: white;
}
    </style>
  </head>
  <!-- <div class="topnav">

    <a href="../logout.php">Log out</a>
    <a href="#">Welcome! <?php echo $_SESSION['name'];?></a>
    <a href="employerdashboardhome.php">Home</a>
    
  </div> -->
  <body class="font-poppins">
    
    <div class="bg-blue-200 p-4">
      <form action="findworkers.php" method="post">
      <div class="container mx-auto flex justify-center space-x-4 items-center">
        <input type="text" name="job_type" placeholder="Search..." class="w-[23rem] px-4 py-2 rounded border border-gray-300">
        <div class="relative">
          <select name="location" class="w-[23rem] px-4 py-2 rounded border border-gray-300 bg-white">
            <option disabled selected class="hidden">Location</option>
            <option value="">Location</option>
            <option value="manila">Manila</option>
            <option value="caloocan">Caloocan</option>
            <option value="valenzuela">Valenzuela</option>
            <option value="pasay">Pasay</option>
            <option value="makati">Makati</option>
            <option value="quezon_city">Quezon City</option>
            <option value="navotas">Navotas</option>
            <option value="las_piñas">Las Piñas</option>
            <option value="malabon">Malabon</option>
            <option value="mandaluyong">Mandaluyong</option>
            <option value="marikina">Marikina</option>
            <option value="muntinlupa">Muntinlupa</option>
            <option value="parañaque">Parañaque</option>
            <option value="pasig">Pasig</option>
            <option value="san_juan">San Juan</option>
            <option value="taguig">Taguig</option>
            <option value="valenzuela">Valenzuela</option>
            <option value="pateros">Pateros</option>
          </select>
        </div>
        <button type="submit" class="w-[13rem] bg-blue-500 text-white px-4 py-2 rounded">Find Now!</button>
      </div>
      </form>
      
    </div>
    <?php 
      $data = [];
      if ($result != null)
        $data = $result->fetch_all(MYSQLI_ASSOC);
      else 
        echo '';
    ?>
    <?php if ($data): ?>
      <?php foreach($data as $row): ?>
        <div class="container mx-auto mt-8 p-4 border-t-4 border-indigo-200 shadow-lg rounded-lg bg-white">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-xl font-bold"><?php echo $row['lastname'] . ', ' . $row['firstname'] . ' ' . $row['middlename'];?></h2>
            <p class="text-gray-600">Location: <?php echo $row['city']?></p>
            <p class="text-gray-600">Type of Worker: <?php echo $row['job_type']?></p>
            <div class="flex items-center mt-2">
              <span class="text-yellow-500">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </span>
            </div>
          </div>
          <div class="text-center">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTGcXaN1eANzJpV2p02f3Up6BqJ8I46Zc4BonvdyCvldGnrDLoAZ3E9lHH7ZGFr_-0F0LQ&usqp=CAU" alt="no image" class="rounded-full mb-2 w-24 h-24"> <!-- Adjusted size -->
            <a href='jobseekerviewprofile.php?id=<?php echo $row['id'];?>' class="text-blue-500">View Profile</a>
          </div>
        </div>
      </div>
      <?php endforeach;?>
    <?php else: ?>
      <div class="container mx-auto mt-8 p-4 border-t-4 border-indigo-200 shadow-lg rounded-lg bg-white">
        <div class="flex items-center justify-between">
          <p>NO RESULTS FOUND</p>
        </div>
      </div>
      
    <?php endif;?>
    
  </body>
</html>
