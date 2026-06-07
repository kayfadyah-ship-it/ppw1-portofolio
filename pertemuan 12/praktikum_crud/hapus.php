<?php
include_once("config.php");
requireLogin();
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    // Ambil nama foto SEBELUM data dihapus
    $result = mysqli_query($conn, "SELECT foto FROM mahasiswa WHERE
id=$id");
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $foto = $row["foto"];
        // Hapus data dari database
        if (
            mysqli_query($conn, "DELETE FROM mahasiswa WHERE
id=$id")
        ) {
            // Hapus file foto jika ada
            if ($foto)
                deleteFile($foto);
            $_SESSION['message'] = 'Data berhasil dihapus!';
        } else {
            $_SESSION['error'] = 'Error: ' . mysqli_error($conn);
        }
    }
}
// Kembali ke halaman utama
header('Location: indeks.php');
exit();
?>