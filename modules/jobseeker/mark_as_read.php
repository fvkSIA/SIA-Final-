<?php

require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();

if (isset($_POST['jr_id'])) {
    $jr_id = $_POST['jr_id'];

    $sql = "UPDATE job_requests SET is_read = 1 WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $jr_id);
        $stmt->execute();
        $stmt->close();
    }
    $conn->close();
}
?>