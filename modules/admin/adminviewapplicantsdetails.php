<?php 

require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
$id = $_GET['id'];

$users = "SELECT users.*, user_types.id as user_type_id, user_types.description as user_type_desc FROM users
    INNER JOIN user_types ON users.type = user_types.id
    WHERE users.id = ?";

if ($stmt = $conn->prepare($users)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result() ?? null;

    $stmt->close();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $verify = $_POST['verify'];

    $sql = "UPDATE users SET verified=? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $verify, $user_id);
        if ($stmt->execute()){
            // success
            header('location: adminregistration.php');
        } else {
            echo "ERROR APPROVING USER";
        }
        
    
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap');

        

.form-control:focus {
    box-shadow: none;
    border-color: #BA68C8
}

.profile-button {
    background: rgb(99, 39, 120);
    box-shadow: none;
    border: none
}

.profile-button:hover {
    background: #682773
}

.profile-button:focus {
    background: #682773;
    box-shadow: none
}

.profile-button:active {
    background: #682773;
    box-shadow: none
}

.back:hover {
    color: #682773;
    cursor: pointer
}

.labels {
    font-size: 11px
}

.add-experience:hover {
    background: #BA68C8;
    color: #fff;
    cursor: pointer;
    border: solid 1px #BA68C8
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
    <nav aria-label="breadcrumb" class="m-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="adminregistration.php">Users</a></li>
            <li class="breadcrumb-item active" aria-current="page">Profile</li>
        </ol>
    </nav>
<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                <img class="rounded-circle mt-5" width="150px" height="150" src="<?php echo $data['type'] == 3 ? '../employer/assets/images/' : '../jobseeker/assets/images/'?><?php echo $data['profile']?>">
                <span class="font-weight-bold"><?php echo $data['firstname'];?></span>
                <span class="text-black-50"><?php echo $data['email'];?></span>
                <span> 

                </span>
            </div>
        </div>
        <div class="col-md-5 border-right">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Profile</h4>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6"><label class="labels">First name</label><input type="text" class="form-control" placeholder="first name" value="<?php echo $data['firstname'];?>" disabled></div>
                    <div class="col-md-6"><label class="labels">Last name</label><input type="text" class="form-control" value="<?php echo $data['lastname'];?>" placeholder="last name" disabled></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Phone Number</label><input type="text" class="form-control" placeholder="enter phone number" value="<?php echo $data['phone_number'];?>" disabled></div>
                    <div class="col-md-12"><label class="labels">Home Address</label><input type="text" class="form-control" placeholder="enter home address" value="<?php echo $data['home_address'];?>" disabled></div>
                    <div class="col-md-12"><label class="labels">City</label><input type="text" class="form-control" placeholder="enter city" value="<?php echo $data['city'];?>" disabled></div>
                    <div class="col-md-12"><label class="labels">Birthdate</label><input type="text" class="form-control" placeholder="enter birthdate" value="<?php $date=date_create($data['birthdate']); echo date_format($date,"M d, Y");?>" disabled></div>
                    <div class="col-md-12"><label class="labels">Gender</label><input type="text" class="form-control" placeholder="enter gender" value="<?php echo $data['gender'];?>" disabled></div>
                    <div class="col-md-12"><label class="labels">Type</label><input type="text" class="form-control" placeholder="enter email id" value="<?php echo $data['user_type_desc'];?>" disabled></div>
                    <!-- <div class="col-md-12"><label class="labels">Education</label><input type="text" class="form-control" placeholder="education" value=""></div> -->
                </div>
                <div class="row mt-3">
                    <!-- <div class="col-md-6"><label class="labels">Country</label><input type="text" class="form-control" placeholder="country" value=""></div>
                    <div class="col-md-6"><label class="labels">State/Region</label><input type="text" class="form-control" value="" placeholder="state"></div> -->
                </div>
                <div class="mt-5 text-center">
                    <form action="adminviewapplicantsdetails.php" method="POST" id="approval_form">
                        <input type="hidden" name="user_id" value="<?php echo $id;?>">
                        <input type="hidden" name="verify" value="1">

                        <button class="btn btn-success" type="submit">Approve</button>
                        <a href="adminregistration.php" class="btn btn-danger mx-2" type="button">Cancel</a>
                    </form>
                    
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center experience"><span>Files</span></div><br>
                <div class="col-md-12"><label class="labels">Resume</label><br><a href="#">resume.pdf</a></div> <br>
                <!-- <div class="col-md-12"><label class="labels">Birth Certificate</label><input type="text" class="form-control" placeholder="" value=""></div> -->
            </div>
        </div>
    </div>
</div>
</div>
</div>
    <!-- <main class="view">
        <div class="half-color-bg p-6">
            <button class="close-btn" onclick="closeDiv()">X</button>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <img src="1.jpg" alt="Circular Image" style="width: 200px; height: 200px; border-radius: 50%; object-fit: cover;">
                <p style="padding-top: 50px; font-weight: bold; font-size: 35px;">JOHN CARLO JAVALLA</p>
                <i style="color: red; padding-top: 50px;">backend echo</i>
            </div>
        </div>

        <div class="bg-#f1f1f1 p-6 mb-3">
            <div style="display: flex; justify-content: space-between; max-width: 100%; margin: 0 auto;">
                <div style="width: 13%; margin-left: 30px; box-sizing: border-box;">
                    <p style="font-weight: bold;">
                        APPLIED FOR:
                    </p>
                    <p style="margin-top: 15px; font-weight: bold;">
                        AGE:
                    </p>
                    <p style="margin-top: 15px; font-weight: bold;">
                        ADDRESS:
                    </p>
                    <p style="margin-top: 15px; font-weight: bold;">
                        SEX:
                    </p>
                    <p style="margin-top: 15px; font-weight: bold;">
                        CONTACT NUMBER:
                    </p>
                    <p style="margin-top: 15px; font-weight: bold;">
                        EMAIL ADDRESS:
                    </p>
                </div>
                <div style="width: 25%; box-sizing: border-box;">
                    <p>
                        DRIVER <i style="color: red;">backend echo</i>
                    </p>
                    <p style="margin-top: 15px;">
                        23 <i style="color: red;">backend echo</i>
                    </p>
                    <p style="margin-top: 15px;">
                        DIMAKITA ST. <i style="color: red;">backend echo</i>
                    </p>
                    <p style="margin-top: 15px;">
                        UNKNOWN <i style="color: red;">backend echo</i>
                    </p>
                    <p style="margin-top: 15px;">
                        09123456789 <i style="color: red;">backend echo</i>
                    </p>
                    <p style="margin-top: 15px;">
                        JCMASARAP123@GMAIL.COM <i style="color: red;">backend echo</i>
                    </p>
                </div>
                <div style="width: 50%; margin-left: 20px; box-sizing: border-box;">
                    <p style="font-weight: bold;">RESUME:</p>
                    <p style="margin-top: 5px; margin-left: 170px; color: red;">backend echo</p>
                    <p style="margin-top: 10px; font-weight: bold;">2 VALID ID'S</p>
                    <p style="margin-top: 5px; margin-left: 170px; color: red;">backend echo 1 backend echo 2</p>
                    <p style="margin-top: 10px; font-weight: bold;">BIRTH CERTIFICATE:</p>
                    <p style="margin-top: 5px; margin-left: 170px; color: red;">backend echo</p>
                    <p style="margin-top: 10px; font-weight: bold;">RECENT JOB:</p>
                    <p style="margin-top: 5px; margin-left: 170px; color: red;">backend echo</p>
                </div>
            </div>
        </div>

        <div class="btn-container">
            <button class="approve-btn" onclick="approve()">APPROVE</button>
            <button class="decline-btn" onclick="decline()">DECLINE</button>
        </div>
    </main> -->

    <script>
        function closeDiv() {
            window.location.href = 'adminregistration.php';
        }

        function approve() {
            // Handle approve button action
            console.log('Approved');
        }

        function decline() {
            // Handle decline button action
            console.log('Declined');
        }
    </script>
</body>
</html>
