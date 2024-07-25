<?php
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    
    // Prepare SQL to delete user
    $sql = "DELETE FROM users WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        
        if ($stmt->execute()) {
            // Deletion successful
            session_start();
            $_SESSION["flash"] = ["type" => "success", "message" => "User successfully deleted."];
            header("Location: admdashboardjobseeker.php"); // or wherever you want to redirect
            exit();
        } else {
            // Deletion failed
            session_start();
            $_SESSION["flash"] = ["type" => "error", "message" => "Error deleting user."];
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
        

    } else {
        // SQL preparation failed
        session_start();
        $_SESSION["flash"] = ["type" => "error", "message" => "Error preparing deletion query."];
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
    
} else {
    // Invalid request
    header("Location: index.php");
    exit();
}
?>