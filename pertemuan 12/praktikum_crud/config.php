<?php
session_start();
// Konfigurasi koneksi database
$host = "localhost";
$username = "root";
$password = "";
$database = "praktikum_crud";
// Buat koneksi
$conn = mysqli_connect($host, $username, $password, $database);
// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
// Cek apakah user sudah login
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}
// Redirect ke login jika belum login
function requireLogin()
{
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}
// Fungsi upload foto
function uploadFile($file, $target_dir = "uploads/mahasiswa/")
{
    if (!file_exists($target_dir)) {
        mkdir(
            $target_dir,
            0777,
            true
        );
    }
    $imageFileType = strtolower(
        pathinfo(
            $file["name"],
            PATHINFO_EXTENSION
        )
    );
    if ($file["size"] > 5000000) {
        return [
            'success' => false,
            'message' => 'File terlalu besar. Maks 5MB.'
        ];
    }
    $allowed = [
        "jpg",
        "jpeg",
        "png",
        "gif"
    ];
    if (
        !in_array(
            $imageFileType,
            $allowed
        )
    ) {
        return [
            'success' => false,
            'message' => 'Format file tidak didukung.'
        ];
    }
    $new_filename =
        uniqid()
        . "."
        . $imageFileType;
    $target_file =
        $target_dir
        . $new_filename;
    if (
        move_uploaded_file(
            $file["tmp_name"],
            $target_file
        )
    ) {
        return [
            'success' => true,
            'filename' => $new_filename
        ];
    }
    return [
        'success' => false,
        'message' => 'Gagal upload file.'
    ];
}
// Fungsi hapus file foto
function deleteFile($filename, $dir = "uploads/mahasiswa/")
{
    if ($filename && file_exists($dir . $filename))
        unlink($dir . $filename);
}
?>