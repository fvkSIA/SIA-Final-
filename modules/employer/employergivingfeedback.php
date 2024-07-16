<?php 
require '/xampp/htdocs/SIA-Final-/vendor/autoload.php';
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';

$sentiment = new \Sentiment\Analyzer();
session_start();

$message = '';
$result = null;
$showModal = false;
$total = 0;


// $output_text = $sentiment->getSentiment("David is smart, handsome, and funny.");
// $total += $output_text['pos'];
// $output_text = $sentiment->getSentiment("He is very talented");
// $total += $output_text['pos'];

// print_r($total);

// die();
if($_SERVER["REQUEST_METHOD"] == "POST") {
  $job_req_id = $_POST['job_req_id'];
  $user_id = $_POST['user_id'];

  $update_flag = $_POST['update'] ?? 0;
  if ($update_flag == 1) {
    $reviewStr = $_POST['review_text'];
    $proof_img = $_FILES['image_proof']['name'];
    $img_destination = '../jobseeker/assets/image_proofs/' . $proof_img;
    $points = $sentiment->getSentiment($reviewStr);
    $positivePts = $points['pos'];
    // print_r(['review' => $reviewStr, 'img' => $proof_img, 'points' => $points['pos'], 'job_req_id' => $job_req_id, 'user_id' => $user_id]);
    // die();

    $update_rating = "UPDATE ratings SET points = ?, reviews = ?, proof_img = ? WHERE user_id = ? AND job_req_id = ?";
    if ($update = $conn->prepare($update_rating)){
      $update->bind_param('dssii', $positivePts, $reviewStr, $proof_img, $user_id, $job_req_id);
      if($update->execute()){
        // if success move file to directory
        move_uploaded_file($_FILES['image_proof']['tmp_name'], $img_destination);
        $update->close();
        header('Location: employerongoingdetails.php');
        // die();
      }
    }

  }

  // get user details
  $user_details = "SELECT * FROM users WHERE id = ?";

  if ($select = $conn->prepare($user_details)) {
    $select->bind_param("i", $user_id);
    $select->execute();
    $result = $select->get_result() ?? null;
    $select->close();
  }

  
 //  update request status from ongoing to done 0 = ongoing , 1 = done
 $update_status = "UPDATE job_requests SET status = 1 WHERE id = ?";
 //  add ratings
 $review = "INSERT INTO ratings (user_id, job_req_id) VALUE (?,?)";

  if ($stmt = $conn->prepare($update_status)){
    // updated 
    $stmt->bind_param('i', $job_req_id);
    if($stmt->execute()){
      // success
      if ($stmt2 = $conn->prepare($review)){
        $stmt2->bind_param('ii', $user_id, $job_req_id);
        if ($stmt2->execute()){
          $showModal = true;
          
          $stmt2->close();
          // echo "success"; 
          // die();
        }
        $stmt->close();
      }
    } else {
      $message = 'an error occured';
    }
  }

  

  // print_r($_POST);
  // die();
}
$conn->close();

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employer Dashboard</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <style>
    .review-container {
      background-color: #ffffff;
      margin-top: 80px;
      width: 100%;
      border-radius: 10px;
      height: auto; /* Adjusted height */
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: center;
      position: relative;
      justify-content: center;
    }
    .profile-pic {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      margin-bottom: 10px;
      position: absolute;
      top: -60px; /* Adjusted top position */
      left: 50%;
      transform: translateX(-50%);
    }
    .work {
      color: #1E3B85;
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      font-size: 36px; /* Adjusted font size */
      padding-top: 6%;
    }
    .location {
      color: #000;
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      font-size: 20px;
      margin-top: 5px;
    }
    .worker {
      color: #000;
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      font-size: 18px; /* Adjusted font size */
      margin-top: 10px;
    }
    .name {
      color: #00b300;
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      font-size: 18px; /* Adjusted font size */
      margin-top: -5px; /* Adjusted margin */
    }
    .tag {
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      font-size: 13px;
      margin-top: 20px; /* Adjusted margin */
      font-style: italic;
    }
    .stars {
      margin: 15px 0;
    }
    .stars input[type="radio"] {
      display: none;
    }
    .stars label {
      font-size: 40px; /* Adjusted font size */
      color: #ffc107;
      cursor: pointer;
    }
    .review-text {
      width: 100%;
      padding: 10px;
      height: 150px;
      margin-top: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      resize: vertical; /* Allow vertical resizing */
    }
    .send-button {
      background-color: #3b5998;
      color: #ffffff;
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      padding: 10px;
      width: 25%;
      border: none;
      border-radius: 10px;
      font-size: 15px;
      cursor: pointer;
      margin-top: 20px;
      margin: 0 auto; /* Ito ang pag-update para i-center horizontally */
      margin-bottom: 5%;
  }
  
    .label {
      font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      font-size: 13px;
      font-style: italic;
      margin-top: 20px; /* Adjusted margin */
      display: block;
      text-align: left; /* Align left for labels */
      text-align: center;
    }
    #imagePreview {
      margin-top: 10px;
    }
  </style>
</head>
<body>
<?php 
    $data = [];
    if ($result != null) {
    $row = $result->fetch_assoc();
    } else {
    echo '';
    }

  ?>

  <form action="employergivingfeedback.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="update" value="1">
    <input type="hidden" name="job_req_id" value="<?php echo $job_req_id ?? 0?>">
    <input type="hidden" name="user_id" value="<?php echo $user_id ?? 0?>">

    <div class="review-container">
    <img src="../jobseeker/assets/images/<?php echo $row['profile'] ?? 'no-image.png' ?>" alt="Profile Picture" class="profile-pic">
    <h2 class="work"><?php echo $row['job_type'] ?? '';?></h2>
    <p class="location"><?php echo $row['city'] ?? '';?></p>
    <p class="worker"><b>EMPLOYED WORKER:</b></p>
    <p class="name"><b><?php echo $row['firstname'] ?? ''?> <?php echo $row['lastname'] ?? '';?></b></p>
    <!-- <p class="tag">Please rate your experience:</p> -->
    <!-- <div class="stars">
      <input type="radio" id="star5" name="rating" value="5"><label for="star5">★</label>
      <input type="radio" id="star4" name="rating" value="4"><label for="star4">★</label>
      <input type="radio" id="star3" name="rating" value="3"><label for="star3">★</label>
      <input type="radio" id="star2" name="rating" value="2"><label for="star2">★</label>
      <input type="radio" id="star1" name="rating" value="1"><label for="star1">★</label>
    </div> -->
    <label class="label" for="image_proof">Proof of completion</label><br>
    <input type="file" id="image_proof" accept="image/*" name="image_proof" style="padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; color: #333; background-color: #fff;">
    <div id="imagePreview"></div>
    <label class="label" for="reviewText">Tell us more about the job completed.</label>
    <textarea id="reviewText" name="review_text" class="review-text" minlength="1" maxlength="500" placeholder="Type review here." required style="width: 300px;"></textarea><br>
    <button class="send-button" type="submit">SEND REVIEW</button>
  </div>

  </form>
  


  <script>
    document.getElementById('image_proof').addEventListener('change', function() {
      var input = this;

      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          var img = document.createElement('img');
          img.src = e.target.result;
          img.style.maxWidth = '100%'; // Adjust as needed
          document.getElementById('imagePreview').innerHTML = ''; // Clear previous preview
          document.getElementById('imagePreview').appendChild(img); // Append new image preview
        };

        reader.readAsDataURL(input.files[0]);
      }
    });
  </script>

</body>
</html>
