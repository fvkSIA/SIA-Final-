<?php

// Include the database connection file
// include 'db_connection.php';

// $conn = new mysqli($servername, $username, $password, $dbname);

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// if (isset($_POST['selectedOption'])) {
//     $selectedOption = $_POST['selectedOption'];

//     if ($selectedOption == 'EMPLOYER') {
//         $sql = "UPDATE number_of_registration SET registrations = registrations + 1 WHERE type = 'EMPLOYER'";
//     } else if ($selectedOption == 'JOBSEEKER') {
//         $sql = "UPDATE number_of_registration SET registrations = registrations + 1 WHERE type = 'JOBSEEKER'";
//     }

//     if (isset($sql) && $conn->query($sql) === TRUE) {
//         if ($selectedOption == 'EMPLOYER') {
//             header("Location: termsemployer.html");
//         } else if ($selectedOption == 'JOBSEEKER') {
//             header("Location: termsjobseeker.html");
//         }
//     } else {
//         echo "Error updating record: " . $conn->error;
//     }
// } 
if (isset($_POST['selectedOption'])){
    $selectedOption = $_POST['selectedOption'];

    if ($selectedOption == 'EMPLOYER') {
        header("Location: ../signup/termsemployer.html");
    } else if ($selectedOption == 'JOBSEEKER') {
        header("Location: ../signup/termsjobseeker.html");
    }

}




// $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHOOSE CATEGORY</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color:#4481eb;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            border: 1px solid;
            background-color: #F4F6FC;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 550px;
            padding: 20px 30px;
            text-align: center;
            transition: transform 0.3s;
            height: 330px;
            margin-top: 0;
        }

        .container:hover {
            transform: translateY(-10px);
        }
        .container h1 {
            font-size: 50px;
            color: #333;
            font-family:'Times New Roman', Times, serif;
            margin-bottom: 20px;
            letter-spacing: 2px;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        .button-container button {
            background-color: #0056b3;
            border: none;
            padding: 20px 20px;
            cursor: pointer;
            border-radius: 10px;
            font-size: 20px;
            color: black;
            transition: background-color 0.3s, transform 0.3s;
            width: 45%;
        }
        .button-container button:hover {
            background-color: #007bff;
            transform: translateY(-2px);
        }
        .button-container button.selected {
            background-color: #003366; /* Darker shade when selected */
            color: white; /* Change text color when selected */
        }
        .create-account {
            background-color: #9DCD5A;
            border: 1px solid;
            padding: 12px 25px;
            cursor: pointer;
            border-radius: 15px;
            font-size: 20px;
            font-weight: bold;
            color: black;
            transition: background-color 0.3s, transform 0.3s;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
            width: auto;
        }
        .create-account:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        .red-text {
            color: black;
            font-family: 'Times New Roman', Times, serif;
            font-size: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><b>SIGN UP</b></h1>
        <hr>
        <p class="red-text">Are you?</p>
        <div class="button-container">
            <button type="button" onclick="selectOption('EMPLOYER', this)"><b>EMPLOYER</b></button>
            <button type="button" onclick="selectOption('JOBSEEKER', this)"><b>JOBSEEKER</b></button>
        </div>
        <form id="registrationForm" method="POST" action="create.php">
            <input type="hidden" id="selectedOption" name="selectedOption">
            <button type="submit" class="create-account">CREATE ACCOUNT</button>
        </form>
    </div>

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
        }

        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            if (!document.getElementById('selectedOption').value) {
                event.preventDefault();
                alert('Please select an option.');
            }
        });
    </script>
</body>
</html>