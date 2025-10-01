<?php
session_start();
include '../koneksi.php';

// Check login
if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admin.php');
    exit();
}

$username = $_SESSION['username'] ?? 'Admin';

// ================== DELETE ==================
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $sql = "DELETE FROM kategori WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: kelola_kategori.php");
    exit();
}

// ================== UPDATE ==================
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $nama = $_POST['nama_budaya'];
    $gambar = $_POST['gambar'];
    $jenis = $_POST['jenis_budaya'];

    $sql = "UPDATE kategori SET nama_budaya=?, gambar=?, jenis_budaya=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nama, $gambar, $jenis, $id);
    $stmt->execute();
    header("Location: kelola_kategori.php");
    exit();
}

// ================== INSERT ==================
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama_budaya'];
    $gambar = $_POST['gambar'];
    $jenis = $_POST['jenis_budaya'];

    $sql = "INSERT INTO kategori (nama_budaya, gambar, jenis_budaya, waktu) VALUES (?,?,?,NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nama, $gambar, $jenis);
    $stmt->execute();
    header("Location: kelola_kategori.php");
    exit();
}

// ================== SELECT ==================
$categories = [];
$sql = "SELECT * FROM kategori ORDER BY waktu DESC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Kelola Kategori</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {margin:0;font-family:'Segoe UI',sans-serif;background:#f4f6f9;}
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
    .content{padding:2rem;margin-left:0;transition:margin-left .3s;}
    .sidebar.active ~ .content{margin-left:250px;}
    .flashcard-container{display:flex;flex-wrap:wrap;gap:1rem;margin-top:1rem;}
    .flashcard{background:#667eea;color:white;padding:1.5rem 2rem;border-radius:12px;box-shadow:0 4px 10px rgba(102,126,234,.4);flex:1 1 250px;text-align:center;transition:.3s;}
    .flashcard:hover{background:#764ba2;box-shadow:0 6px 15px rgba(118,75,162,.6);}
    .flashcard img{max-width:100%;border-radius:10px;margin-bottom:10px;}
    .flashcard p{font-weight:normal;margin:.5rem 0;}
    .flashcard small{display:block;font-size:.8rem;opacity:.9;}
    .card{background:white;padding:20px;border-radius:10px;box-shadow:2px 10px 10px rgba(0,0,0,.1);margin-bottom:1.5rem;}
    form input, form textarea{width:100%;padding:8px;margin:5px 0;border:1px solid #ccc;border-radius:5px;}
    form button{padding:8px 15px;border:none;border-radius:5px;cursor:pointer;}
    .btn-edit{background:#f1c40f;color:#000;}
    .btn-delete{background:#e74c3c;color:#fff;}
    .btn-save{background:#2ecc71;color:#fff;}
  </style>
</head>
<body>

<div class="menu-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></div>

<div class="sidebar" id="sidebar">
  <h2>Menu</h2>
  <ul>
    <li><a href="Dashboard_admin.php"><i class="fas fa-home"></i> Beranda</a></li>
    <li><a href="profil_admin.php"><i class="fas fa-user"></i> Profile</a></li>
    <li><a href="kelola_admin.php"><i class="fas fa-user-shield"></i> Kelola Admin</a></li>
    <li><a href="kelola_kategori.php"><i class="fas fa-list"></i> Kelola Kategori</a></li>
    <li><a href="Dashboard_admin.php?logout=true"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
  </ul>
</div>

<div class="content">
  <div class="card">
    <h2>Tambah Kategori</h2>
    <form method="POST">
      <input type="text" name="nama_budaya" placeholder="Nama Budaya" required>
      <input type="text" name="gambar" placeholder="URL Gambar" required>
      <input type="text" name="jenis_budaya" placeholder="Jenis Budaya" required>
      <button type="submit" name="tambah" class="btn-save">Tambah</button>
    </form>
  </div>

  <div class="card">
    <h2>Daftar Kategori</h2>
    <div class="flashcard-container">
      <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $kategori): ?>
          <div class="flashcard">
            <img src="<?php echo htmlspecialchars($kategori['gambar']); ?>" alt="<?php echo htmlspecialchars($kategori['nama_budaya']); ?>">
            <h3><?php echo htmlspecialchars($kategori['nama_budaya']); ?></h3>
            <small><i><?php echo htmlspecialchars($kategori['jenis_budaya']); ?></i></small>
            <small><?php echo htmlspecialchars($kategori['waktu']); ?></small>

            <!-- Form Edit Inline -->
            <details style="margin-top:10px;text-align:left;">
              <summary style="cursor:pointer;color:#ffd700;">✏ Edit</summary>
              <form method="POST">
                <input type="hidden" name="id" value="<?php echo $kategori['id']; ?>">
                <input type="text" name="nama_budaya" value="<?php echo htmlspecialchars($kategori['nama_budaya']); ?>" required>
                <input type="text" name="gambar" value="<?php echo htmlspecialchars($kategori['gambar']); ?>" required>
                <input type="text" name="jenis_budaya" value="<?php echo htmlspecialchars($kategori['jenis_budaya']); ?>" required>
                <button type="submit" name="update" class="btn-edit">Simpan</button>
              </form>
            </details>

            <!-- Tombol Hapus -->
            <a href="kelola_kategori.php?hapus=<?php echo $kategori['id']; ?>" 
               onclick="return confirm('Yakin ingin menghapus kategori ini?');">
               <button class="btn-delete">Hapus</button>
            </a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Tidak ada kategori yang tersedia.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
function toggleSidebar() {
  document.getElementById("sidebar").classList.toggle("active");
}
</script>
</body>
</html>