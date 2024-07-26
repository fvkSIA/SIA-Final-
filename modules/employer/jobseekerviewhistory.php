<?php 
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$user = null;
$unique_images = [];

if($_SERVER["REQUEST_METHOD"] == "GET") {
  $id = $_GET['id'];
  
  // Query to fetch user data and associated proof_img
  $sql = "
    SELECT users.*, ratings.proof_img 
    FROM users 
    LEFT JOIN ratings ON users.id = ratings.user_id 
    WHERE users.id = ?
  ";

  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result() ?? null;

    if ($result) {
      while ($row = $result->fetch_assoc()) {
        $user = $row;
        $proof_img = $row['proof_img'];
        if ($proof_img && !in_array($proof_img, $unique_images)) {
          $unique_images[] = $proof_img;  // Store unique proof_img
        }
      }
    }
    $stmt->close();
  }
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jobseeker Dashboard</title>
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <style>
    #myButton {
      display: inline-block;
      padding: 10px 20px;
      font-size: 16px;
      font-weight: bold;
      color: #fff;
      background-color: #007bff;
      border: none;
      border-radius: 5px;
      text-align: center;
      text-decoration: none;
      transition: background-color 0.3s ease, transform 0.3s ease;
    }
    #myButton:hover {
      background-color: #0056b3;
      transform: scale(1.05);
    }
    #myButton:active {
      background-color: #004494;
      transform: scale(1);
    }
    .rounded-lg {
      max-width: 100%;
      overflow: hidden;
      word-wrap: break-word;
    }
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      position: relative; /* Make sure the body is positioned relatively */
    }
    .custom-div {
      width: 100%;
      max-width: 800px;
      padding: 20px;
      background: #fff;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      box-sizing: border-box;
    }
    .first-letter-uppercase {
      text-transform: capitalize;
    }
    .responsive-text {
      font-size: 2.5rem; /* Default size */
    }
    .button-container {
      text-align: center;
    }
    .top-left-container {
      position: flex; /* Positioning it absolutely */
      top: 20px; /* Distance from the top */
      left: 20px; /* Distance from the left */
    }
    @media (max-width: 768px) {
      .responsive-text {
        font-size: 2rem; /* Smaller size for tablets */
      }
    }
    @media (max-width: 640px) {
      .responsive-text {
        font-size: 1.5rem; /* Smaller size for phones */
      }
    }
    /* Modal styles */
    .modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1000; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0,0,0,0.7); /* Black background with opacity */
    }

    /* Modal content */
    .modal-content {
    margin: auto;
    display: block;
    width: 80%; /* Adjust as needed */
    max-width: 700px;
    }

    /* Image inside modal */
    .modal img {
    width: 100%;
    height: auto;
    }

    /* Close button */
    .close {
    position: absolute;
    top: 10px;
    right: 25px;
    color: #f1f1f1;
    font-size: 35px;
    font-weight: bold;
    }

    .close:hover,
    .close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
    }

  </style>
</head>
<body>
  <!-- Back Button Positioned at Top-Left -->
 

  <div class="custom-div">
  <div class="top-left-container">
    <a id="myButton" href='jobseekerviewprofile.php?id=<?php echo $user['id'];?>'>Back</a>
  </div>
    <div class="flex flex-col items-center mb-4">
      <img src="../jobseeker/assets/images/<?php echo $user['profile'] ?? 'no-image.png';?>" class="w-32 h-32 rounded-full mb-2" alt="Profile picture of driver">
      <h1 class="font-bold text-center responsive-text"><?php echo $user['firstname'] . ' ' . $user['lastname'];?></h1>
    </div>
    <div class="space-y-2 text-sm text-gray-600 ml-5">
      <div class="flex flex-col md:flex-row justify-center">
        <div class="flex-item pr-8">
          <span class="font-semibold">Work Type:</span>
          <span class="ml-2 text-blue-500 font-semibold underline"><?php echo $user['job_type'];?></span>
        </div>
        <div class="flex-item pr-8">
          <span class="font-semibold">Age:</span>
          <span class="ml-2">
            <?php 
            $birthdate = $user['birthdate'];
            $currentDate = date('Y-m-d');
            $age = date_diff(date_create($birthdate), date_create($currentDate))->y;
            echo "$age years old";
            ?>
          </span>
        </div>
        <div class="flex-item pr-8">
          <span class="font-semibold">Gender:</span>
          <span class="ml-2"><?php echo $user['gender'];?></span>
        </div>
        <div class="flex-item pr-8">
          <span class="font-semibold">Location:</span>
          <span class="ml-2"><?php echo $user['city'];?></span>
        </div>
        <div class="flex-item">
          <span class="font-semibold">View:</span>
          <a class="ml-2 underline" href='jobseekerviewhistory.php?id=<?php echo $user['id'];?>'>History</a>
        </div>
      </div>
    </div>
    <!-- Display Unique Proof Image(s) -->
    <?php if (!empty($unique_images)): ?>
  <div class="mt-4">
    <h2 class="text-lg font-semibold mb-2">Proof Images</h2>
    <div class="flex flex-wrap gap-2">
      <?php foreach ($unique_images as $image): ?>
        <div class="relative w-24 h-24 bg-gray-200 rounded-lg overflow-hidden">
          <img src="../jobseeker/assets/image_proofs/<?php echo $image;?>" alt="Proof Image" class="clickable-image w-full h-full object-cover" data-image="../jobseeker/assets/image_proofs/<?php echo $image;?>">
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>


    <div class="mt-4 p-4 border-t border-gray-300 flex justify-center">
      <div class="button-container">
        <a id="myButton" href='hireform.php?id=<?php echo $user['id'];?>'>Give Offer</a>
        <!-- Removed the Back button from here -->
      </div>
    </div>
  </div>

<script>
    // Get all the images with the class 'clickable-image'
var images = document.querySelectorAll('.clickable-image');

// Get the modal
var modal = document.createElement('div');
modal.className = 'modal';
document.body.appendChild(modal);

// Create the modal content
var modalImg = document.createElement('img');
modalImg.className = 'modal-content';
modal.appendChild(modalImg);

// Create the close button
var closeButton = document.createElement('span');
closeButton.className = 'close';
closeButton.innerHTML = '&times;';
modal.appendChild(closeButton);

// Add event listeners to images
images.forEach(function(image) {
  image.addEventListener('click', function() {
    modal.style.display = 'block';
    modalImg.src = image.getAttribute('data-image');
  });
});

// Add event listener to close button
closeButton.addEventListener('click', function() {
  modal.style.display = 'none';
});

// Close modal if the user clicks anywhere outside the modal
window.addEventListener('click', function(event) {
  if (event.target === modal) {
    modal.style.display = 'none';
  }
});

</script>
</body>
</html>
