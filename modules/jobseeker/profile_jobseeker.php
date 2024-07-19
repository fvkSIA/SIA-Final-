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
                <img src="../jobseeker/assets/images/<?php echo $data['profile'] ?? 'no-image.png'?>" alt="no image" class="rounded-full w-25 h-25 md:w-40 md:h-40 lg:w-40 lg:h-40 border-4 border-white -mt-50 mr-6">
                <div class="ml-4">
                    <h1 class="text-4xl font-bold text-gray-800"><?php echo $data['firstname'];?> <?php echo $data['lastname'];?></h1>
                    <p class="text-2xl text-gray-600">
                        <i class="fas fa-envelope mr-3"></i>&nbsp;<?php echo $data['email'];?>
                    </p>
                    <p class="text-2xl text-gray-600 flex items-center">
                        <i class="fas fa-phone mr-3"></i>&nbsp;<?php echo $data['phone_number'];?>
                    </p>
                    <p class="text-2xl text-gray-600 flex items-center">
                        <i class="fas fa-map-marker-alt mr-4"></i>&nbsp;<?php echo $data['home_address'];?>
                    </p>
                    <p class="text-2xl text-gray-600 flex items-center">
                        <!-- <i class="fas fa-medal mr-3"></i>&nbsp;no rank yet -->
                    </p>
                    <p class="text-2xl text-gray-600 flex items-center text-emerald-600">
                        <?php if($data['availability'] == 1):?>
                            <i class="fas fa-circle mr-3 text-emerald-600" ></i>&nbsp;Available
                        <?php else:?>
                            <i class="fas fa-circle mr-3 text-red-600" ></i>&nbsp;Not Available
                        <?php endif;?>
                        
                    </p>
                </div>
            </div>
            <a class="text-blue-500 border border-blue-500 rounded-lg px-4 py-2 mr-10" href="jobseekereditprofile.php">Edit</a>
        </div>
        
        <div class="p-4">
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-[#4B5EAB]">Personal Summary</h2>
                <p class="text-gray-500 mt-1"><?php echo $data['bio'];?></p>
                <!-- <button class="text-blue-500 border border-blue-500 rounded-lg px-4 py-2 mt-1" id="addsummaryBtn">Add Summary</button> -->
            </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Areas -->
    <div id="editModal" class="modal">
      <div class="modal-content">
          <span class="close">&times;</span>
          <h1 style="font-weight: bold;">Edit Profile</h1>
          <iframe src="jobseeker_profile.html" width="100%" height="400"></iframe>
      </div>
    </div>
  
    
    <div id="summaryModal" class="modal">
      <div class="modal-content">
          <span class="close">&times;</span>
          <h1 style="font-weight: bold;">Add Summary</h1>
          <iframe src="modalsummary.html" width="100%" height="400"></iframe>
      </div>
    </div>


    
    


    

    <script>
        const modals = {
            editBtn: 'editModal',
            addsummaryBtn: 'summaryModal',
        };

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
