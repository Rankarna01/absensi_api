<?php
// admin/update_leave_status.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->leave_id) || !isset($data->status)) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit();
}

$leave_id = $data->leave_id;
$status = $data->status; // 'approved' atau 'rejected'

$sql = "UPDATE leaves SET status = '$status' WHERE id = '$leave_id'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Status berhasil diubah']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
}
?>