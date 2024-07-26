<?php 
$db = mysqli_connect('localhost', 'root', '', 'hanapkita_db');

// Check database connection
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all unique job types
$job_types_query = "SELECT DISTINCT job_type FROM users WHERE job_type IS NOT NULL ORDER BY job_type";
$job_types_result = mysqli_query($db, $job_types_query);

// Get the selected job type
$selected_job = isset($_GET['job_type']) ? $_GET['job_type'] : '';

// Modify the main query to filter by job type if selected
$query = "SELECT u.id, u.firstname, u.middlename, u.lastname, u.job_type, SUM(r.compound) as total_rating
          FROM users u
          INNER JOIN ratings r ON u.id = r.user_id 
          WHERE r.compound IS NOT NULL";

if (!empty($selected_job)) {
    $query .= " AND u.job_type = '" . mysqli_real_escape_string($db, $selected_job) . "'";
}

$query .= " GROUP BY u.id ORDER BY total_rating DESC";

$result = mysqli_query($db, $query);

// Check for query execution error
if (!$result) {
    die("Query error: " . mysqli_error($db));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Leaderboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6c63ff;
            --secondary-color: #3f3d56;
            --background-color: #f4f7fa;
            --card-bg-color: #ffffff;
        }
        body {
            background-color: var(--background-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background-color: var(--primary-color);
        }
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-bottom: none;
            padding: 1.5rem;
        }
        .table {
            margin-bottom: 0;
        }
        .table th {
            border-top: none;
            font-weight: 600;
            color: var(--secondary-color);
            text-transform: uppercase;
            font-size: 0.85rem;
        }
        .table td {
            vertical-align: middle;
        }
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(108, 99, 255, 0.25);
        }
        .badge {
            font-weight: 500;
            padding: 0.5em 0.75em;
            border-radius: 30px;
        }
        .progress {
            height: 8px;
            border-radius: 4px;
        }
        .worker-name {
            font-weight: 600;
            color: var (--secondary-color);
        }
        .top-rank {
            font-size: 1.2rem;
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: white;
        }
        .animate-in {
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .custom-container {
            max-width: calc(100% - 10px); /* Add 5px to each side */
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }
    </style>
</head>
<body>
    <br>
    <div class="container custom-container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card animate-in mb-4">
                    <div class="card-header">
                        <h2 class="mb-0 fw-bold text-center">
                            <i class="fas fa-user-tie me-2"></i>Top Performers
                        </h2>
                    </div>
                    <div class="card-body">
                        <form method="get" class="mb-4">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <label for="job_type" class="form-label mb-0">Filter by Job Type:</label>
                                </div>
                                <div class="col-md-8">
                                    <select class="form-select" id="job_type" name="job_type" onchange="this.form.submit()">
                                        <option value="">All Job Types</option>
                                        <?php while ($job = mysqli_fetch_assoc($job_types_result)): ?>
                                            <option value="<?= htmlspecialchars($job['job_type']) ?>" <?= $selected_job === $job['job_type'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($job['job_type']) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Worker</th>
                                        <th>Job Type</th>
                                        <th>Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if ($result) {
                                        $index = 0;
                                        $max_rating = 0;
                                        $ratings = [];
                                        
                                        // First pass to get max rating
                                        while ($value = mysqli_fetch_array($result)) {
                                            $max_rating = max($max_rating, $value['total_rating']);
                                            $ratings[] = $value;
                                        }
                                        
                                        // Debug output
                                        if (empty($ratings)) {
                                            echo "<tr><td colspan='4' class='text-center'>No data found.</td></tr>";
                                        }
                                        
                                        // Second pass to display data
                                        foreach ($ratings as $value) {
                                            $index++;
                                            $rankClass = $index <= 3 ? 'bg-warning' : 'bg-secondary';
                                            $rankStyle = $index <= 3 ? 'color: #000;' : '';
                                            
                                            echo "<tr class='animate-in' style='animation-delay: " . ($index * 0.1) . "s;'>"; 
                                            echo "<td><span class='top-rank {$rankClass}' style='{$rankStyle}'>{$index}</span></td>";
                                            echo "<td class='worker-name'>" . htmlspecialchars($value['firstname'] . ' ' . $value['lastname']) . "</td>";
                                            echo "<td><span class='badge bg-info'>" . (isset($value['job_type']) ? htmlspecialchars($value['job_type']) : 'N/A') . "</span></td>";
                                            echo "<td class='fw-bold'>" . number_format($value['total_rating'], 2) . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4' class='text-center'>Error: " . mysqli_error($db) . "</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

