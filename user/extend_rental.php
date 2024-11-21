<?php
session_start();
include("../koneksi.php");

// Cek apakah pengguna sudah login dan data yang dikirim valid
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['extensionDuration']) && is_numeric($data['extensionDuration']) && $data['extensionDuration'] > 0) {
        $extensionDuration = $data['extensionDuration'];
        $id_user = $_SESSION['user_id'];  // Ambil ID pengguna dari session

        // Query untuk memperpanjang sewa
        $query = "UPDATE transactions SET durasi = durasi + $extensionDuration WHERE id_user = $id_user AND status = 'aktif'";

        $result = mysqli_query($conn, $query);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Sewa berhasil diperpanjang.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperpanjang sewa.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Durasi tidak valid.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Request tidak valid.']);
}
?>
