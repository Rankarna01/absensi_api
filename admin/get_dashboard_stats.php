<?php
// admin/get_dashboard_stats.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

$today = date('Y-m-d');

// 1. Hitung Total Karyawan (Role User)
$sql_users = "SELECT COUNT(*) as total FROM users WHERE role = 'user'";
$res_users = $conn->query($sql_users);
$row_users = $res_users->fetch_assoc();
$total_employees = $row_users['total'];

// 2. Hitung Yang Sudah Absen Masuk Hari Ini
$sql_present = "SELECT COUNT(DISTINCT user_id) as total FROM attendance 
                WHERE type = 'IN' AND DATE(timestamp) = '$today'";
$res_present = $conn->query($sql_present);
$row_present = $res_present->fetch_assoc();
$total_present = $row_present['total'];

echo json_encode([
    'success' => true,
    'data' => [
        'total_employees' => $total_employees,
        'total_present'   => $total_present,
        'total_absent'    => $total_employees - $total_present // Belum hadir
    ]
]);
?>