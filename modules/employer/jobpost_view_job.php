<?php
session_start();
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php'; // Ensure this file contains your database connection

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    echo "Invalid access.";
    exit;
}

$user_id = $_SESSION['user_id'];
$job_id = intval($_GET['id']);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hanapkita_db";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_title = $_POST['job'];
    $job_type = $_POST['job_type'];
    $date = $_POST['date'];
    $salary_offer = $_POST['salary_offer'];
    $location = $_POST['location'];
    $responsibilities = $_POST['responsibilities'];
    $qualifications = $_POST['qualifications'];

    // Update job details
    $update_sql = "UPDATE job_listings SET job = ?, type = ?, date = ?, salary_offer = ?, location = ?, responsibilities = ?, qualifications = ? WHERE id = ? AND employer_id = ?";
    $stmt = $conn->prepare($update_sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ssssssssi", $job_title, $job_type, $date, $salary_offer, $location, $responsibilities, $qualifications, $job_id, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo '<div style="float: right; padding: 10px; color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px;">Job details updated successfully!</div>';
    } else {
        echo '<div style="float: right; padding: 10px; color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px;">No changes made or update failed. Please try again.</div>';
    }
       
    $stmt->close();
}

// Fetch job details
$sql = "SELECT * FROM job_listings WHERE id = ? AND employer_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("ii", $job_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

$stmt->close();
$conn->close();

