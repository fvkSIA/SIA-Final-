<?php 
$db = mysqli_connect('localhost', 'root', '', 'hanapkita_db');

// Fetch all unique job types
$job_types_query = "SELECT DISTINCT job_type FROM users WHERE job_type IS NOT NULL ORDER BY job_type";
$job_types_result = mysqli_query($db, $job_types_query);

// Get the selected job type
$selected_job = isset($_GET['job_type']) ? $_GET['job_type'] : '';

// Modify the main query to filter by job type if selected
$query = "SELECT u.id, u.firstname, u.middlename, u.lastname, u.job_type, r.compound
          FROM users u
          INNER JOIN ratings r ON u.id = r.user_id 
          WHERE r.compound IS NOT NULL";

if (!empty($selected_job)) {
    $query .= " AND u.job_type = '" . mysqli_real_escape_string($db, $selected_job) . "'";
}

$query .= " ORDER BY r.compound DESC";

$result = mysqli_query($db, $query);

// Check for query execution error
if (!$result) {
    die("Query error: " . mysqli_error($db));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>List of Workers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>List of Workers</h1>

        <form method="get" class="mb-4">
            <div class="row">
                <div class="col-auto">
                    <select class="form-select" name="job_type" onchange="this.form.submit()">
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

        <table class="table table-light table-striped">
            <thead>
                <tr>
                    <th>Job Type</th>
                    <th>First name</th>
                    <th>Middle name</th>
                    <th>Last name</th>
                    <th>Ratings Points</th>
                    <th>Rank</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($result) {
                    $index = 0;
                    while ($value = mysqli_fetch_array($result)) {
                        $index++;
                        echo "<tr>"; 
                        echo "<td>". (isset($value['job_type']) ? htmlspecialchars($value['job_type']) : 'N/A') . "</td>";
                        echo "<td>". htmlspecialchars($value['firstname']) . "</td>";
                        echo "<td>". htmlspecialchars($value['middlename']) . "</td>";
                        echo "<td>". htmlspecialchars($value['lastname']) . "</td>";
                        echo "<td>". htmlspecialchars($value['compound']) . "</td>";
                        echo "<td>". $index . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Error: " . mysqli_error($db) . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>