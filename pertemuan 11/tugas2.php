<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tugas 2 - Hitung IMT</title>
</head>
<body>
    <h1 align="center"><?php echo "Indeks Masa Tubuh"; ?></h1>
<?php
function hitungIMT($berat, $tinggi) {

            $tinggiMeter = $tinggi / 100;
            $imt = $berat / ($tinggiMeter * $tinggiMeter);
            if ($imt < 18.5) {
                $kategori = "Kurus";
            } elseif ($imt < 25) {
                $kategori = "Normal";
            } elseif ($imt < 30) {
                $kategori = "Gemuk";
            } else {
                $kategori = "Obesitas";
            }
            return [
                "nilaiIMT" => round($imt, 2),
                "kategori" => $kategori
            ];
        }
?>
<form method="POST">
    <label for="berat">Berat Badan (kg):</label><br>
    <input type="number" id="berat" name="berat" required><br><br>

    <label for="tinggi">Tinggi Badan (cm):</label><br>
    <input type="number" id="tinggi" name="tinggi" required><br><br>

    <button type="submit">Hitung IMT</button>
</form>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $berat = $_POST["berat"];
        $tinggi = $_POST["tinggi"];

        $hasil = hitungIMT($berat, $tinggi);

        echo"
        <div class='hasil'>
            <p>Berat Badan : $berat kg</p>
            <p>Tinggi Badan : $tinggi cm</p>
            <p>Nilai IMT : {$hasil['nilaiIMT']}</p>
            <p>Kategori : {$hasil['kategori']}</p>
        </div>
        ";
    }
?>
    
</body>
</html>