if (!$job) {
    echo "Job not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            position: relative;
        }
        .container {
            width: 95%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        form {
            display: grid;
            gap: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .form-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .form-group > div {
            flex: 1;
            min-width: 200px;
        }
        textarea {
            resize: vertical;
        }
        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #007bff; /* Blue background */
            color: white; /* White text */
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
        }
        .back-button a {
            color: white;
            text-decoration: none;
        }
        .back-button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
        .title {
            font-size: 32px;
            color: #1E3B85;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="back-button">
        <a href="/SIA-Final-/modules/employer/employer_jobpost_history.php">Back to Job List</a>
    </div>
    <div class="container">
        <div class="title">Edit Job Details</div>
        <form method="POST" action="">
            <div class="form-group">
                <div>
                    <label for="job">Job Title:</label>
                    <select id="job" name="job" required>
                        <option value="">Select job title</option>
                        <option value="Welder" <?php echo $job['job'] === 'Welder' ? 'selected' : ''; ?>>Welder</option>
                        <option value="Plumber" <?php echo $job['job'] === 'Plumber' ? 'selected' : ''; ?>>Plumber</option>
                        <option value="Lineman" <?php echo $job['job'] === 'Lineman' ? 'selected' : ''; ?>>Lineman</option>
                        <option value="Security Guard" <?php echo $job['job'] === 'Security Guard' ? 'selected' : ''; ?>>Security Guard</option>
                        <option value="Electrician" <?php echo $job['job'] === 'Electrician' ? 'selected' : ''; ?>>Electrician</option>
                        <option value="Carpenter" <?php echo $job['job'] === 'Carpenter' ? 'selected' : ''; ?>>Carpenter</option>
                        <option value="Driver" <?php echo $job['job'] === 'Driver' ? 'selected' : ''; ?>>Driver</option>
                        <option value="Refrigerator and Aircon Service" <?php echo $job['job'] === 'Refrigerator and Aircon Service' ? 'selected' : ''; ?>>Refrigerator and Aircon Service</option>
                        <option value="Food Service" <?php echo $job['job'] === 'Food Service' ? 'selected' : ''; ?>>Food Service</option>
                        <option value="Laundry Staff" <?php echo $job['job'] === 'Laundry Staff' ? 'selected' : ''; ?>>Laundry Staff</option>
                        <option value="Factory Worker" <?php echo $job['job'] === 'Factory Worker' ? 'selected' : ''; ?>>Factory Worker</option>
                        <option value="Housekeeper" <?php echo $job['job'] === 'Housekeeper' ? 'selected' : ''; ?>>Housekeeper</option>
                        <option value="Janitor" <?php echo $job['job'] === 'Janitor' ? 'selected' : ''; ?>>Janitor</option>
                        <option value="Construction Worker" <?php echo $job['job'] === 'Construction Worker' ? 'selected' : ''; ?>>Construction Worker</option>
                    </select>
                </div>
                <div>
                    <label for="job_type">Job Type:</label>
                    <select id="job_type" name="job_type" required>
                        <option value="Part Time" <?php echo $job['type'] === 'Part-Time' ? 'selected' : ''; ?>>Part-Time</option>
                        <option value="Full Time" <?php echo $job['type'] === 'Full-Time' ? 'selected' : ''; ?>>Full-Time</option>
                        <option value="One Time" <?php echo $job['type'] === 'One-Time' ? 'selected' : ''; ?>>One-Time</option>
                    </select>
                </div>
                <div>
                    <label for="location">Location:</label>
                    <select id="location" name="location" required>
                        <option value="">Select Location</option>
                        <option value="Manila" <?php echo $job['location'] === 'Manila' ? 'selected' : ''; ?>>Manila</option>
                        <option value="Quezon City" <?php echo $job['location'] === 'Quezon City' ? 'selected' : ''; ?>>Quezon City</option>
                        <option value="Makati" <?php echo $job['location'] === 'Makati' ? 'selected' : ''; ?>>Makati</option>
                        <option value="Taguig" <?php echo $job['location'] === 'Taguig' ? 'selected' : ''; ?>>Taguig</option>
                        <option value="Pasig" <?php echo $job['location'] === 'Pasig' ? 'selected' : ''; ?>>Pasig</option>
                        <option value="Caloocan" <?php echo $job['location'] === 'Caloocan' ? 'selected' : ''; ?>>Caloocan</option>
                        <option value="SanㅤJuan" <?php echo $job['location'] === 'SanㅤJuan' ? 'selected' : ''; ?>>San Juan</option>
                        <option value="Pasay" <?php echo $job['location'] === 'Pasay' ? 'selected' : ''; ?>>Pasay</option>
                        <option value="Valenzuela" <?php echo $job['location'] === 'Valenzuela' ? 'selected' : ''; ?>>Valenzuela</option>
                        <option value="Navotas" <?php echo $job['location'] === 'Navotas' ? 'selected' : ''; ?>>Navotas</option>
                        <option value="LasㅤPiñas" <?php echo $job['location'] === 'LasㅤPiñas' ? 'selected' : ''; ?>>Las Piñas</option>
                        <option value="Parañaque" <?php echo $job['location'] === 'Parañaque' ? 'selected' : ''; ?>>Parañaque</option>
                        <option value="Muntinlupa" <?php echo $job['location'] === 'Muntinlupa' ? 'selected' : ''; ?>>Muntinlupa</option>
                        <option value="Pateros" <?php echo $job['location'] === 'Pateros' ? 'selected' : ''; ?>>Pateros</option>
                        <option value="Marikina" <?php echo $job['location'] === 'Marikina' ? 'selected' : ''; ?>>Marikina</option>
                        <option value="Mandaluyong" <?php echo $job['location'] === 'Mandaluyong' ? 'selected' : ''; ?>>Mandaluyong</option>
                        <option value="Malabon" <?php echo $job['location'] === 'Malabon' ? 'selected' : ''; ?>>Malabon</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($job['date']); ?>" required>
                </div>
                <div>
                    <label for="time">Time:</label>
                    <input type="time" id="time" name="time" value="<?php echo htmlspecialchars($job['time']); ?>" required>
                </div>
                <div>
                    <label for="salary_offer">Salary Offer:</label>
                    <input type="text" id="salary_offer" name="salary_offer" value="<?php echo htmlspecialchars($job['salary_offer']); ?>" required>
                </div>
            </div>

            

            <label for="responsibilities">Responsibilities:</label>
            <textarea id="responsibilities" name="responsibilities" rows="5" required><?php echo htmlspecialchars($job['responsibilities']); ?></textarea>
            <script>
                document.getElementById('responsibilities').addEventListener('input', function() {
                    let lines = this.value.split('\n');
                    let formattedLines = lines.map(line => line.startsWith('• ') ? line : '• ' + line);
                    this.value = formattedLines.join('\n');
                });
            </script>

            <label for="qualifications">Qualifications:</label>
            <textarea id="qualifications" name="qualifications" rows="5" required><?php echo htmlspecialchars($job['qualifications']); ?></textarea>
            <script>
                document.getElementById('qualifications').addEventListener('input', function() {
                    let lines = this.value.split('\n');
                    let formattedLines = lines.map(line => line.startsWith('• ') ? line : '• ' + line);
                    this.value = formattedLines.join('\n');
                });
            </script>
            <button type="submit">Update Job</button>
        </form>
    </div>
</body>
</html>
