<?php
include '../koneksi.php';
$result = mysqli_query($conn, "SELECT * FROM kategori");

// fungsi ambil ID dari link youtube
function getYoutubeID($url) {
    preg_match('/(?:v=|\/)([0-9A-Za-z_-]{11})/', $url, $matches);
    return $matches[1] ?? null;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>📂 Daftar Data Budaya</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .card {
            background: #fff;
            width: 280px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            text-align: center;
            transition: 0.3s;
        }
        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        .card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
        }
        .card h3 {
            margin: 10px 0 5px;
            font-size: 1.3rem;
            color: #333;
        }
        .card small {
            display: block;
            color: #777;
            margin-bottom: 5px;
        }
        .card .btn-youtube {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 20px;
            background: #ff0000;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }
        .card .btn-youtube:hover {
            background: #cc0000;
        }
        .btn-edit, .btn-delete {
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin: 5px;
            font-size: 0.9rem;
            text-decoration: none;
            color: #fff;
            display: inline-block;
        }
        .btn-edit {
            background: #f1c40f;
            color: #000;
        }
        .btn-delete {
            background: #e74c3c;
        }
        .btn-delete:hover {
            background: #c0392b;
        }
        .top-link {
            display: inline-block;
            margin-bottom: 25px;
            background: #28a745;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }
        .top-link:hover {
            background: #218838;
        }
    </style>
</head>
<body>

    <h2>📂 Daftar Data Budaya</h2>
    <a class="top-link" href="tambah_kategori.php">➕ Tambah Budaya Baru</a>

    <div class="container">
        <?php while ($row = mysqli_fetch_assoc($result)) : 
            $ytID = getYoutubeID($row['link_youtube']);
            $thumbnail = $ytID ? "https://img.youtube.com/vi/$ytID/hqdefault.jpg" : $row['gambar'];
        ?>
        <div class="card">
            <?php if ($thumbnail): ?>
                <img src="<?= $thumbnail ?>" alt="<?= $row['nama_budaya'] ?>">
            <?php endif; ?>

            <h3><?= $row['nama_budaya'] ?></h3>
            <small><em><?= $row['jenis_budaya'] ?></em></small>
            <small><?= $row['waktu'] ?></small>

            <?php if ($ytID): ?>
                <a class="btn-youtube" href="<?= $row['link_youtube'] ?>" target="_blank">🎵 Lihat di YouTube</a>
            <?php endif; ?>

            <div>
                <a href="edit_kategori.php?id=<?= $row['id'] ?>" class="btn-edit">✏️ Edit</a>
                <a href="hapus_kategori.php?id=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus?')">🗑️ Hapus</a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

</body>
</html>