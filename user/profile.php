<?php
session_start();
require ("../koneksi.php"); // Include your database connection file

// Ensure user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    header("Location: ../forminput/login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch the user ID from session
$userId = $_SESSION['user_id'];

// Fetch the user data from the database
$query = "SELECT username, email, profile_pic FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($username, $email, $profile_pic);
if (!$stmt->fetch()) {
    // Handle case where no user found
    $username = "Unknown User"; // Default fallback
    $email = "Unknown Email"; // Default fallback
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="icon" href="/ta/img/logo.png" media="(prefers-color-scheme: light)" />
    <link rel="icon" href="/ta/img/logo.png" media="(prefers-color-scheme: dark)" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .card-body img {
            border: 5px solid #6c757d;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        /* trash */
/* Styling for the trash icon lid */
#trash-icon {
    position: relative;
    display: inline-block;
}

.lid {
    width: 15px;
    height: 10px;
    background-color: black;
    position: absolute;
    top: -15px;
    left: 5px;
    transform-origin: left center;
    transform: rotate(0deg); /* Initial closed position */
    transition: transform 0.3s ease; /* Animation for opening/closing */
}

/* Open the trash lid */
.lid.open {
    transform: rotate(-45deg); /* Opens the lid */
}

</style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <form id="profile-form" action="update_profile.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-4 text-center">
    <?php if ($profile_pic): ?>
        <img src="<?php echo $profile_pic; ?>" alt="Profile Picture" class="rounded-circle" width="150" height="150">
    <?php else: ?>
        <i class="bi bi-person-circle" style="font-size: 150px;"></i>
    <?php endif; ?>
    <div class="d-flex justify-content-center align-items-center mt-2">
    <input type="file" name="profile_pic" class="form-control" id="profile-pic-input" style="width: auto;">
    <a href="#" id="trash-icon" class="ms-3" style="position: relative;"> <!-- Icon with animation -->
        <i class="bi bi-trash3"></i>
        <div class="lid"></div> <!-- Trash lid element -->
    </a>
</div>

</div>


                            <!-- Username (not editable) -->
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" id="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" disabled>
                            </div>
                            <!-- Email (not editable) -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" disabled>
                            </div>
                            <!-- Password Change -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Ganti Kata Sandi</label>
                                <input type="password" name="new_password" id="password" class="form-control" placeholder="Kata sandi baru">
                            </div>
                            <!-- Save Button -->
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <!-- Cancel Button -->
                            <a href="dashboard.php" class="btn btn-secondary">Batal</a>
                        </form>

                        
                        <div class="d-flex justify-content-center mt-4">
    <!-- Logout Button -->
    <a href="../forminput/logout.php" class="btn btn-warning me-2">Logout</a>

    <!-- Delete Account Button -->
    <form action="delete_account.php" method="POST">
        <button type="submit" class="btn btn-danger">Delete Account</button>
    </form>
</div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('trash-icon').addEventListener('click', function(e) {
    e.preventDefault(); // Prevents immediate navigation to delete action

    // Get the trash lid element
    const lid = document.querySelector('.lid');

    // Add 'open' class to lid to start the animation
    lid.classList.add('open');

    // Wait for the animation to finish (e.g., 0.3 seconds) before navigating to the delete link
    setTimeout(function() {
        // Perform delete action after animation (open delete_profile_pic.php or add further confirmation)
        window.location.href = 'delete_profile_pic.php';
    }, 500); // Adjust timing based on the animation duration
});

</script>
</body>
</html>
