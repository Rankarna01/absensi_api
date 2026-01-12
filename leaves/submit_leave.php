
<?php
// leaves/submit_leave.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../config/database.php';

$user_id = $_POST['user_id'];
$type = $_POST['type']; // sakit, izin, cuti
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$reason = $_POST['reason'];

// Upload Bukti (Opsional)
$attachment_url = null;
if (isset($_FILES['attachment']['name'])) {
    $target_dir = "../uploads/leaves/";
    if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
    
    $filename = "leave_" . $user_id . "_" . time() . ".jpg";
    $target_file = $target_dir . $filename;
    
    if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)) {
        $attachment_url = "uploads/leaves/" . $filename;
    }
}

$sql = "INSERT INTO leaves (user_id, type, start_date, end_date, reason, attachment_url) 
        VALUES ('$user_id', '$type', '$start_date', '$end_date', '$reason', '$attachment_url')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Pengajuan berhasil dikirim']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
}
?>