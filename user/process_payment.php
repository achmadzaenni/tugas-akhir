<?php
// process_payment.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture form data
    $id_sewa = $_POST['id_sewa'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $no_kost = $_POST['no_kost'];
    $harga = $_POST['harga'];
    $durasi = $_POST['durasi'];
    $total_harga = $_POST['total_harga'];
    $metode_pembayaran = $_POST['metode_pembayaran'];

    // Perform the necessary logic to store the payment information (e.g., update database, etc.)

    // Here we simulate a successful payment response (e.g., update status in the database)
    $response = [
        'success' => true,
        'id_sewa' => $id_sewa,
        'username' => $username,
        'email' => $email,
        'no_kost' => $no_kost,
        'harga' => $harga,
        'durasi' => $durasi,
        'total_harga' => $total_harga,
        'metode_pembayaran' => $metode_pembayaran
    ];

    // Return JSON response
    echo json_encode($response);
}
?>
