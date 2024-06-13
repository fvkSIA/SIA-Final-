<?php
// Include the database connection file
include 'db_connection.php';

// Initialize variables
$showModal = false;
$errorMessage = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $profile_image_path = $_FILES['profile_image_path']['name'];
    $valid_id_path = $_FILES['valid_id_path']['name'];

    // File upload paths
    $profile_destination = 'profile_images/' . $profile_image_path;
    $valid_destination = 'valid_ids/' . $valid_id_path;

    // Move uploaded files to their respective destinations
    move_uploaded_file($_FILES['profile_image_path']['tmp_name'], $profile_destination);
    move_uploaded_file($_FILES['valid_id_path']['tmp_name'], $valid_destination);

    // Check if email already exists
    $checkEmailQuery = "SELECT email FROM employers WHERE email = '$email'";
    $result = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($result) > 0) {
        $errorMessage = "Error: The email address is already registered. Please use a different email.";
        $showModal = false;
    } else {
        // Prepare SQL insert statement
        $sql = "INSERT INTO employers (full_name, email, phone_number, birth_date, gender, address, password, profile_image_path, valid_id_path) 
                VALUES ('$full_name', '$email', '$phone_number', '$birth_date', '$gender', '$address', '$hashed_password', '$profile_destination', '$valid_destination')";

        if (mysqli_query($conn, $sql)) {
            $showModal = true;
        } else {
            $errorMessage = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>HANAPKITA</title>
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
        .skilled select {
            position: relative;
            height: 50px;
            width: 30%;
            outline: none;
            font-size: 1rem;
            color: black;
            margin-top: 8px;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 0 15px;
        }

        .unskilled select {
            position: relative;
            height: 50px;
            width: 50%;
            outline: none;
            font-size: 1rem;
            color: black;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 0 15px;
        }

        .unskilled{
          margin-top: -50px;
          margin-left: 335px;
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

        .error-message {
            color: red;
            text-align: left;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <section class="container">
        <header>CREATE A NEW ACCOUNT</header>
        <form action="employersignup.php" method="POST" enctype="multipart/form-data" class="form" onsubmit="return validatePasswords()">
            <div class="input-box">
                <label><b>Full Name:</b></label>
                <input type="text" name="full_name" placeholder="Enter full name" required />
            </div>
            <div class="input-box">
                <label><b>Email Address:</b></label>
                <input type="text" name="email" placeholder="Enter email address" required />
            </div>
            <div class="column">
                <div class="input-box">
                    <label><b>Phone Number:</b></label>
                    <input type="number" name="phone_number" placeholder="Enter phone number" required />
                </div>
                <div class="input-box">
                    <label><b>Birth Date:</b></label>
                    <input type="date" name="birth_date" placeholder="Enter birth date" min="1995-01-01" max="2005-12-31" required />
                </div>
            </div>
            <div class="input-box info">
                <label><b>Gender:</b></label>
                <input type="text" name="gender" placeholder="Enter your Gender" required />
            </div>
            <div class="input-box info">
                <label><b>Address:</b></label>
                <input type="text" name="address" placeholder="Enter your Address" required />
            </div>
            <div class="input-box info">
                <label><b>Password:</b></label>
                <input type="password" id="password" name="password" placeholder="Enter your Password" required />
            </div>
            <div class="input-box info">
                <label><b>Confirm Password:</b></label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required />
            </div>
  
            <div class="input-box info picture">
                <label><b>Profile:</b></label>
                <input type="file" id="profile" name="profile_image_path" accept=".pdf, .jpg, .png" required  >
            </div>
          
            <div class="input-box info">
                <label><b>2 Valid IDs / Birth Certificate:</b></label>
                <input type="file" id="valid" name="valid_id_path" accept=".pdf" required  >
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

    <script>
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
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            if (password !== confirmPassword) {
                alert("Passwords do not match. Please try again.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
