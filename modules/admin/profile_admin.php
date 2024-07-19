<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
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
    <div class="container">
        <div class="card w-100 mt-5">
            <div class="card-body">
                <h5 class="card-title h3">Hi! <?php echo $_SESSION['name'];?></h5>
                <p class="card-text"><?php echo $_SESSION['email']?></p>
                <a href="userviewprofile.php?id=<?php echo $_SESSION['user_id'];?>" class="btn btn-primary float-right">EDIT PROFILE</a>
            </div>
        </div>
        <?php if(isset($_SESSION['flash'])):?>
            <div class="alert alert-success my-3" role="alert">
                Success!
            </div>
            <?php unset($_SESSION["flash"]);?>
        <?php endif;?>
    </div>

    
    <!-- Modal Areas -->
    <div id="editModal" class="modal">
      <div class="modal-content">
          <span class="close">&times;</span>
          <h1 style="font-weight: bold;">Edit Profile</h1>
          <iframe src="admin_profile.html" width="100%" height="400"></iframe>
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
