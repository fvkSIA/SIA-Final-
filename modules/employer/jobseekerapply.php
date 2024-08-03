<?php
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'] ?? null; // Get user ID from URL
    $jr_id = $_GET['jrid'] ?? null; // Get job request ID if available

    if ($id) {
        $sql = "SELECT * FROM users WHERE id = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "User ID is missing.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jobseeker Profile</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
  <link rel="icon" type="image/png" href="../HanapKITA.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
    .container {
      width: 100%;
    }
    .profile-card {
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      padding: 30px;
      text-align: left;
      border: 1px solid #ddd;
    }
    .profile-card img {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 20px;
      border: 4px solid #007bff;
    }
    .profile-card h1 {
      font-size: 28px;
      font-weight: bold;
      margin-bottom: 15px;
      color: #333;
    }
    .profile-card .info {
      margin-bottom: 5px;
    }
    .profile-card .info div {
      margin-bottom: 12px;
    }
    .profile-card .info span.label {
      font-weight: bold;
      color: #333;
      display: inline-block;
      width: 150px;
    }
    .profile-card .info span.value {
      color: #555;
    }
    .btn-hire {
      display: inline-block;
      padding: 12px 24px;
      border-radius: 6px;
      background-color: #007bff;
      color: #ffffff;
      text-decoration: none;
      font-weight: bold;
      text-align: right;
      transition: background-color 0.3s, transform 0.3s;
    }
    .btn-hire:hover {
      background-color: #0056b3;
      transform: scale(1.05);
    }
    .close-button {
      background-color: #ff4d4d;
      color: #ffffff;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      font-size: 18px;
      cursor: pointer;
      transition: background-color 0.3s, transform 0.3s;
    }
    .close-button:hover {
      background-color: #cc0000;
      transform: scale(1.05);
    }
    .profile-container {
    display: flex;
    align-items: flex-start; 
    gap: 20px; 
    }
    .profile-image {
    max-width: 150px;
    height: auto;
    border-radius: 50%; 
    }
    .profile-info {
    flex: 1; 
    padding-top: 8%;
    }
    .info {
    display: flex;
    justify-content: center; 
    flex-wrap: wrap; 
    gap: 2px; 
    }
    .info > div {
    flex: 1;
    min-width: 100px;
    text-align: center; 
    }
    .hr-container {
    text-align: center; 
    margin-bottom: 10px;
    }
    hr {
    border: 0;
    border-top: 2px solid #ccc;
    margin: 0 auto; 
    width: 100%; 
    }
    .label {
    font-weight: bold;
    }
    .value {
    margin-left: 5px;
    }
    .file{
    align-items: center;
    }
    .action-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    }
    .resume-section, .valid-id-section, .work-experience-section {
    margin-bottom: 30px;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .resume-section label, .valid-id-section label, .work-experience-section label {
    font-size: 1.25rem;
    font-weight: bold;
    margin-bottom: 10px;
    display: block;
    }
    iframe {
    border-radius: 8px;
    }  
    .image-container {
    width: 100%;
    height: 600px;
    background-size: cover;
    background-position: center;
    }
    </style>
</head>
<body>
  <div class="container">
    <?php if ($result && $user = $result->fetch_assoc()): ?>
      <div class="profile-card">
        <div class="profile-container">
          <img src="../jobseeker/assets/images/<?php echo htmlspecialchars($user['profile'] ?? 'no-image.png'); ?>" alt="Profile Image" class="profile-image">
          <div class="profile-info">
            <h1><?php echo htmlspecialchars($user['lastname']) . ', ' . htmlspecialchars($user['firstname']); ?></h1>
          </div>
        </div>

        <div class="info">
          <div><span class="label">Phone:</span> <span class="value"><?php echo htmlspecialchars($user['phone_number']); ?></span></div>
          <div><span class="label">Email:</span> <span class="value"><?php echo htmlspecialchars($user['email']); ?></span></div>
          <div><span class="label">Gender:</span> <span class="value"><?php echo htmlspecialchars($user['gender']); ?></span></div>
          <div><span class="label">City:</span> <span class="value"><?php echo htmlspecialchars($user['city']); ?></span></div>
          <div>
            <!-- Corrected View Button Link -->
            <a href='jobseeker_history.php?id=<?php echo htmlspecialchars($user['id']); ?>'>
              <button style='display: inline-block; padding: 6px 12px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; font-size: 14px;'>
              <i class='bx bx-show-alt' style='vertical-align: middle; margin-right: 5px;'></i> View History
              </button> 
            </a>
          </div>
        </div>
        
        <div class="hr-container">
          <hr>
        </div>

        <div class="resume-section">
          <label>Resume</label>
          <iframe src="<?php echo htmlspecialchars($user['resume']); ?>" width="100%" height="600px" style="border: none;"></iframe>
        </div>

        <div class="valid-id-section">
          <label>Valid ID</label>
          <div class="image-container" style="background-image: url('<?php echo htmlspecialchars($user['valid_ids']); ?>');"></div>
          </div>

        <div class="work-experience-section">
          <label>Certificate</label>
          <iframe src="<?php echo htmlspecialchars($user['recent_job_experience']); ?>" width="100%" height="600px" style="border: none;"></iframe>
        </div>

        <div class="action-buttons">
          <a href="jobseekerapplication.php?id=<?php echo htmlspecialchars($jr_id); ?>" class="btn-hire" onclick="return confirm('Are you sure you want to hire this seeker?');">Hire</a>
          <button class="close-button" onclick="window.location.href='findworkers.php'">Close</button>
        </div>
      </div>
    <?php else: ?>
      <p>No user data found.</p>
    <?php endif; ?>
  </div>
</body>
</html>
