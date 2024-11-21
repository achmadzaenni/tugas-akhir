<?php
// koneksi ke database
include("../koneksi.php"); // Pastikan koneksi tersambung

// Tangkap input pencarian dari form
$searchKeyword = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Query untuk mengambil data kamar berdasarkan pencarian
if ($searchKeyword) {
    // Jika ada kata kunci pencarian, tampilkan hasil yang sesuai
    $query = "SELECT * FROM kosts WHERE no_kost LIKE '%$searchKeyword%' OR type_kost LIKE '%$searchKeyword%' OR alamat_kost LIKE '%$searchKeyword%' OR fasilitas LIKE '%$searchKeyword%'";
} else {
    // Jika tidak ada pencarian, tampilkan semua data
    $query = "SELECT * FROM kosts";
}
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}

// Cek apakah ada data yang di-post untuk penilaian
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
    <style>
        .rating span {
            font-size: 1.5em;
            cursor: pointer;
            color: #000;
            /* Default star color */
        }

        .rating span:hover,
        .rating span:hover~span {
            color: #FFD700;
            /* Hover color */
        }

        /* Gen
/* Styling for <h1> */
        .carousel-caption h1 {
            font-size: 4rem;
            font-weight: bold;
            color: #fff;
            text-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
            /* Shadow effect */
            letter-spacing: 2px;
            /* Adds some spacing between letters */
            transition: transform 0.3s ease, color 0.3s ease;
            /* Smooth transition for effects */
        }

        /* Styling for <p> */
        .carousel-caption p {
            font-size: 1.5rem;
            color: #ddd;
            margin-top: 10px;
            text-shadow: 0px 2px 8px rgba(0, 0, 0, 0.3);
            /* Lighter shadow for the paragraph */
            transition: transform 0.3s ease, color 0.3s ease;
            /* Smooth transition for effects */
        }

        /* Hover effect for <h1> and <p> */
        .carousel-caption:hover h1 {
            color: #ff7300;
            /* Changes to a vibrant color on hover */
            transform: scale(1.1);
            /* Slightly enlarges the heading */
        }

        .carousel-caption:hover p {
            color: #FF9300;
            /* Changes to a brighter color on hover */
            transform: scale(1.05);
            /* Slightly enlarges the paragraph */
        }

        /* Button */
        .carousel-caption .btn {
            margin: 20px;
            /* Adjust margin for better positioning inside the carousel */
            padding: 15px 40px;
            border: none;
            outline: none;
            color: #FFF;
            cursor: pointer;
            position: relative;
            z-index: 0;
            border-radius: 12px;
            text-transform: uppercase;
            font-size: 18px;
            font-weight: bold;
        }

        /* Background behind the button */
        .carousel-caption .btn::after {
            content: "";
            z-index: -1;
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: #333;
            left: 0;
            top: 0;
            border-radius: 10px;
        }

        /* Glow effect */
        .carousel-caption .btn::before {
            content: "";
            background: linear-gradient(45deg,
                    #FF0000, #FF7300, #FFFB00, #48FF00,
                    #00FFD5, #002BFF, #FF00C8, #FF0000);
            position: absolute;
            top: -2px;
            left: -2px;
            background-size: 600%;
            z-index: -1;
            width: calc(100% + 4px);
            height: calc(100% + 4px);
            filter: blur(8px);
            animation: glowing 20s linear infinite;
            transition: opacity .3s ease-in-out;
            border-radius: 10px;
            opacity: 0;
        }

        /* Glow animation */
        @keyframes glowing {
            0% {
                background-position: 0 0;
            }

            50% {
                background-position: 400% 0;
            }

            100% {
                background-position: 0 0;
            }
        }

        /* Hover state to show glow */
        .carousel-caption .btn:hover::before {
            opacity: 1;
        }

        /* When the button is clicked */
        .carousel-caption .btn:active:after {
            background: transparent;
        }

        .carousel-caption .btn:active {
            color: #000;
            font-weight: bold;
        }
        
        .payment-method-list{
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .payment-method-list img{
            float: right;
        }
    </style>
</head>

<body>

    <?php include '../componentuser/navbar.php'; ?>

    <!-- carousel -->
    <div class="carousel-inner">
        <div class="carousel-item active" style="background-image: url('../img/kost.jpeg'); background-size: cover; background-position: center;">
            <div class="carousel-caption d-none d-md-block">
                <h1>Ekost</h1>
                <p>tempatnya aman nyaman tentram</p>
                <a href="#card-section" class="btn">Sewa</a>
            </div>
        </div>
    </div>

    <!-- end carousel -->
    <!-- card -->
    <div class="container mt-4" id="card-section">
        <div class="row row-cols-1 row-cols-md-4 g-4">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col">
                    <div class="card" style="width: 100%;">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal<?php echo $row['id']; ?>">
                            <img src="/ta/uploads/<?php echo $row['gambar']; ?>" class="card-img-top" alt="gambar kamar"></a>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fa-solid fa-list"></i> <?php echo $row['no_kost']; ?></li>
                            <li class="list-group-item"><i class="fa-solid fa-coins"></i> <?php echo $row['harga_kost']; ?></li>
                            <li class="list-group-item"><i class="bi bi-geo-alt"></i> <?php echo $row['alamat_kost']; ?></li>
                            <li class="list-group-item"><i class="bi bi-list"></i> <?php echo $row['fasilitas']; ?></li>
                            <li class="list-group-item">
                                <?php echo $row['status_kost']; ?>
                            </li>
                        </ul>
                        <div class="card-body">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal<?php echo $row['id']; ?>">
                                Pesan
                            </button>
                        </div>

                        <!-- modal image card -->
                        <div class="modal fade" id="imageModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="imageModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="imageModalLabel<?php echo $row['id']; ?>">Gambar Kamar</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Bootstrap Carousel -->
                                        <div id="carousel<?php echo $row['id']; ?>" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                <!-- Add images to the carousel -->
                                                <div class="carousel-item active">
                                                    <img src="/ta/uploads/<?php echo $row['gambar']; ?>" class="d-block w-100" alt="gambar kamar">
                                                </div>
                                                <?php
                                                // Assuming $additionalImages contains extra image paths for the room
                                                $additionalImages = ["/ta/uploads/image2.jpg", "/ta/uploads/image3.jpg"]; // Replace with dynamic fetch if available
                                                foreach ($additionalImages as $imagePath):
                                                ?>
                                                    <div class="carousel-item">
                                                        <img src="<?php echo $imagePath; ?>" class="d-block w-100" alt="gambar kamar tambahan">
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>

                                            <!-- Controls for sliding -->
                                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?php echo $row['id']; ?>" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#carousel<?php echo $row['id']; ?>" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Pembayaran -->
                        <div class="modal fade" id="paymentModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="paymentModalLabel">Pembayaran Sewa Kost</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="form<?php echo $row['id']; ?>">
                                            <input type="hidden" name="id_sewa" value="<?php echo $row['id']; ?>">
                                            <div class="mb-3">
                                                <label for="username<?php echo $row['id']; ?>" class="form-label">Username</label>
                                                <input type="text" class="form-control" id="username<?php echo $row['id']; ?>" name="username" placeholder="mohon masukkan sesuai nama akun" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="email<?php echo $row['id']; ?>" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email<?php echo $row['id']; ?>" name="email" placeholder="Email" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="no_kost<?php echo $row['id']; ?>" class="form-label">No Kost</label>
                                                <input type="text" class="form-control" id="no_kost<?php echo $row['id']; ?>" name="no_kost" value="<?php echo $row['no_kost']; ?>" readonly>
                                            </div>
                                            <div class="mb-3">

                                                <label for="harga<?php echo $row['id']; ?>" class="form-label">Harga per Bulan</label>
                                                <input type="text" class="form-control harga" id="harga<?php echo $row['id']; ?>" name="harga" value="<?php echo $row['harga_kost']; ?>" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="durasi<?php echo $row['id']; ?>" class="form-label">Durasi Sewa (bulan)</label>
                                                <input type="number" class="form-control durasi" id="durasi<?php echo $row['id']; ?>" name="durasi" value="1" min="1" onchange="updateTotal(<?php echo $row['id']; ?>)">
                                            </div>
                                            <div class="mb-3">
                                                <label for="total_harga<?php echo $row['id']; ?>" class="form-label">Total Harga</label>
                                                <input type="text" class="form-control" id="total_harga<?php echo $row['id']; ?>" name="total_harga" readonly>
                                            </div>
                                        </form>
                                        <!-- Payment method section -->
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
                                                    Cash <img class="payment-method-logo" src="../img/Cash wordmark.jpeg"  width="100" alt="cash">
                                                </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" onclick="submitPayment(<?php echo $row['id']; ?>)">Bayar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Review -->
                        <div class="modal fade" id="reviewModall<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="reviewModalLabel">Berikan Ulasan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="reviewForm<?php echo $row['id']; ?>">
                                            <input type="hidden" name="id_sewa" value="<?php echo $row['id']; ?>">

                                            <!-- Name Input -->
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Nama</label>
                                                <input type="text" class="form-control" id="name" name="name" required>
                                            </div>

                                            <!-- Rating Input -->
                                            <div class="rateyo" id="rating"
                                                data-rateyo-rating="4"
                                                data-rateyo-num-stars="5"
                                                data-rateyo-score="3">
                                            </div>
                                            <span class="result">0</span>
                                            <input type="hidden" name="rating">

                                            <!-- Review Input -->
                                            <div class="mb-3">
                                                <label for="review" class="form-label">Ulasan</label>
                                                <textarea class="form-control" id="review" name="review" rows="3"></textarea>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" onclick="submitReview(<?php echo $row['id']; ?>)">Kirim Ulasan</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <br>
    <hr>
    <div class="text-center positio-relative">
        <a class="btn-decorated">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal"
                style="--bs-btn-padding-y: .50rem; --bs-btn-padding-x: 13rem; --bs-btn-font-size: .90rem; border-radius: 25px;">

                Lihat reviewer
            </button></a>
    </div>
    <style>
        .btn-decorated {
            position: relative;
            display: inline-block;
        }

        /* Decorative elements on the sides */
        .btn-decorated::before,
        .btn-decorated::after {
            content: "";
            position: absolute;
            width: 20px;
            /* Width of decoration */
            height: 20px;
            /* Height of decoration */
            background-color: #007bff;
            /* Same color as the button */
            border-radius: 50%;
            /* Circular shape */
            top: 50%;
            transform: translateY(-50%);
        }

        /* Left decoration */
        .btn-decorated::before {
            left: -30px;
            /* Adjust distance from button */
        }

        /* Right decoration */
        .btn-decorated::after {
            right: -30px;
            /* Adjust distance from button */
        }
    </style>
    <!-- Modal for displaying reviews -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    // Connect to the database
                    include("../koneksi.php");

                    // Query to fetch reviews and their corresponding replies from the `ulasan` table
                    $query = "SELECT ulasan.id, name, rating, komen, created_at FROM ulasan ORDER BY created_at DESC";
                    $result = mysqli_query($conn, $query);

                    // Display reviews
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $reviewId = $row['id'];
                            echo '<div class="review-item mb-3">';
                            echo '<div><i class="bi bi-person">: </i><strong>' . htmlspecialchars($row['name']) . '</strong></div>';
                            echo '<div>Rating: ' . str_repeat('★', $row['rating']) . str_repeat('☆', 5 - $row['rating']) . '</div>';
                            echo '<div>Ulasan: ' . htmlspecialchars($row['komen']) . '</div>';
                            echo '<div class="text-muted" style="font-size: 0.8rem;">' . date("d M Y", strtotime($row['created_at'])) . '</div>';

                            // Fetch replies for the current review
                            $replyQuery = "SELECT reply, created_at FROM replies WHERE review_id = $reviewId ORDER BY created_at DESC";
                            $replyResult = mysqli_query($conn, $replyQuery);

                            if (mysqli_num_rows($replyResult) > 0) {
                                while ($replyRow = mysqli_fetch_assoc($replyResult)) {
                                    echo '<div class="reply-section mt-2">';
                                    echo '<strong>Admin:</strong> ' . htmlspecialchars($replyRow['reply']);
                                    echo '<div class="text-muted" style="font-size: 0.8rem;">' . date("d M Y", strtotime($replyRow['created_at'])) . '</div>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<div class="reply-section mt-2 text-muted">Belum dibalas.</div>';
                            }

                            echo '<hr>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>tidak ada review.</p>';
                    }
                    ?>
                </div>
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
    <?php include '../componentuser/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="SB-Mid-client-YskmDxVncBKcWs4E"></script>



    <!-- pembayaran -->
    <script>
        function updateTotal(id) {
            // Get the harga per month and durasi
            const harga = parseFloat(document.getElementById('harga' + id).value);
            const durasi = parseInt(document.getElementById('durasi' + id).value);

            // Calculate the total harga
            const totalHarga = harga * durasi;

            // Set the total harga field
            document.getElementById('total_harga' + id).value = totalHarga.toFixed(2);
        }

        function submitPayment(id) {
    // Get the form data
    var form = document.getElementById('form' + id);
    var formData = new FormData(form);

    // Fetch the payment method
    var paymentMethod = document.querySelector('input[name="metode_pembayaran"]:checked');
    if (!paymentMethod) {
        alert("Pilih metode pembayaran!");
        return;
    }

    // Append payment method to the form data
    formData.append('metode_pembayaran', paymentMethod.value);

    // Submit the form via AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'process_payment.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            // If payment is successful, redirect to the receipts page
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                window.location.href = 'receipts.php?id_sewa=' + response.id_sewa + '&username=' + response.username + '&email=' + response.email + '&no_kost=' + response.no_kost + '&harga=' + response.harga + '&durasi=' + response.durasi + '&total_harga=' + response.total_harga + '&metode_pembayaran=' + response.metode_pembayaran;
            } else {
                alert("Terjadi kesalahan saat memproses pembayaran.");
            }
        } else {
            alert("Gagal menghubungi server.");
        }
    };
    xhr.send(formData);
}
    </script>
    <!-- view rating -->

    <!-- review -->
    <script>
        // Set up star rating click event
        document.querySelectorAll('.rating span').forEach(star => {
            star.addEventListener('click', function() {
                const ratingValue = this.getAttribute('data-rating');
                const ratingInputId = 'ratingValue' + this.closest('.rating').getAttribute('id').replace('ratingStars', '');
                document.getElementById(ratingInputId).value = ratingValue;

                // Highlight selected stars
                const stars = this.parentNode.children;
                for (let i = 0; i < stars.length; i++) {
                    stars[i].style.color = i < ratingValue ? 'orange' : 'gray';
                }
            });
        });

        // Submit review via AJAX
        function submitReview(id) {
            const formData = new FormData(document.getElementById('reviewForm' + id));

            fetch('add_rate.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    alert("Review submitted successfully!");
                    location.reload(); // Refresh to show the new review
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
    <script>
        // Function to set the rating and color the stars
        function setRating(rating, id) {
            const stars = document.querySelectorAll(`#ratingStars${id} span`);
            document.getElementById(`ratingValue${id}`).value = rating;

            stars.forEach((star, index) => {
                if (index < rating) {
                    star.style.color = '#FFD700'; // Gold color for selected stars
                } else {
                    star.style.color = '#000'; // Default color for unselected stars
                }
            });
        }
    </script>
    <script>
        $(function() {
            $(".rateyo").rateYo().on("rateyo.change", function(e, data) {
                var rating = data.rating;
                $(this).parent().find('.score').text('score :' + $(this).attr('data-rateyo-score'));
                $(this).parent().find('.result').text('rating :' + rating);
                $(this).parent().find('input[name=rating]').val(rating); //add rating value to input field
            });
        });
    </script>
</body>

</html>