<?php
include '../koneksi.php'; // koneksi database

// ================= CEK PARAMETER ID =================
if (!isset($_GET['id'])) {
    die("⚠️ ID kategori tidak ditemukan.");
}

$id = intval($_GET['id']);

// ================= AMBIL DATA =================
$query = "SELECT * FROM kategori WHERE id = $id";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) === 0) {
    die("⚠️ Data kategori tidak ditemukan.");
}
$data = mysqli_fetch_assoc($result);

// ================= PROSES UPDATE =================
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_budaya   = mysqli_real_escape_string($conn, $_POST['nama_budaya']);
    $gambar        = mysqli_real_escape_string($conn, $_POST['gambar']);
    $jenis_budaya  = mysqli_real_escape_string($conn, $_POST['jenis_budaya']);
    $link_youtube  = mysqli_real_escape_string($conn, $_POST['link_youtube']);

    $update = "UPDATE kategori 
               SET nama_budaya='$nama_budaya', 
                   gambar='$gambar', 
                   jenis_budaya='$jenis_budaya', 
                   link_youtube='$link_youtube'
               WHERE id=$id";

    if (mysqli_query($conn, $update)) {
        $message = "✅ Data berhasil diperbarui!";
        // refresh data yang sudah diperbarui
        $result = mysqli_query($conn, "SELECT * FROM kategori WHERE id=$id");
        $data = mysqli_fetch_assoc($result);
    } else {
        $message = "❌ Gagal memperbarui data: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kategori</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }
        h2 { color: #333; }
        form {
            background: #fff;
            padding: 20px;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            margin-top: 15px;
            background: #2ecc71;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background: #27ae60;
        }
        .message {
            margin-bottom: 15px;
            font-weight: bold;
            color: green;
        }
        a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #3498db;
        }
    </style>
</head>
<body>

<h2>✏️ Edit Data Kategori</h2>

<?php if ($message): ?>
    <p class="message"><?= $message; ?></p>
<?php endif; ?>

<form method="POST">
    <label>Nama Budaya:</label>
    <input type="text" name="nama_budaya" value="<?= htmlspecialchars($data['nama_budaya']); ?>" required>

    <label>URL Gambar:</label>
    <input type="text" name="gambar" value="<?= htmlspecialchars($data['gambar']); ?>" required>

    <label>Jenis Budaya:</label>
    <input type="text" name="jenis_budaya" value="<?= htmlspecialchars($data['jenis_budaya']); ?>" required>

    <label>Link YouTube (opsional):</label>
    <input type="text" name="link_youtube" value="<?= htmlspecialchars($data['link_youtube']); ?>">

    <button type="submit">💾 Simpan Perubahan</button>
</form>

<a href="kelola_kategori.php">⬅️ Kembali ke Daftar Kategori</a>

</body>
</html>
