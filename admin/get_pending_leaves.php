<?php
// admin/get_pending_leaves.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

// Ambil data izin status 'pending', join dengan tabel users untuk info nama
$sql = "SELECT l.*, u.name, u.nip, u.position 
        FROM leaves l 
        JOIN users u ON l.user_id = u.id 
        WHERE l.status = 'pending' 
        ORDER BY l.created_at ASC";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(['success' => true, 'data' => $data]);
?>