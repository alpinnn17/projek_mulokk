<?php
session_start();
include '../koneksi.php';

// Cek login
if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admin.php');
    exit();
}

// Tambah kategori
if (isset($_POST['tambah'])) {
    $nama   = trim($_POST['nama_budaya']);
    $jenis  = trim($_POST['jenis_budaya']);
    $link   = trim($_POST['link_youtube']);
    $gambar = trim($_POST['gambar']);

    $sql = "INSERT INTO kategori (nama_budaya, jenis_budaya, link_youtube, gambar, waktu) VALUES (?,?,?,?,NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nama, $jenis, $link, $gambar);
    $stmt->execute();

    header("Location: kelola_kategori.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Kategori Budaya</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #ece9e6, #ffffff);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    padding: 40px 30px;
    width: 450px;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.25);
}

.card h2 {
    margin-bottom: 30px;
    color: #333;
    font-size: 1.8rem;
    letter-spacing: 1px;
}

input[type="text"] {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

input[type="text"]:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 8px rgba(59,130,246,0.3);
    outline: none;
}

button.btn-add {
    width: 100%;
    padding: 12px;
    background: linear-gradient(45deg, #3b82f6, #2563eb);
    border: none;
    border-radius: 10px;
    color: #fff;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(59,130,246,0.3);
}

button.btn-add:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(59,130,246,0.5);
}

a.btn-back {
    display: inline-block;
    margin-top: 20px;
    text-decoration: none;
    color: #3b82f6;
    font-weight: 500;
    transition: color 0.3s ease;
}

a.btn-back:hover {
    color: #1e40af;
}

</style>
</head>
<body>

<div class="card">
    <h2>Tambah Kategori Budaya</h2>
    <form method="POST">
        <input type="text" name="nama_budaya" placeholder="Nama Budaya" required>
        <input type="text" name="jenis_budaya" placeholder="Jenis Budaya" required>
        <input type="text" name="link_youtube" placeholder="Link YouTube (isi jika lagu/upacara)">
        <input type="text" name="gambar" placeholder="Link Gambar (isi jika bukan lagu/upacara)">
        <button type="submit" name="tambah" class="btn-add">➕ Tambah Kategori</button>
    </form>
    <a href="kelola_kategori.php" class="btn-back">⬅ Kembali ke Daftar Kategori</a>
</div>

</body>
</html>
