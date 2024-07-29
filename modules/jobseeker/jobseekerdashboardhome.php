<?php 

session_start();

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Employer Dashboard</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <style>

@import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');



    .home-section {
      position: relative;
      background-color: var(--color-body);
      min-height: 100vh;
      top: 0;
      width: calc(100% - 78px);
      transition: all .5s ease;
      z-index: 2;
      margin: 0 auto;
    }
    

.home-section .text{
  display: inline-block;
  color:var(--color-default);
  font-size: 25px;
  font-weight: 500;
  margin: 18px;
}




.text1 {
  display: inline-block;
  margin: 0;
  color: #3D52A0;
  font-size: 120px;
  font-family: 'Poppins', sans-serif;
  position: absolute;
  top: -600px;
  left: 70px; /* Adjusted value to position the title relative to the logo */
  }
  .text2 {
  display: inline-block;
  margin: 0;
  color: #3D52A0;
  font-size: 15px;
  font-family: 'Poppins', sans-serif;
  font-style: italic;
  position: absolute;
  top: -295px;
  left: 90px; /* Adjusted value to position the title relative to the logo */
  }
  .landpic {
  position: absolute;
  top: -680px; /* Adjust as needed */
  left: 670px; /* Adjust to move it to the right */
  }
  .pic1 {
  height: 650px; /* Adjust height as needed */
  width: 580px; /* Adjust width as needed */
  }

  @media (max-width: 768px) {
    /* Adjustments for smaller screens (tablets and phones) */
    .text1 {
        font-size: 2em; /* Decrease font size */
    }

    .text2 {
        font-size: 0.8em; /* Decrease font size */
    }
}
  .right-half {
  position: absolute;
  top: 0px;
  right: 0;
  width: 40%; /* Adjust as needed */
  height: 100%;
  background-color: #EDE8F5; /* Set background color */
  }
  .search-bar {
  background-color: #7091E6; /* Medium blue background */
  padding: 20px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  position: absolute;
  top: -180px; /* Adjust this value to position the search bar */
  left: 120px; /* Adjust this value to position the search bar */
  gap: 15px;
  }
  .search-bar input, .search-bar select, .search-bar button {
  border: none;
  border-radius: 10px;
  padding: 13px;
  margin-right: 25px;
  font-size: 20px;
  }
  .search-bar input:focus, .search-bar select:focus, .search-bar button:focus {
  outline: none;
  }
  .search-bar input {
  flex: 1;
  }
  .search-bar select {
  flex: 1.5; /* Adjust flex value to expand the select box */
  }
  .search-bar button {
  background-color: #3D52A0; /* Dark blue button */
  color: white;
  cursor: pointer;
  }
  .search-bar button:hover {
  background-color: #2d3d82; /* Slightly darker blue on hover */
  }

  .container {
    display: flex;
    background-color: rgba(255, 255, 255);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
    margin-top: 0px; /* Adjusted margin to move the content further down */
    width: 100%; /* Make the container span the entire width */
    margin: 0 auto; /* Center align horizontally */
    flex-wrap: nowrap; /* Prevent wrapping to keep items in a row */
}


.image-section {
    flex: 1; /* Adjusted width to make it flexible */
    position: relative; /* Needed for absolute positioning within */
}

.image-section img {
    width: 95%; /* Ensure the image takes up the entire container */
    height: 500px ; /* Maintain aspect ratio */
    border-radius: 5px;
    position: absolute; /* Position the image absolutely within its container */
    top: 0; /* Adjust as needed */
    left: 0; /* Adjust as needed */
    right: 0; /* Adjust as needed */
    bottom: 0; /* Adjust as needed */
    margin: auto; /* Center the image within its container */
}

