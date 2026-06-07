<?php

include_once("config.php");
// Jika sudah login, langsung ke index
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

include_once("config.php");

// Jika sudah login, langsung ke index
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = ""; // TAMBAHKAN INI

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string(
        $conn,
        $_POST['username']
    );

    $password = $_POST['password'];

    // kode login
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string(
        $conn,
        $_POST['username']
    );
    $password = $_POST['password'];
    // Cari user berdasarkan username ATAU email
    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        // Verifikasi password dengan hash bcrypt
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            header('Location: indeks.php');
            exit();
        } else {
            $error = "Username atau password salah!";
        }
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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
            width: 350px;
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
            background: #1942bd;
        }
        .error {
            background: #ffd7d7;
            padding: 10px;
            color: red;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .register {
            text-align: center;
            margin-top: 15px;
        }
        a {
            color: #140042;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>LOGIN</h2>
        <?php
        if ($error != "") { echo "<div class='error'> 
        $error
    </div>";
        }
        ?>
        <form method="POST">
            <label>Username / Email</label>
            <input type="text" name="username" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit" name="login">Masuk</button>
        </form>
        <div class="register">Belum punya akun?<a href="register.php">Daftar</a>
        </div>
    </div>
</body>
</html>