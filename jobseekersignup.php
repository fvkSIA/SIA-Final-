<?php
// Include the database connection file
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
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

    // Insert data into the database
    $sql = "INSERT INTO jobseekers (full_name, email, phone_number, birth_date, gender, address, password, worker_type, type_of_work, profile, resume, valid_ids, recent_job_experience)
            VALUES ('$full_name', '$email', '$phone_number', '$birth_date', '$gender', '$address', '$password', '$worker_type', '$type_of_work', '$profile', '$resume', '$valid_ids', '$recent_job_experience')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('successModal').style.display = 'block';
                });
              </script>";
    } else {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('errorModal').style.display = 'block';
                });
              </script>";
    }

    $conn->close();
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
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
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
    </style>
</head>
<body>
    <section class="container">
        <header>CREATE A NEW ACCOUNT</header>
        <form action="jobseekersignup.php" method="post" enctype="multipart/form-data" class="form">
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
                    <input type="date" name="birth_date" placeholder="Enter birth date" required />
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
                <input type="password" name="password" placeholder="Enter your Password" required />
            </div>
            <div class="input-box info">
                <label><b>Confirm Password:</b></label>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required />
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
                <input type="file" id="profile" name="profile" accept=".pdf, .jpg, .png" required  >
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
    </section>
    <button onclick="history.back()" class="btn prev"> <i class="fas fa-arrow-left"></i></button>
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
    </script>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('successModal').style.display='none'">&times;</span>
            <p>New record created successfully</p>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('errorModal').style.display='none'">&times;</span>
            <p>Error creating record. Please try again.</p>
        </div>
    </div>
</body>
</html>
