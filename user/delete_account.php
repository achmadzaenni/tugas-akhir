<?php
session_start();
require ("../koneksi.php");

$userId = $_SESSION['user_id'];

// Delete the user's account
$query = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);

if ($stmt->execute()) {
    session_destroy(); // End the session
    header("Location: ../forminput/login.php"); // Redirect to login after account deletion
} else {
    echo "Error deleting account.";
}

$stmt->close();
$conn->close();
?>
