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

$sql_job_types = "SELECT DISTINCT job_type FROM users WHERE type = 2";
$result_job_types = $conn->query($sql_job_types);

$sql = "SELECT u.id, u.firstname, u.middlename, u.lastname, u.gender, u.home_address, u.job_type, u.city, u.profile, SUM(r.compound) AS total_compound
        FROM (SELECT DISTINCT id, firstname, middlename, lastname, gender, home_address, job_type, city, profile FROM users WHERE type = 2) u
        INNER JOIN ratings r ON u.id = r.user_id 
        WHERE r.compound IS NOT NULL";

if (!empty($_GET['job_type'])) {
    $job_type = $conn->real_escape_string($_GET['job_type']);
    $sql .= " AND u.job_type = '".$job_type."'";
}

$sql .= " GROUP BY u.id, u.firstname, u.middlename, u.lastname, u.gender, u.home_address, u.job_type, u.city, u.profile
          ORDER BY total_compound DESC";

$result = $conn->query($sql);

if ($result === false) {
  echo "Error executing query: " . $conn->error;
  exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employer Dashboard</title>
  <!-- External Styles -->
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
  <link rel="icon" type="image/png" href="../HanapKITA.png">
  <style>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");

    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>
<body>

<form method="GET" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" style="margin-bottom: 20px; display: flex; align-items: center;">
  <label for="job_type" style="margin-right: 10px;">Select Job Type:</label>
  <select name="job_type" id="job_type" style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
    <option value="">All Job Types</option>
    <?php while ($row_job_type = $result_job_types->fetch_assoc()): ?>
      <?php $selected = ($_GET['job_type'] == $row_job_type['job_type']) ? 'selected' : ''; ?>
      <option value="<?php echo htmlspecialchars($row_job_type['job_type']); ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($row_job_type['job_type']); ?></option>
    <?php endwhile; ?>
  </select>
  <button type="submit" style="padding: 8px 20px; background-color: #6b0d0d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">Filter</button>
</form>

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
      <?php $rank = 1; ?>
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

<?php include '../employer/em_footer.html'; ?>

</body>
</html>

<?php $conn->close(); ?>

