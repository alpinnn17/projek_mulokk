<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      display: flex;
      min-height: 100vh;
      background: #f5f6fa;
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      background: linear-gradient(135deg, #4f46e5, #9333ea);
      color: #fff;
      display: flex;
      flex-direction: column;
      padding: 20px;
      position: fixed;
      height: 100%;
      box-shadow: 4px 0 15px rgba(0,0,0,0.1);
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 20px;
    }

    .sidebar a {
      color: #fff;
      text-decoration: none;
      padding: 12px 15px;
      border-radius: 10px;
      display: block;
      margin: 5px 0;
      transition: 0.3s;
    }

    .sidebar a:hover, .sidebar a.active {
      background: rgba(255, 255, 255, 0.2);
      transform: translateX(5px);
    }

    /* Main Content */
    .main-content {
      margin-left: 250px;
      padding: 30px;
      flex: 1;
    }

    .header {
      background: #fff;
      padding: 15px 20px;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      margin-bottom: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .header h1 {
      font-size: 22px;
      color: #333;
    }

    .header a {
      text-decoration: none;
      color: #f44336;
      font-weight: bold;
    }

    .card {
      background: #fff;
      padding: 25px;
      border-radius: 16px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
      margin-bottom: 20px;
    }

    .card h2 {
      margin-bottom: 15px;
      font-size: 18px;
      color: #4f46e5;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="Dashboard_admin.php" class="active">🏠 Home</a>
    <a href="kelola_kategori.php">📂 Kelola Kategori</a>
    <a href="kelola_admin.php">👨‍💼 Kelola Admin</a>
    <a href="logout_admin.php">🚪 Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="header">
      <h1>Selamat Datang, Admin</h1>
      <a href="logout_admin.php">Logout</a>
    </div>

    <div class="card">
      <h2>📊 Ringkasan</h2>
      <p>Ini adalah halaman Home dashboard admin.  
      Silakan pilih menu di sidebar untuk mengelola kategori atau admin.</p>
    </div>

    <div class="card">
      <h2>🔔 Informasi</h2>
      <p>Tampilan ini masih sederhana. Anda bisa menambahkan grafik, statistik, atau notifikasi di sini.</p>
    </div>
  </div>
</body>
</html>
