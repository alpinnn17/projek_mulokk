<?php
session_start();
include '../koneksi.php';

// Cek login
if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admin.php');
    exit();
}

// Cek apakah ada parameter id
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // pastikan id berupa angka

    // Query hapus kategori
    $sql = "DELETE FROM kategori WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Redirect kembali ke halaman kelola kategori
header("Location: kelola_kategori.php");
exit();
?>
