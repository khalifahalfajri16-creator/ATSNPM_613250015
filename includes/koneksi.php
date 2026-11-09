
<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'ats_npm613250015';

// Membuat koneksi database
$conn = mysqli_connect($host, $user, $pass, $db);

// Memeriksa koneksi
if (!$conn) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}

// Mengatur karakter database
mysqli_set_charset($conn, 'utf8mb4');
