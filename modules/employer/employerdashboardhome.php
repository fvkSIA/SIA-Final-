<?php
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;
$showModal = false;
$result2 = null;

$servername = "localhost"; 
$username = "root";
$password = ""; 
$dbname = "hanapkita_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination settings
$limit = 5; // Number of rows per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch job types for filter
$sql_job_types = "SELECT DISTINCT job_type FROM users WHERE type = 2";
$result_job_types = $conn->query($sql_job_types);

// Main query
$sql = "SELECT u.id, u.firstname, u.middlename, u.lastname, u.gender, u.home_address, u.job_type, u.city, u.profile, SUM(r.compound) AS total_compound
        FROM (SELECT DISTINCT id, firstname, middlename, lastname, gender, home_address, job_type, city, profile FROM users WHERE type = 2) u
        INNER JOIN ratings r ON u.id = r.user_id 
        WHERE r.compound IS NOT NULL";

if (!empty($_GET['job_type'])) {
    $job_type = $conn->real_escape_string($_GET['job_type']);
    $sql .= " AND u.job_type = '".$job_type."'";
}

$sql .= " GROUP BY u.id, u.firstname, u.middlename, u.lastname, u.gender, u.home_address, u.job_type, u.city, u.profile
          ORDER BY total_compound DESC
          LIMIT $limit OFFSET $offset";

// Get the results
$result = $conn->query($sql);

if ($result === false) {
    echo "Error executing query: " . $conn->error;
    exit;
}

// Get total number of rows for pagination
$sql_count = "SELECT COUNT(DISTINCT u.id) AS total_rows
              FROM (SELECT DISTINCT id, firstname, middlename, lastname, gender, home_address, job_type, city, profile FROM users WHERE type = 2) u
              INNER JOIN ratings r ON u.id = r.user_id
              WHERE r.compound IS NOT NULL";

if (!empty($_GET['job_type'])) {
    $job_type = $conn->real_escape_string($_GET['job_type']);
    $sql_count .= " AND u.job_type = '".$job_type."'";
}

