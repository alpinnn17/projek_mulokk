<?php
session_start();
include '../koneksi.php';

// Cek login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit();
}


$id = $_SESSION['admin_id'];
$email = $_SESSION['admin_email'];

// Proses update profil
if (isset($_POST['update_profile'])) {
    $new_email = $_POST['email'];
    $new_password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if ($new_password) {
        $stmt = $conn->prepare("UPDATE admin SET email=?, password=? WHERE id=?");
        $stmt->bind_param("ssi", $new_email, $new_password, $id);
    } else {
        $stmt = $conn->prepare("UPDATE admin SET email=? WHERE id=?");
        $stmt->bind_param("si", $new_email, $id);
    }
    $stmt->execute();

    $_SESSION['admin_email'] = $new_email;
    header("Location: dashboard_admin.php?page=profile&success=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Admin - Kebudayaan Kalimantan</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f5f6fa;
    }

    /* === SIDEBAR === */
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

    /* === TOGGLE === */
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

    /* === CONTENT === */
    .content {
      padding: 2rem;
      margin-left: 0;
      transition: margin-left 0.3s ease;
    }

    .sidebar.active ~ .content {
      margin-left: 260px;
    }

    /* === CARD === */
    .card {
      background: #ffffff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
    }

    h1, h2 { color: #1f2937; }

    .welcome {
      font-size: 1.3rem;
      color: #374151;
      margin-bottom: 1rem;
    }

    .form-group { margin-bottom: 20px; }
    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #374151;
    }
    .form-group input {
      width: 100%;
      padding: 10px;
      border: 1.5px solid #cbd5e1;
      border-radius: 8px;
      font-size: 1rem;
      transition: border 0.3s ease;
    }
    .form-group input:focus {
      border-color: #3b82f6;
      outline: none;
    }

    button {
      background: linear-gradient(90deg, #3b82f6, #2563eb);
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-size: 1rem;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    button:hover {
      background: linear-gradient(90deg, #2563eb, #1d4ed8);
      transform: scale(1.04);
    }

    .alert {
      padding: 12px;
      background: #10b981;
      color: white;
      border-radius: 8px;
      margin-bottom: 15px;
      font-weight: 500;
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }
  </style>
</head>
<body>

  <!-- Toggle -->
  <div class="menu-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </div>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <h2>Admin Panel</h2>
    <ul>
      <li><a href="?dashboard_admin.php"><i class="fas fa-home"></i> Dashboard</a></li>
      <li><a href="?page=profile"><i class="fas fa-user"></i> Profile</a></li>
      <li><a href="kelola_kategori.php"><i class="fas fa-list"></i> Kelola Kategori</a></li>
      <li><a href="kelola_admin.php"><i class="fas fa-user-shield"></i> Kelola Admin</a></li>
      <li><a href="logout_admin.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </div>

  <!-- Konten -->
  <div class="content">
    <div class="card">
      <?php
      $page = $_GET['page'] ?? 'dashboard';

      switch($page) {
        case 'dashboard':
          echo "<h1>Selamat Datang 👋</h1>
                <p class='welcome'>Halo, <b>" . htmlspecialchars($email) . "</b>!</p>
                <p>Selamat datang di <b>Dashboard Admin Kebudayaan Kalimantan</b>. 
                Di sini kamu dapat mengelola data kategori budaya, mengatur akun admin, dan memperbarui profil. 
                Gunakan menu di samping untuk memulai pengelolaan data.</p>";
          break;

        case 'profile':
          $stmt = $conn->prepare("SELECT email, status FROM admin WHERE id=?");
          $stmt->bind_param("i", $id);
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();

          if(isset($_GET['success'])){
            echo "<div class='alert'>Profil berhasil diperbarui!</div>";
          }

          echo "<h2>Profil Admin</h2>
                <form method='post'>
                  <div class='form-group'>
                    <label>Email:</label>
                    <input type='email' name='email' value='".htmlspecialchars($row['email'])."' required>
                  </div>
                  <div class='form-group'>
                    <label>Password Baru (kosongkan jika tidak diganti):</label>
                    <input type='password' name='password'>
                  </div>
                  <div class='form-group'>
                    <label>Status:</label>
                    <input type='text' value='".htmlspecialchars($row['status'])."' readonly>
                  </div>
                  <button type='submit' name='update_profile'>Simpan Perubahan</button>
                </form>";
          break;

        case 'kategori':
          header("Location: kelola_kategori.php");
          exit();

        case 'admin':
          header("Location: kelola_admin.php");
          exit();

        default:
          echo "<h2>Halaman tidak ditemukan</h2>";
      }
      ?>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("active");
    }
  </script>

</body>
</html>
