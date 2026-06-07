<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas 3 - Menampilkan Nama Bulan Sekarang</title>
</head>
<body>
    <h1><?php echo "Menampilkan Nama Bulan Sekarang dan Sisa Hari"; ?></h1>
    <p>Bulan sekarang: <?php echo date("F"); ?></p>
    <p>Hari tersisa di bulan ini: <?php echo date("t") - date("j"); ?></p>
</body>
</html>