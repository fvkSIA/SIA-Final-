<?php 
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';

session_start();

$id = $_GET['id'];

$sql = "DELETE FROM users WHERE id = ?";

if($stmt = $conn->prepare($sql)){
    $stmt->bind_param("i", $id);
    if($stmt->execute()){
        if (isset($_SESSION['flash_message'])) {
            unset($_SESSION['flash_message']);
        }

        $_SESSION["flash"] = ["type" => "success", "message" => "You are great!"];
        header('refresh: 0 ; url = adminregistration.php');
        $stmt->close();
    }
}

$conn->close();


?>