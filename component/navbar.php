<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Pastikan session sudah dimulai
}
require ("../koneksi.php"); // Include your database connection file

// Ensure user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    header("Location: ../forminput/login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch the user ID from session
$userId = $_SESSION['user_id'];

// Initialize the default profile picture path
$profilePic = '/ta/img/default-avatar.png'; // Path to default profile image

// Fetch the user's profile picture from the database
$query = "SELECT profile_pic FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($profilePicFromDB);
if ($stmt->fetch() && !empty($profilePicFromDB)) {
    // If user has uploaded a profile picture, set it to the correct path
    $profilePic = '/ta/uploads/' . $profilePicFromDB;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="/ta/img/logo.png" media="(prefers-color-scheme: light)" />
    <link rel="icon" href="/ta/img/logo.png" media="(prefers-color-scheme: dark)" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand">
                <img src="/ta/img/logo1.png" alt="Bootstrap" width="55" height="30">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="dashboard.php">beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="room.php">Kamar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="billing.php">Penagihan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="feedback.php"> Feedback</a>
                    </li>
                </ul>
                <!-- Pencarian -->
                
                     <!-- Profile Photo -->
                <div class="profile ms-3">
                    <a href="profile.php">
                        <img src="<?php echo $profilePic; ?>" alt="Profile Picture" width="40" height="40" class="rounded-circle">
                    </a>
                </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- endnav -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>