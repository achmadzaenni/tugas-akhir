<?php
include ("../koneksi.php"); // Include your database connection

// Fetch all reviews from the 'ulasan' table
$sql = "SELECT * FROM ulasan ORDER BY created_at DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<link rel="icon" href="../img/logo.png" media="(prefers-color-scheme: light)" />
<link rel="icon" href="../img/logo.png" media="(prefers-color-scheme: dark)" />
<head>
    <meta charset="UTF-8">
    <title>ekost</title>
    <style>
        /* Basic styling for feedback and reply sections */
        .feedback-item {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 10px;
        }
        .reply-section {
            margin-left: 20px;
            color: #555;
        }
    </style>
</head>
<body>

<h2>Feedback</h2>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reviewId = $row['id'];
?>
        <div class="feedback-item">
            <p><strong><?php echo htmlspecialchars($row['name']); ?></strong></p>
            <p>Rating: <?php echo htmlspecialchars($row['rating']); ?> stars</p>
            <p><?php echo htmlspecialchars($row['komen']); ?></p>
            <p><small>Posted on: <?php echo $row['created_at']; ?></small></p>

            <!-- Display replies for each review -->
            <div class="reply-section">
                <h4>Replies:</h4>
                <?php
                $replySql = "SELECT * FROM replies WHERE review_id = $reviewId";
                $replyResult = $conn->query($replySql);

                if ($replyResult->num_rows > 0) {
                    while ($replyRow = $replyResult->fetch_assoc()) {
                        echo "<p><strong>Admin:</strong> " . htmlspecialchars($replyRow['reply']) . "</p>";
                        echo "<p><small>Replied on: " . $replyRow['created_at'] . "</small></p>";
                    }
                } else {
                    echo "<p>belum dibalas.</p>";
                }
                ?>

                <!-- Form for admin to reply to this review -->
                <form action="add_reply.php" method="post">
                    <input type="hidden" name="review_id" value="<?php echo $reviewId; ?>">
                    <textarea name="reply" rows="2" placeholder="tulis balasan..." required></textarea>
                    <button type="submit">Reply</button>
                </form>
            </div>
        </div>
<?php
    }
} else {
    echo "<p>tidak ada feedback.</p>";
}
$conn->close();
?>

</body>
</html>
