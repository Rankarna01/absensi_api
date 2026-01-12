<?php
// admin/add_employee.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->nip) || !isset($data->name) || !isset($data->password)) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit();
}

$nip = $data->nip;
$name = $data->name;
// Hash Password biar aman (Wajib!)
$password = password_hash($data->password, PASSWORD_DEFAULT);
$position = $data->position ?? 'Staff';
$department = $data->department ?? 'Umum';
$role = 'user';

// Cek apakah NIP sudah ada
$check = $conn->query("SELECT id FROM users WHERE nip = '$nip'");
if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'NIP sudah terdaftar!']);
    exit();
}

$sql = "INSERT INTO users (nip, name, password, role, position, department) 
        VALUES ('$nip', '$name', '$password', '$role', '$position', '$department')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Pegawai berhasil ditambahkan']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal: ' . $conn->error]);
}
?>