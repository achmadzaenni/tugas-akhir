<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_id = $_POST['review_id'];
    $reply = $_POST['reply'];

    // Insert admin reply into the database
    $sql = "INSERT INTO replies (review_id, reply) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $review_id, $reply);
    $stmt->execute();

    // Redirect or return response
    header("Location: ../user/dashboard.php"); // Redirect after submission
    exit();
}
?>
