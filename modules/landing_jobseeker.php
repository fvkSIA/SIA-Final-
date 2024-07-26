<?php
if (isset($_POST['selectedOption'])){
    $selectedOption = $_POST['selectedOption'];

    if ($selectedOption == 'EMPLOYER') {
        header("Location: ../modules/signup/termsemployer.html");
    } else if ($selectedOption == 'JOBSEEKER') {
        header("Location: ../modules/signup/termsjobseeker.html");
    }

}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HANAPKITA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        body{
            margin: 0;
            background-color: #E9F1FA;

        }

        .container {
            display: flex;
            background-color: transparent;
            width: 100%; 
            margin: 0 auto; 
            align-items: center;
        }

        .image-section {
            flex: 1;
            position: relative;
            padding-right: 20px;
        }

        .image-section img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .text-section {
            flex: 1;
            padding: 40px;
            font-family: 'Poppins', sans-serif;
            background-color: transparent;
            text-align: justify;
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
        .content {
            padding: 20px;
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 90%;
            margin: 0 auto;
            margin-top: -100px;
            text-align: center;
        }
        .content p {
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            text-align: justify;
        }
        .content .vision p {
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
        }
        h2 {
            font-weight: bold;
            font-family: 'Poppins', sans-serif;
        }

nav {
  background-color: #3D52A0;
  color: #fff;
  padding: 10px 20px;
  font-family: 'Arial', sans-serif;
}

.container-fluid {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.nav-left {
  display: flex;
  align-items: center;
  margin-left: 30px;
}

.logo {
  width: 40px;
  height: auto;
  margin-right: 10px;
}

.title {
  font-size: 1.5em;
  margin: 0;
  color: #fff;
  font-weight: bold;
}

.nav-center {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-left: 30px;
}

.nav-link {
  color: #fff;
  text-decoration: none;
  margin: 0 15px;
  font-size: 14px;
  position: relative;
}

.nav-link::after {
  content: '';
  position: absolute;
  width: 0;
  height: 2px;
  bottom: -5px;
  left: 0;
  background-color: #fff;
  transition: width 0.3s ease;
}

.nav-link:hover::after {
  width: 100%;
}

.nav-right {
  display: flex;
  align-items: center;
  margin-right: 30px;
}

.btn-link {
  padding: 8px 16px;
  text-decoration: none;
  color: #ffffff;
  background-color: transparent;
  border: 2px solid transparent;
  border-radius: 5px;
  transition: background-color 0.3s ease, color 0.3s ease;
  font-weight: bold;
  margin-left: 10px;
  font-size: 14px;
}

.btn-signin {
    color: #ffffff;
    background-color: transparent;
}

.btn-link:hover {
    background-color: #5a7ad1;
    color: #ffffff;
    border: 2px solid #000;
}

.btn-signin:hover {
    background-color: #5a7ad1;
    color: #ffffff;
    border: 2px solid #000;
}
  
        h1 {
            font-size: 80px;
            color: #3D52A0;
            margin-bottom: 20px;
            font-family: 'Poppins', sans-serif;
            font-weight: bold;
            padding-right: 50px;
        }

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    color: #333;
}

.main-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    text-align: center;
    background-color: #E9F1FA;
}

.text-content {
    max-width: 600px;
}

.text-content h1 {
    font-size: 2.5em;
    margin-bottom: 20px;
}

.text-content p {
    font-size: 1.2em;
    margin-bottom: 30px;
}

.search-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
}

.search-bar input[type="text"] {
    padding: 10px;
    font-size: 1em;
    border: 1px solid #ddd;
    border-radius: 5px;
    flex: 1;
    min-width: 200px;
}

.search-bar select {
    padding: 10px;
    font-size: 1em;
    border: 1px solid #ddd;
    border-radius: 5px;
    flex: 1;
    min-width: 150px;
}

.search-bar button {
    padding: 10px 20px;
    font-size: 1em;
    color: #fff;
    background-color: #3D52A0;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    min-width: 150px;
}

.search-bar button:hover {
    background-color: #4C82E4;
}

.image-content {
    margin-top: 20px;
}

.image-content img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
}

@media (min-width: 768px) {
    .main-content {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }

    .text-content {
        text-align: left;
    }

    .image-content {
        margin-top: 0;
    }

    .search-bar {
        flex-wrap: nowrap;
    }
}

@media (max-width: 768px) {
    .container-fluid {
        flex-direction: column;
        align-items: center;
    }

    .nav-left, .nav-center, .nav-right {
        width: 100%;
        justify-content: center;
        margin-bottom: 10px;
    }

    .nav-center {
        flex-wrap: wrap;
    }

    .link, .btn-link {
        margin: 5px;
    }
}

