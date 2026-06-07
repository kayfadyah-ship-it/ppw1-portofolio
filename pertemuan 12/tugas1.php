<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Konversi Nilai</title>
</head>
<body>

<h2>Konversi Nilai</h2>

<form method="POST" action="konversi.php">
    <label for="nilai">Masukkan Nilai:</label><br>
    <input type="number" id="nilai" name="nilai" min="0" max="100" required><br><br>
    <button type="submit">Konversi</button>
</form>


</body>
</html>