<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HANAPKITA</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your external stylesheet -->


    <style>
        body{
            margin: 0;
            background-color: #E9F1FA; /* Updated background color */

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
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 100%; /* Adjusted to span full width */
    max-width: 1200px; /* Optional: Set a max-width for large screens */
    margin: 0 auto; /* Centering the container horizontally */
}




.image-section {
    flex: 1; /* Adjusted width to make it flexible */
    position: relative; /* Needed for absolute positioning within */
}

.image-section img {
    width: 100%; /* Ensure the image takes up the entire container */
    height: auto; /* Maintain aspect ratio */
    border-radius: 5px;
}

.text-section {
    flex: 1; /* Adjusted width to make it flexible */
    padding: 40px; /* Adjusted padding for better spacing */
    font-family: 'Poppins', sans-serif;
    background-color: #f8f9fa;
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
    background-color: white;
    padding: 20px;
    border-radius: 20px;
    width: 50%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    margin: 0 auto; /* This centers the container horizontally */
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



        
        .card {
  position: relative;
  width: 250px; /* Adjusted width */
  background: #fafafa;
  box-shadow: 0 2px 7px #000;
  font-family: 'Montserrat', sans-serif;
  margin: 20px auto;
  padding: 15px;
  height: 400px; /* Adjusted height */
  margin-top: 60px;
  margin-left: -325px; /* Added margin to the left */
  padding-bottom: 20px;
  border-radius: 10px;
  }
  .item1 img {
  max-width: 100%;
  height: auto;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  .catagory {
  color: #000;
  font-size: 25px;
  font-style: italic;
  font-family: 'Montserrat', sans-serif;
  text-align: center;
  margin-top: 15px;
  }
  .price-1 {
  color: black;
  font-family: 'Montserrat', sans-serif;
  font-style: italic;
  margin-top: 10px;
  text-align: center;
  }
  .button-1 {
  border: none;
  padding: 10px 30px; /* Increased padding for a larger button */
  border-radius: 5px;
  background-color: #3D52A0;
  color: white;
  font-weight: bold;
  font-size: 15px;
  cursor: pointer;
  display: block;
  margin: 0 auto; /* Centering the button */
  margin-top: 15px;
  }
  .button-1:hover {
  background-color: #0000FF;
  }

  .card-1 {
  position: relative;
  width: 250px; /* Adjusted width */
  background: #fafafa;
  box-shadow: 0 2px 7px #000;
  font-family: 'Montserrat', sans-serif;
  margin: 20px auto;
  padding: 15px;
  height: 400px; /* Adjusted height */
  margin-top: -410px;
  margin-left: 290px; /* Added margin to the left */
  padding-bottom: 20px;
  border-radius: 10px;
  }
  .item2 img {
  max-width: 100%;
  height: 250px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  .catagory-1 {
  color: #000;
  font-size: 25px;
  font-style: italic;
  font-family: 'Montserrat', sans-serif;
  text-align: center;
  margin-top: 15px;
  }
  .price-2 {
  color: black;
  font-family: 'Montserrat', sans-serif;
  font-style: italic;
  margin-top: 10px;
  text-align: center;
  }
  .button-2 {
  border: none;
  padding: 10px 30px; /* Increased padding for a larger button */
  border-radius: 5px;
  background-color: #3D52A0;
  color: white;
  font-weight: bold;
  font-size: 15px;
  cursor: pointer;
  display: block;
  margin: 0 auto; /* Centering the button */
  margin-top: 15px;
  }
  .button-2:hover {
  background-color: #0000FF;
  }

  .card-2 {
  position: relative;
  width: 250px; /* Adjusted width */
  background: #fafafa;
  box-shadow: 0 2px 7px #000;
  font-family: 'Montserrat', sans-serif;
  margin: 20px auto;
  padding: 15px;
  height: 400px; /* Adjusted height */
  margin-top: -410px;
  margin-left: 290px; /* Added margin to the left */
  padding-bottom: 20px;
  border-radius: 10px;
  }
  .item3 img {
  max-width: 100%;
  height: 250px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  .catagory-3 {
  color: #000;
  font-size: 25px;
  font-style: italic;
  font-family: 'Montserrat', sans-serif;
  text-align: center;
  margin-top: 15px;
  }
  .price-3 {
  color: black;
  font-family: 'Montserrat', sans-serif;
  font-style: italic;
  margin-top: 10px;
  text-align: center;
  }
  .button-3 {
  border: none;
  padding: 10px 30px; /* Increased padding for a larger button */
  border-radius: 5px;
  background-color: #3D52A0;
  color: white;
  font-weight: bold;
  font-size: 15px;
  cursor: pointer;
  display: block;
  margin: 0 auto; /* Centering the button */
  margin-top: -3px;
  }
  .button-3:hover {
  background-color: #0000FF;
  }


  .card-3 {
  position: relative;
  width: 250px; /* Adjusted width */
  background: #fafafa;
  box-shadow: 0 2px 7px #000;
  font-family: 'Montserrat', sans-serif;
  margin: 20px auto;
  padding: 15px;
  height: 400px; /* Adjusted height */
  margin-top: -412px;
  margin-left: 285px; /* Added margin to the left */
  padding-bottom: 20px;
  border-radius: 10px;
  }
  .item4 img {
  max-width: 100%;
  height: 250px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  .catagory-4 {
  color: #000;
  font-size: 25px;
  font-style: italic;
  font-family: 'Montserrat', sans-serif;
  text-align: center;
  margin-top: 15px;
  }
  .price-4 {
  color: black;
  font-family: 'Montserrat', sans-serif;
  font-style: italic;
  margin-top: 10px;
  text-align: center;
  }
  .button-4 {
  border: none;
  padding: 10px 30px; /* Increased padding for a larger button */
  border-radius: 5px;
  background-color: #3D52A0;
  color: white;
  font-weight: bold;
  font-size: 15px;
  cursor: pointer;
  display: block;
  margin: 0 auto; /* Centering the button */
  margin-top: 15px;
  }
  .button-4:hover {
  background-color: #0000FF;
  }








  .card-5 {
  width: 100%; /* Adjusted width */
  background: #fafafa;
  box-shadow: 0 2px 7px #000;
  font-family: 'Montserrat', sans-serif;
  }
  .item5 img { 
  max-width: 50%;
  height: 360px;
  height: 350px;
  margin-left: -590px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  .text-1 {
  color: #000;
  font-size: 50px;
  font-style: italic;
  font-family: 'Montserrat', sans-serif;
  text-align: center;
  margin-left: 140px;
  margin-top: -340px;

  }
  .text-2 {
  color: black;
  font-family: 'Montserrat', sans-serif;
  font-size: 23px;
  margin-left: 620px;
  font-style: normal;
  margin-top: 25px;
  text-align: center;
  }



  .item6 img {
  max-width: 42%;
  height: 360px;
  margin-left: 693px;
  border-radius: 10px;
  margin-top: 35px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .text-3 {
  color: #000;
  font-size: 50px;
  font-style: italic;
  font-family: 'Montserrat', sans-serif;
  text-align: center;
  margin-right: 1080px;
  margin-top: -340px;

  }
  .text-4 {
  color: black;
  font-family: 'Montserrat', sans-serif;
  font-size: 23px;
  margin-right: 500px;    
  font-style: normal;
  margin-top: 20px;
  text-align: center;
  }

  .footer {
    width: 100%; /* Adjusted to span full width */
    background-color: #f8f9fa;
    font-family: Arial, sans-serif;
    margin-top: 70px;
    border-radius: 5px;
}

.footer-section {
    display: flex;
    justify-content: space-around;
    width: 100%; /* Changed from max-width to width */
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
    color: #000;
}
.footer-column h4 {
    font-weight: bold;
    margin-bottom: 10px;
}
.footer-bottom {
    text-align: center;
    padding-top: 10px;
    border-top: 1px solid #ccc;
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

nav {
    background-color: #4661a8;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    font-family: 'Arial', sans-serif; /* dagdag na font family */
}

.logo-container {
    display: flex;
    align-items: center;
}

.logo {
    width: 40px; /* Adjust size as needed */
    height: auto;
    margin-right: 10px;
}

.title {
    font-size: 1.5em;
    margin: 0;
}

.links-container {
    display: flex;
    align-items: center;
}

.link, .btn-link {
    color: #fff;
    text-decoration: none;
    margin: 0 10px;
}
.btn-container {
display: inline-block;
padding: 10px 20px;
background-color: #f0f0f0; /* default background color */
border: 1px solid #ccc;
border-radius: 5px;
transition: background-color 0.3s ease; /* smooth background color transition */
}

.btn-container:hover {
background-color: #e0e0e0; /* background color when hovered */
}

.btn-link {
display: inline-block;
padding: 10px 20px;
text-decoration: none;
color: #000000;
background-color: #007bff;
border: 2px solid #007bff; 
border-radius: 5px;
transition: background-color 0.3s ease, border-color 0.3s ease; 
font-weight: bold;
}

.btn-link:hover {
background-color: #a0ceff;
border-color: #000101; 
}

@media (max-width: 768px) {
    nav {
        flex-direction: column;
        align-items: flex-start;
    }

    .logo-container {
        margin-bottom: 10px;
    }

    .links-container {
        flex-direction: column;
        align-items: flex-start;
    }

    .link, .btn-link {
        margin: 5px 0;
    }
}
</style>

    </style>
</head>

<body>
    
      
      <!-- Main Content -->
      <header>
        <nav>
        <div class="logo-container">
            <img class="logo"
            src="../hanapKITA.png">
            <h2 class="title"> HANAPKITA</h2>
        </div>
            <div class="links-container">
                <a class="link" href="landing.html"> HOME </a>
                <a class="link" href="#top"> JOB SEARCH </a>
                <a class="link" href="employerworkerwelder1.html"> WORKER </a>
                <a class="link" href="#middle"> JOB HIRING </a>  
                <a class="link" href="#bottom"> ABOUT </a>
                <a class="btn-link" href="./login/login.html"> SIGN IN </a>
            </div>
        </nav>
      </header>

    <div  style="display: flex; flex-wrap: wrap; background-color: rgba(255, 255, 255, 0.529); margin: 0 auto; width: 100%; padding-bottom: 20px;">
        <div style="flex: 1 1 60%; padding: 20px; box-sizing: border-box; text-align: center;">
            <h1 style="font-family: Georgia, serif; font-size: 2.5em; font-weight: bold; color: #333; padding-top: 30px;">
                Search, Find, and Apply!
            </h1>
            <div style="display: flex; justify-content: center; align-items: center; gap: 10px; background-color: #f0f0f0; padding: 10px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                <input type="text" style="flex: 1 1 auto; padding: 8px; width: 40px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;" placeholder="Enter Types of Job: Driver">
                <select style="flex: 1 1 auto; padding: 8px; width: 40px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; background-color: white;">
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
                <button style="flex: 1 1 auto; padding: 8px 20px; width: 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; font-size: 14px; cursor: pointer;" onclick="window.location.href='./login/login.html'">
                    Find Now!
                </button>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="image-section">
            <img src="../pic1.jpg">
        </div>
        <div class="text-section">
            <p>HanapKITA aims to revolutionize the job search experience by providing an intuitive, efficient, and comprehensive platform for job seekers and employers alike. </p> 
            <p>Our primary goal is to bridge the gap between talent and opportunity, ensuring that individuals can find job positions that truly match their skills, interests, and career aspirations. We understand the complexities of the job market and strive to simplify this process by offering a user-friendly interface, advanced search functionalities, and a wide array of job listings across various industries.</p>
            <p>Moreover, HanapKITA seeks to foster a community where employers and job seekers can connect seamlessly and effectively. We prioritize creating a transparent and supportive environment that allows employers to find the right candidates quickly and efficiently. By leveraging technology and a deep understanding of the job market, HanapKITA aspires to become the go-to platform for anyone looking to advance their career or find the perfect candidate for their organization, thereby contributing to a more dynamic and thriving workforce.</p>

          </div>
    </div>

    <br><br>
    <div id="middle"></div>
    <div class="top-job-seekers">
        <h2>Top Job Seekers</h2>
        <div class="job-seeker">
            <img src="asim.jpg" alt="Job Seeker">
            <div class="job-seeker-info">
                <h3>GALE KAREN PARTOS</h3>
                <p>RATINGS: <span class="ratings">⭐⭐⭐⭐⭐</span></p>
            </div>
            <div class="rank">
                RANK 1
                <span>SEE MORE DETAILS</span>
            </div>
        </div>
        <div class="job-seeker">
            <img src="bersoto.jpg" alt="Job Seeker">
            <div class="job-seeker-info">
                <h3>JOSEPH CARLO BERSOTO</h3>
                <p>RATINGS: <span class="ratings">⭐⭐⭐⭐⭐</span></p>
            </div>
            <div class="rank">
                RANK 2
                <span>SEE MORE DETAILS</span>
            </div>
        </div>
        <div class="navigation">
            <span class="active"></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>

    </div>
<br>
<iframe src="../slide/index.html" frameborder="0" style="width: 100%; height: 540px;"></iframe>


    
    <div style="padding: 20px; background-color: #f8f9fa; border: 1px solid #ccc; border-radius: 5px; width: 90%; margin: 0 auto; text-align: center;">

        <h2><b>MISSION </b></h2>
        <p style="font-size: 20px;">Our mission at HanapKITA is to empower skilled and unskilled workers by providing a comprehensive, user-friendly web-based platform that connects them with employment opportunities. We aim to bridge the gap between job seekers and employers by facilitating efficient, transparent, and reliable job matching services, ultimately contributing to the economic growth and development of our communities.</p>
        
        <h2><b>VISION </b></h2>
        <p style="font-size: 16px;">Our vision is to become the leading job search platform in the region, known for its commitment to inclusivity, innovation, and excellence. We aspire to transform the job-seeking experience for skilled and unskilled workers, ensuring that everyone has equal access to opportunities that allow them to thrive and succeed in their careers. Through continuous improvement and community engagement, we aim to build a future where employment is accessible to all, and every individual can realize their full potential.</p>
    
    </div>
    
    
    
                        

                  



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

</body>
</html>