</style>

</head>

<body>
    
      <header>
      <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <div class="nav-left">
            <img class="logo" src="hanapKITA.png">
            <h2 class="title">HANAPKITA</h2>
            </div>
            <div class="nav-center">
            <a class="nav-link" href="./login/login_seeker.php">HOME</a>
            <a class="nav-link" href="#top">JOB SEARCH</a>
            <a class="nav-link" href="#middle">WORKER</a>
            <a class="nav-link" href="#bottom">ABOUT</a>
            </div>
            <div class="nav-right">
            <div class="button-container">
            <button class="btn-link" type="button" onclick="selectOption('JOBSEEKER', this)">Create Account</button>
            </div>
            <form id="registrationForm" method="POST" action="">
            <input type="hidden" id="selectedOption" name="selectedOption">
            </form>
            <a class="btn-link btn-signin" href="./login/login_seeker.php">Sign In</a>
            </div>
        </div>
    </nav>
</header>

<div class="main-content">
        <div class="text-content">
            <h1>Search, Find,<br>and Apply!</h1>
            <p>HanapKITA aims to revolutionize the job search experience by providing an intuitive, efficient, and comprehensive platform for job seekers and employers alike.</p>
            <div class="search-bar">
                <input type="text" placeholder="Job title or Keyword">
                <select>
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
                    <option value="pateros">Pateros</option>
                </select>
                <button onclick="window.location.href='./login/login_seeker.php'">Find Now!</button>
            </div>
        </div>
        <div class="image-content">
            <img src="pst.png" alt="Job Search Image">
        </div>
    </div>
</div>

  <div class="container my-3">
        
        <div class="image-section">
            <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="5000">
                    <img src="1.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item" data-bs-interval="5000">
                    <img src="2.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item" data-bs-interval="5000">
                    <img src="3.jpg" class="d-block w-100" alt="...">
                    </div>
                </div>
            </div>
        </div>
        <div class="text-section">
            <p>HanapKITA aims to revolutionize the job search experience by providing an intuitive, efficient, and comprehensive platform for job seekers and employers alike. </p> 
            <p>Our primary goal is to bridge the gap between talent and opportunity, ensuring that individuals can find job positions that truly match their skills, interests, and career aspirations. We understand the complexities of the job market and strive to simplify this process by offering a user-friendly interface, advanced search functionalities, and a wide array of job listings across various industries.</p>
            <p>Moreover, HanapKITA seeks to foster a community where employers and job seekers can connect seamlessly and effectively. We prioritize creating a transparent and supportive environment that allows employers to find the right candidates quickly and efficiently. By leveraging technology and a deep understanding of the job market, HanapKITA aspires to become the go-to platform for anyone looking to advance their career or find the perfect candidate for their organization, thereby contributing to a more dynamic and thriving workforce.</p>

          </div>
    </div>

    <br><br>
    <div id="middle"></div>   

    </div>
<br>
<iframe src="../slide/index.html" frameborder="0" style="width: 100%; height: 540px;"></iframe>


    
    <div id="bottom" style="padding: 30px; background-color: #f8f9fa; border: 1px solid #ccc; border-radius: 5px; width: 90%; margin: 0 auto; text-align: center;">

        <h2><b>OUR MISSION </b></h2>
        <p style="font-size: 16px;">Our mission at HanapKITA is to empower skilled and unskilled workers by providing a comprehensive, user-friendly web-based platform that connects them with employment opportunities. We aim to bridge the gap between job seekers and employers by facilitating efficient, transparent, and reliable job matching services, ultimately contributing to the economic growth and development of our communities.</p>
        <label class="my-5"> </label>
        <h2><b>OUR VISION </b></h2>
        <p style="font-size: 16px;">Our vision is to become the leading job search platform in the region, known for its commitment to inclusivity, innovation, and excellence. We aspire to transform the job-seeking experience for skilled and unskilled workers, ensuring that everyone has equal access to opportunities that allow them to thrive and succeed in their careers. Through continuous improvement and community engagement, we aim to build a future where employment is accessible to all, and every individual can realize their full potential.</p>
    
    </div> 
    

<?php include '../modules/landing_footer.html'; ?>

<script>
    function selectOption(option, button) {
        document.getElementById('selectedOption').value = option;
            
        var buttons = document.querySelectorAll('.button-container button');
        buttons.forEach(function(btn) {
        btn.classList.remove('selected');
        });

        button.classList.add('selected');

        document.getElementById('registrationForm').submit();
    }
    const carousel = new bootstrap.Carousel('#myCarousel')
</script>
</body>
</html>