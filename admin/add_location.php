<?php
// admin/add_location.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../config/database.php';

// Ambil data POST
$data = json_decode(file_get_contents("php://input"));

// Validasi input
if (
    !isset($data->name) || 
    !isset($data->latitude) || 
    !isset($data->longitude) || 
    !isset($data->radius)
) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit();
}

$name = $conn->real_escape_string($data->name);
$latitude = $data->latitude;
$longitude = $data->longitude;
$radius = $data->radius; // Dalam meter (misal 50 atau 100)
$is_active = 1; 

// Query Insert Lokasi Baru
$sql = "INSERT INTO locations (name, latitude, longitude, radius_meter, is_active) 
        VALUES ('$name', '$latitude', '$longitude', '$radius', '$is_active')";

if ($conn->query($sql) === TRUE) {
    echo json_encode([
        'success' => true,
        'message' => 'Lokasi kantor berhasil disimpan!'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menyimpan: ' . $conn->error
    ]);
}

$conn->close();
?>