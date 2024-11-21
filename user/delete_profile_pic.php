<?php
session_start();
require ("../koneksi.php");

$userId = $_SESSION['user_id'];

// Remove profile picture from the database
$query = "UPDATE users SET profile_pic = NULL WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);

if ($stmt->execute()) {
    header("Location: profile.php"); // Redirect to profile page after deletion
} else {
    echo "Error deleting profile picture.";
}

$stmt->close();
$conn->close();
?>
