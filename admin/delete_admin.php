<?php
session_start();
include '../koneksi.php';

// Cek login
if (!isset($_SESSION['admin_id'])) {
    header('HTTP/1.1 403 Forbidden');
    echo "Akses ditolak.";
    exit();
}

// Hapus admin jika ada parameter POST
if (isset($_POST['delete_admin']) && isset($_POST['admin_id'])) {
    $admin_id = intval($_POST['admin_id']);

    // Hapus admin
    $stmt = $conn->prepare("DELETE FROM admin WHERE id = ?");
    $stmt->bind_param("i", $admin_id);

    if ($stmt->execute()) {
        echo "berhasil";
    } else {
        echo "gagal";
    }
    $stmt->close();
    exit();
}

// Jika tidak ada parameter
echo "tidak ada aksi";
?>
