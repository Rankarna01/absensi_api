<?php
// admin/get_attendance_report.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

// Ambil parameter tanggal (jika tidak ada, pakai hari ini)
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Query: Join users & attendance, filter by date, order by time descending
$sql = "SELECT a.id, a.type, a.timestamp, a.photo_url, a.status, 
               u.name as user_name, u.nip 
        FROM attendance a
        JOIN users u ON a.user_id = u.id 
        WHERE DATE(a.timestamp) = '$date' 
        ORDER BY a.timestamp DESC";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'id' => $row['id'],
        'user_name' => $row['user_name'],
        'nip' => $row['nip'],
        'type' => $row['type'], // IN / OUT
        'time' => date('H:i', strtotime($row['timestamp'])),
        'photo_url' => $row['photo_url'],
        'status' => $row['status']
    ];
}

echo json_encode([
    'success' => true,
    'date' => $date,
    'data' => $data
]);
?>