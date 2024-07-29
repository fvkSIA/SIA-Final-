<?php
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jr_id'])) {
    $jr_id = intval($_POST['jr_id']);

    // Prepare the DELETE statement
    $sql = "DELETE FROM job_requests WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $jr_id);
        $stmt->execute();
        $stmt->close();
    }

    $conn->close();
    header("Location: jobseekerinbox.php"); // Redirect back to the dashboard
    exit();
}
?>
