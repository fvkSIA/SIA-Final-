<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jobseeker Dashboard</title>
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <script>
    // Function to change content automatically
    function changeContentAutomatically() {
        var iframe = document.getElementById('myIframe');
        iframe.src = 'jobseekerdashboardhome.php'; // Set the source of iframe
    }

    // Automatically change content on page load
    window.onload = function() {
        changeContentAutomatically(); // Call the function when the page loads
    };

    // Function to load page content into the iframe
    function changeContent(page) {
      document.getElementById('myIframe').src = page;
    }
  </script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

    :root {
      --color-default: #004f83;
      --color-second: #004f83;
      --color-white: #fff;
      --color-body: #e4e9f7;
      --color-light: #e0e0e0;
    }

    * {
      padding: 0;
      margin: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      min-height: 100vh;
      background-color: var(--color-body);
      display: flex;
    }

    .sidebar {
      position: absolute;
      height: 100vh;
      width: 78px;
      padding: 6px 14px;
      z-index: 99;
      background-color: var(--color-default);
      transition: all .5s ease;
      top: 0;
      left: 0;
      overflow-y: auto;
    }

    .sidebar.open {
      width: 250px;
    }

    .sidebar .logo_details {
      height: 60px;
      display: flex;
      align-items: center;
      position: relative;
    }

    .sidebar .logo_details .icon {
      opacity: 0;
      transition: all 0.5s ease;
    }

    .sidebar .logo_details .logo_name {
      color: var(--color-white);
      font-size: 22px;
      font-weight: 600;
      opacity: 0;
      transition: all .5s ease;
    }

    .sidebar.open .logo_details .icon,
    .sidebar.open .logo_details .logo_name {
      opacity: 1;
    }

    .sidebar .logo_details #btn {
      position: absolute;
      top: 50%;
      right: 0;
      transform: translateY(-50%);
      font-size: 23px;
      text-align: center;
      cursor: pointer;
      transition: all .5s ease;
    }

    .sidebar.open .logo_details #btn {
      text-align: right;
    }

    .sidebar i {
      color: var(--color-white);
      height: 60px;
      line-height: 60px;
      min-width: 50px;
      font-size: 25px;
      text-align: center;
    }

    .sidebar .nav-list {
      margin-top: 20px;
    }

    .sidebar li {
      position: relative;
      margin: 8px 0;
      list-style: none;
    }

    .sidebar li .tooltip {
      position: absolute;
      top: -20px;
      left: calc(100% + 15px);
      z-index: 3;
      background-color: var(--color-white);
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
      padding: 6px 14px;
      font-size: 15px;
      font-weight: 400;
      border-radius: 5px;
      white-space: nowrap;
      opacity: 0;
      pointer-events: none;
    }

    .sidebar li:hover .tooltip {
      opacity: 1;
      pointer-events: auto;
      transition: all 0.4s ease;
      top: 50%;
      transform: translateY(-50%);
    }

    .sidebar.open li .tooltip {
      display: none;
    }

    .sidebar li a {
      display: flex;
      height: 100%;
      width: 100%;
      align-items: center;
      text-decoration: none;
      background-color: var(--color-default);
      position: relative;
      transition: all .5s ease;
      z-index: 12;
    }

    .sidebar li a::after {
      content: "";
      position: absolute;
      width: 100%;
      height: 100%;
      transform: scaleX(0);
      background-color: var(--color-white);
      border-radius: 5px;
      transition: transform 0.3s ease-in-out;
      transform-origin: left;
      z-index: -2;
    }

    .sidebar li a:hover::after {
      transform: scaleX(1);
      color: var(--color-default);
    }

    .sidebar li a .link_name {
      color: var(--color-white);
      font-size: 15px;
      font-weight: 400;
      white-space: nowrap;
      pointer-events: auto;
      transition: all 0.4s ease;
      pointer-events: none;
      opacity: 0;
    }

    .sidebar li a:hover .link_name,
    .sidebar li a:hover i {
      transition: all 0.5s ease;
      color: var(--color-default);
    }

    .sidebar.open li a .link_name {
      opacity: 1;
      pointer-events: auto;
    }

    .sidebar li i {
      height: 35px;
      line-height: 35px;
      font-size: 18px;
      border-radius: 5px;
    }

    .sidebar li.profile {
      position: fixed;
      height: 60px;
      width: 78px;
      left: 0;
      bottom: -8px;
      padding: 10px 14px;
      overflow: hidden;
      transition: all .5s ease;
    }

    .sidebar.open li.profile {
      width: 250px;
    }

    .sidebar .profile .profile_details {
      display: flex;
      align-items: center;
      flex-wrap: nowrap;
    }

    .sidebar li img {
      height: 45px;
      width: 45px;
      object-fit: cover;
      border-radius: 50%;
      margin-right: 10px;
    }

    .sidebar li.profile .name,
    .sidebar li.profile .designation {
      font-size: 15px;
      font-weight: 400;
      color: var(--color-white);
      white-space: nowrap;
    }

    .sidebar li.profile .designation {
      font-size: 12px;
    }

    .sidebar .profile #log_out {
      position: absolute;
      top: 50%;
      right: 0;
      transform: translateY(-50%);
      background-color: var(--color-second);
      width: 100%;
      height: 60px;
      line-height: 60px;
      border-radius: 5px;
      cursor: pointer;
      transition: all 0.5s ease;
    }

    .sidebar.open .profile #log_out {
      width: 50px;
      background: none;
    }

    .main-content {
      flex: 1;
      margin-left: 78px;
      padding: 20px;
      transition: margin-left 0.5s ease;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .sidebar.open ~ .main-content {
      margin-left: 250px;
    }

    #myIframe {
      width: 100%;
      height: 100%;
      border: none;
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 250px;
        left: -250px;
      }

      .sidebar.open {
        left: 0;
      }

      .main-content {
        margin-left: 0;
        padding: 10px;
      }

      .sidebar.open ~ .main-content {
        margin-left: 250px;
      }
    }

    @media (max-width: 576px) {
      .sidebar {
        width: 100%;
        height: auto;
        left: -100%;
        bottom: 0;
        top: auto;
        z-index: 100;
      }

      .sidebar.open {
        left: 0;
      }

      .main-content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <div class="logo_details">
      <i class="bx bxl-audible icon"></i>
      <div class="logo_name">HANAPKITA</div>
      <i class="bx bx-menu" id="btn"></i>
    </div>
    <ul class="nav-list">
      <li>
        <a href="javascript:void(0)" onclick="changeContent('jobseekerdashboardhome.php')">
          <i class="bx bx-grid-alt"></i>
          <span class="link_name">Home</span>
        </a>
      </li>
      <li>
        <a href="javascript:void(0)" onclick="changeContent('jobseekerinbox.php')">
          <i class="bx bx-chat"></i>
          <span class="link_name">Inbox</span>
        </a>
      </li>
      <li>
        <a href="javascript:void(0)" onclick="changeContent('jobseekerhiring.php')">
          <i class="bx bx-search"></i>
          <span class="link_name">Job Hiring</span>
        </a>
      </li>
      <li>
        <a href="javascript:void(0)" onclick="changeContent('joboffer.php')">
          <i class="bx bx-news"></i>
          <span class="link_name">Job Offer</span>
        </a>
      </li>
      <li>
        <a href="javascript:void(0)" onclick="changeContent('jobseeker_feedbacks_entry.html')">
          <i class="bx bx-folder"></i>
          <span class="link_name">Feedback</span>
        </a>
      </li>
      <li>
        <a href="javascript:void(0)" onclick="changeContent('jobseeker_calendar.html')">
          <i class="bx bx-calendar"></i>
          <span class="link_name">Calendar</span>
        </a>
      </li>
      <li>
        <a href="javascript:void(0)" onclick="changeContent('profile_jobseeker.php')">
          <i class="bx bxs-user-circle"></i>
          <span class="link_name">Profile</span>
        </a>
      </li>
      <li class="profile">
        <a href="../logout.php">
          <i class="bx bx-log-out"></i>
          <span class="link_name">Logout</span>
        </a>
        <i class="bx bx-log-out" id="log_out"></i>
      </li>
    </ul>
  </div>

  <div class="main-content">
    <iframe id="myIframe" width="100%" height="100%" frameborder="0"></iframe>
  </div>

  <script>
    document.getElementById("btn").onclick = function() {
      document.querySelector(".sidebar").classList.toggle("open");
    };
  </script>
</body>
</html>
