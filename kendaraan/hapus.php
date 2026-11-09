<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit;
}
require_once '../includes/koneksi.php';

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    mysqli_query($conn, "DELETE FROM mobil015 WHERE no_id = $id");
}

header('Location: index.php?pesan=hapus');
exit;
