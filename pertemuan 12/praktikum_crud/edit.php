<?php
include_once("config.php");
requireLogin();
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}
$id = (int) $_GET["id"];
$result = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE
id=$id");
if (mysqli_num_rows($result) == 0) {
    header('Location: indeks.php');
    exit();
}
$row = mysqli_fetch_assoc($result);
$current_foto = $row["foto"];
if (isset($_POST['update'])) {
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $errors = [];

    // VALIDASI NIM
    if (!is_numeric($nim)) {
        $errors[] =
            "NIM hanya boleh berisi angka";
    } elseif (
        strlen($nim) < 8 || strlen($nim) > 12) {
        $errors[] =
            "Panjang NIM harus 8 sampai 12 digit";
    }
    $foto_filename = $current_foto;
    $foto_filename = $current_foto;

    if (!empty($_FILES['foto']['name'])) {
        $upload = uploadFile($_FILES['foto']);
        if ($upload['success']) {
            if ($current_foto)
                deleteFile($current_foto); // hapus foto lama
            $foto_filename = $upload['filename'];
        } else {
            $errors[] = $upload['message'];
        }
    }
    if (isset($_POST['hapus_foto']) && $_POST['hapus_foto'] == '1') {
        if ($current_foto)
            deleteFile($current_foto);
        $foto_filename = null;
    }
    if (empty($errors)) {
        $foto_sql = $foto_filename ? "'$foto_filename'" : 'NULL';
        $sql = "UPDATE mahasiswa SET
            nim='$nim', nama='$nama', jurusan='$jurusan',
            email='$email', alamat='$alamat', foto=$foto_sql
            WHERE id=$id";
        if (mysqli_query($conn, $sql))
            $success = 'Data berhasil diperbarui!';
        else
            $errors[] = 'Error: ' . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Mahasiswa</title>
    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: #edf2ff;
        }

        .navbar {
            background: #140042;
            padding: 18px;
            color: white;
            font-size: 22px;
            font-weight: bold;
        }

        .container {
            width: 550px;
            margin: 35px auto;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow:
                0 0 10px rgba(0, 0, 0, .1);
        }

        h2 {
            text-align: center;
            color: #140042;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: normal;
            margin: 0;
        }

        .checkbox-label input {
            width: auto;
            margin: 0;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;
            border:
                1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        textarea {
            height: 90px;
            resize: none;
        }

        img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
            border:
                2px solid #ddd;
            margin-bottom: 10px;
        }

        .btn {
            padding: 12px 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-save {
            background: #140042;
            color: white;
        }

        .btn-back {
            background: gray;
            color: white;
        }

        .alert {
            background: #ffd5d5;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            color: red;
        }

        .success {
            background: #d4ffd4;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            color: green;
        }

        .button-area {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="navbar">Edit Data Mahasiswa</div>
    <div class="container">
        <div class="card">
            <h2>Form Edit Mahasiswa</h2>
            <?php
            if (!empty($errors)) {
                foreach ($errors as $e) {
                    echo "<div class='alert'>$e</div>";
                }
            }
            if (isset($success)) {
                echo "<div class='success'>$success</div>";
            }
            ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Foto Saat Ini</label>
                    <?php if ($current_foto): ?>
                        <img src="uploads/mahasiswa/<?= $current_foto ?>">
                    <?php else: ?>
                        <p>Belum ada foto</p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Ganti Foto</label>
                    <input type="file" name="foto" accept="image/*">
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="hapus_foto" value="1">Hapus foto lama
                    </label>
                </div>
                <div class="form-group">
                    <label>NIM</label>
                    <input type="text" name="nim" required value="<?= htmlspecialchars($row['nim']) ?>">
                </div>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" required value="<?= htmlspecialchars($row['nama']) ?>">
                </div>
                <div class="form-group">
                    <label>Jurusan</label>
                    <input type="text" name="jurusan" required value="<?= htmlspecialchars($row['jurusan']) ?>">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required value="<?= htmlspecialchars($row['email']) ?>">
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat"><?= htmlspecialchars($row['alamat']) ?></textarea>
                </div>
                <div class="button-area">
                    <button type="submit" name="update" class="btn btn-save">Update Data</button>
                    <a href="indeks.php" class="btn btn-back">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>