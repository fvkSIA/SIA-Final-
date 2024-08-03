<?php 
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();

$id = $_SESSION['user_id'];

$users = "SELECT users.*, user_types.id as user_type_id, user_types.description as user_type_desc FROM users
    INNER JOIN user_types ON users.type = user_types.id
    WHERE users.id = ?";

if ($stmt = $conn->prepare($users)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result() ?? null;
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];

    if (isset($_FILES['profile_pic'])) {
        $profilePic = $_FILES['profile_pic'];
        $targetDir = "../employer/assets/images/";
        $targetFile = $targetDir . basename($profilePic["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($profilePic["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($profilePic["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($profilePic["tmp_name"], $targetFile)) {
                $sql = "UPDATE users SET profile = ? WHERE id = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("si", $profilePic["name"], $user_id);
                    if ($stmt->execute()) {
                        // success
                        header('location: profile_employer.php');
                    } else {
                        echo "ERROR UPDATING USER";
                    }
                    $stmt->close();
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $phone = $_POST['phone_number'];
        $home_add = $_POST['home_address'];
        $city = $_POST['city'];
        $bio = $_POST['bio'];

        $sql = "UPDATE users SET bio = ?, phone_number = ?, home_address = ?, city = ? WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssssi", $bio, $phone, $home_add, $city, $user_id);
            if ($stmt->execute()) {
                // success
                header('location: profile_employer.php');
            } else {
                echo "ERROR UPDATING USER";
            }
            $stmt->close();
        }
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
    <link rel="icon" type="image/png" href="../HanapKITA.png">
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
.profile-pic {
            position: relative;
            width: 150px;
            height: 150px;
        }
        .profile-pic input[type="file"] {
            display: none;
        }
        .profile-pic label {
            cursor: pointer;
            display: block;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }
    </style>
</head>
<body>
<?php 
    $data = [];
    if ($result != null) $data = $result->fetch_assoc();
    ?>
    <nav aria-label="breadcrumb" class="m-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="profile_employer.php">Profile</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>
    <div class="container rounded bg-white mt-5 mb-5">
        <div class="row">
            <div class="col-md-3 border-right">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                    <div class="profile-pic">
                        <img class="rounded-circle mt-5" width="150px" height="150" src="<?php echo $data['type'] == 3 ? '../employer/assets/images/' : '../jobseeker/assets/images/'?><?php echo $data['profile']?>">
                        <form action="employereditprofile.php" method="POST" enctype="multipart/form-data">
                            <input type="file" name="profile_pic" accept="image/*" id="fileInput" onchange="this.form.submit()">
                            <label for="fileInput" id="changeProfilePicButton"></label>
                        </form>
                    </div>
                    <span class="font-weight-bold"><?php echo $data['firstname'];?></span>
                    <br>
                    <span class="text-black-50"><?php echo $data['email'];?></span>
                    <button class="btn btn-primary mt-3" onclick="document.getElementById('fileInput').click()">Change Profile Picture</button>
                </div>
            </div>
            <div class="col-md-5 border-right">
                <div class="p-3 py-5">
                    <form action="employereditprofile.php" method="POST">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Profile</h4>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6"><label class="labels">First name</label><input type="text" class="form-control" placeholder="first name" value="<?php echo $data['firstname'];?>" disabled></div>
                        <div class="col-md-6"><label class="labels">Last name</label><input type="text" class="form-control" value="<?php echo $data['lastname'];?>" placeholder="last name" disabled></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12"><label class="labels">Phone Number</label><input type="text" class="form-control" placeholder="enter phone number" value="<?php echo $data['phone_number'];?>" name="phone_number" ></div>
                        <div class="col-md-12"><label class="labels">Home Address</label><input type="text" class="form-control" placeholder="enter home address" value="<?php echo $data['home_address'];?>" name="home_address"></div>
                        <div class="col-md-12">
                        <label class="labels">City</label>
                        <select class="form-control" name="city" required>
                            <option value="">Select Location</option>
                            <option value="Manila" <?php if($data['city'] == 'Manila') echo 'selected'; ?>>Manila</option>
                            <option value="Caloocan" <?php if($data['city'] == 'Caloocan') echo 'selected'; ?>>Caloocan</option>
                            <option value="Pasay" <?php if($data['city'] == 'Pasay') echo 'selected'; ?>>Pasay</option>
                            <option value="Makati" <?php if($data['city'] == 'Makati') echo 'selected'; ?>>Makati</option>
                            <option value="QuezonㅤCity" <?php if($data['city'] == 'QuezonㅤCity') echo 'selected'; ?>>QuezonㅤCity</option>
                            <option value="Navotas" <?php if($data['city'] == 'Navotas') echo 'selected'; ?>>Navotas</option>
                            <option value="LasㅤPiñas" <?php if($data['city'] == 'LasㅤPiñas') echo 'selected'; ?>>LasㅤPiñas</option>
                            <option value="Malabon" <?php if($data['city'] == 'Malabon') echo 'selected'; ?>>Malabon</option>
                            <option value="Mandaluyong" <?php if($data['city'] == 'Mandaluyong') echo 'selected'; ?>>Mandaluyong</option>
                            <option value="Marikina" <?php if($data['city'] == 'Marikina') echo 'selected'; ?>>Marikina</option>
                            <option value="Muntinlupa" <?php if($data['city'] == 'Muntinlupa') echo 'selected'; ?>>Muntinlupa</option>
                            <option value="Parañaque" <?php if($data['city'] == 'Parañaque') echo 'selected'; ?>>Parañaque</option>
                            <option value="Pasig" <?php if($data['city'] == 'Pasig') echo 'selected'; ?>>Pasig</option>
                            <option value="SanㅤJuan" <?php if($data['city'] == 'SanㅤJuan') echo 'selected'; ?>>SanㅤJuan</option>
                            <option value="Taguig" <?php if($data['city'] == 'Taguig') echo 'selected'; ?>>Taguig</option>
                            <option value="Valenzuela" <?php if($data['city'] == 'Valenzuela') echo 'selected'; ?>>Valenzuela</option>
                            <option value="Pateros" <?php if($data['city'] == 'Pateros') echo 'selected'; ?>>Pateros</option>
                        </select>
                        </div>

                        <div class="col-md-12"><label class="labels">Birthdate</label><input type="text" class="form-control" placeholder="enter birthdate" value="<?php $date=date_create($data['birthdate']); echo date_format($date,"M d, Y");?>" disabled></div>
                        <div class="col-md-12"><label class="labels">Gender</label><input type="text" class="form-control" placeholder="enter gender" value="<?php echo $data['gender'];?>" disabled></div>
                        <div class="col-md-12"><label class="labels">Type</label><input type="text" class="form-control" placeholder="enter email id" value="<?php echo $data['user_type_desc'];?>" disabled></div>
                        <div class="col-md-12"><label class="labels">Bio</label><textarea type="text" class="form-control" placeholder="Bio" name="bio" ><?php echo $data['bio'];?></textarea></div>
                        <!-- <div class="col-md-12">
                            <label for="avail" class="labels">Status</label>
                            <select name="status" id="avail" class="form-select">
                                <?php if($data['availability'] == 1):?>
                                    <option value="1" selected>Available</option>
                                    <option value="0">Not Available</option>
                                <?php else:?>
                                    <option value="1">Available</option>
                                    <option value="0" selected>Not Available</option>
                                <?php endif;?>
                                
                            </select>
                        </div> -->
                        <!-- <div class="col-md-12"><label class="labels">Education</label><input type="text" class="form-control" placeholder="education" value=""></div> -->
                    </div>
                    <div class="row mt-3">
                        <!-- <div class="col-md-6"><label class="labels">Country</label><input type="text" class="form-control" placeholder="country" value=""></div>
                        <div class="col-md-6"><label class="labels">State/Region</label><input type="text" class="form-control" value="" placeholder="state"></div> -->
                    </div>
                    <div class="mt-5 text-center">
                        
                        
                        <button class="btn btn-primary" type="submit">Update</button>
                        <a href="profile_employer.php" class="btn btn-danger mx-2" type="button">Cancel</a>
                        
                    
                    </div>
                </form>
                
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 py-5">
                <label class="labels">Valid ID / Birth Certificate</label><br>
                <a href="<?php echo $data['valid_id_path']; ?>" target="_blank">View Valid ID/Birth Certificate</a>
            </div>
        </div>
    </div>
</div>
</div>
</div>
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

        document.getElementById('changeProfilePicButton').addEventListener('click', function() {
            document.getElementById('fileInput').click();
        });
    </script>
</body>
</html>
