<?php
include ("../koneksi.php"); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reviewId = $_POST['review_id'];
    $reply = $_POST['reply'];

    // Prepare SQL statement to insert the reply into the 'replies' table
    $stmt = $conn->prepare("INSERT INTO replies (review_id, reply) VALUES (?, ?)");
    $stmt->bind_param("is", $reviewId, $reply);

    if ($stmt->execute()) {
        // Redirect back to feedback page with success message (optional)
        header("Location: feedback.php");
        exit();
    } else {
        echo "Error: Could not add reply.";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: feedback.php"); // Redirect if accessed directly
}
