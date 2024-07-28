<?php
if (isset($_POST['selectedOption'])){
    $selectedOption = $_POST['selectedOption'];

    if ($selectedOption == 'EMPLOYER') {
        header("Location: ../signup/termsemployer.html");
    } else if ($selectedOption == 'JOBSEEKER') {
        header("Location: ../signup/termsjobseeker.html");
    }

}
?>

<!DOCTYPE html>
<html lang>
  <head>
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous">
    </script>  
    <script>
        function goBack() {
        window.history.back();
      }
      document.getElementById('sign-up-btn').addEventListener('click', function() {
        window.location.href = 'signup.html';
      });
    </script>


  <style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
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
.title1 {
    font-family: 'Roboto', sans-serif; 
    font-size: 2.5rem; 
    font-weight: bold; 
    color: #3D52A0; 
    text-transform: uppercase;
    letter-spacing: 2px; 
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
    text-align: center;
    margin: 2px 0;
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
    body,
    input {
      font-family: "Poppins", sans-serif;
    }

    .container {
      position: relative;
      width: 100%;
      background-color: #fff;
      min-height: 100vh;
      overflow: hidden;
    }

    .forms-container {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
    }

    .signin-signup {
      position: absolute;
      top: 50%;
      transform: translate(-50%, -50%);
      left: 75%;
      width: 50%;
      transition: 1s 0.7s ease-in-out;
      display: grid;
      grid-template-columns: 1fr;
      z-index: 5;
    }

    form {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      padding: 0rem 5rem;
      transition: all 0.2s 0.7s;
      overflow: hidden;
      grid-column: 1 / 2;
      grid-row: 1 / 2;
    }

    form.sign-up-form {
      opacity: 0;
      z-index: 1;
    }

    form.sign-in-form {
      z-index: 2;
    }

    .title {
        font-size: 1.5em;
        margin: 0;
        color: #fff;
        font-weight: bold;
      }

    .input-field {
      max-width: 380px;
      width: 100%;
      background-color: #f0f0f0;
      margin: 10px 0;
      height: 55px;
      border-radius: 55px;
      display: grid;
      grid-template-columns: 15% 85%;
      padding: 0 0.4rem;
      position: relative;
    }

    .input-field i {
      text-align: center;
      line-height: 55px;
      color: #acacac;
      transition: 0.5s;
      font-size: 1.1rem;
    }

    .input-field input {
      background: none;
      outline: none;
      border: none;
      line-height: 1;
      font-weight: 600;
      font-size: 1.1rem;
      color: #333;
    }

    .input-field input::placeholder {
      color: #aaa;
      font-weight: 500;
    }

    .social-text {
      padding: 0.7rem 0;
      font-size: 1rem;
      margin-left: 208px; /* Adjust the margin to move it slightly to the left */
    }

    .social-text a {
      color: #333;
    }

    .social-text a:hover {
      color: #931717;
    }

    .social-media {
      display: flex;
      justify-content: center;
    }

    .social-icon {
      height: 46px;
      width: 46px;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0 0.45rem;
      color: #333;
      border-radius: 50%;
      border: 1px solid #333;
      text-decoration: none;
      font-size: 1.1rem;
      transition: 0.3s;
    }

    .social-icon:hover {
      color: #931717;
      border-color: #931717;
    }

    .btn {
      width: 170px;
      background-color: #3D52A0;
      border: none;
      outline: none;
      height: 49px;
      font-size: 20px;
      border-radius: 49px;
      color: #fff;
      text-transform: uppercase;
      font-weight: 600;
      margin: 10px 0;
      cursor: pointer;
      transition: 0.5s;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
    }

    .btn:hover {
      background-color: #4C82E4;
    }
    .panels-container {
      position: absolute;
      height: 100%;
      width: 100%;
      top: 0;
      left: 0;
      display: grid;
      grid-template-columns: repeat(2, 1fr);
    }

    .container:before {
      content: "";
      position: absolute;
      height: 2000px;
      width: 2000px;
      top: -10%;
      right: 48%;
      transform: translateY(-50%);
      background-image: linear-gradient(-45deg, #3D52A0 0%, #B4D4FF 100%);
      transition: 1.8s ease-in-out;
      border-radius: 50%;
      z-index: 6;
    }

    .image {
      width: 97%;
      margin-top: 200px; /* Adjust the margin-top value to move it down */
      margin-right: 20px;
    }

    .panel {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      justify-content: space-around;
      text-align: center;
      z-index: 6;
    }

    .left-panel {
      pointer-events: all;
      padding: 3rem 17% 2rem 12%;
    }

    .right-panel {
      pointer-events: none;
      padding: 3rem 12% 2rem 17%;
    }

    .panel .content {
      color: #fff;
      margin-top: 20px; /* Adjust the margin-top as needed to move it up */
    }

    .panel h3 {
      font-weight: bold;
      line-height: 1;
      font-size: 1.5rem;
      text-align: center;
    }

    .panel p {
      font-size: 0.95rem;
      padding: 0.7rem 0;
    }
    .btn.transparent {
      margin-top: 3px;
      background: none;
      border: 2px solid #fff;
      margin-left: 160px;
      width: 130px;
      height: 41px;
      font-weight: 600;
      font-size: 0.8rem;      
    }
    .right-panel .image,
    .right-panel .content {
      transform: translateX(800px);
    }
    .btn.prev {
      margin-top: -220px;
      background: none;
      border: 2px solid #fff;
      margin-left: -60px;
      width: 40px; /* Adjust width as needed */
      height: 40px; /* Adjust height as needed */
      font-size: 1.2rem; /* Adjust font size as needed */
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      border-radius: 50%;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn.prev:hover {
      background-color: #3D52A0;
    }
    @media (max-width: 870px) {
      .container {
        min-height: 800px;
        height: 100vh;
      }
      .signin-signup {
        width: 100%;
        top: 95%;
        transform: translate(-50%, -100%);
        transition: 1s 0.8s ease-in-out;
      }

      .signin-signup,
      .container.sign-up-mode .signin-signup {
        left: 50%;
      }

      .panels-container {
        grid-template-columns: 1fr;
        grid-template-rows: 1fr 2fr 1fr;
      }

      .panel {
        flex-direction: row;
        justify-content: space-around;
        align-items: center;
        padding: 2.5rem 8%;
        grid-column: 1 / 2;
      }

      .right-panel {
        grid-row: 3 / 4;
      }

      .left-panel {
        grid-row: 1 / 2;
      }

      .container:before {
        width: 1500px;
        height: 1500px;
        transform: translateX(-50%);
        left: 30%;
        bottom: 68%;
        right: initial;
        top: initial;
        transition: 2s ease-in-out;
      }

      .container.sign-up-mode:before {
        transform: translate(-50%, 100%);
        bottom: 32%;
        left: 50%;
      }

      .container.sign-up-mode .left-panel .image,
      .container.sign-up-mode .left-panel .content {
        transform: translateY(-300px);
      }

      .container.sign-up-mode .right-panel .image,
      .container.sign-up-mode .right-panel .content {
        transform: translateY(0px);
      }

      .right-panel .image,
      .right-panel .content {
        transform: translateY(300px);
      }

      .container.sign-up-mode .signin-signup {
        top: 5%;
        transform: translate(-50%, 0);
      }
    }

    @media (max-width: 570px) {
      form {
        padding: 0 1.5rem;
      }

      .image {
        display: none;
      }
      .panel .content {
        padding: 0.5rem 1rem;
      }
      .container {
        padding: 1.5rem;
      }

      .container:before {
        bottom: 72%;
        left: 50%;
      }

      .container.sign-up-mode:before {
        bottom: 28%;
        left: 50%;
      }
    }
    .input-field {
  max-width: 380px;
  width: 100%;
  background-color: #f0f0f0;
  margin: 10px 0;
  height: 55px;
  border-radius: 55px;
  display: grid;
  grid-template-columns: 15% 70% 15%;
  padding: 0 0.4rem;
  position: relative;
}

.input-field i {
  text-align: center;
  line-height: 55px;
  color: #acacac;
  transition: 0.5s;
  font-size: 1.1rem;
}

.input-field input {
  background: none;
  outline: none;
  border: none;
  line-height: 1;
  font-weight: 600;
  font-size: 1.1rem;
  color: #333;
}

.input-field input::placeholder {
  color: #aaa;
  font-weight: 500;
}
  </style>


    <title>Login</title>
     <!-- Main Content -->
     <header>
      <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <div class="nav-left">
            <img class="logo" src="hanapKITA.png">
            <h2 class="title">HANAPKITA</h2>
            </div>
            <div class="nav-center">
            <a class="nav-link" href="../landing_jobseeker.php">HOME</a>
            <a class="nav-link" href="#top">JOB SEARCH</a>
            <a class="nav-link" href="#middle">WORKER</a>
            <a class="nav-link" href="#bottom">ABOUT</a>
            </div>
            <div class="nav-right">
            <a class="btn-link btn-signin" href="../landing_employer.php" style="font-size: 15px;">Are you an employer?</a>
            </div>
        </div>
    </nav>
</header>
  </head>
  <body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <form action="login_j.php" method="post" class="sign-in-form">
            <h2 class="title1">Sign in</h2>
            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input type="text" placeholder="Email" name="email" />
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" placeholder="Password" name="password" id="password"/>
              <i class="fas fa-eye" id="togglePassword" style="cursor: pointer;"></i>
            </div>
            <div class="social-text">
              <a href="enteremail_seeker.html">Forgot Password?</a> 
            </div>
            <button type="submit" class="btn solid">Login</button>
          </form>
        </div>
      </div>
  
      <div class="panels-container">
        <div class="panel left-panel">
          <div class="content">
            <h3>New here ?</h3>
            <p>
              Welcome to our community! Join us now by creating an account to unlock a world of exciting opportunities and exclusive features tailored just for you.

            </p>
            <div class="button-container">
            <button class="btn transparent" type="button" onclick="selectOption('JOBSEEKER', this)"><b>Sign up</b></button>
            </div>
            <form id="registrationForm" method="POST" action="">
            <input type="hidden" id="selectedOption" name="selectedOption">
            </form>


            <button onclick="goBack()" class="btn prev"> <i class="fas fa-arrow-left"></i></button>

          </div>
          <img src="img/log.svg" class="image" alt="" />
        </div>
      </div>
    </div>
    <button onclick="goBack()" class="btn prev"> <i class="fas fa-arrow-left"></i></button>

    <script src="app.js"></script>
    <script>
        function selectOption(option, button) {
            document.getElementById('selectedOption').value = option;
            
            // Remove selected class from all buttons
            var buttons = document.querySelectorAll('.button-container button');
            buttons.forEach(function(btn) {
                btn.classList.remove('selected');
            });

            // Add selected class to the clicked button
            button.classList.add('selected');

            // Submit the form
            document.getElementById('registrationForm').submit();
        }

        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
        // Toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        // Toggle the eye icon
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
        });
    </script>
  </body>
  </html>