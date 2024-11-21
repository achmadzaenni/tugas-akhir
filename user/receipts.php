<?php
include("../koneksi.php");
date_default_timezone_set('Asia/Jakarta');

// Handle review form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['rating'], $_POST['komen'])) {
    $name = $_POST['name'];
    $rating = (int)$_POST['rating'];
    $komen = $_POST['komen'];
    
    // Prepare and execute the insert query for the review
    $stmt = $conn->prepare("INSERT INTO ulasan (name, rating, komen) VALUES (?, ?, ?)");
    $stmt->bind_param('sis', $name, $rating, $komen);
    $response = ['success' => false, 'message' => 'Gagal mengirim ulasan'];
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Ulasan berhasil dikirim!';
    } else {
        $response['message'] = 'Error: ' . $stmt->error;
    }
    $stmt->close();
    
    // Return JSON response for AJAX
    echo json_encode($response);
    exit;
}

// Handle transaction insertion
if (isset($_GET['username'], $_GET['no_kost'], $_GET['total_harga'], $_GET['email'], $_GET['durasi'])) {
    $username = $_GET['username'];
    $no_kost = (int)$_GET['no_kost'];
    $total_harga = (float)$_GET['total_harga'];
    $email = $_GET['email'];
    $durasi = (int)$_GET['durasi'];
    $date_transaksi = date('Y-m-d H:i:s');

    // Fetch user ID based on the username
    $user_query = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $user_query->bind_param("s", $username);
    $user_query->execute();
    $user_result = $user_query->get_result();
    $user_data = $user_result->fetch_assoc();
    $id_user = $user_data['id'];
    $user_query->close();

    // Prepare and execute the insert query for the transaction
// Prepare and execute the insert query for the transaction
$stmt = $conn->prepare("INSERT INTO transactions (id_user, username, no_kost, harga, durasi, total_harga, email, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'aktif', ?)");
$stmt->bind_param('isidisss', $id_user, $username, $no_kost, $total_harga, $durasi, $total_harga, $email, $date_transaksi);

    
    if ($stmt->execute()) {
        
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    // If required fields are not available, redirect to home
    header("Location: ../user/dashboard.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/logo.png" media="(prefers-color-scheme: light)" />
    <link rel="icon" href="../img/logo.png" media="(prefers-color-scheme: dark)" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Ekost</title>
    <style>
        /* Style your receipt and button positions */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .receipt {
            background-color: #e0e0e0;
            width: 300px;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .receipt-logo {
            background-color: #888;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            margin: 0 auto 20px;
        }
        .receipt h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .receipt p {
            margin: 5px 0;
        }
        .top-buttons {
            display: flex;
            justify-content: flex-start;
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .top-buttons button {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 5px;
            margin-left: 5px;
            margin-right: 5px;
        }
        .left-button {
            margin-right: auto;
        }
    </style>
</head>
<body>
    <!-- Top Buttons -->
    <div class="top-buttons">
        <button type="button" class="btn btn-secondary" onclick="showReviewModal()">Review</button>
        <button onclick="downloadPNG()">Download Nota</button>
        <button onclick="exitPage()">Exit</button>
    </div>

    <!-- Receipt Content -->
    <div class="receipt">
        <div class="logo">
            <img src="../img/logo.png" alt="Logo" style="width: 70px; height:70px; border-radius:50%;">
        </div>
        <h2>Transaksi selesai</h2>
        <p>Disewa oleh: <strong><?php echo $username; ?></strong></p>
        <p>No kost: <strong style="font-weight: bold;">Kamar <?php echo $no_kost; ?></strong></p>
        <p>Date transaksi: <strong><?php echo $date_transaksi; ?></strong></p>
        <p>Jumlah: Rp. <strong><?php echo number_format($total_harga, 2); ?></strong></p>
        <p>Untuk bulan: <strong><?php echo $durasi; ?> Bulan</strong></p>
        <p>Sampai: <strong><?php echo date('Y-m-d', strtotime("+$durasi months")); ?></strong></p>
        <p>Email: <strong><?php echo $email; ?></strong></p>
    </div>

    <!-- Modal Review -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">Berikan Ulasan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="reviewForm">
                        <!-- Name Input -->
                        <div class="mb-3">
    <label for="name" class="form-label">Nama</label>
    <!-- Pre-filled with the user's name and set to readonly -->
    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($username); ?>" required readonly>
</div>

                        <!-- Rating Input -->
                        <div class="rateyo" id="rating"
                            data-rateyo-rating="4"
                            data-rateyo-num-stars="5"
                            data-rateyo-score="3">
                        </div>
                        <input type="hidden" name="rating" id="ratingInput">

                        <!-- Review Input -->
                        <div class="mb-3">
                            <label for="komen" class="form-label">Ulasan</label>
                            <textarea class="form-control" id="komen" name="komen" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitReview()">Kirim Ulasan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modal, Review, and Actions -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>

    <script>
        function showReviewModal() {
            $('#reviewModal').modal('show');
        }

        function submitReview() {
            const name = document.getElementById('name').value;
            const rating = document.getElementById('ratingInput').value;
            const comment = document.getElementById('komen').value;

            // Send data to the server via AJAX
            $.ajax({
                url: '', // Submit to the same file
                type: 'POST',
                dataType: 'json',
                data: {
                    name: name,
                    rating: rating,
                    komen: comment
                },
                success: function(response) {
                    alert(response.message);
                    if (response.success) {
                        $('#reviewModal').modal('hide');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Terjadi kesalahan: " + xhr.responseText);
                }
            });
        }

        function exitPage() {
            window.location.href = '../user/dashboard.php';
        }

        function downloadPNG() {
            html2canvas(document.querySelector('.receipt')).then(function(canvas) {
                const link = document.createElement('a');
                link.href = canvas.toDataURL("image/png");
                link.download = 'Nota kost.png';
                link.click();
            });
        }

        $(function () {
            $(".rateyo").rateYo().on("rateyo.change", function (e, data) {
                const rating = data.rating;
                $('#ratingInput').val(rating); // Store the rating value in a hidden input
            });
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>
