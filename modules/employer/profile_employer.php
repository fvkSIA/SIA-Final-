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
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 3% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 100%;
            max-width: 95%;
            border-radius: 10px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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
        <div class="flex justify-between items-center p-4 rounded-t-lg bg-indigo-100">
            <div class="flex items-center" style="padding: 1%;">
                <img src="../employer/assets/images/<?php echo $data['profile'] ?? 'no-image.png'?>" alt="image" class="rounded-full w-25 h-25 md:w-40 md:h-40 lg:w-40 lg:h-40 border-4 border-white -mt-50 mr-6">
                <div class="ml-4">
                    <h1 class="text-4xl font-bold text-gray-800"><?php echo $data['firstname'];?> <?php echo $data['lastname'];?></h1>
                    <p class="text-2xl text-gray-600">
                        <i class="fas fa-envelope mr-3"></i>&nbsp;<?php echo $data['email'];?>
                    </p>
                    <p class="text-2xl text-gray-600 flex items-center">
                        <i class="fas fa-phone mr-3"></i>&nbsp;<?php echo $data['phone_number'];?>
                    </p>
                    <p class="text-2xl text-gray-600 flex items-center">
                        <i class="fas fa-map-marker-alt mr-4"></i>&nbsp;<?php echo $data['home_address'].',';?>
                        <?php 
                        $sql_city = "SELECT city FROM users WHERE id = ?";
                        $stmt_city = $conn->prepare($sql_city);
                        $stmt_city->bind_param("i", $user_id);
                        $stmt_city->execute();
                        $result_city = $stmt_city->get_result();
                        
                        if ($result_city->num_rows > 0) {
                            while($row = $result_city->fetch_assoc()) {
                                echo htmlspecialchars($row["city"]) . "<br>";
                            }
                        } else {
                            echo "0 results";
                        }
                        ?>
                    </p>
                </div>
            </div>
            <a class="text-blue-500 border border-blue-500 rounded-lg px-4 py-2 mr-10" href="employereditprofile.php">Edit</a>
        </div>
        
        <div class="p-6">
            <div class="mb-1">
                <h2 class="text-2xl font-semibold text-[#4B5EAB]">Personal Summary</h2>
                <p class="text-gray-500 mb-4"><?php echo $data['bio'];?></p>
                <!-- <button class="text-blue-500 border border-blue-500 rounded-lg px-4 py-2 mt-1" id="addsummaryBtn">Add Summary</button> -->
            </div>
        </div>
    
                    
                    
                    
                   
                  
                  <script>
                      document.getElementById('resume-upload').addEventListener('change', function(event) {
                          const file = event.target.files[0];
                          if (file) {
                              const reader = new FileReader();
                              reader.onload = function(e) {
                                  const pdfViewer = document.getElementById('pdf-viewer');
                                  pdfViewer.setAttribute('src', e.target.result);
                                  document.getElementById('pdf-container').style.display = 'block';
                              };
                              reader.readAsDataURL(file);
                          }
                      });
                  </script>
                                
                
            </div>
    </div>

    <!-- Modal Areas -->
    <div id="editModal" class="modal">
      <div class="modal-content">
          <span class="close">&times;</span>
          <h1 style="font-weight: bold;">Edit Profile</h1>
          <iframe src="employer_profile.html" width="100%" height="400"></iframe>
      </div>
    </div>
  
    
   

    
    
    


    

    <script>
    

        Object.keys(modals).forEach(id => {
            document.getElementById(id).onclick = function() {
               
              const modalId = modals[id];
              const modal = document.getElementById(modalId);
              const span = modal.getElementsByClassName("close")[0];
          
              modal.style.display = "block";
          
              span.onclick = function() {
                  modal.style.display = "none";
              }
          
              window.onclick = function(event) {
                  if (event.target == modal) {
                      modal.style.display = "none";
                  }
              }
          };
      });
  </script>
</body>
</html>
