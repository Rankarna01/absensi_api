<?php
// config/database.php

$host = "localhost";
$user = "root";      // Default user XAMPP
$pass = "";          // Default password XAMPP (kosong)
$db   = "db_absen_app";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    // Jika koneksi gagal, kirim respons JSON error
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Koneksi database gagal: ' . $conn->connect_error
    ]);
    exit();
}
?>