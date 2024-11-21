<?php
include("../koneksi.php");

session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];  
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];  
            $_SESSION['role'] = $row['role'];

            if ($row['role'] == 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../user/dashboard.php");
            }
            exit();  
        } else {
            $error = "Password salah";
        }
    } else {
        $error = "No user found with that username or email.";
    }

    $stmt->close();
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
    right: -25px; 
    top: 35%; 
    transform: translateY(-50%); 
    z-index: 2;
    background-color: white; 
    border-left: none; 
    border-radius: 0; 
}


</style>
</head>
<body>
<div class="form-container">
    <div class="form-section">
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="login.php">
    <input type="text" name="username" placeholder="Username or Email" required>
    <div class="input-group mb-3">
        <input type="password" name="password" placeholder="Password" id="password-input" required class="form-control">
        <span class="input-group-text" id="toggle-password" style="cursor: pointer;">
            <i class="bi bi-eye" id="eye-icon"></i>
        </span>
    </div>
    <button type="submit">Login</button>
</form>

        <p>Belum Punya Akun? <a href="register.php">Register</a></p>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
    document.getElementById('toggle-password').addEventListener('click', function () {
        const passwordInput = document.getElementById('password-input');
        const eyeIcon = document.getElementById('eye-icon');

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
