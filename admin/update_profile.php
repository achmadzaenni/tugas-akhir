<?php
session_start();
require ("../koneksi.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id']; // Get user ID from session

    // Process password change
    if (!empty($_POST['new_password'])) {
        $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('si', $newPassword, $userId);
        $stmt->execute();
        $stmt->close();
    }
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {
        // Define the upload directory
        $targetDir = "../uploads/";
        $targetFile = $targetDir . basename($_FILES['profile_pic']['name']);
        
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile)) {
            // Update the profile_pic field in the database
            $query = "UPDATE users SET profile_pic = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $targetFile, $userId);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Error uploading file.";
        }
    }    
    

    // Redirect back to profile page after update
    header("Location: profile.php");
    exit();
}
?>
