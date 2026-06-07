<?php
include_once("config.php");
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}
$errors = [];
if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string(
        $conn,
        $_POST['username']
    );
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $full_name = mysqli_real_escape_string(
        $conn,
        $_POST['full_name']
    );
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    // Validasi input
    if (empty($username))
        $errors[] = 'Username tidak boleh
kosong';
    if (empty($email))
        $errors[] = 'Email tidak boleh kosong';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = 'Format email tidak valid';
    if (empty($full_name))
        $errors[] = 'Nama lengkap tidak boleh
kosong';
    if (strlen($password) < 6)
        $errors[] = 'Password minimal 6
karakter';
    if ($password !== $confirm)
        $errors[] = 'Konfirmasi password
tidak cocok';
    // Cek apakah username/email sudah terdaftar
    $check = mysqli_query(
        $conn,
        "SELECT id FROM users WHERE username='$username' OR
email='$email'"
    );
    if (mysqli_num_rows($check) > 0)
        $errors[] = 'Username atau email sudah terdaftar';
    if (empty($errors)) {
        // Hash password sebelum disimpan
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, full_name,
password)
VALUES
('$username','$email','$full_name','$hashed')";
        if (mysqli_query($conn, $sql))
            $success = 'Registrasi berhasil! Silakan login.';
        else
            $errors[] = 'Error: ' . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body {
            background: #e8f0fe;
            font-family: Arial;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .box {
            background: white;
            padding: 35px;
            width: 380px;
            border-radius: 15px;
            box-shadow:
                0 0 15px rgba(0, 0, 0, 0.15);
        }
        h2 {
            text-align: center;
            color: #140042;
            margin-bottom: 25px;
        }
        input {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #140042;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover {
            background: #140042;
        }
        .error {
            background: #ffd7d7;
            padding: 10px;
            color: red;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .success {
            background: #d4ffd4;
            padding: 10px;
            color: green;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .login {
            text-align: center;
            margin-top: 15px;
        }
        a {
            text-decoration: none;
            color: #140042;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>REGISTER</h2>
        <?php
        foreach ($errors as $err) {
            echo "<div class='error'>$err</div>";
        }
        if (isset($success)) {
            echo "<div class='success'>$success</div>";
        }
        ?>
        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" required>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Nama Lengkap</label>
            <input type="text" name="full_name" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <label>Konfirmasi Password</label>
            <input type="password" name="confirm_password" required>
            <button type="submit" name="register">Daftar</button>
        </form>
        <div class="login">Sudah punya akun?<a href="login.php">Login</a></div>
    </div>
</body>
</html>