.text-section {
    flex: 1; /* Adjusted width to make it flexible */
    padding: 40px; /* Adjusted padding for better spacing */
    font-family: 'Poppins', sans-serif;
    background-color: #f8f9fa00;
}

  
  .text-section h1 {
    margin-top: 0;
    font-size: 2em;
    color: #333;
    font-weight: 600;
  }
  .text-section p {
    font-size: 1em;
    color: #555;
    line-height: 1.6;
    margin-bottom: 20px;
  }
  .text-section p:last-child {
    margin-bottom: 0;
  }

  .top-job-seekers {
    background-color: rgba(255, 255, 255, 0);
    padding: 10px;
    border-radius: 20px;
    width: 600px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 25px auto; /* Center horizontally with auto margins */
    text-align: center; /* Center text content inside the div */
    height: 320px; /* Adjusted height */
}


        .top-job-seekers h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #3D52A0;
        }
        .job-seeker {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #ADBBDA;
            border-radius: 20px;
            padding: 15px 20px;
            margin-bottom: 10px;
        }
        .job-seeker img {
            border-radius: 50%;
            width: 60px;
            height: 70px;
        }
        .job-seeker-info {
            flex: 1;
            margin-left: 15px;
            text-align: left;
        }
        .job-seeker-info h3 {
            margin: 0;
            font-size: 16px;
        }
        .job-seeker-info p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }
        .job-seeker-info .ratings {
            color: #FFD700;
        }
        .rank {
            font-size: 18px;
            color: #3D52A0;
            text-align: right;
        }
        .rank span {
            display: block;
            font-size: 14px;
            color: #555;
        }
        .navigation {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
        .navigation span {
            width: 10px;
            height: 10px;
            margin: 0 5px;
            background-color: #ccc;
            border-radius: 50%;
            display: inline-block;
        }
        .navigation .active {
            background-color: #3D52A0;
        }
  </style>
</head>
<body>

  <section class="home-section">
    
    <div class="container" style="display: flex; flex-wrap: wrap; background-color: rgba(255, 255, 255, 0.529); margin: 0 auto; max-width: 100%; padding-bottom: 20px; border: 1px solid;">
        <div style="flex: 1 1 60%; padding: 20px; box-sizing: border-box; text-align: center;">
            <h1 style="font-family: 'Poppins', sans-serif; font-size: 3.5em; font-weight: bold; color: #004f83; padding-top: 30px;">
                Search, Find, and Apply!
            </h1>
            <form action="jobseekerhiring.php" method="post">
                <div style="display: flex; justify-content: center; align-items: center; gap: 10px; background-color: #f0f0f0; padding: 10px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                    <select style="flex: 1 1 auto; padding: 8px; width: 40px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; background-color: white;" name="job_type">
                        <option>Select type of worker</option>
                        <option>Welder</option>
                        <option>Plumber</option>
                        <option>Lineman</option>
                        <option>Security Guard</option>
                        <option>Electrician</option>
                        <option>Carpenter</option>
                        <option>Driver</option>
                        <option>Refrigarator and Aircon Service</option>
                        <option>Food Service</option>
                        <option>Laundry Staff</option>
                        <option>Factory Worker</option>
                        <option>Housekeeper</option>
                        <option>Janitor</option>
                        <option>Construction Worker</option>
                    </select>          
                    <select style="flex: 1 1 auto; padding: 8px; width: 40px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; background-color: white;" name="location">
                        <option value="">Location</option>
                        <option value="Manila">Manila</option>
                        <option value="Caloocan">Caloocan</option>
                        <option value="Valenzuela">Valenzuela</option>
                        <option value="Pasay">Pasay</option>
                        <option value="Makati">Makati</option>
                        <option value="Quezon City">Quezon City</option>
                        <option value="Navotas">Navotas</option>
                        <option value="Las Piñas">Las Piñas</option>
                        <option value="Malabon">Malabon</option>
                        <option value="Mandaluyong">Mandaluyong</option>
                        <option value="Marikina">Marikina</option>
                        <option value="Muntinlupa">Muntinlupa</option>
                        <option value="Parañaque">Parañaque</option>
                        <option value="Pasig">Pasig</option>
                        <option value="San Juan">San Juan</option>
                        <option value="Taguig">Taguig</option>
                        <option value="Valenzuela">Valenzuela</option>
                        <option value="Pateros">Pateros</option>
                    </select>
                    <button style="flex: 1 1 auto; padding: 8px 20px; width: 20px; background-color: #004f83; color: white; border: none; border-radius: 4px; font-size: 14px; cursor: pointer;" type="submit">
                        Find Now!
                    </button>
                </div>
            </form>
            
        </div>
    </div>
    <br>
    
    <div class="container" style="border: 1px solid;">
        <div class="image-section">
            <img src="pic1.jpg">
        </div>
        <div class="text-section">
            <p>HanapKITA aims to revolutionize the job search experience by providing an intuitive, efficient, and comprehensive platform for job seekers and employers alike. </p> 
            <p>Our primary goal is to bridge the gap between talent and opportunity, ensuring that individuals can find job positions that truly match their skills, interests, and career aspirations. We understand the complexities of the job market and strive to simplify this process by offering a user-friendly interface, advanced search functionalities, and a wide array of job listings across various industries.</p>
            <p>Moreover, HanapKITA seeks to foster a community where employers and job seekers can connect seamlessly and effectively. We prioritize creating a transparent and supportive environment that allows employers to find the right candidates quickly and efficiently. By leveraging technology and a deep understanding of the job market, HanapKITA aspires to become the go-to platform for anyone looking to advance their career or find the perfect candidate for their organization, thereby contributing to a more dynamic and thriving workforce.</p>

          </div>
    </div>
    <br>
    <div class="container">
        <?php include '../metromanila.php' ?>
    </div>

          <?php include '../employer/em_footer.html'; ?>


  </section>
  <!-- Scripts -->
  <script src="script.js"></script>
</body>
</html>
