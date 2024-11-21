<?php
    session_start(); // Ensure session is started
include("../koneksi.php");

// Query to fetch tenant data from the 'transactions' table
if (isset($_GET['search'])) {
    $searchKeyword = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT t.username, t.email, t.no_kost, t.harga, t.durasi, t.total_harga, t.status, t.created_at
              FROM transactions t
              WHERE (t.username LIKE '%$searchKeyword%' OR t.email LIKE '%$searchKeyword%' OR t.no_kost LIKE '%$searchKeyword%' OR t.status LIKE '%$searchKeyword%')
              AND t.status = 'aktif'";
} else {
    $query = "SELECT t.username, t.email, t.no_kost, t.harga, t.durasi, t.total_harga, t.status, t.created_at
              FROM transactions t
              WHERE t.status = 'aktif'";
}

$result = mysqli_query($conn, $query);

// Display results here
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ekost</title>
    <link rel="icon" href="/ta/img/logo.png" media="(prefers-color-scheme: light)" />
    <link rel="icon" href="/ta/img/logo.png" media="(prefers-color-scheme: dark)" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
<?php include '../component/navbar.php'; ?>

    <div class="container mt-4">
        <h2>Data Room</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>No Kost</th>
                    <th>Email</th>
                    <th>Harga</th>
                    <th>Durasi (bulan)</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['no_kost']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['harga']; ?></td>
                        <td><?php echo $row['durasi']; ?></td>
                        <td><?php echo $row['total_harga']; ?></td>
                        <td><?php echo ucfirst($row['status']); ?></td>
                        <td><a href="send_invoice.php" class="btn btn-warning">Tagih</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
