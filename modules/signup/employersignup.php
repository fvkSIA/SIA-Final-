<?php
// Include the database connection file
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';

// Initialize variables
$showModal = false;
$errorMessage = '';
$employer_id = 3;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // print_r(json_encode($_REQUEST));
    // die();
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $birth_date = $_POST['birth_date'];
    $sex = $_POST['sex'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $profile_image_path = $_FILES['profile_image_path']['name'];
    $valid_id_path = $_FILES['valid_id_path']['name'];
    // File upload paths
    $profile_destination = '../employer/assets/images/' . $profile_image_path;
    $valid_destination = '../employer/assets/files/' . $valid_id_path;

    // Move uploaded files to their respective destinations
    move_uploaded_file($_FILES['profile_image_path']['tmp_name'], $profile_destination);
    move_uploaded_file($_FILES['valid_id_path']['tmp_name'], $valid_destination);
    $valid_id_path = $valid_destination;

    // Check if email already exists
    $checkEmailQuery = "SELECT email FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($result) > 0) {
        $errorMessage = "Error: The email address is already registered. Please use a different email.";
        $showModal = false;
    } else {
        // Prepare SQL insert statement
        // $sql = "INSERT INTO users (first_name, middle_name, last_name, email, phone_number, birth_date, sex, address, password, profile_image_path, valid_id_path) 
        //         VALUES ('$first_name', '$middle_name', '$last_name', '$email', '$phone_number', '$birth_date', '$sex', '$address', '$hashed_password', '$profile_destination', '$valid_destination')";
        $sql = "INSERT INTO users (profile, email, firstname, middlename, lastname, phone_number, birthdate, gender, home_address, city, password, type, valid_id_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)){
            $stmt->bind_param("sssssssssssss",$profile_image_path, $email, $first_name, $middle_name, $last_name, $phone_number, $birth_date, $sex, $address, $city, $password, $employer_id, $valid_id_path);
           
            
            if ($stmt->execute()){
                $showModal = true;
            } else {
                $errorMessage = "Error: " . $sql . "<br>" . mysqli_error($conn);
            }

            $stmt->close();
            
        }
        // if (mysqli_query($conn, $sql)) {
        //     $showModal = true;
        // } else {
        //     $errorMessage = "Error: " . $sql . "<br>" . mysqli_error($conn);
        // }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>EMPLOYER SIGNUP</title>
    <link rel="icon" type="image/png" href="../HanapKITA.png">
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #8391c6;;
        }
        .btn.prev {
            position: fixed;
            top: 20px;
            left: 20px;
            background: none;
            border: 2px solid #fff;
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            border-radius: 50%;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn.prev:hover {
            background-color: #4481eb;
        }
        .container {
            position: relative;
            width: 1050px;
            background: #fff;
            padding: 25px;
            margin-top: 50px;
            margin-left: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .container header {
            font-size: 30px;
            color: black;
            font-weight: bold;
            text-align: center;
        }
        .container .form {
            margin-top: 30px;
        }
        .form .input-box {
            width: 100%;
            margin-top: 20px;
        }
        .input-box label {
            color: #333;
        }
        .form :where(.input-box input, .select-box) {
            position: relative;
            height: 50px;
            width: 100%;
            outline: none;
            font-size: 1rem;
            color: black;
            margin-top: 8px;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 0 15px;
        }
        .input-box input:focus {
            box-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
        }
        .input-box.info [type="file"] {
            padding: 10px;
        }
        .form .column {
            display: flex;
            column-gap: 15px;
        }
        .info :where(input, .select-box) {
            margin-top: 15px;
        }
        .select-box select {
            height: 100%;
            width: 100%;
            outline: none;
            border: none;
            color: #707070;
            font-size: 1rem;
        }
        .form button {
            height: 55px;
            width: 100%;
            color: #fff;
            font-size: 1rem;
            font-weight: 400;
            margin-top: 30px;
            border-radius: 5px;
            border: none;
            cursor: progress;
            transition: all 0.2s ease;
            background: #4481eb;
        }
        .form button:hover {
            background: rgb(33, 108, 230);
        }
        .skilled {
            margin-top: 20px;
        }
        .skilled label, .unskilled label {
            display: block;
            color: #333;
            margin-bottom: 8px;
        }
        .skilled select {
            position: relative;
            height: 50px;
            width: 100%;
            outline: none;
            font-size: 1rem;
            color: black;
            margin-top: 8px;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 0 15px;
        }

        .skilled select:focus{
            box-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
        }

        .input-box.info.resume {
            margin-top: 20px; /* Adjust this value to move the resume label down */
        }

        /* Responsive */
        @media screen and (max-width: 500px) {
            .form .column {
                flex-wrap: wrap;
            }
            .form :where(.gender-option, .gender) {
                row-gap: 15px;
            }
        }
        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 300px; /* Set width to desired size */
            border-radius: 10px; /* Add border radius for aesthetics */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add shadow for depth */
            text-align: center;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-body h2 {
            color: #333;
            margin-bottom: 10px;
        }

        .modal-body p {
            color: #666;
            margin-bottom: 20px;
        }

        .modal-body button {
            background-color: #4481eb;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .modal-body button:hover {
            background-color: #3569d1;
        }

        .error-message {
            color: red;
            text-align: left;
            margin-top: 10px;
        }

        .eye-icon {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 70%;
            transform: translateY(-50%);
        }

        .input-box {
            position: relative;
        }

    </style>
</head>
<body>
    <section class="container">
        <header>CREATE A NEW ACCOUNT</header>
        <form action="employersignup.php" method="POST" enctype="multipart/form-data" class="form" onsubmit="return validatePasswords()">
        <div class="input-box">
                <label><b>First Name:</b></label>
                <input type="text" name="first_name" placeholder="Enter first name" required />
            </div>
            <div class="input-box">
                <label><b>Middle Initial:</b></label>
                <input type="text" name="middle_name" placeholder="Enter middle initial"  maxlength="1"  required />
            </div>
            <div class="input-box">
                <label><b>Last Name:</b></label>
                <input type="text" name="last_name" placeholder="Enter last name" required />
            </div>
            <div class="input-box">
                <label><b>Email Address:</b></label>
                <input type="text" name="email" placeholder="Enter email address" required />
            </div>
            <div class="column">
                <div class="input-box">
                    <label><b>Phone Number:</b></label>
                    <input type="text" name="phone_number" id="phone_number" placeholder="(09**-***-****)" maxlength="11" required onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                </div>
                <div class="input-box">
                    <label><b>Birth Date:</b></label>
                    <input type="date" name="birth_date" placeholder="Enter birth date" min="1995-01-01" max="2005-12-31" required />
                </div>
            </div>
            <div class="input-box info" style="margin-bottom: 10px;">
            <label><b>Sex:</b></label></div>
            <input type="radio" id="female" name="sex" value="female" required>
            <label for="female" style="margin-right: 15px; font-size: 18px;">Female</label>
            <input type="radio" id="male" name="sex" value="male" required>
            <label for="male" style="margin-right: 10px; font-size: 18px;">Male</label>
            <div class="input-box info">
                <label><b>Address:</b></label>
                <input type="text" name="address" placeholder="Enter your Address" required />
            </div>
            <div class="skilled">
             <label><b>City:</b></label>
                    <select name="city" required>
                    <option value="">Select Location</option>
                    <option value="Manila">Manila</option>
                    <option value="Caloocan">Caloocan</option>
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
                </div>
            <div class="input-box info">
                <label><b>Password:</b></label>
                <input type="password" name="password" id="password" placeholder="Enter password: (Juandelacruz123!!)" required />
                <i class="fas fa-eye eye-icon" id="passwordIcon" onclick="togglePasswordVisibility('password', 'passwordIcon')"></i>
            </div>
            </div>
            <div class="input-box info">
                <label><b>Confirm Password:</b></label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm password" required />
                <i class="fas fa-eye eye-icon" id="confirmPasswordIcon" onclick="togglePasswordVisibility('confirm_password', 'confirmPasswordIcon')"></i>
            </div>
            <div class="input-box info picture">
                <label><b>Profile Picture:</b></label>
                <input type="file" id="profile" name="profile_image_path" accept=".jpg, .png"  required >
            </div>
          
            <div class="input-box info">
                <label><b>2 Valid IDs (ex: Drivers License, National ID):</b></label>
                <input type="file" id="valid" name="valid_id_path" accept=".jpg, .png" required  >
            </div>
            

            <button type="submit">SUBMIT</button>
            <?php if ($errorMessage) : ?>
                <p class="error-message"><?php echo $errorMessage; ?></p>
            <?php endif; ?>
        </form>
    </section>
    <button onclick="history.back()" class="btn prev"> <i class="fas fa-arrow-left"></i></button>
    <!-- The Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="modal-body">
                <h2>New record created successfully!</h2>
                <p>Your data has been successfully saved.</p>
                <button id="continueBtn">Continue</button>
            </div>
        </div>
    </div>
    <?php if ($errorMessage) : ?>
    <script>
        alert("Error: The email address is already registered. Please use a different email.");
    </script>
<?php endif; ?>
    <script>

        function handlePhoneNumber(input) {
            if (!input.value.startsWith('09')) {
                input.value = '09' + input.value.substring(2);
            }
            if (input.value.length > 11) {
                input.value = input.value.slice(0, 11);
            }
        }

        // Add event listeners when the DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            var phoneInput = document.getElementById('phone_number');
            
            // Set default value
            if (!phoneInput.value) {
                phoneInput.value = '09';
            }

            // Add event listeners
            phoneInput.addEventListener('input', function() {
                handlePhoneNumber(this);
            });

            phoneInput.addEventListener('focus', function() {
                if (this.value === '') {
                    this.value = '09';
                }
            });

            phoneInput.addEventListener('blur', function() {
                if (this.value === '09') {
                    this.value = '';
                }
            });
        });
        
        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the button that closes the modal
        var continueBtn = document.getElementById("continueBtn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks on the button, close the modal
        continueBtn.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Show the modal if the PHP variable indicates success
        <?php if ($showModal) : ?>
        document.addEventListener('DOMContentLoaded', function() {
            modal.style.display = 'block';
        });
        <?php endif; ?>

        function validatePasswords() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            
            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            
            // if (!passwordRegex.test(password)) {
            //     alert("Password must be at least 8 characters long, contain at least one uppercase letter, and include at least one special character.");
            //     return false;
            // }
            if (password.length < 8 || confirmPassword.length < 8){
                alert("Password must be at least 8 characters long");
                return false;
            }
            
            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }
            
            return true;
        }
        
        function togglePasswordVisibility(inputId, iconId) {
            var input = document.getElementById(inputId);
            var eyeIcon = document.getElementById(iconId);
                if (input.type === "password") {
                    input.type = "text";
                    eyeIcon.classList.remove("fa-eye");
                    eyeIcon.classList.add("fa-eye-slash");
                } else {
                    input.type = "password";
                    eyeIcon.classList.remove("fa-eye-slash");
                    eyeIcon.classList.add("fa-eye");
                }
        }

             // When the user clicks the button, redirect them to the login page
        continueBtn.onclick = function() {
            window.location.href = "../login/login_emp.php";
        }

    // Add event listener for phone number input
    document.querySelector('input[name="phone_number"]').addEventListener('input', function() {
            validatePhoneNumber(this);
        });
    </script>
</body>
</html>
