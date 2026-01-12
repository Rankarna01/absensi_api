<?php
include_once 'config/database.php';

// Ganti data ini sesuai keinginan
$nip = "12345";
$name = "Randy Karyawan";
$password = "123456"; // Password mentah
$role = "user";
$position = "Staff IT";

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (nip, name, password, role, position) VALUES ('$nip', '$name', '$hashed_password', '$role', '$position')";

if ($conn->query($sql) === TRUE) {
    echo "User berhasil dibuat. NIP: $nip, Pass: $password";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>