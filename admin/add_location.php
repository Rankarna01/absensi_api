<?php
// admin/add_location.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->name) || !isset($data->latitude) || !isset($data->longitude) || !isset($data->radius)) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit();
}

$name = $conn->real_escape_string($data->name);
$latitude = (float) $data->latitude;
$longitude = (float) $data->longitude;
$radius = (int) $data->radius;
$is_active = 1;

// --- PERBAIKAN LOGIKA: RESET LOKASI DULU ---
// Hapus semua data lokasi yang ada agar tersisa 1 saja yang terbaru
$conn->query("DELETE FROM locations"); 

// Baru Insert data baru
$sql = "INSERT INTO locations (name, latitude, longitude, radius_meter, is_active) 
        VALUES ('$name', '$latitude', '$longitude', '$radius', '$is_active')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Lokasi kantor diperbarui!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
}

$conn->close();
?>