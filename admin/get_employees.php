<?php
// admin/get_employees.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

// Ambil semua user yang role-nya BUKAN admin
$sql = "SELECT id, nip, name, position, department FROM users WHERE role = 'user' ORDER BY created_at DESC";
$result = $conn->query($sql);

$employees = [];
while ($row = $result->fetch_assoc()) {
    $employees[] = $row;
}

echo json_encode(['success' => true, 'data' => $employees]);
?>