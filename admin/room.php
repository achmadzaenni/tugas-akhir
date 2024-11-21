<?php
include("../koneksi.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $no_kost = $_POST['no_kost'];
    $type_kost = $_POST['type_kost'];
    $ukuran = $_POST['ukuran'];
    $alamat_kost = $_POST['alamat_kost'];
    $harga_kost = $_POST['harga_kost'];
    $status_kost = $_POST['status_kost'];

    // Handle fasilitas
    $fasilitas = isset($_POST['fasilitas']) ? implode(',', $_POST['fasilitas']) : '';

    // Handle gambar upload
    $gambar = $_FILES['gambar']['name'];
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($gambar);

    if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
        // Insert data ke database
        $sql = "INSERT INTO kosts (no_kost, type_kost,ukuran ,alamat_kost, harga_kost, fasilitas, status_kost, gambar) 
                VALUES ('$no_kost', '$type_kost', '$ukuran','$alamat_kost', '$harga_kost', '$fasilitas', '$status_kost', '$gambar')";
        if ($conn->query($sql) === TRUE) {
            header("Location: room.php");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Maaf, terjadi kesalahan saat mengunggah file.";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="/ta/img/logo.png" media="(prefers-color-scheme: light)">
    <link rel="icon" href="/ta/img/logo.png" media="(prefers-color-scheme: dark)">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Daftar Kamar</title>
</head>

<body>
    <?php include '../component/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4">Daftar Kamar</h2>
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addKamarModal">
            Tambah Kamar Baru
        </button>

        <!-- Modal -->
        <div class="modal fade" id="addKamarModal" tabindex="-1" aria-labelledby="addKamarModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addKamarModalLabel">Tambah Kamar Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="room.php" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="no_kost" class="form-label">No Kost</label>
                                <input type="number" class="form-control" id="no_kost" name="no_kost" required>
                            </div>
                            <div class="mb-3">
                                <label for="type_kost" class="form-label">Type Kost</label>
                                <input type="text" class="form-control" id="type_kost" name="type_kost" required>
                            </div>
                            <div class="mb-3">
                                <label for="ukuran" class="form-label">ukuran</label>
                                <input type="text" class="form-control" id="ukuran" name="ukuran" required>
                            </div>
                            <div class="mb-3">
                                <label for="alamat_kost" class="form-label">Alamat Kost</label>
                                <textarea class="form-control" id="alamat_kost" name="alamat_kost" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="harga_kost" class="form-label">Harga Kost</label>
                                <input type="number" class="form-control" id="harga_kost" name="harga_kost" required>
                            </div>
                            <div class="mb-3">
                                <label for="fasilitas" class="form-label">Fasilitas</label>
                                <div>
                                    <input type="checkbox" name="fasilitas[]" value="AC"> AC<br>
                                    <input type="checkbox" name="fasilitas[]" value="WiFi"> WiFi<br>
                                    <input type="checkbox" name="fasilitas[]" value="Listrik"> Listrik<br>
                                    <input type="checkbox" name="fasilitas[]" value="KM Luar"> KM Luar<br>
                                    <input type="checkbox" name="fasilitas[]" value="KM Dalam"> KM Dalam<br>
                                    <input type="checkbox" name="fasilitas[]" value="Air"> Air<br>
                                    <input type="checkbox" name="fasilitas[]" value="Parkiran"> Parkiran<br>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="status_kost" class="form-label">Status Kost</label>
                                <select class="form-control" id="status_kost" name="status_kost">
                                    <option value="tersedia">Tersedia</option>
                                    <option value="terisi">Terisi</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="gambar" class="form-label">Upload Gambar</label>
                                <input type="file" class="form-control" id="gambar" name="gambar" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Tambah Kamar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Kamar -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No Kost</th>
                    <th>Type Kost</th>
                    <th>ukuran</th>
                    <th>Alamat Kost</th>
                    <th>Harga Kost</th>
                    <th>Fasilitas</th>
                    <th>Status Kost</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM kosts";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['no_kost']; ?></td>
                        <td><?php echo $row['type_kost']; ?></td>
                        <td><?php echo $row['ukuran']; ?></td>
                        <td><?php echo $row['alamat_kost']; ?></td>
                        <td>Rp<?php echo number_format($row['harga_kost'], 0, ',', '.'); ?></td>
                        <td><?php echo str_replace(',', ', ', $row['fasilitas']); ?></td>
                        <td><?php echo ucfirst($row['status_kost']); ?></td>
                        <td><img src="../uploads/<?php echo $row['gambar']; ?>" alt="Gambar Kost" style="width: 100px;"></td>
                        <td>
                            <a href="edit_room.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete_room.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
