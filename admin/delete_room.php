<?php
include("../koneksi.php");

$id = $_GET['id'];

$sql = "DELETE FROM kosts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();

header("Location: room.php");
?>
