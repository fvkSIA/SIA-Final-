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
  <div class="review-container">
    <img src="../jobseeker/assets/images/no-image.png" alt="Profile Picture" class="profile-pic">
    <h2 class="work">DRIVER</h2>
    <p class="location">Quezon City</p>
    <p class="worker"><b>EMPLOYED WORKER:</b></p>
    <p class="name"><b>JOSEPH BERSOTO</b></p>
    <!-- <p class="tag">Please rate your experience:</p> -->
    <!-- <div class="stars">
      <input type="radio" id="star5" name="rating" value="5"><label for="star5">★</label>
      <input type="radio" id="star4" name="rating" value="4"><label for="star4">★</label>
      <input type="radio" id="star3" name="rating" value="3"><label for="star3">★</label>
      <input type="radio" id="star2" name="rating" value="2"><label for="star2">★</label>
      <input type="radio" id="star1" name="rating" value="1"><label for="star1">★</label>
    </div> -->
    <label class="label" for="imageUpload">Proof of completion</label><br>
    <input type="file" id="imageUpload" accept="image/*" style="padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; color: #333; background-color: #fff;">
    <div id="imagePreview"></div>
    <label class="label" for="reviewText">Tell us more about the job completed.</label>
    <textarea id="reviewText" class="review-text" minlength="1" maxlength="500" placeholder="Type review here." required style="width: 300px;"></textarea><br>
    <button class="send-button">SEND REVIEW</button>
  </div>


  <script>
    document.getElementById('imageUpload').addEventListener('change', function() {
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
