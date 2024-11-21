<?php
session_start();
include("../koneksi.php");

if (isset($_GET['id_user']) && isset($_GET['no_kost'])) {
    $id_user = $_GET['id_user'];
    $no_kost = $_GET['no_kost'];

    // Set invoice amount and due date (for example, 30 days from today)
    $amount = 500000; // Example amount; customize as needed
    $due_date = date('Y-m-d', strtotime('+30 days'));

    // Insert the invoice data into the `invoices` table
    $query = "INSERT INTO invoices (id_user, no_kost, amount, due_date, status) VALUES (?, ?, ?, ?, 'unpaid')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iids', $id_user, $no_kost, $amount, $due_date);

    if ($stmt->execute()) {
        echo "Invoice successfully sent!";
    } else {
        echo "Error sending invoice: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
