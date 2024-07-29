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
$filter_type = isset($_POST['filter_type']) ? $_POST['filter_type'] : 'all';

if (isset($_POST['filter']) || isset($_POST['accepted_jobs']) || isset($_POST['pending_jobs']) || isset($_POST['all_jobs'])) {
    if (isset($_POST['accepted_jobs'])) {
        $filter_type = 'accepted';
    } elseif (isset($_POST['pending_jobs'])) {
        $filter_type = 'pending';
    } elseif (isset($_POST['all_jobs'])) {
        $filter_type = 'all';
    }
}

// Prepare the base query
$sql_posts = "SELECT id, job, type, salary_offer, location, accepted FROM job_listings WHERE employer_id = ?";
$params = [$user_id];
$param_types = "i";

// Add job filter if a specific job is selected
if (!empty($selected_job)) {
    $sql_posts .= " AND job = ?";
    $params[] = $selected_job;
    $param_types .= "s";
}

// Modify the query based on the filter type
switch ($filter_type) {
    case 'accepted':
        $sql_posts .= " AND accepted = 1";
        break;
    case 'pending':
        $sql_posts .= " AND accepted = 0";
        break;
    // For 'all', we don't need to modify the query
}

// Prepare and execute the query
$stmt_posts = $conn->prepare($sql_posts);
$stmt_posts->bind_param($param_types, ...$params);
$stmt_posts->execute();
$result_posts = $stmt_posts->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobPulse - Manage Your Job Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="icon" type="image/png" href="../HanapKITA.png">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
        :root {
            --primary-color: #6C63FF;
            --secondary-color: #4CAF50;
            --accent-color: #FF6B6B;
            --background-color: #F0F2F5;
            --text-color: #333333;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
        }
        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .navbar-brand {
            font-weight: 800;
            color: white !important;
            font-size: 1.5rem;
        }
        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .nav-link:hover, .nav-link.active {
            color: white !important;
            transform: translateY(-2px);
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.12);
        }
        .card-header {
            background-color: #6b0d0d; /* New color for Job Listings */
            color: white;
            border-radius: 15px 15px 0 0 !important;
            font-weight: 700;
        }
        .card.mb-4 {
            height: 120px;
            padding: 20px;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #5753D9;
            border-color: #5753D9;
            transform: translateY(-2px);
        }
        .table {
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }
        .table td {
            vertical-align: middle;
        }
        .badge {
            font-weight: 600;
            padding: 0.5em 1em;
            border-radius: 15px;
        }
        .badge-fulltime {
            background-color: var(--secondary-color);
            color: white;
        }
        .badge-parttime {
            background-color: var(--accent-color);
            color: white;
        }
        .btn-action {
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .btn-action:hover {
            transform: scale(1.1);
        }
        .form-control, .form-select {
            border-radius: 25px;
            padding: 0.5rem 1rem;
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in;
            margin-top: 50px;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .text-primary {
            color: #6b0d0d !important; /* New color for Your Job Posts */
        }
    </style>
</head>
<body>

    <div class="container animate-fade-in">
        <div class="row mb-4">
            <div class="col">
                <h1 class="display-4 fw-bold text-primary">Your Job Posts</h1>
                <p class="lead">Manage and track your job listings with ease.</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body d-flex justify-content-end">
                <form method="post" class="row g-3 align-items-end justify-content-end">
                    <div class="col-auto">
                        <button type="submit" name="accepted_jobs" class="btn btn-success"><i class="fas fa-check-circle me-2"></i>Accepted Jobs</button>
                    </div>
                    <div class="col-auto">
                        <button type="submit" name="pending_jobs" class="btn btn-warning"><i class="fas fa-clock me-2"></i>Pending Jobs</button>
                    </div>
                    <div class="col-auto">
                        <button type="submit" name="all_jobs" class="btn btn-info"><i class="fas fa-list me-2"></i>All Jobs</button>
                    </div>
                    <input type="hidden" name="filter_type" value="<?php echo $filter_type; ?>">
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header text-center">
                <h5 class="card-title mb-0">JOB LISTING</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Type</th>
                                <th>Salary Offer</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result_posts->num_rows > 0) {
                                while ($row = $result_posts->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td><strong>" . htmlspecialchars($row['job']) . "</strong></td>";
                                    $badgeClass = ($row['type'] == 'Full Time') ? 'badge-fulltime' : 'badge-parttime';
                                    echo "<td><span class='badge {$badgeClass}'>" . htmlspecialchars($row['type']) . "</span></td>";
                                    echo "<td><i class='fas fa-money-bill-wave me-2 text-success'></i>" . htmlspecialchars($row['salary_offer']) . "</td>";
                                    echo "<td><i class='fas fa-map-marker-alt me-2 text-danger'></i>" . htmlspecialchars($row['location']) . "</td>";
                                    $statusBadge = $row['accepted'] ? '<span class="badge bg-success">Accepted</span>' : '<span class="badge bg-warning">Pending</span>';
                                    echo "<td>{$statusBadge}</td>";
                                    echo "<td>";
                                    echo "<a href='jobpost_view_job.php?id=" . $row['id'] . "' class='btn btn-action btn-outline-primary me-2' title='View'><i class='fas fa-eye'></i></a>";
                                    echo "<a href='jobpost_delete_job.php?id=" . $row['id'] . "' class='btn btn-action btn-outline-danger' onclick='return confirm(\"Are you sure you want to delete this job?\")' title='Delete'><i class='fas fa-trash-alt'></i></a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center py-5'>No Job Posts Found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
