<?php
// attendance/get_today_status.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once '../config/database.php';

if (!isset($_GET['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User ID required']);
    exit();
}

$user_id = $_GET['user_id'];
$today = date('Y-m-d');

// Cek Absen Masuk
$sql_in = "SELECT timestamp FROM attendance WHERE user_id = '$user_id' AND type = 'IN' AND DATE(timestamp) = '$today' LIMIT 1";
$result_in = $conn->query($sql_in);
$data_in = $result_in->fetch_assoc();

// Cek Absen Pulang
$sql_out = "SELECT timestamp FROM attendance WHERE user_id = '$user_id' AND type = 'OUT' AND DATE(timestamp) = '$today' LIMIT 1";
$result_out = $conn->query($sql_out);
$data_out = $result_out->fetch_assoc();

echo json_encode([
    'success' => true,
    'data' => [
        'is_check_in' => $result_in->num_rows > 0,
        'time_in'     => $data_in ? date('H:i', strtotime($data_in['timestamp'])) : '--:--',
        'is_check_out'=> $result_out->num_rows > 0,
        'time_out'    => $data_out ? date('H:i', strtotime($data_out['timestamp'])) : '--:--',
    ]
]);
?>