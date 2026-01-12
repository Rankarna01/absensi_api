<?php
// attendance/get_history.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

if (!isset($_GET['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User ID Missing']);
    exit();
}

$user_id = $_GET['user_id'];

// Query: Ambil data absensi user ini, urutkan dari yang terbaru
// Kita akan melakukan GROUP BY per tanggal agar IN dan OUT menyatu dalam satu baris (opsional logic)
// TAPI, untuk pemula, lebih mudah kita tampilkan raw data dulu atau kita olah sedikit.

// Cara simpel: Tampilkan list aktivitas absensi (Log Activity)
$sql = "SELECT * FROM attendance WHERE user_id = '$user_id' ORDER BY timestamp DESC LIMIT 30";
$result = $conn->query($sql);

$history = [];

while ($row = $result->fetch_assoc()) {
    $history[] = [
        'id' => $row['id'],
        'type' => $row['type'], // IN atau OUT
        'date' => date('d-m-Y', strtotime($row['timestamp'])),
        'time' => date('H:i', strtotime($row['timestamp'])),
        'status' => $row['status'], // ontime/late
        'photo_url' => $row['photo_url']
    ];
}

echo json_encode([
    'success' => true,
    'data' => $history
]);
?>