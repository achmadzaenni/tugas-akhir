<?php
session_start();
// Include your database connection
include("../koneksi.php");

// Retrieve id_user from session if available
$id_user = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

// Ensure user is logged in
if ($id_user > 0) {
    // Query to get transaction details by user
    $query = "SELECT t.no_kost, t.username, t.harga, t.durasi, t.created_at, t.status,  t.email 
              FROM transactions t 
              WHERE t.id_user = $id_user AND t.status = 'aktif' ";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Transaction data
        $no_kost = htmlspecialchars($row['no_kost']);
        $username = htmlspecialchars($row['username']);
        $email = htmlspecialchars($row['email']);
        $harga = $row['harga'];
        $durasi = $row['durasi'];
        $tanggal_sewa = date('d F, Y', strtotime($row['created_at']));
        $tanggal_akhir = date('d F, Y', strtotime($row['created_at'] . " + $durasi months"));
        $status = $row['status'] === 'aktif' ? 'aktif' : 'tidak aktif';
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="../img/logo.png" media="(prefers-color-scheme: light)" />
    <link rel="icon" href="../img/logo.png" media="(prefers-color-scheme: dark)" />
    <title>ekost</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .content {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .image-placeholder {
            text-align: center;
            margin-bottom: 20px;
        }

        .details {
            margin-bottom: 20px;
        }

        .details span {
            display: block;
            margin: 5px 0;
            font-size: 16px;
        }

        .buttons {
            text-align: center;
        }

        .buttons button {
            padding: 10px 20px;
            margin: 5px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .extend {
            background-color: #4CAF50;
            color: white;
        }

        .stop {
            background-color: #f44336;
            color: white;
        }

        .footer {
            background-color: #d3d3d3;
            padding: 10px;
            text-align: center;
            position: fixed;
            width: 100%;
            bottom: 0;
            left: 0;
        }
    </style>
</head>

<body>
    <?php include '../componentuser/navbar.php'; ?>

    <div class="content">
        <h1 style="text-align: center;">Disewa</h1>

        <div class="details">
            <p><strong>No Kost:</strong> <?php echo isset($no_kost) ? htmlspecialchars($no_kost) : 'N/A'; ?></p>
            <p><strong>Disewa Oleh:</strong> <?php echo isset($username) ? htmlspecialchars($username) : 'N/A'; ?></p>
            <p><strong>Harga:</strong> Rp <?php echo isset($harga) ? htmlspecialchars($harga) : '0,00'; ?></p>
            <p><strong>Tanggal Sewa:</strong> <?php echo isset($tanggal_sewa) ? htmlspecialchars($tanggal_sewa) : 'N/A'; ?></p>
            <p><strong>Tanggal Akhir:</strong> <?php echo isset($tanggal_akhir) ? htmlspecialchars($tanggal_akhir) : 'N/A'; ?></p>
            <p><strong>Durasi:</strong> <?php echo isset($durasi) ? htmlspecialchars($durasi) : '0'; ?> bulan</p>
            <p><strong>Status:</strong> <?php echo isset($status) ? htmlspecialchars($status) : 'N/A'; ?></p>
        </div>

        <div class="buttons">
            <button class="extend" data-bs-toggle="modal" data-bs-target="#extensionModal" data-price="<?php echo $harga; ?>">Perpanjang</button>
            <button class="stop" onclick="stopRental()">Berhenti Sewa</button>
        </div>
    </div>

    <div class="modal" id="extensionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Perpanjang Sewa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="extensionDuration">Durasi Perpanjangan (bulan):</label>
                    <input type="number" id="extensionDuration" min="1" class="form-control" placeholder="Masukkan durasi perpanjangan" required />
                    <br>
                    <label for="totalPrice">Total Harga</label>
                    <input type="text" id="totalPrice" class="form-control" readonly />
                </div>
                <div>
                                            <label for="metode_pembayaran">Metode Pembayaran:</label>
                                            <div class="payment-method-list">
                                                <div class="card shadow-sm">
                                                <label class="ms-3">
                                                    <input type="radio" name="metode_pembayaran" value="bca" required>
                                                    BCA Virtual Account <img class="payment-method-logo" src="../img/bca.jpeg" width="100" alt="BCA">
                                                </label>
                                                </div>
                                                <div class="card shadow-sm">
                                                <label class="ms-3">
                                                    <input type="radio" name="metode_pembayaran" value="gopay" required>
                                                    Go-Pay <img class="payment-method-logo" src="../img/gopay.jpeg"  width="100"  alt="Go-Pay">
                                                </label>
                                                </div>
                                                <div class="card shadow-sm">
                                                <label class="ms-3">
                                                    <input type="radio" name="metode_pembayaran" value="ovo" required>
                                                    OVO <img class="payment-method-logo" src="../img/ovo.jpeg"  width="100" alt="OVO">
                                                </label>
                                                </div>
                                                <div class="card shadow-sm">
                                                <label class="ms-3">
                                                    <input type="radio" name="metode_pembayaran" value="cod" required>
                                                    Cash <img class="payment-method-logo" src="../img/Cash wordmark.jpeg"  width="100" alt="Bank Transfer">
                                                </label>
                                                </div>
                                            </div>
                                        </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="extendRental()">Perpanjang</button>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        kost-masa kini
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // Mengambil harga dari tombol perpanjang
        const pricePerMonth = document.querySelector('.extend').getAttribute('data-price');

        // Event listener untuk menghitung total harga saat durasi perpanjangan diubah
        document.getElementById("extensionDuration").addEventListener("input", function() {
            const extensionDuration = document.getElementById("extensionDuration").value;
            if (extensionDuration > 0) {
                const totalPrice = pricePerMonth * extensionDuration;
                document.getElementById("totalPrice").value = totalPrice.toLocaleString(); // Menampilkan total harga
            } else {
                document.getElementById("totalPrice").value = "0";
            }
        });

        function extendRental() {
            const extensionDuration = document.getElementById("extensionDuration").value;
            const totalPrice = document.getElementById("totalPrice").value.replace(/\D/g, ''); // Menghapus karakter non-digit

            // Validasi input durasi dan harga
            if (extensionDuration && extensionDuration > 0 && totalPrice > 0) {
                // Kirim data ke server menggunakan Fetch API
                fetch('extend_rental.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            extensionDuration: extensionDuration,
                            totalPrice: totalPrice
                        })
                    })
                    .then(response => response.json()) // Mengonversi response ke format JSON
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message); // Menampilkan pesan sukses
                            closeExtendModal(); // Menutup modal
                            location.reload(); // Refresh halaman untuk memperbarui data
                        } else {
                            alert(data.message); // Menampilkan pesan error
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan. Coba lagi.');
                    });
            } else {
                alert('Isi durasi perpanjangan dan harga dengan benar.');
            }
        }

        // Menutup modal setelah perpanjangan berhasil
        function closeExtendModal() {
            var myModal = new bootstrap.Modal(document.getElementById('extensionModal'));
            myModal.hide();
        }

        function stopRental() {
            if (confirm("Apakah Anda yakin ingin berhenti sewa?")) {
                fetch('delete_rental.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            document.querySelector(".content").innerHTML = "<h1>Tidak ada kamar yang dipesan</h1>";
                            alert(data.message);
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan. Coba lagi.');
                    });
            }
        }
    </script>
</body>

</html>