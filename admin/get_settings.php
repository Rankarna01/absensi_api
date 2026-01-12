<?php
// admin/get_settings.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

// Ambil baris pertama (id=1)
$sql = "SELECT * FROM settings WHERE id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo json_encode(['success' => true, 'data' => $result->fetch_assoc()]);
} else {
    // Default fallback jika tabel kosong
    echo json_encode(['success' => true, 'data' => [
        'office_start_time' => '08:00:00',
        'office_end_time' => '17:00:00',
        'late_tolerance_minutes' => 15
    ]]);
}
?>