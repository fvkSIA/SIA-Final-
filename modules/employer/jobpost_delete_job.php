<?php
session_start();
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    echo "Invalid access.";
    exit;
}

$user_id = $_SESSION['user_id'];
$job_id = intval($_GET['id']);

// Check connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hanapkita_db";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete job post
$sql = "DELETE FROM job_listings WHERE id = ? AND employer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $job_id, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Job deleted successfully.";
} else {
    echo "Failed to delete job. It may not exist or you may not have permission.";
}

$stmt->close();
$conn->close();

header("Location: jobpost_list.php"); // Redirect to job list page
exit;
