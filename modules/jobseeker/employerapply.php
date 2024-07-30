<?php
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'] ?? null; // Get user ID from URL
    $jr_id = $_GET['jrid'] ?? null; // Get job request ID if available
    $jr_empid = $_GET['jr_empid'] ?? null;

    if ($jr_empid) {
        $sql = "SELECT * FROM users WHERE id = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $jr_empid);
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

body {
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
      font-family: 'Poppins', sans-serif;
    }

    .container {
      width: 100%;
      max-width: 700px;
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
    align-items: flex-start; /* Align items to the start vertically */
    gap: 20px; /* Adjust space between image and text as needed */
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
    justify-content: center; /* Center items horizontally */
    flex-wrap: wrap; /* Allow wrapping to next line if needed */
    gap: 2px; /* Optional: Adjust spacing between items */
}

.info > div {
    flex: 1;
    min-width: 100px; /* Optional: Adjust minimum width if needed */
    text-align: center; /* Center text within each item */
}

.hr-container {
    text-align: center; /* Center the horizontal rule */
    margin-bottom: 10px; /* Optional: Adjust spacing below the horizontal rule */
}

hr {
    border: 0;
    border-top: 2px solid #ccc; /* Adjust color and thickness as needed */
    margin: 0 auto; /* Center the horizontal rule */
    width: 100%; /* Adjust width as needed */
}

.label {
    font-weight: bold;
}

.value {
    margin-left: 5px; /* Space between label and value */
}
.file{
  align-items: center;
}
.action-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 10px; /* Optional: to add space between buttons */
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
        }  </style>
</head>
<body>
  <div class="container">
    <?php if ($result && $user = $result->fetch_assoc()): ?>
      <div class="profile-card">
        <div class="profile-container">
          <img src="../employer/assets/images/<?php echo htmlspecialchars($user['profile'] ?? 'no-image.png'); ?>" alt="Profile Image" class="profile-image">
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
          </div>
        </div>
        
        <div class="hr-container">
          <hr>
        </div>
        <div class="valid-id-section">
          <label>Valid ID</label>
          <iframe src="<?php echo htmlspecialchars($user['valid_id_path']); ?>" width="100%" height="600px" style="border: none;"></iframe>
        </div>
        <div class="action-buttons">
          <a href="jobseekerofferdetails.php?id=<?php echo htmlspecialchars($jr_id); ?>" class="btn-hire" onclick="return confirm('Are you sure you want to view the job details?');">Proceed</a>
          <button class="close-button" onclick="window.location.href='jobseekerinbox.php'">Back</button>
        </div>
      </div>
    <?php else: ?>
      <p>No user data found.</p>
    <?php endif; ?>
  </div>
</body>
</html>