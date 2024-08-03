<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Application Accepted</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
  <div class="container mx-auto px-4 py-8 max-w-5xl">
    <div class="bg-white rounded-lg shadow-md p-6">
      <h1 class="text-2xl font-bold mb-4 text-gray-800">Application Status</h1>
      <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
        <h2 class="text-lg font-semibold text-green-700">Congratulations, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>
        <p class="text-green-600">Your application has been accepted.</p>
      </div>
      <p class="text-gray-700 mb-4">Dear <?php echo htmlspecialchars($_SESSION['name']); ?>,</p>
      <p class="text-gray-600 mb-6">
        We are thrilled to inform you that your application has been successful. Your professionalism, skills, and enthusiasm have truly impressed our team. We believe you will be a valuable addition to our organization and look forward to a rewarding and productive work experience ahead.
      </p>
      <p class="text-gray-600 mb-6">
        Welcome to our team! We're excited to have you on board and can't wait to see the great contributions you'll make.
      </p>
    </div>
  </div>

</body>
</html> 