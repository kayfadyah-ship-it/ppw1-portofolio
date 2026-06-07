<?php
include_once("config.php");
requireLogin();
if (!isset($_GET['id'])) {
    header("Location:index.php");
    exit();
}
$id = (int) $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id=$id");
if (mysqli_num_rows($query) == 0) {
    header("Location:index.php");
    exit();
}
$data = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detail Mahasiswa</title>
    <style>
        body {
            font-family: Arial;
            background: #edf2ff;
            margin: 0;
        }
        .container {
            width: 600px;
            margin: 40px auto;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow:
                0 0 10px rgba(0, 0, 0, .1);
        }
        img {
            width: 220px;
            height: 220px;
            object-fit: cover;
            border-radius: 10px;
            display: block;
            margin: auto;
        }
        h2 {
            text-align: center;
            color: #140042;
        }
        .data {
            margin-top: 20px;
            line-height: 2;
        }
        .btn {
            padding: 10px 15px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            margin-right: 10px;
        }
        .back {
            background: gray;
        }
        .edit {
            background: #140042;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Detail Mahasiswa</h2>
            <?php
            if ($data['foto']) {
                ?>
                <img src="uploads/mahasiswa/<?= $data['foto'] ?>">
                <?php
            } else {
                echo"<p align='center'>Tidak ada foto</p>";
            }
            ?>
            <div class="data">
                <b>NIM :</b>
                <?= htmlspecialchars($data['nim']) ?>
                <br>
                <b>Nama :</b>
                <?= htmlspecialchars($data['nama']) ?>
                <br>
                <b>Jurusan :</b>
                <?= htmlspecialchars($data['jurusan']) ?>
                <br>
                <b>Email :</b>
                <?= htmlspecialchars($data['email']) ?>
                <br>
                <b>Alamat :</b>
                <?= htmlspecialchars($data['alamat']) ?>
                <br>
                <b>Tanggal Daftar :</b>
                <?= $data['created_at'] ?>
            </div>
            <br>
            <a href="indeks.php" class="btn back">Kembali</a>
            <a href="edit.php?id=<?= $data['id'] ?>" class="btn edit">Edit</a>
        </div>
    </div>
</body>
</html>