$result_count = $conn->query($sql_count);
$total_rows = $result_count->fetch_assoc()['total_rows'];
$total_pages = ceil($total_rows / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Employer Dashboard</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <link rel="icon" type="image/png" href="../HanapKITA.png">
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");

    body {
  margin: 0;
  font-family: 'Poppins', sans-serif;
}

.pagination {
  display: flex;
  justify-content: center;
  margin: 20px 0;
}

.pagination a {
  text-decoration: none;
  padding: 8px 16px;
  border: 1px solid #ddd;
  color: #333;
  margin: 0 4px;
  border-radius: 4px;
}

.pagination a.active {
  background-color: #6b0d0d;
  color: white;
}

.home-section {
  position: relative;
  background-color: var(--color-body);
  min-height: 100vh;
  top: 0;
  width: calc(100% - 78px);
  transition: all .5s ease;
  z-index: 2;
  margin: 0 auto;
  padding: 20px; /* Add padding for better spacing */
}

.home-section .text {
  display: inline-block;
  color: var(--color-default);
  font-size: 25px;
  font-weight: 500;
  margin: 18px;
}

.container {
  display: flex;
  background-color: rgba(255, 255, 255);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  overflow: hidden;
  margin-top: 0px; /* Adjusted margin to move the content further down */
  width: 100%; /* Make the container span the entire width */
  margin: 0 auto; /* Center align horizontally */
  flex-wrap: nowrap; /* Prevent wrapping to keep items in a row */
  padding: 20px; /* Add padding inside the container */
}

@media (max-width: 768px) {
  .home-section {
    width: calc(100% - 20px); 
  }

  .home-section .text {
    font-size: 20px; 
    margin: 10px;
  }

  .container {
    flex-direction: column; 
    padding: 10px; 
  }

  .pagination a {
    padding: 6px 12px;
    font-size: 14px;
  }
}

@media (max-width: 480px) {
  .home-section .text {
    font-size: 18px;
    margin: 8px;
  }

  .pagination a {
    padding: 4px 8px; 
    font-size: 12px;
  }
}
</style>
</head>
<body>
  <section class="home-section">
  <div style="text-align: right;">
  <form method="GET" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" style="margin-bottom: 20px; display: inline-flex; align-items: center; gap: 15px;">
    <div style="display: flex; align-items: center; gap: 15px; background-color: #f9f9f9; padding: 15px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
      <label for="job_type" style="font-weight: bold; font-size: 16px; color: #333; margin-right: 10px;">Select Job Type:</label>
      <select name="job_type" id="job_type" style="padding: 2px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; width: 220px; transition: border-color 0.3s;">
        <option value="">All Job Types</option>
        <?php while ($row_job_type = $result_job_types->fetch_assoc()): ?>
          <?php $selected = ($_GET['job_type'] == $row_job_type['job_type']) ? 'selected' : ''; ?>
          <option value="<?php echo htmlspecialchars($row_job_type['job_type']); ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($row_job_type['job_type']); ?></option>
        <?php endwhile; ?>
      </select>
      <button type="submit" style="padding: 10px 20px; background-color: #6b0d0d; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; transition: background-color 0.3s, transform 0.2s;">
        Filter
      </button>
    </div>
  </form>
</div>



<?php if ($result->num_rows > 0): ?>
  <table border='0' style='border-collapse: collapse; width: 100%;'>
    <thead>
      <tr style='background-color: #f6b4ab; align-items: center;'>
        <th style='padding: 10px; width: 2%;'>Rank</th>
        <th style='padding: 10px; width: 1%;'></th>
        <th style='padding: 10px; width: 10%; text-align: left;'>Name</th>
        <th style='padding: 10px; width: 10%;'>Gender</th>
        <th style='padding: 10px; width: 10%;'>Job Type</th>
        <th style='padding: 10px; width: 10%;'>City</th>
        <th style='padding: 10px; width: 10%;'>Details</th>
      </tr>
    </thead>
    <tbody>
      <?php $rank = $offset + 1; ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <?php $row_color = ($rank % 2 == 0) ? '#f6fbfe' : '#ffffff'; // Alternate row colors ?>
        <tr style='background-color: <?php echo $row_color; ?>;'>
          <td style='padding: 10px; text-align: center;'><?php echo $rank; ?></td>
          <td style='padding: 5px; text-align: right;'><img src='../jobseeker/assets/images/<?php echo htmlspecialchars($row['profile'] ?? 'no-image.png'); ?>' style='width: 35px; height: 35px; object-fit: cover; border-radius: 50%;'></td>
          <td style='padding: 10px; align-items: left;'><?php echo $row["firstname"]." ".$row["middlename"]." ".$row["lastname"]; ?></td>
          <td style='padding: 10px; text-align: center;'><?php echo $row["gender"]; ?></td>
          <td style='padding: 10px; text-align: center;'><?php echo $row["job_type"]; ?></td>
          <td style='padding: 10px; text-align: center;'><?php echo $row["city"]; ?></td>
          <td style='padding: 10px; text-align: center;'>
          <a href='jobseekerviewprofile.php?id=<?php echo $row['id']; ?>' 
          style='display: inline-block; padding: 6px 12px; background-color: #6b0d0d; color: white; text-decoration: none; border-radius: 4px; font-size: 14px;'>
          <i class='bx bx-show-alt' style='vertical-align: middle; margin-right: 5px;'></i> View
          </a>
          </td>
        </tr>
        <?php $rank++; ?>
      <?php endwhile; ?>
    </tbody>
  </table>
<?php else: ?>
  <p>No results found.</p>
<?php endif; ?>

<!-- Pagination controls -->
<div class="pagination">
  <?php if ($page > 1): ?>
    <a href="?page=<?php echo $page - 1; ?>&job_type=<?php echo urlencode($_GET['job_type'] ?? ''); ?>">Previous</a>
  <?php endif; ?>

  <?php for ($i = 1; $i <= $total_pages; $i++): ?>
    <a href="?page=<?php echo $i; ?>&job_type=<?php echo urlencode($_GET['job_type'] ?? ''); ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
  <?php endfor; ?>

  <?php if ($page < $total_pages): ?>
    <a href="?page=<?php echo $page + 1; ?>&job_type=<?php echo urlencode($_GET['job_type'] ?? ''); ?>">Next</a>
  <?php endif; ?>
</div>



<?php $conn->close(); ?>



</section>
<?php include '../employer/em_footer.html'; ?>

  <script src="script.js"></script>
</body>
</html>
