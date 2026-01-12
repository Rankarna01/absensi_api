<?php
// auth/update_profile.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../config/database.php';

$user_id = $_POST['user_id'];
$name = $_POST['name'];

// 1. Update Password (Jika diisi)
$password_query = "";
if (!empty($_POST['password'])) {
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $password_query = ", password = '$hashed_password'";
}

// 2. Update Foto Profil (Jika ada upload)
$photo_query = "";
if (isset($_FILES['image']['name'])) {
    $target_dir = "../uploads/profiles/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $filename = "profile_" . $user_id . "_" . time() . ".jpg";
    $target_file = $target_dir . $filename;
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $photo_url = "uploads/profiles/" . $filename;
        $photo_query = ", image_url = '$photo_url'";
    }
}

// 3. Eksekusi Update
$sql = "UPDATE users SET name = '$name' $password_query $photo_query WHERE id = '$user_id'";

if ($conn->query($sql) === TRUE) {
    // Ambil data user terbaru untuk update session di HP
    $result = $conn->query("SELECT * FROM users WHERE id = '$user_id'");
    $user = $result->fetch_assoc();
    unset($user['password']); // Hapus password dari response

    echo json_encode([
        'success' => true,
        'message' => 'Profil berhasil diperbarui',
        'data' => $user
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal update: ' . $conn->error]);
}
?>