<?php
session_start();
if (!isset($_SESSION['admin_id'])) exit("Unauthorized");

include '../koneksi.php';

// Toggle status
if(isset($_POST['toogle_status']) && isset($_POST['admin_id'])){
    $admin_id = (int)$_POST['admin_id'];
    $query = mysqli_query($conn, "SELECT status FROM admin WHERE id=$admin_id");
    if($row = mysqli_fetch_assoc($query)){
        $newStatus = ($row['status']==='on') ? 'off' : 'on';
        mysqli_query($conn, "UPDATE admin SET status='$newStatus' WHERE id=$admin_id");
        echo $newStatus;
    }
    exit;
}

// Hapus admin
if(isset($_POST['delete_admin']) && isset($_POST['admin_id'])){
    $admin_id = (int)$_POST['admin_id'];
    mysqli_query($conn, "DELETE FROM admin WHERE id=$admin_id");
    echo "berhasil";
    exit;
}
?>
