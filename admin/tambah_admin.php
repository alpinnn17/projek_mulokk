<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit();
}

include '../koneksi.php';

// Pesan feedback
$message = "";
$error = "";

// Handle tambah admin
if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Validasi sederhana
    if (empty($email) || empty($password)) {
        $error = "Email dan password harus diisi.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } else {
        // Cek apakah email sudah ada
        $check_email = mysqli_query($conn, "SELECT id FROM admin WHERE email = '$email'");
        if (mysqli_num_rows($check_email) > 0) {
            $error = "Email sudah terdaftar.";
        } else {
            // Insert admin baru (password plain text seperti di file asli, tapi sebaiknya di-hash di produksi)
            $query = "INSERT INTO admin (email, password, status) VALUES ('$email', '$password', 'on')";
            if (mysqli_query($conn, $query)) {
                $message = "Admin berhasil ditambahkan.";
                // Redirect setelah 2 detik atau langsung
                header("Location: kelola_admin.php?success=1");
                exit();
            } else {
                $error = "Gagal menambahkan admin: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(to right, #83a4d4, #b6fbff);
      padding: 30px;
      font-family: Arial, sans-serif;
    }
    .form-container {
      background: #ffffff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      max-width: 500px;
      margin: 0 auto;
    }
    .sidebar {
      position: fixed;
      top: 0;
      left: -260px;
      width: 260px;
      height: 100%;
      background: linear-gradient(180deg, #1f2937, #111827);
      color: #f9fafb;
      box-shadow: 4px 0 15px rgba(0, 0, 0, 0.4);
      transition: all 0.3s ease;
      z-index: 1000;
    }

    .sidebar.active { left: 0; }

    .sidebar h2 {
      text-align: center;
      padding: 1.4rem;
      background: rgba(255, 255, 255, 0.05);
      font-size: 1.6rem;
      font-weight: 600;
      margin: 0;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .sidebar ul { list-style: none; padding: 0; margin-top: 1.8rem; }
    .sidebar ul li { margin: 10px 15px; }

    .sidebar ul li a {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 18px;
      text-decoration: none;
      color: #d1d5db;
      border-radius: 10px;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .sidebar ul li a i {
      font-size: 1.3rem;
      width: 25px;
      text-align: center;
      color: #9ca3af;
      transition: color 0.3s;
    }

    .sidebar ul li a:hover {
      background: linear-gradient(90deg, #3b82f6, #2563eb);
      color: #fff;
      transform: translateX(6px);
      box-shadow: 0 4px 14px rgba(59, 130, 246, 0.4);
    }

    .sidebar ul li a:hover i { color: #f9fafb; }
    .menu-toggle {
      position: fixed;
      top: 18px;
      left: 20px;
      font-size: 28px;
      color: #1f2937;
      background: #fff;
      padding: 10px;
      border-radius: 10px;
      cursor: pointer;
      box-shadow: 0 3px 15px rgba(0,0,0,0.2);
      z-index: 1100;
      transition: all 0.3s ease;
    }

    .menu-toggle:hover {
      background: #3b82f6;
      color: #fff;
      transform: rotate(90deg);
    }
  </style>
</head>

<body>
  <div class="menu-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </div>

  <div class="sidebar" id="sidebar">
    <h2>Menu</h2>
    <ul>
      <li><a href="Dashboard_admin.php"><i class="fas fa-home"></i> Beranda</a></li>
      <li><a href="kelola_admin.php"><i class="fas fa-user-shield"></i> Kelola Admin</a></li>
      <li><a href="kelola_kategori.php"><i class="fas fa-list"></i> Kelola Kategori</a></li>
      <li><a href="Dashboard_admin.php?logout=true"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </div>

  <div class="container">
    <div class="form-container">
      <h2 class="text-center mb-4">Tambah Admin Baru</h2>
      
      <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
      <?php endif; ?>
      
      <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label for="email" class="form-label">Email Admin</label>
          <input type="email" class="form-control" id="email" name="email" required 
                 placeholder="Masukkan email admin" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
        </div>
        
        <div class="mb-3">
          <label for="password" class="form-label">Password Admin</label>
          <input type="password" class="form-control" id="password" name="password" required 
                 placeholder="Masukkan password (minimal 6 karakter)">
        </div>
        
        <div class="d-grid gap-2">
          <button type="submit" name="submit" class="btn btn-primary">Tambah</button>
          <a href="kelola_admin.php" class="btn btn-secondary">Kembali</a>
        </div>
      </form>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("active");
    }
  </script>
</body>
</html>
