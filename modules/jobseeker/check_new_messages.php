<?php
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();

$user_id = $_SESSION['user_id'];

$unread_query = "SELECT COUNT(*) as unread_count FROM job_requests 
                 WHERE user_id = ? AND is_read = 0 AND type = 1";
$stmt = $conn->prepare($unread_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$unread_count = $result->fetch_assoc()['unread_count'];
$stmt->close();

header('Content-Type: application/json');
echo json_encode(['unread_count' => $unread_count]);