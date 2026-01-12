<?php
// auth/login.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

// Ambil data JSON yang dikirim dari Flutter
$data = json_decode(file_get_contents("php://input"));

// Validasi input
if (!isset($data->nip) || !isset($data->password)) {
    echo json_encode([
        'success' => false,
        'message' => 'NIP dan Password wajib diisi.'
    ]);
    exit();
}

$nip = $conn->real_escape_string($data->nip);
$password = $data->password;

// Query cari user berdasarkan NIP
$sql = "SELECT id, nip, name, password, role, position, image_url, department FROM users WHERE nip = '$nip' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verifikasi Password
    // PENTING: Di database, password harus sudah di-hash pakai password_hash()
    // Jika data dummy kamu passwordnya masih teks biasa (belum di-hash), 
    // ubah baris di bawah ini menjadi: if ($password == $user['password']) { ... }
    // Tapi untuk produksi wajib pakai password_verify
    
    if (password_verify($password, $user['password'])) {
        
        // Hapus password dari object user sebelum dikirim ke Flutter agar aman
        unset($user['password']);

        echo json_encode([
            'success' => true,
            'message' => 'Login berhasil!',
            'data'    => $user
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Password salah.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'NIP tidak ditemukan.'
    ]);
}

$conn->close();
?>