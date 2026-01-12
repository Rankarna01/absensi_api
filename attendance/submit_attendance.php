<?php
// attendance/submit_attendance.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../config/database.php';

// Fungsi Menghitung Jarak (Haversine Formula)
function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371000; // Radius bumi dalam meter

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);
    
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
    return $earthRadius * $c; // Hasil dalam meter
}

// 1. Ambil Data POST
$user_id = $_POST['user_id'];
$type = $_POST['type']; // 'IN' atau 'OUT'
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

// 2. Ambil Lokasi Kantor (Untuk Geofencing)
// Asumsi: Kita ambil lokasi kantor pertama (atau bisa diset per user nanti)
$sql_loc = "SELECT * FROM locations WHERE is_active = 1 LIMIT 1";
$result_loc = $conn->query($sql_loc);

if ($result_loc->num_rows > 0) {
    $office = $result_loc->fetch_assoc();
    $office_lat = $office['latitude'];
    $office_lng = $office['longitude'];
    $radius_limit = $office['radius_meter']; // Misal 100 meter

    // Hitung Jarak User ke Kantor
    $distance = calculateDistance($latitude, $longitude, $office_lat, $office_lng);

    if ($distance > $radius_limit) {
        echo json_encode([
            'success' => false,
            'message' => 'Anda berada di luar jangkauan kantor! Jarak: ' . round($distance) . 'm'
        ]);
        exit();
    }
} else {
    // Jika tidak ada data kantor, kita anggap WFA (bebas lokasi) atau tolak
    // Untuk tutorial ini, kita loloskan saja jika belum set lokasi
}

// 3. Proses Upload Foto
if (isset($_FILES['photo']['name'])) {
    $target_dir = "../uploads/attendance/";
    // Nama file unik: USERID_TIPE_TIMESTAMP.jpg
    $filename = $user_id . "_" . $type . "_" . time() . ".jpg";
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        $photo_url = "uploads/attendance/" . $filename;
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal upload foto']);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Foto wajib disertakan']);
    exit();
}

// 4. Simpan ke Database
// --- LOGIKA CEK TERLAMBAT ---
$status = 'ontime';

// Hanya cek keterlambatan jika absen MASUK (IN)
if ($type == 'IN') {
    // 1. Ambil Jam Kerja dari Database
    $sql_setting = "SELECT office_start_time, late_tolerance_minutes FROM settings WHERE id = 1";
    $res_setting = $conn->query($sql_setting);
    $setting = $res_setting->fetch_assoc();

    $jam_masuk = $setting['office_start_time']; // Contoh: 08:00:00
    $toleransi = $setting['late_tolerance_minutes']; // Contoh: 15 menit

    // 2. Hitung Waktu Batas Telat
    // Konversi jam masuk ke timestamp hari ini
    $jadwal_masuk = strtotime(date('Y-m-d') . ' ' . $jam_masuk);
    // Tambah toleransi menit
    $batas_telat = $jadwal_masuk + ($toleransi * 60);

    // 3. Bandingkan dengan Waktu Sekarang
    $waktu_sekarang = time(); // Waktu server saat ini

    if ($waktu_sekarang > $batas_telat) {
        $status = 'late';
    }
}
// -----------------------------
// $sql = "INSERT INTO attendance (user_id, type, latitude, longitude, photo_url, status) VALUES ('$user_id', '$type', '$latitude', '$longitude', '$photo_url', '$status')";
// Gunakan Prepared Statement biar aman
$stmt = $conn->prepare("INSERT INTO attendance (user_id, type, latitude, longitude, photo_url, status) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isddss", $user_id, $type, $latitude, $longitude, $photo_url, $status);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Absensi ' . $type . ' Berhasil!'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Gagal simpan ke database'
    ]);
}
?>