<?php
session_start();
include("../koneksi.php");

// Retrieve id_user from session if available
$id_user = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

// Ensure user is logged in
if ($id_user > 0) {
    // Delete the rental transaction where id_user matches and status is completed
    $query = "DELETE FROM transactions WHERE id_user = $id_user AND status = 'aktif' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Rental deleted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete rental.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
}
?>
