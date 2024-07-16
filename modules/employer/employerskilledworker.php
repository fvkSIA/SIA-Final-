<?php 

require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;
$showModal = false;
$result2 = null;

$worker_type = "SELECT * FROM worker_type";
if ($stmt = $conn->prepare($worker_type)) {
  $stmt->execute();
  $result = $stmt->get_result() ?? null;
  $stmt->close();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {

  $worker_type = $_POST['worker_type'];

  $param = "%{$worker_type}%";
  
  $sql = "SELECT DISTINCT users.id,users.profile, users.firstname, users.lastname, users.email, SUM(ratings.points) AS total_points FROM users
          LEFT JOIN ratings ON ratings.user_id = users.id
          WHERE users.type like 2
          AND job_type like ?
          GROUP BY users.id
          ORDER BY total_points DESC";

  // echo $job_type . " " . $location . ' query: ' . $sql; die();
  if ($user_stmt = $conn->prepare($sql)) {
    $user_stmt->bind_param("s", $param);
    $user_stmt->execute();
    $result2 = $user_stmt->get_result() ?? null;
    
    $user_stmt->close();
  }
}


$conn->close();


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employer Dashboard</title>
  <!-- External Styles -->
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
  <!-- <link rel="stylesheet" href="/css/em-skilled.css"> -->
   <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f0f0f0;
    }

    /* Sidebar */
    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      height: 100%;
      width: 78px; /* Initial sidebar width */
      transition: width 0.5s;
      z-index: 1;
    }

    .sidebar.open {
      width: 250px; /* Expanded sidebar width */
    }

    /* Main Content */
    .home-section {
      transition: margin-left 0.5s;
      padding: 20px;
    }

    .home-section.open {
      margin-left: 250px; /* Adjusted margin for open sidebar */
    }

    /* Dropdown for status */
    .status {
      margin-top: 20px;
    }

    .status select {
      padding: 10px;
      font-size: 14px;
      border-radius: 5px;
      border: 1px solid #ccc;
      background-color: #fff;
      color: #333;
    }

    /* Skilled workers buttons */
    .skilled {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 15px;
    }

    .unskilled {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 15px;
    }

    .box {
      background-color: #fff;
      border-radius: 5px;
      border: 1px solid #ccc;
      padding: 10px;
      text-align: center;
      font-size: 14px;
      font-weight: bold;
      width: calc(33.33% - 10px);
      color: #333;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
    }

    /* Container for top workers */
    .container {
      background-color: #fff;
      border: 1px solid #ccc;
      padding: 20px;
      margin-top: 20px;
      font-size: 16px;
      color: #333;
      display: flex;
      align-items: center;
    }

    .container img.profile {
      border-radius: 50%;
      width: 70px;
      height: 70px;
      margin-right: 20px;
    }

    .details {
      flex-grow: 1;
    }

    .details .name {
      font-size: 18px;
      font-weight: bold;
    }

    .details .ratings {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .details .ratings img {
      width: 20px;
      height: 20px;
    }

    .top {
      font-size: 20px;
      margin-left: auto;
    }

    .link {
      color: #3b5998;
      text-decoration: none;
      font-size: 12px;
      margin-left: 10px;
    }

    .see-more {
      color: #3b5998;
      text-decoration: none;
      font-size: 14px;
      margin-left: auto;
      margin-top: 10px;
      display: block;
      text-align: right;
    }

    /* Footer */
    .footer {
      width: 100%;
      padding: 20px;
      font-family: Arial, sans-serif;
      margin-top: 30px;
      background-color: #fff;
      border-top: 1px solid #ccc;
      box-sizing: border-box;
    }

    .footer-section {
      display: flex;
      justify-content: space-around;
      max-width: 1200px;
      margin: 0 auto;
    }

    .footer-column {
      list-style: none;
      padding: 0;
    }

    .footer-column li {
      margin-bottom: 10px;
    }

    .footer-column li a {
      text-decoration: none;
      color: #333;
    }

    .footer-column h4 {
      font-weight: bold;
      margin-bottom: 10px;
    }

    .footer-bottom {
      text-align: center;
      padding-top: 10px;
      font-size: 0.9em;
      color: #6c757d;
    }

    .footer-bottom a {
      text-decoration: none;
      color: inherit;
    }

    .footer-bottom a:hover {
      text-decoration: underline;
    }

    .hidden {
      display: none;
    }
    .show {
      display: flex;
    }

    




  .top-entry {
    margin-top: 20px; /* Increased top margin */
    margin-left: 5%; /* Adjusted left margin */
    margin-right: 5%; /* Adjusted right margin */
    padding: 20px; /* Added padding */
    align-content: space-evenly;
    gap: 10px;
    display: grid;
    grid-template-columns: 0.5fr 1fr 1fr;
    border: solid 2px gray;
    border-radius: 10px;
    background: #FFF9F9;
  }

  .top-entry .pic-entry {
    margin-left: auto; /* Centering the image */
    margin-right: auto; /* Centering the image */
    width: 70%; /* Adjusted width */
    border-radius: 50%;
  }

.top-entry .top-entry-details2{
text-align: center;
}

.main-container .top-entry .pic-entry{
    margin-left: 25%;
    margin-right:25;
    width: 40%;
    border-radius: 50%;
}
   </style>
</head>
<body>
<?php 
      $data = [];
      $users = [];
      if ($result2 != null)
        $users = $result2->fetch_all(MYSQLI_ASSOC);
      else 
        echo '';


      if ($result != null)
        $data = $result->fetch_all(MYSQLI_ASSOC);
        
      
      else 
        echo '';
    ?>
  <!-- Sidebar -->
  <div class="sidebar"></div>

  <!-- Main Content -->
  <div class="home-section">
    <!-- Dropdown for status -->
    <div class="status">
      <select name="worker_type" onchange="change(event)">
        <?php if($data):?>
          <?php foreach($data as $row):?>
            <option value="<?php echo $row['id'];?>"><?php echo strtoupper($row['description']);?></option>
          <?php endforeach;?>
        <?php endif;?>
        <!-- <option value="SKILLED WORKERS">SKILLED WORKERS</option>
        <option value="UNSKILLED WORKERS">UNSKILLED WORKERS</option> -->
      </select>
    </div>
    <form action="employerskilledworker.php" method="post" id="worker_type_form">
      <input type="hidden" name="worker_type" id="worker_type" value="">
      <input type="hidden" name="worker" id="worker" value="">
    </form>
    <!-- Skilled workers buttons -->
    <div class="skilled" id="skilled_workers">
      <button id="Welder" onclick="submit(this.id)" class="box">WELDER</button>
      <button id="Electrician" onclick="submit(this.id)" class="box">ELECTRICIAN</button>
      <button id="Plumber" onclick="submit(this.id)" class="box">PLUMBER</button>
      <button id="Carpenter" onclick="submit(this.id)" class="box">CARPENTER</button>
      <button id="Lineman" onclick="submit(this.id)" class="box">LINEMAN</button>
      <button id="Driver" onclick="submit(this.id)" class="box">DRIVER</button>
      <button id="Security" onclick="submit(this.id)" class="box">SECURITY GUARD</button>
      <button id="Refrigerator" onclick="submit(this.id)" class="box">REFRIGERATOR AND AIRCON SERVICE</button>
    </div>

    <div class="unskilled hidden" id="unskilled_workers">
      <button id="Foodservice" onclick="submit(this.id)" class="box">FOODSERVICE</button>
      <button id="Housekeeper" onclick="submit(this.id)" class="box">HOUSEKEEPER</button>
      <button id="Laundry" onclick="submit(this.id)" class="box">LAUNDRY STAFF</button>
      <button id="Janitor" onclick="submit(this.id)" class="box">JANITOR</button>
      <button id="Factory" onclick="submit(this.id)" class="box">FACTORY WORKER</button>
      <button id="Construction" onclick="submit(this.id)" class="box">CONSTRUCTION WORKER</button>
    </div>
    
  </div>

  <div class="main-container">
    <?php if($users):?>
      <?php $i = 1; ?>
      <?php foreach($users as $row):?>
        <div class="top-entry">
          <img class="pic-entry" src="../jobseeker/assets/images/<?php echo $row['profile'] ?? 'no-image.png';?>" alt="driver">
          <div class="top-entry-details1">
              <h3> <?php echo $row['firstname'] . ' ' . $row['lastname'];?> </h3>
              <!-- <span>RATINGS:</span>
              <div class="stars">★★★★☆</div> -->
          </div>
          <div class="top-entry-details2">
              <h3>TOP <?php echo '' . $i++;?></h3>
              <a href="jobseekerviewprofile.php?id=<?php echo $row['id'];?>">VIEW DETAILS</a>
          </div>
      </div>
      <?php endforeach;?>
    <?php else:?>
      <div class="top-entry">
          <!-- <img class="pic-entry" src="../jobseeker/assets/images/<?php echo $row['profile'] ?? 'no-image.png';?>" alt="driver"> -->
          <div class="top-entry-details1">
              <h3> NO RESULTS </h3>
          </div>
          <!-- <div class="top-entry-details2">
              <h3>TOP 1 </h3>
              <a href="jobseekerviewprofile.html">VIEW DETAILS</a>
          </div> -->
    <?php endif;?>
    
    
  </div>
  
  <!-- Footer -->
  <footer class="footer">
    <div class="footer-section">
      <ul class="footer-column">
        <h4>Job Seekers</h4>
        <li><a href="#top">Job Search</a></li>
        <li><a href="#">Profile</a></li>
        <li><a href="#">Recommended Jobs</a></li>
        <li><a href="#">Saved Searches</a></li>
        <li><a href="#">Saved Jobs</a></li>
        <li><a href="#">Job Applications</a></li>
      </ul>
      <ul class="footer-column">
        <h4>Employers</h4>
        <li><a href="#">Registration for Free</a></li>
        <li><a href="#">Post a Job ad</a></li>
      </ul>
      <ul class="footer-column">
        <h4>About Jobstreet</h4>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Work for Jobstreet</a></li>
      </ul>
      <ul class="footer-column">
        <h4>Contact</h4>
        <li><a href="#">Contact Us</a></li>
      </ul>
    </div>
    <div class="footer-bottom">
      <a href="#">Terms & conditions</a> | <a href="#">Security & Privacy</a>
    </div>
  </footer>

  <!-- Scripts -->
  <script>
    function change(event) {
      const value = event.target.value;
      var skilled = document.getElementById("skilled_workers");
      var unskilled = document.getElementById("unskilled_workers");
      if (value == 1) {
        
        // window.location.href = "employerunskilledworker.html";
        skilled.classList.remove("hidden");
        // skilled.classList.add("show");
        unskilled.classList.add("hidden");
        console.log(value);
      } else {
        unskilled.classList.remove("hidden");
        // unskilled.classList.add("show");
        skilled.classList.add("hidden");
        console.log(value);
      }
    }

    function submit(id){
      var worker_type = document.getElementById("worker_type");
      worker_type.value = id;

      var form = document.getElementById("worker_type_form");
      form.submit();
    }
  </script>
</body>
</html>
