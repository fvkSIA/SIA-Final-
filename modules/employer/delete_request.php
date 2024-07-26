<?php
// Ensure error reporting is enabled for development purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
require_once '/xampp/htdocs/SIA-Final-/db/db_connection.php';

// Start session
session_start();

// Check if POST data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jr_id'])) {
    // Sanitize input (assuming jr_id is an integer)
    $jr_id = filter_var($_POST['jr_id'], FILTER_VALIDATE_INT);

    if ($jr_id === false) {
        $message = 'Invalid job request ID.';
    } else {
        // Prepare SQL statement
        $sql = "DELETE FROM job_requests WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("i", $jr_id);

            // Execute statement
            if ($stmt->execute()) {
                $message = 'Record successfully deleted.';
            } else {
                $message = 'Error deleting record: ' . $conn->error;
            }

            // Close statement
            $stmt->close();
        } else {
            $message = 'Failed to prepare the SQL statement.';
        }
    }
} else {
    $message = 'Invalid request.';
}

// Close database connection
$conn->close();

// Redirect to referring page with message
if (isset($_SERVER['HTTP_REFERER'])) {
    header("Location: {$_SERVER['HTTP_REFERER']}?message=" . urlencode($message));
} else {
    header("Location: /"); // Redirect to home page if no referrer
}
exit();
?>
