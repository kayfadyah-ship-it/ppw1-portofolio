<?php
include_once("config.php");
requireLogin();
if (isset($_POST['submit'])) {
    // Ambil dan escape data
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $errors = [];
    $foto_filename = null;
    // Validasi field wajib
    if (empty($nim)) {
        $errors[] =
            'NIM tidak boleh kosong';
    } elseif (!is_numeric($nim)) {
        $errors[] =
            'NIM hanya boleh angka';
    } elseif (
        strlen($nim) < 8 ||
        strlen($nim) > 12
    ) {
        $errors[] =
            'Panjang NIM harus 8-12 digit';
    }
    if (empty($nama))
        $errors[] = 'Nama tidak boleh kosong';
    if (empty($jurusan))
        $errors[] = 'Jurusan tidak boleh kosong';
    if (empty($email))
        $errors[] = 'Email tidak boleh kosong';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = 'Format email tidak valid';
    // Cek NIM sudah terdaftar
    $chk = mysqli_query($conn, "SELECT nim FROM mahasiswa WHERE nim='$nim'");
    if (mysqli_num_rows($chk) > 0)
        $errors[] = 'NIM sudah
terdaftar';
    // Proses upload foto (opsional)
    if (!empty($_FILES['foto']['name'])) {
        $upload = uploadFile($_FILES['foto']);
        if ($upload['success'])
            $foto_filename = $upload['filename'];
        else
            $errors[] = $upload['message'];
    }
    // Jika valid, simpan ke database
    if (empty($errors)) {
        $foto_sql = $foto_filename ? "'$foto_filename'" : 'NULL';
        $sql = "INSERT INTO mahasiswa (nim, nama, jurusan, email, alamat, foto)VALUES
('$nim','$nama','$jurusan','$email','$alamat',$foto_sql)";
        if (mysqli_query($conn, $sql))
            $success = 'Data berhasil ditambahkan!';
        else {
            $errors[] = 'Error: ' . mysqli_error($conn);
            if ($foto_filename)
                deleteFile($foto_filename); // rollback foto
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Tambah Mahasiswa</title>
    <style>
        body {
            font-family: Arial;
            background: #edf2ff;
            margin: 0;
        }
        .navbar {
            background: #0a0041;
            padding: 18px;
            color: white;
            font-size: 22px;
            font-weight: bold;
        }
        .container {
            width: 500px;
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
            margin-bottom: 6px;
            font-weight: bold;
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
        small {
            color: gray;
        }
        .required {
            color: red;
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
            background: #11004f;
            color: white;
        }
        .btn-back {
            background: gray;
            color: white;
        }
        .alert {
            background: #ffd5d5;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
            color: red;
        }
        .success {
            background: #d4ffd4;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
            color: green;
        }
        .button-area {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="navbar">Tambah Data Mahasiswa</div>
    <div class="container">
        <div class="card">
            <h2>Form Tambah Mahasiswa</h2>
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
            <form action="tambah.php" method="POST" enctype="multipart/form-data">
                <div class="form-group"><label>Foto Profil</label>
                    <input type="file" name="foto" accept="image/*">
                    <small>JPG, PNG | Maks 5MB</small>
                </div>
                <div class="form-group">
                    <label>NIM<span class="required">*</span></label>
                    <input type="text" name="nim" required
                        value="<?= isset($_POST['nim']) ? htmlspecialchars($_POST['nim']) : '' ?>">
                </div>
                <div class="form-group">
                    <label>Nama Lengkap<span class="required">*</span></label>
                    <input type="text" name="nama" required
                        value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>">
                </div>
                <div class="form-group">
                    <label>Jurusan<span class="required">*</span></label>
                    <input type="text" name="jurusan" required
                        value="<?= isset($_POST['jurusan']) ? htmlspecialchars($_POST['jurusan']) : '' ?>">
                </div>
                <div class="form-group">
                    <label>Email<span class="required">*</span></label>
                    <input type="email" name="email" required
                        value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea
                        name="alamat"><?= isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : '' ?></textarea>
                </div>
                <div class="button-area">
                    <button type="submit" name="submit" class="btn btn-save">Simpan Data</button>
                    <a href="indeks.php" class="btn btn-back">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>