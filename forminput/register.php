<?php
include("../koneksi.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
    $role = 'user'; // Automatically set role to user

    // Check if username or email already exists
    $sqlCheck = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $resultCheck = $conn->query($sqlCheck);

    if ($resultCheck->num_rows > 0) {
        // Username or email already exists
        $error = "Username or email already exists. Please choose a different one.";
    } else {
        // Insert user into database
        $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";

        if ($conn->query($sql) === TRUE) {
            header("Location: login.php"); // Redirect to login page after successful registration
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="/projek_ukk/img/logo.png" media="(prefers-color-scheme: light)" />
    <link rel="icon" href="/projek_ukk/img/logo.png" media="(prefers-color-scheme: dark)" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <title>ekost</title>
    <style>
    .input-group {
    position: relative;
}

.input-group-text {
    position: absolute;
    right: -25px; /* Add some space from the right */
    top: 35%; /* Center it vertically */
    transform: translateY(-50%); /* Adjust for the vertical alignment */
    z-index: 2;
    background-color: white; /* Optional: to ensure the background is visible */
    border-left: none; /* Remove left border to match the input */
    border-radius: 0; /* Optional: remove border-radius if you want a sharper corner */
}
</style>
</head>
<body>
<div class="form-container">
    <div class="form-section">
        <h2>Register</h2>
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="register.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <div class="input-group mb-3">
        <input type="password" name="password" placeholder="Password" id="password-input" required class="form-control">
        <span class="input-group-text" id="toggle-password" style="cursor: pointer;">
            <i class="bi bi-eye" id="eye-icon"></i>
        </span>
    </div>
            <button type="submit">Register</button>
        </form>
        <p>Sudah Punya Akun? <a href="login.php">Login</a></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    document.getElementById('toggle-password').addEventListener('click', function () {
        const passwordInput = document.getElementById('password-input');
        const eyeIcon = document.getElementById('eye-icon');

        // Toggle the type attribute
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('bi-eye');
            eyeIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('bi-eye-slash');
            eyeIcon.classList.add('bi-eye');
        }
    });
</script>
</body>
</html>
