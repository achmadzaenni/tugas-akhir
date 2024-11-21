<?php
include("../koneksi.php");

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $no_kost = $_POST['no_kost'];
    $type_kost = $_POST['type_kost'];
    $alamat_kost = $_POST['alamat_kost'];
    $harga_kost = $_POST['harga_kost'];
    if (isset($_POST['fasilitas'])) {
        $fasilitas = implode(',', $_POST['fasilitas']);
    } else {
        $fasilitas = '';
    }
    $status_kost = $_POST['status_kost'];

    // If a new image is uploaded
    if ($_FILES['gambar']['tmp_name']) {
        // Define the target directory and file path
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
        
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            // Prepare SQL query with the image path
            $sql = "UPDATE kosts SET no_kost = ?, type_kost = ?, alamat_kost = ?, harga_kost = ?, fasilitas = ?, status_kost = ?, gambar = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssssssi', $no_kost, $type_kost, $alamat_kost, $harga_kost, $fasilitas, $status_kost, $target_file, $id);
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        // Prepare SQL query without the image
        $sql = "UPDATE kosts SET no_kost = ?, type_kost = ?, alamat_kost = ?, harga_kost = ?, fasilitas = ?, status_kost = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssi', $no_kost, $type_kost, $alamat_kost, $harga_kost, $fasilitas, $status_kost, $id);
    }
    $stmt->execute();
    header("Location: room.php");
}

$sql = "SELECT * FROM kosts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$kost = $result->fetch_assoc();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/ta/img/logo.png" media="(prefers-color-scheme: light)" />
    <link rel="icon" href="/ta/img/logo.png" media="(prefers-color-scheme: dark)" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.3.5/tailwind.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Edu+Australia+VIC+WA+NT+Hand&display=swap" rel="stylesheet">
    <title>ekost</title>
    <style>
        /* General form styling */
form {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    font-family: 'Edu Australia VIC WA NT Hand', sans-serif;
}

/* Form inputs styling */
input[type="text"],
input[type="number"],
select,
input[type="file"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
}

/* Button styling */
button[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

/* Navbar padding */
body {
    padding-top: 56px;
}

select {
    font-size: 1rem;
}

/* Customize image upload field */
input[type="file"] {
    background-color: #fff;
    padding: 10px;
    border: 1px solid #007bff;
    color: #007bff;
    cursor: pointer;
}

input[type="file"]::-webkit-file-upload-button {
    display: none;
}

input[type="file"]::before {
    content: 'Choose a file';
    display: inline-block;
    background: #007bff;
    color: white;
    border: 1px solid #007bff;
    border-radius: 5px;
    padding: 5px 10px;
    cursor: pointer;
}

/* Hover effect for file input */
input[type="file"]:hover::before {
    background: #0056b3;
}

/* Responsive */
@media (max-width: 768px) {
    form {
        padding: 15px;
    }
    button[type="submit"] {
        font-size: 1rem;
    }
}

    </style>
</head>
<body>

 <br>
 <form action="edit_room.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
    <h2 style="text-align: center;">Edit Room</h2>
    <br>
    <input type="text" name="no_kost" value="<?php echo $kost['no_kost']; ?>" required>
    <input type="text" name="type_kost" value="<?php echo $kost['type_kost']; ?>" required>
    <input type="text" name="alamat_kost" value="<?php echo $kost['alamat_kost']; ?>" required>
    <input type="number" name="harga_kost" value="<?php echo $kost['harga_kost']; ?>" required>
    <div class="mb-3">
    <label for="fasilitas" class="form-label">Fasilitas</label>
    <button class="btn btn-secondary mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#fasilitasCollapse" aria-expanded="false" aria-controls="fasilitasCollapse">
        Pilih Fasilitas
    </button>
    <div class="collapse" id="fasilitasCollapse">
        <div class="card card-body">
            <div>
                <?php
                $fasilitas = explode(',', $kost['fasilitas']);
                ?>
                <input type="checkbox" name="fasilitas[]" value="AC" <?php if (in_array('AC', $fasilitas)) echo 'checked'; ?>> AC<br>
                <input type="checkbox" name="fasilitas[]" value="WiFi" <?php if (in_array('WiFi', $fasilitas)) echo 'checked'; ?>> WiFi<br>
                <input type="checkbox" name="fasilitas[]" value="Listrik" <?php if (in_array('Listrik', $fasilitas)) echo 'checked'; ?>> Listrik<br>
                <input type="checkbox" name="fasilitas[]" value="KM Luar" <?php if (in_array('KM Luar', $fasilitas)) echo 'checked'; ?>> KM Luar<br>
                <input type="checkbox" name="fasilitas[]" value="KM Dalam" <?php if (in_array('KM Dalam', $fasilitas)) echo 'checked'; ?>> KM Dalam<br>
                <input type="checkbox" name="fasilitas[]" value="Air" <?php if (in_array('Air', $fasilitas)) echo 'checked'; ?>> Air<br>
                <input type="checkbox" name="fasilitas[]" value="Parkiran" <?php if (in_array('Parkiran', $fasilitas)) echo 'checked'; ?>> Parkiran<br>
            </div>
        </div>
    </div>
</div>
    <select name="status_kost">
        <option value="tersedia" <?php if ($kost['status_kost'] == 'tersedia') echo 'selected'; ?>>tersedia</option>
        <option value="terisi" <?php if ($kost['status_kost'] == 'terisi') echo 'selected'; ?>>terisi</option>
    </select>
    <input type="file" name="gambar">
    <button type="submit">Update Room</button>
    <a href="room.php" class="btn btn-secondary ">cancel</a>
</form>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>