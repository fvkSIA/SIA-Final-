<?php
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;

$jrid = $_GET['jrid'];
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'] ?? null;
    $jr_id = $_GET['jrid'] ?? null;
    $jr_empid = $_GET['jr_empid'] ?? null;

    if ($jr_empid) {
        $sql = "SELECT * FROM users WHERE id = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $jr_empid);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "User ID is missing.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employer Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body class="bg-gray-100 font-sans">
  <div class="container mx-auto px-4 py-8">
    <?php if ($result && $user = $result->fetch_assoc()): ?>
      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center mb-6">
          <img src="../employer/assets/images/<?php echo htmlspecialchars($user['profile'] ?? 'no-image.png'); ?>" alt="Profile Image" class="w-24 h-24 rounded-full object-cover border-4 border-blue-500 mr-6">
          <h1 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($user['lastname']) . ', ' . htmlspecialchars($user['firstname']); ?></h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
          <div class="bg-gray-50 p-4 rounded-md">
            <p class="text-sm font-medium text-black-600">Phone</p>
            <p class="text-gray-800"><?php echo htmlspecialchars($user['phone_number']); ?></p>
          </div>
          <div class="bg-gray-50 p-4 rounded-md">
            <p class="text-sm font-medium text-black-600">Email</p>
            <p class="text-gray-800"><?php echo htmlspecialchars($user['email']); ?></p>
          </div>
          <div class="bg-gray-50 p-4 rounded-md">
            <p class="text-sm font-medium text-black-600">Gender</p>
            <p class="text-gray-800"><?php echo htmlspecialchars($user['gender']); ?></p>
          </div>
          <div class="bg-gray-50 p-4 rounded-md">
            <p class="text-sm font-medium text-black-600">City</p>
            <p class="text-gray-800"><?php echo htmlspecialchars($user['city']); ?></p>
          </div>
        </div>

        <div class="mb-6">
          <h2 class="text-xl font-semibold mb-2 text-gray-700">Valid ID</h2>
          <div class=" h-64 rounded-md ">
          <img src="<?php echo htmlspecialchars($user['valid_id_path']); ?>" alt="Valid ID" style="max-width: 100%; max-height: 100%; object-fit: cover;">
          </div>
        </div>

        <div class="flex justify-end space-x-4">
          <a href="jobseekerofferdetails.php?id=<?php echo $id;?>&jrid=<?php echo $jrid;?>" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">Proceed</a>
          <button onclick="window.location.href='jobseekerinbox.php'" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-300">Back</button>
        </div>
      </div>
    <?php else: ?>
      <p class="text-gray-500">No user data found.</p>
    <?php endif; ?>
  </div>
</body>
</html>