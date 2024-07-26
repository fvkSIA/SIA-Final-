<?php 
session_start();

require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result() ?? null;

    $stmt->close();
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hanapkita_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit;
}

// User ID from session
$user_id = $_SESSION['user_id'];

// Function to sanitize user inputs
function sanitize_input($conn, $input) {
    return htmlspecialchars(strip_tags(mysqli_real_escape_string($conn, $input)));
}

// Query to get distinct job types associated with the user
$sql_jobs = "SELECT DISTINCT job FROM job_listings WHERE employer_id = ?";
$stmt_jobs = $conn->prepare($sql_jobs);
$stmt_jobs->bind_param("i", $user_id);
$stmt_jobs->execute();
$result_jobs = $stmt_jobs->get_result();

// Prepare an array of job types
$jobs = [];
while ($row = $result_jobs->fetch_assoc()) {
    $jobs[] = $row['job'];
}

// Handle form submission
$selected_job = isset($_POST['job']) ? sanitize_input($conn, $_POST['job']) : '';
if (isset($_POST['filter'])) {
    // Query to get job posts associated with the selected job
    $sql_posts = "SELECT id, job, type, salary_offer, location FROM job_listings WHERE employer_id = ? AND job = ?";
    $stmt_posts = $conn->prepare($sql_posts);
    $stmt_posts->bind_param("is", $user_id, $selected_job);
    $stmt_posts->execute();
    $result_posts = $stmt_posts->get_result();
} else {
    // Default query to get all job posts
    $sql_posts = "SELECT id, job, type, salary_offer, location FROM job_listings WHERE employer_id = ?";
    $stmt_posts = $conn->prepare($sql_posts);
    $stmt_posts->bind_param("i", $user_id);
    $stmt_posts->execute();
    $result_posts = $stmt_posts->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .filter-button {
            background-color: #0099d9; 
            border: none;
            color: white;
            padding: 5px 32px;
            text-align: center;
            text-decoration: none; 
            display: inline-block;
            font-size: 16px; 
            margin: 4px 2px; 
            cursor: pointer;
            border-radius: 8px; 
        }

        .filter-button:hover {
            background-color: #195772; 
        }

    </style>
</head>
<body>
<?php 
      $data = [];
      if ($result != null)
        $data = $result->fetch_assoc();
      else 
        echo '';
    ?>
    <div class="mx-auto p-4 bg-white rounded-lg shadow-md">
        <div class="p-6">
            <div class="mb-1">
                <h2 class="text-2xl font-semibold text-[#4B5EAB]">Your Job Post List</h2>
                <p class="text-gray-500 mb-4">Table displaying job posts.</p>
            <div class="p-6">
            <div class="mb-1">

                <form method="post">
                    <label for="job">Select Job:</label>
                    <select name="job" id="job">
                        <option value="">All Jobs</option>
                        <?php
                        foreach ($jobs as $job) {
                            $selected = ($selected_job == $job) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($job) . "' $selected>" . htmlspecialchars($job) . "</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" name="filter" class="filter-button">Filter</button>
                    </form>

                <br>

                <table>
                    <tr>
                        <th>Job</th>
                        <th>Type</th>
                        <th>Salary Offer</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>

                    <?php
                    if ($result_posts->num_rows > 0) {
                        while ($row = $result_posts->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['job']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['salary_offer']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                            echo "<td>";
                            echo "<a href='jobpost_view_job.php?id=" . $row['id'] . "' style='margin-right: 10px;'>View</a>";
                            echo "<a href='jobpost_delete_job.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this job?\")'>Delete</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No Job Posts</td></tr>";
                    }
                    ?>

                </table>

            </div>
        </div>
        </div>
    </div>
</body>
</html>
