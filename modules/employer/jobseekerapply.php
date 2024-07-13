<!DOCTYPE html>
<html lang="en">
<head>
  <title>Jobseeker Dashboard</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <style>
    /* Your custom button styles */
    #myButton {
      position: relative;
      border-radius: 50px;
      cursor: pointer;
      width: 100px;
      background-color: white;
      color: #000000;
      font-weight: 500;
      padding: 0.5rem 1rem;
      border: 1px solid #000000;
      align-items: center;
      margin-left: auto;
      margin-right: auto;
      font-size: 16px;
      transition: transform 0.3s;
    }

    #myButton:hover {
      background-color: rgb(235, 235, 235);
    }

    @media screen and (max-width: 768px) {
      #myButton {
        transform: scale(0.9);
      }
    }

    @media screen and (max-width: 640px) {
      #myButton {
        transform: scale(0.8);
      }
    }

    .rounded-lg {
      max-width: 100%;
      overflow: hidden;
      word-wrap: break-word;
    }

    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>
<body class="bg-gray-100">
  <div class="flex justify-center mt-10">
    <div class="rounded-lg shadow-lg p-6 bg-white w-full sm:w-3/4 md:w-2/3 lg:w-1/2 xl:w-2/5">
      <div class="flex items-center mb-4">
        <img src="../jobseeker/assets/images/no-image.png" class="w-20 h-20 rounded-full mr-4" alt="Profile picture of driver">
        <div>
          <h1 class="text-xl font-bold mb-2">KAREN GALE PARTOS</h1>
          <div class="flex items-center text-sm text-gray-600 mb-2">
            <span class="mr-2 font-bold">RANKING:</span>
            <a href="#" class="text-yellow-500 font-semibold underline">TOP 1 - DRIVER</a>
          </div>
          <div class="flex items-center mb-2">
            <span class="text-sm text-gray-600 mr-2 font-bold">RATING:</span>
            <div class="flex">
              <i class="fas fa-star text-yellow-500"></i>
              <i class="fas fa-star text-yellow-500"></i>
              <i class="fas fa-star text-yellow-500"></i>
              <i class="fas fa-star text-yellow-500"></i>
              <i class="fas fa-star text-yellow-500"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="space-y-2 text-sm text-gray-600">
        <div class="flex">
          <span class="font-semibold">PHONE NUMBER:</span>
          <span class="ml-2">09632654842</span>
        </div>
        <div class="flex">
          <span class="font-semibold">EMAIL ADDRESS:</span>
          <span class="ml-2">karengalepartos@gmail.com</span>
        </div>
        <div class="flex">
          <span class="font-semibold">GENDER:</span>
          <span class="ml-2">Female</span>
        </div>
        <div class="flex">
          <span class="font-semibold">Location:</span>
          <span class="ml-2"> Caloocan City</span>
        </div>
      </div>
      <div class="mt-4 p-4 border-t border-gray-300 flex justify-between items-center">
       
        <div class="flex items-center">
          <span class="font-semibold text-gray-600 mr-2">RESUME:</span>
          <a href="#" class="text-sm text-blue-500 underline"><i class="far fa-file-alt mr-1"></i> JAVALLA, JOHN CARLO</a>
        </div>
        <button id="myButton" onclick="window.location.href='#'">HIRE</button>
      </div>
    </div>
  </div>
</body>
</html>
