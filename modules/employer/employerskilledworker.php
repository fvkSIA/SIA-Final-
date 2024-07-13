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
   </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar"></div>

  <!-- Main Content -->
  <div class="home-section">
    <!-- Dropdown for status -->
    <div class="status">
      <select onchange="handleStatusChange(event)">
        <option value="SKILLED WORKERS">SKILLED WORKERS</option>
        <option value="UNSKILLED WORKERS">UNSKILLED WORKERS</option>
      </select>
    </div>

    <!-- Skilled workers buttons -->
    <div class="skilled">
      <a href="employerworkerwelder.html" class="box">WELDER</a>
      <a href="employerworkerelectrician.html" class="box">ELECTRICIAN</a>
      <a href="employerworkerplumber.html" class="box">PLUMBER</a>
      <a href="employerworkercarpenter.html" class="box">CARPENTER</a>
      <a href="employerworkerlineman.html" class="box">LINEMAN</a>
      <a href="employerworkerdriver.html" class="box">DRIVER</a>
      <a href="employerworkerguard.html" class="box">SECURITY GUARD</a>
      <a href="employerworkerservice.html" class="box">REFRIGERATOR AND AIRCON SERVICE</a>
    </div>

    
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
    function handleStatusChange(event) {
      const value = event.target.value;
      if (value === "UNSKILLED WORKERS") {
        window.location.href = "employerunskilledworker.html";
      }
    }
  </script>
</body>
</html>
