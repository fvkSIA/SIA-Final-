<?php 
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';

session_start();

$id = $_GET['id'];

$sql = "DELETE FROM users WHERE id = ?";

if($stmt = $conn->prepare($sql)){
    $stmt->bind_param("i", $id);
    if($stmt->execute()){
        $_SESSION['delete_success'] = true;
        header('Location: adminregistration.php');
        exit();
    }
    $stmt->close();
}

$conn->close();
?>