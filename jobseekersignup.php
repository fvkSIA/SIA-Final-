<?php
// Include the database connection file
include 'db_connection.php';

// Initialize variables
$showModal = false;
$emailExists = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $birth_date = $_POST['birth_date'];
    $sex = $_POST['sex'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $worker_type = $_POST['worker_type'];
    $type_of_work = $_POST['type_of_work'];
    
    // File uploads
    $profile = $_FILES['profile']['name'];
    $resume = $_FILES['resume']['name'];
    $valid_ids = $_FILES['valid']['name'];
    $recent_job_experience = $_FILES['recent']['name'];
    
    // Set file upload paths
    $target_dir = "uploads/";
    $profile_target = $target_dir . basename($profile);
    $resume_target = $target_dir . basename($resume);
    $valid_target = $target_dir . basename($valid_ids);
    $recent_target = $target_dir . basename($recent_job_experience);

    // Move uploaded files to target directory
    move_uploaded_file($_FILES['profile']['tmp_name'], $profile_target);
    move_uploaded_file($_FILES['resume']['tmp_name'], $resume_target);
    move_uploaded_file($_FILES['valid']['tmp_name'], $valid_target);
    move_uploaded_file($_FILES['recent']['tmp_name'], $recent_target);

    // Check if email already exists
    $email_check_sql = "SELECT * FROM jobseekers WHERE email='$email'";
    $result = $conn->query($email_check_sql);

    if ($result->num_rows > 0) {
        // Email already exists
        $emailExists = true;
    } else {
        // Insert data into the database
        $sql = "INSERT INTO jobseekers (first_name, middle_name, last_name, email, phone_number, birth_date, sex, address, password, worker_type, type_of_work, profile, resume, valid_ids, recent_job_experience)
                VALUES ('$first_name', '$middle_name', '$last_name', '$email', '$phone_number', '$birth_date', '$sex', '$address', '$password', '$worker_type', '$type_of_work', '$profile', '$resume', '$valid_ids', '$recent_job_experience')";

    if (mysqli_query($conn, $sql)) {
        $showModal = true;
    } else {
        $errorMessage = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>JOBSEEKER SIGNUP</title>
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
            background: #4481eb;
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
        .form .column {
            display: flex;
            column-gap: 15px;
        }
        .info :where(input, .select-box) {
            margin-top: 15px;
        }
        .input-box.info [type="file"] {
            padding: 10px;
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
            cursor: pointer;
            transition: all 0.2s ease;
            background: #4481eb;
        }
        .form button:hover {
            background: rgb(33, 108, 230);
        }
        .skilled, .unskilled {
            margin-top: 20px;
        }
        .skilled label, .unskilled label {
            display: block;
            color: #333;
            margin-bottom: 8px;
        }
        .skilled select, .unskilled select {
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
        .skilled select:focus, .unskilled select:focus {
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
        <form action="jobseekersignup.php" method="post" enctype="multipart/form-data" class="form" onsubmit="return validatePasswords()">
            <div class="input-box">
                <label><b>First Name:</b></label>
                <input type="text" name="first_name" placeholder="Enter first name" required />
            </div>
            <div class="input-box">
                <label><b>Middle Name:</b></label>
                <input type="text" name="middle_name" placeholder="Enter middle name" required />
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
                    <input type="text" name="phone_number" placeholder="Enter your phone number" maxlength="11" required onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                </div>
                <div class="input-box">
                    <label><b>Birth Date:</b></label>
                    <input type="date" name="birth_date" placeholder="Enter birth date" min="1995-01-01" max="2005-12-31" required />
                </div>
            </div>
            <div class="input-box info">
                <label><b>Sex:</b></label>
                <input type="text" name="sex" placeholder="Enter your Sex" required />
            </div>
            <div class="input-box info">
                <label><b>Address:</b></label>
                <input type="text" name="address" placeholder="Enter your Address" required />
            </div>
            <div class="input-box info">
                <label><b>Password:</b></label>
                <input type="password" name="password" id="password" placeholder="Enter password" required />
                <i class="fas fa-eye eye-icon" id="passwordIcon" onclick="togglePasswordVisibility('password', 'passwordIcon')"></i>
            </div>
            </div>
            <div class="input-box info">
                <label><b>Confirm Password:</b></label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm password" required />
                <i class="fas fa-eye eye-icon" id="confirmPasswordIcon" onclick="togglePasswordVisibility('confirm_password', 'confirmPasswordIcon')"></i>
            </div>

            <div class="skilled">
                <label><b>Select Type of Workers:</b></label>
                <select name="worker_type" id="workerType" onchange="updateWorkType()">
                    <option value="">Select Type of Worker</option>
                    <option value="skilled">Skilled</option>
                    <option value="unskilled">Unskilled</option>
                </select>                  
            </div>
            <div class="unskilled">
                <label><b>Type of Work:</b></label>
                <select name="type_of_work" id="workType">
                    <option value="">Select Type of Work</option>
                </select>                  
            </div>
            <div class="input-box info picture">
                <label><b>Profile:</b></label>
                <input type="file" id="profile" name="profile" accept=".jpg, .png" required  >
            </div>
            <div class="input-box info resume">
                <label><b>Resume:</b></label>
                <input type="file" id="resume" name="resume" accept=".pdf" required  >
            </div>
          
            <div class="input-box info">
                <label><b>2 Valid IDs / Birth Certificate:</b></label>
                <input type="file" id="valid" name="valid" accept=".pdf" required  >
            </div>

            <div class="input-box info">
                <label><b>Recent Job Experience:</b></label>
                <input type="file" id="recent" name="recent" accept=".pdf" required  >
            </div>

            <button type="submit">SUBMIT</button>
        </form>
        <?php
        if ($emailExists) {
            echo "<p style='color: red;'>Error: The email address is already registered. Please use a different email.</p>";
        }
        ?>
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
    <script>
        const workOptions = {
            skilled: [
                "Welder",
                "Electrician", 
                "Plumber",
                "Carpenter",
                "Refrigerator and Aircon Service Provider",
                "Professional Driver",
                "Lineman"
            ],
            unskilled: [
                "Laundry Staff",
                "Janitor",
                "Food service (Dishwasher and Waiter)",
                "Factory Worker",
                "House Keeper",
                "Construction Worker",
                "Security Guard"
            ]
        };

        function updateWorkType() {
            const workerType = document.getElementById('workerType').value;
            const workTypeSelect = document.getElementById('workType');
            workTypeSelect.innerHTML = '';

            // Add the default option
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Select Type of Work';
            workTypeSelect.appendChild(defaultOption);

            if (workerType) {
                const options = workOptions[workerType];
                options.forEach(work => {
                    const option = document.createElement('option');
                    option.value = work;
                    option.textContent = work;
                    workTypeSelect.appendChild(option);
                });
            }
        }

        function validatePasswords() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            
            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            
            if (!passwordRegex.test(password)) {
                alert("Password must be at least 8 characters long, contain at least one uppercase letter, and include at least one special character.");
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
        
        // Add event listener for phone number input
        document.querySelector('input[name="phone_number"]').addEventListener('input', function() {
            validatePhoneNumber(this);
        });
    </script>
</body>
</html>
