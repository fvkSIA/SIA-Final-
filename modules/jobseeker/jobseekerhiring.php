<?php 

require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;

if($_SERVER["REQUEST_METHOD"] == "POST") {
  $job_type = $_POST['job_type'];
  $location = $_POST['location'] ?? '';
  
//   $sql = "SELECT  * FROM users where type = 3 AND job_type = ? AND city = ?";

  $sql = "SELECT job_listings.*, users.id as user_id, users.firstname, users.lastname, users.middlename, users.email FROM job_listings 
            INNER JOIN users ON users.id = job_listings.employer_id
            WHERE users.type = 3
            AND job_listings.job = ?
            AND job_listings.location = ?";

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


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Jobseeker Dashboard</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    .apply-now-container {
      position: relative;
      height: 100%;
    }
    .apply-now-button {
      position: absolute;
      bottom: 10px;
      right: 10px;
      padding: 10px 20px;
      background-color: #1E40AF; /* Tailwind's blue-900 */
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-family: 'Poppins', sans-serif;
      text-align: center;
      text-decoration: none; /* Ensure the text isn't underlined */
      display: inline-block;
    }
    .apply-now-button:hover {
      background-color: #1E3A8A; /* Tailwind's blue-800 */
    }
  </style>
</head>
<body>
  <main class="flex-1 p-8">
    <div class="bg-blue-100 p-6 rounded-lg mb-2 flex justify-center">
        <form action="jobseekerhiring.php" method="post">
            <div class="flex space-x-4 items-center">
                <select class="py-2 px-4 rounded bg-white border border-gray-300" name="job_type">
                    <option>Select type of worker</option>
                        <option>Welder</option>
                        <option>Plumber</option>
                        <option>Lineman</option>
                        <option>Security Guard</option>
                        <option>Electrician</option>
                        <option>Carpenter</option>
                        <option>Driver</option>
                        <option>Refrigerator and Aircon Service</option>
                        <option>Food Service</option>
                        <option>Laundry Staff</option>
                        <option>Factory Worker</option>
                        <option>Housekeeper</option>
                        <option>Janitor</option>
                        <option>Construction Worker</option>
                </select>
                <select class="py-2 px-4 rounded bg-white border border-gray-300" name="location">
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
                <button class="py-2 px-6 bg-blue-900 text-white rounded hover:bg-blue-800" type="submit">Find Now!</button>
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

    <?php if($data):?>
        <?php foreach($data as $row): ?>
            <div class="bg-purple-100 p-8 rounded-lg mb-4 apply-now-container">
                <div class="bg-#f1f1f1 p-4 mb-1">
                    <div style="display: flex; justify-content: space-between; max-width: 100%; ">
                        <div style="width: 10%; margin-left: 20px; box-sizing: border-box;">
                            <img src="../jobseeker/assets/images/<?php echo $row['profile'] ?? 'no-image.png'?>" alt="Circular Image" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover;">
                            <a href="employerhiringprofile.php?id=<?php echo $row['employer_id'];?>" style="text-decoration: underline; display: flex; justify-content: center; margin-top: 10px;">View Profile</a>
                        </div>
                        <div style="width: 15%; margin: 1px; box-sizing: border-box;">
                            <p style="font-weight: bold;">
                                LOOKING FOR:
                            </p><p style="margin-top: 15px; font-weight: bold;">
                                LOCATION:
                        </div>
                        <div style="width: 25%;  box-sizing: border-box;">
                            <p>
                                <?php echo $row['job'];?>
                            </p><p style="margin-top: 10px;">
                                <?php echo $row['location'];?>
                        </div>
                        <div style="width: 15%; margin: 1px; box-sizing: border-box;">
                            <p style="font-weight: bold;">
                                SALARY:
                            </p><p style="margin-top: 15px; font-weight: bold;">
                                DATE:
                            </p>
                        </div>
                        <div style="width: 25%;  box-sizing: border-box;">
                            <p>
                                <?php echo $row['salary_offer'];?>
                            </p><p style="margin-top: 10px;">
                                <?php echo $row['date'];?>
                            </p>
                        </div>
                    </div>
                </div>
                <a href="applydetails.php?id=<?php echo $row['id']?>" class="apply-now-button">Apply Now</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="container mx-auto mt-8 p-4 border-t-4 border-indigo-200 shadow-lg rounded-lg bg-white">
            <div class="flex items-center justify-between">
            <p>NO RESULTS FOUND</p>
            </div>
        </div>
    <?php endif;?>
    

</main>

<script>
    function foldAside() {
        const aside = document.querySelector('aside');
        aside.classList.toggle('folded');
    }
</script>

  <!-- Scripts -->
  <script src="script.js"></script>
</body>
</html>
