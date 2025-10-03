<?php
session_start();
include '../koneksi.php';

// Cek login
if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admin.php');
    exit();
}

// Ambil data kategori
$result = mysqli_query($conn, "SELECT * FROM kategori ORDER BY id DESC");
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Kelola Kategori</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
<style>
  body{margin:0;font-family:'Segoe UI',sans-serif;background:#f4f6f9;}
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
      position: fixed; top: 15px; left: 15px; font-size: 24px;
      color: #73945bff; cursor: pointer; z-index: 1100;
    }

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

/* ===== CARD ===== */
.card {
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 2px 10px 10px rgba(0, 0, 0, 0.1);
  margin-bottom: 1.5rem;
}

/* ===== TABLE ===== */
table {
  width: 100%;
  border-collapse: collapse;
}

th,
td {
  padding: 12px;
  text-align: center;
  border-bottom: 1px solid #ddd;
}

th {
  background: #1f2937;
  color: #fff;
}

/* ===== THUMBNAIL ===== */
.thumb {
  width: 160px;
  border-radius: 10px;
}

/* ===== BUTTONS ===== */
.btn-delete {
  background: #e74c3c;
  color: #fff;
  padding: 8px 15px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.btn-edit {
  background: #f1c40f;
  color: #000;
  padding: 8px 15px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.btn-youtube {
  display: inline-block;
  margin-top: 10px;
  padding: 8px 15px;
  background: #ff0000;
  color: #fff;
  border-radius: 5px;
  text-decoration: none;
  font-weight: bold;
  transition: background 0.3s ease;
}

.btn-youtube:hover {
  background: #cc0000;
}

/* ===== INPUT FIELD ===== */
input[type="text"] {
  padding: 8px;
  width: 100%;
  margin-bottom: 8px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

/* Tombol tambah kategori elegan */
.btn-add {
    display: inline-block;
    margin-bottom: 20px;
    padding: 12px 25px;
    background: linear-gradient(45deg, #3b82f6, #2563eb);
    color: #fff;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    box-shadow: 0 5px 15px rgba(59,130,246,0.3);
    transition: all 0.3s ease;
}

.btn-add:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(59,130,246,0.5);
    background: linear-gradient(45deg, #2563eb, #1e40af);
}

</style>
</style>
</head>
<body>

<div class="menu-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></div>

<div class="sidebar" id="sidebar">
  <h2>Menu</h2>
  <ul>
    <li><a href="Dashboard_admin.php"><i class="fas fa-home"></i> Beranda</a></li>

    <li><a href="kelola_admin.php"><i class="fas fa-user-shield"></i> Kelola Admin</a></li>
    <li><a href="kelola_kategori.php"><i class="fas fa-list"></i> Kelola Kategori</a></li>
    <li><a href="Dashboard_admin.php?logout=true"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
  </ul>
</div>

<div class="content">
  <div class="card">
    <h2>📂 Daftar Kategori Budaya</h2>

    <!-- ✅ Hanya tombol tambah -->
    <!-- Tombol Tambah Kategori -->
<a href="tambah_kategori.php" class="btn-add">Tambah Kategori</a>
    <table>
  <tr>
    <th>ID</th>
    <th>Nama Budaya</th>
    <th>Jenis</th>
    <th>Gambar / Thumbnail</th>
    <th>Video</th>
    <th>Edit</th>
    <th>Hapus</th>
  </tr>
  <?php foreach ($categories as $kategori): ?>
    <?php 
      $isVideo = in_array(strtolower($kategori['jenis_budaya']), ['lagu','upacara']);
      $videoId = '';
      if ($isVideo && preg_match('/(?:v=|be\/)([a-zA-Z0-9_-]{11})/', $kategori['link_youtube'], $match)) {
          $videoId = $match[1];
      }
    ?>
    <tr>
      <td><?= $kategori['id'] ?></td>
      <td><?= htmlspecialchars($kategori['nama_budaya']) ?></td>
      <td><?= htmlspecialchars($kategori['jenis_budaya']) ?></td>
      <td>
        <?php if ($isVideo && $videoId): ?>
          <img class="thumb" src="https://img.youtube.com/vi/<?= $videoId ?>/hqdefault.jpg" alt="Thumbnail">
        <?php elseif (!$isVideo && !empty($kategori['gambar'])): ?>
          <img class="thumb" src="<?= htmlspecialchars($kategori['gambar']) ?>" alt="Gambar Budaya">
        <?php else: ?>
          <em>Tidak ada gambar</em>
        <?php endif; ?>
      </td>
      <td>
        <?php if ($isVideo && $videoId): ?>
          <a href="https://www.youtube.com/watch?v=<?= $videoId ?>" target="_blank" class="btn-youtube">YouTube</a>
        <?php else: ?>
          <em>-</em>
        <?php endif; ?>
      </td>
      <td>
        <a href="edit_kategori.php?id=<?= $kategori['id'] ?>"><button class="btn-edit">Edit</button></a>
      </td>
      <td>
        <a href="delete_kategori.php?id=<?= $kategori['id'] ?>" onclick="return confirm('Yakin hapus?');"><button class="btn-delete">Hapus</button></a>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
  </div>
</div>

<script>
function toggleSidebar(){document.getElementById("sidebar").classList.toggle("active");}
</script>

</body>
</html>
