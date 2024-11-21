<?php
// koneksi ke database
include("../koneksi.php"); // Pastikan koneksi tersambung

$query_rooms = "SELECT COUNT(*) as available_rooms FROM kosts WHERE status_kost = 'tersedia'";
$result_rooms = mysqli_query($conn, $query_rooms);
$row_rooms = mysqli_fetch_assoc($result_rooms);
$available_rooms = $row_rooms['available_rooms'];

$query_tenants = "SELECT COUNT(*) as total_tenants FROM transactions";
$result_tenants = mysqli_query($conn, $query_tenants);
$row_tenants = mysqli_fetch_assoc($result_tenants);
$total_tenants = $row_tenants['total_tenants'];

$searchKeyword = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

if ($searchKeyword) {
    $query = "SELECT * FROM kosts WHERE no_kost LIKE '%$searchKeyword%' OR type_kost LIKE '%$searchKeyword%' OR alamat_kost LIKE '%$searchKeyword%' OR fasilitas LIKE '%$searchKeyword%'";
} else {
    $query = "SELECT * FROM kosts";
}
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating = $_POST['rating'];
    $id_sewa = $_POST['id_sewa']; // ID dari sewa yang dinilai

    // Simpan penilaian ke dalam database
    $query_rating = "INSERT INTO ulasan (id_sewa, rating, created_at) VALUES ('$id_sewa', '$rating', NOW())";
    if (mysqli_query($conn, $query_rating)) {
        // Hitung rata-rata rating baru setelah berhasil memasukkan data ulasan
        $result_avg = mysqli_query($conn, "SELECT AVG(rating) as avgRating FROM ulasan WHERE id_sewa = '$id_sewa'");
        if ($result_avg) {
            $row = mysqli_fetch_assoc($result_avg);
            $averageRating = $row['avgRating'];

            // Kembalikan rata-rata rating ke front-end (jika menggunakan AJAX)
            echo json_encode(['averageRating' => $averageRating]);
        } else {
            echo json_encode(['error' => 'Gagal menghitung rata-rata rating.']);
        }
    } else {
        echo json_encode(['error' => 'Gagal menyimpan ulasan.']);
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ekost</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../img/logo.png" media="(prefers-color-scheme: light)" />
    <link rel="icon" href="../img/logo.png" media="(prefers-color-scheme: dark)" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: space-around;
            padding: 20px;
        }

        .box {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            padding: 20px;
            width: 200px;
            margin: 10px;
        }

        .box .icon {
            font-size: 40px;
            margin-right: 20px;
        }

        .box .text {
            font-size: 18px;
        }

        .box .text .number {
            font-size: 24px;
            font-weight: bold;
        }

        .cpu-traffic .icon {
            color: #00c0ef;
        }

        .likes .icon {
            color: #dd4b39;
        }

        .sales .icon {
            color: #00a65a;
        }

        .new-members .icon {
            color: #f39c12;
        }
    </style>
</head>

<body>
    <?php include '../component/navbar.php'; ?>

    <div class="container">
        <div class="box cpu-traffic">
            <div class="icon">
                <i class="fa-solid fa-house-chimney"></i>
            </div>
            <div class="text">
                <div>Jumlah Kamar</div>
                <div class="number"><?php echo $available_rooms; ?></div>
            </div>
        </div>
        <div class="box new-members">
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="text">
                <div>Penghuni</div>
                <div class="number"><?php echo $total_tenants; ?></div>
            </div>
        </div>
    </div>
    <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <!-- Footer -->
  <?php include '../component/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to handle rating click
        $('.rating span').on('click', function () {
            const ratingValue = $(this).data('rating');
            const $parent = $(this).parent();
            $parent.find('span').each(function () {
                $(this).css('color', $(this).data('rating') <= ratingValue ? 'gold' : '#d3d3d3');
            });
            $parent.find('#ratingValue' + $parent.data('id')).val(ratingValue);
        });

        // Function to submit review
        function submitReview(id) {
            const form = $('#reviewForm' + id);
            const formData = form.serialize();

            $.post('', formData, function (response) {
                const data = JSON.parse(response);
                if (data.error) {
                    alert(data.error);
                } else {
                    alert('Ulasan berhasil ditambahkan!');
                    // Update average rating display here
                    $('.average-rating').each(function () {
                        const $container = $(this);
                        $container.find('span').css('width', (data.averageRating * 20) + '%'); // Update width for average rating
                        const currentCount = parseInt($container.siblings('.jumlah-komentar').text()) || 0;
                        $container.siblings('.jumlah-komentar').text(currentCount + 1); // Update comment count
                    });
                    $('#reviewModal' + id).modal('hide'); // Close modal
                }
            });
        }

        // Function to update total price based on duration
        function updateTotal(id) {
            const harga = parseFloat($('#harga' + id).val());
            const durasi = parseInt($('#durasi' + id).val());
            const total = harga * durasi;
            $('#total_harga' + id).val(total);
        }

        // Function to submit payment
        function submitPayment(id) {
            const form = $('#form' + id);
            // Implement your payment submission logic here
            alert('Pembayaran untuk kost ID ' + id + ' telah berhasil diproses!');
            $('#paymentModal' + id).modal('hide'); // Close modal
        }
    </script>
</body>

</html>
