<?php
include_once("config.php");
requireLogin();

$limit = 5;
$page = isset($_GET["page"])
    ? (int) $_GET["page"]
    : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET["search"])
    ? mysqli_real_escape_string(
        $conn,
        $_GET["search"]
    )
    : "";
$where = "";
if (!empty($search)) {
    $where = "
WHERE nim LIKE '%$search%'
OR nama LIKE '%$search%'
OR jurusan LIKE '%$search%'
OR email LIKE '%$search%'";
}

$count_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM mahasiswa $where"
);
$total_data = mysqli_fetch_assoc(
    $count_result
)["total"];
$total_pages = ceil($total_data / $limit);
$query = "SELECT * FROM mahasiswa $where ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query); ?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard Mahasiswa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial;
            background: #edf2ff;
        }

        .navbar {
            background: #0c0063;
            padding: 18px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            font-size: 24px;
        }

        .logout {
            background: #ff0000;
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
        }

        .container {
            width: 90%;
            margin: 25px auto;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow:
                0 0 10px rgba(0, 0, 0, .1);
        }

        .top {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .search-box {
            display: flex;
            gap: 10px;
        }

        .search-box input {
            padding: 10px;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .search-box button {
            padding: 10px 15px;
            border: none;
            background: #0c0063;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }

        .tambah {
            background: #0c0063;
            padding: 10px 15px;
            text-decoration: none;
            color: white;
            border-radius: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #0c0063;
            color: white;
            padding: 14px;
        }

        td {
            padding: 12px;
            text-align: center;
            border-bottom:
                1px solid #ddd;
        }

        .photo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
        }

        .no-photo {
            color: gray;
        }

        .btn {
            padding: 8px 10px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
        }

        .btn-warning {
            background: #9f7701;
        }

        .btn-danger {
            background: #9d0303;
        }

        .alert-success {
            background: #81a09d;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
        }

        .alert-danger {
            background: #ffd5d5;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            padding: 8px 12px;
            background: #ddd;
            text-decoration: none;
            border-radius: 5px;
            margin: 3px;
            color: black;
        }

        .current {
            background: #2d5be3;
            color: white;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <h2>Dashboard Mahasiswa</h2>
        <div>Halo,<b><?= htmlspecialchars($_SESSION['full_name']) ?></b>
            <a class="logout" href="logout.php">Logout</a>
        </div>
    </div>
    <div class="container">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert-success">
                <?= $_SESSION['message'] ?>
            </div>
            <?php unset($_SESSION['message']); endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert-danger">
                <?= $_SESSION['error'] ?>
            </div>
            <?php unset($_SESSION['error']); endif; ?>
        <div class="card">
            <div class="top">
                <form method="GET" class="search-box">
                    <input type="text" name="search" placeholder="Cari mahasiswa..."
                        value="<?= htmlspecialchars($search) ?>">
                    <button>Cari</button>
                </form>
                <a href="tambah.php" class="tambah">+ Tambah Data</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Jurusan</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)):
                            ?>
                            <tr>
                                <td>
                                    <?php
                                    if ($row['foto']):
                                        ?>
                                        <img src="uploads/mahasiswa/<?= $row['foto'] ?>" class="photo">
                                    <?php else: ?>
                                        <div class="no-photo">N/A</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['nim']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['nama']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['jurusan']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['email']) ?>
                                </td>
                                <td>
                                    <a href="detaill.php?id=<?= $row['id'] ?>" class="btn" style="background:#0c0063;color:white;">Detail</a>
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning">Edit</a>
                                    <a href="hapus.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus?')"class="btn btn-danger">Hapus</a>
                                </td>
                            </tr>
                            <?php
                        endwhile;
                    else:
                        ?>
                        <tr>
                            <td colspan="6">Data belum ada</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="pagination">
                <?php
                for (
                    $i = 1;
                    $i <= $total_pages;
                    $i++
                ):
                    ?>
                    <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"
                        class="<?= ($i == $page) ? 'current' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</body>

</html>