<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit();
}

include '../koneksi.php';

// 🧠 Auto hapus admin status 'off' lebih dari 10 detik
mysqli_query($conn, "DELETE FROM admin WHERE status='off' AND deactivation_time < DATE_SUB(NOW(), INTERVAL 10 SECOND)");

// 🧠 Toggle status
if (isset($_POST['toggle_status'])) {
    $admin_id = (int) $_POST['admin_id'];

    $check = mysqli_query($conn, "SELECT status FROM admin WHERE id = $admin_id");
    if ($check && mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_assoc($check);
        $newStatus = ($row['status'] === 'on') ? 'off' : 'on';
        if ($newStatus === 'off') {
            mysqli_query($conn, "UPDATE admin SET status='off', deactivation_time=NOW() WHERE id=$admin_id");
        } else {
            mysqli_query($conn, "UPDATE admin SET status='on', deactivation_time=NULL WHERE id=$admin_id");
        }
    }
}

// 🧠 Ambil semua admin KECUALI yang sedang login
$login_id = $_SESSION['admin_id'];
$admins_result = mysqli_query($conn, "SELECT * FROM admin WHERE id != $login_id ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(to right, #83a4d4, #b6fbff);
      padding: 30px;
      font-family: Arial, sans-serif;
    }
    .table-container {
      background: #ffffff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    table thead {
      background: #007bff;
      color: white;
    }
    .sidebar {
      position: fixed;
      top: 0;
      left: -260px;
      width: 260px;
      height: 100%;
      background: linear-gradient(180deg, #1f2937, #111827);
      color: #1f2937;
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
  <div class="container">
    <h2>Kelola Admin</h2>
  </div>
    
  <div class="menu-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </div>

  <div class="sidebar" id="sidebar">
    <h2>Admin!</h2>
    <ul>
      <li><a href="Dashboard_admin.php"><i class="fas fa-home"></i> Beranda</a></li>
      <li><a href="kelola_kategori.php"><i class="fas fa-list"></i> Kelola Kategori</a></li>
      <li><a href="kelola_admin.php"><i class="fas fa-user-shield"></i> Kelola Admin</a></li>
      <li><a href="logout_admin.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </div>

  <div class="container table-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4>Data Admin</h4>
      <a href="tambah_admin.php" class="btn btn-success">+ Tambah Admin</a>
    </div>

    <table class="table table-striped table-hover text-center">
      <thead>
        <tr>
          <th>No</th>
          <th>Email</th>
          <th>Password</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1;
        if ($admins_result && mysqli_num_rows($admins_result) > 0) {
            while ($row = mysqli_fetch_assoc($admins_result)) {
                echo "<tr>
                        <td>$no</td>
                        <td>" . htmlspecialchars($row['email']) . "</td>
                        <td>" . htmlspecialchars($row['password']) . "</td>
                        <td>
                          <form method='POST' style='display:inline;'>
                            <input type='hidden' name='admin_id' value='{$row['id']}'>";
                
                if ($row['status'] === 'off') {
                    $btnClass = 'btn-success';
                    $btnText = 'Aktif';
                    $confirmMsg = 'Aktifkan akun ini?';
                } else {
                    $btnClass = 'btn-danger';
                    $btnText = 'Nonaktif';
                    $confirmMsg = 'Nonaktifkan akun ini?';
                }

                echo "<button type='submit' name='toggle_status' class='btn btn-sm $btnClass' onclick='return confirm(\"$confirmMsg\")'>$btnText</button>
                      </form>
                    </td>
                  </tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='4'>Tidak ada data admin.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("active");
    }

    // 🔁 Auto-refresh halaman setiap 5 detik agar data terupdate (hapus otomatis jalan)
    setInterval(() => {
      location.reload();
    }, 5000);
  </script>
</body>
</html>
