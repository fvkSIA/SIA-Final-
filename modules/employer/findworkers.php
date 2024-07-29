<?php 

require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();
$error = '';
$result = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_type = $_POST['job_type'];
    $location = $_POST['location'] ?? '';

    $param = "%{$location}%";

    $sql = "SELECT * FROM users WHERE type = 2 AND job_type = ? AND city LIKE ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $job_type, $param);
        $stmt->execute();
        $result = $stmt->get_result() ?? null;

        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Workers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" type="image/png" href="../HanapKITA.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        .topnav {
            background-color: #DADADA;
        }
        .topnav a {
            color: #333;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }
        .topnav a:hover {
            color: #007bff;
        }
        .topnav a.active {
            background-color: #04AA6D;
            color: white;
        }
    </style>
</head>
<body class="font-poppins bg-gray-100">
    <!-- Header Section -->
    <header class="bg-red-200 p-4">
        <div class="container mx-auto">
            <form action="findworkers.php" method="post" class="flex flex-col md:flex-row justify-center gap-4">
                <input type="text" name="job_type" placeholder="Search..." class="w-full md:w-[23rem] px-4 py-2 rounded border border-gray-300" required>
                <div class="relative w-full md:w-[23rem]">
                    <select name="location" class="w-full px-4 py-2 rounded border border-gray-300 bg-white">
                        <option disabled selected class="hidden">Location</option>
                        <option value="">Location</option>
                        <!-- List of locations -->
                        <option value="manila">Manila</option>
                        <option value="caloocan">Caloocan</option>
                        <option value="valenzuela">Valenzuela</option>
                        <option value="pasay">Pasay</option>
                        <option value="makati">Makati</option>
                        <option value="quezon_city">Quezon City</option>
                        <option value="navotas">Navotas</option>
                        <option value="las_piñas">Las Piñas</option>
                        <option value="malabon">Malabon</option>
                        <option value="mandaluyong">Mandaluyong</option>
                        <option value="marikina">Marikina</option>
                        <option value="muntinlupa">Muntinlupa</option>
                        <option value="parañaque">Parañaque</option>
                        <option value="pasig">Pasig</option>
                        <option value="san_juan">San Juan</option>
                        <option value="taguig">Taguig</option>
                        <option value="pateros">Pateros</option>
                    </select>
                </div>
                <button type="submit" class="w-full md:w-[13rem] bg-blue-500 text-white px-4 py-2 rounded">Find Now!</button>
            </form>
        </div>
    </header>

    <!-- Main Content Section -->
    <main class="container mx-auto mt-8 px-4">
        <?php 
        $data = [];
        if ($result != null) {
            $data = $result->fetch_all(MYSQLI_ASSOC);
        }
        ?>

        <?php if ($data): ?>
            <?php foreach($data as $row): ?>
                <div class="mb-4 p-4 border-t-4 border-indigo-200 shadow-lg rounded-lg bg-white">
                    <div class="flex flex-col md:flex-row items-center justify-between">
                        <div class="flex-1 mb-4 md:mb-0">
                            <h2 class="text-xl font-bold"><?php echo htmlspecialchars($row['lastname'] . ', ' . $row['firstname'] . ' ' . $row['middlename']); ?></h2>
                            <p class="text-gray-600">Location: <?php echo htmlspecialchars($row['city']); ?></p>
                            <p class="text-gray-600">Type of Worker: <?php echo htmlspecialchars($row['job_type']); ?></p>
                            <p class="text-sm text-gray-600 flex items-center">
                                <?php if($row['availability'] == 1): ?>
                                    <i class="fas fa-circle mr-2 text-emerald-600"></i>&nbsp;Available
                                <?php else: ?>
                                    <i class="fas fa-circle mr-2 text-red-600"></i>&nbsp;Not Available
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="text-center">
                            <img src="../jobseeker/assets/images/<?php echo htmlspecialchars($row['profile'] ?? 'no-image.png'); ?>" alt="Profile Image" class="rounded-full mb-2 w-24 h-24 object-cover">
                            <a href='jobseekerviewprofile.php?id=<?php echo htmlspecialchars($row['id']); ?>' class="text-blue-500">View Profile</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="p-4 border-t-4 border-indigo-200 shadow-lg rounded-lg bg-white">
                <p class="text-center text-gray-600">NO RESULTS FOUND</p>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
