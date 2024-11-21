<?php
require("../koneksi.php"); // Koneksi ke database

if (isset($_GET['id_sewa'])) {
    $id_sewa = $_GET['id_sewa'];

    // Fetch all comments related to this room
    $sql_comments = "SELECT * FROM ulasan WHERE id_sewa = " . intval($id_sewa) . " ORDER BY created_at DESC"; // Order by newest first
    $result_comments = mysqli_query($conn, $sql_comments);

    while ($comment = mysqli_fetch_assoc($result_comments)) {
        echo "<div class='comment'>";
        echo "<strong>" . htmlspecialchars($comment['name']) . "</strong> rated " . htmlspecialchars($comment['rating']) . "/5";
        echo "<p>" . htmlspecialchars($comment['komen']) . "</p></div>";
    }
} else {
    echo "No reviews found.";
}
?>
