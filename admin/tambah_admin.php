<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit();
}

include '../koneksi.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ❗ Cek apakah email sudah ada
    $check = mysqli_query($conn, "SELECT id FROM admin WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Akun sudah ada! Tidak bisa menambahkan duplikat.'); window.location='kelola_admin.php';</script>";
        exit;
    }

    // Jika belum ada baru INSERT
    mysqli_query($conn, "INSERT INTO admin (email, password, status) VALUES ('$email', '$password', 'off')");
    echo "<script>alert('Admin berhasil ditambahkan'); window.location='kelola_admin.php';</script>";
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(to right, #83a4d4, #b6fbff); padding: 30px; font-family: Arial, sans-serif;">
  <div class="container" style="background: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
    <h2>Tambah Admin Baru</h2>
    <?php if ($message): ?>
      <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Email:</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password:</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Tambah Admin</button>
      <a href="kelola_admin.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</body>
</html>
