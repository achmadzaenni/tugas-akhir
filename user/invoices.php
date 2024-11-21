<?php
session_start();
include("../koneksi.php");

$id_user = $_SESSION['user_id']; // Assuming user ID is stored in session upon login

$query = "SELECT * FROM invoices WHERE id_user = ? ORDER BY created_at ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id_user);
$stmt->execute();
$result = $stmt->get_result();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-4">
    <h2>My Invoices</h2>
    <table class="table">
        <thead>
            <tr>
                <th>No Kost</th>
                <th>Amount</th>
                <th>Due Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['no_kost']); ?></td>
                    <td><?php echo htmlspecialchars($row['amount']); ?></td>
                    <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                    <td><?php echo ucfirst($row['status']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>
