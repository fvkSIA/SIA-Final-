<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jobseeker Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="../HanapKITA.png">
</head>
<body class="bg-gray-100 font-sans flex items-center justify-center h-screen">
  <div class="container mx-auto px-4 py-8 w-lg bg-white rounded-lg shadow-lg">
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
      <h2 class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($_GET['fname']) . ' ' . htmlspecialchars($_GET['lname']); ?></h2>
      <p class="text-green-700">Your job offer has been accepted!</p>
    </div>
    <p class="text-gray-700 mb-4">Hi <?php echo htmlspecialchars($_SESSION['name']); ?>,</p>
    <p class="text-gray-600 mb-6">
      Congratulations! Your job offer to the jobseeker has been accepted. Communicate with each other and aim for a productive and successful work relationship.
    </p>
  </div>

  <script>
    function closeContainer() {
      window.location.href = "employerinbox.php";
    }
  </script>
</body>
</html>
