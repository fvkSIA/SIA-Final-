<?php
session_start();
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';

// Get user information
$user_job_type = '';
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_query = "SELECT job_type FROM users WHERE id = ?";
    $stmt = $conn->prepare($user_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        $user_job_type = $user['job_type'];
    }
    $stmt->close();
}

// Fetch job postings based on job type
$sql = "SELECT location, COUNT(*) as count FROM job_listings 
        WHERE job = ? AND accepted = 0
        GROUP BY location";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_job_type);
$stmt->execute();
$result = $stmt->get_result();

$locations = [];
while ($row = $result->fetch_assoc()) {
    $locations[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($locations);
?>