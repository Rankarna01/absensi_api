<?php
// admin/update_settings.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../config/database.php';

$data = json_decode(file_get_contents("php://input"));

$start = $data->office_start_time;
$end = $data->office_end_time;
$tolerance = $data->late_tolerance_minutes;

// Update baris id=1
$sql = "UPDATE settings SET 
        office_start_time = '$start', 
        office_end_time = '$end', 
        late_tolerance_minutes = '$tolerance' 
        WHERE id = 1";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Jam kerja diperbarui']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
}
?>