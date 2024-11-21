<?php
// Konfigurasi database
$host     = "localhost";  // Nama host database (biasanya 'localhost')
$username = "root";       // Username MySQL (default adalah 'root' di XAMPP)
$password = "";           // Password MySQL (kosong secara default di XAMPP)
$database = "kostkita";  // Ganti dengan nama database yang Anda gunakan

// Membuat koneksi ke database
$conn = new mysqli($host, $username, $password, $database)or die(mysqli_error($conn));
?>
