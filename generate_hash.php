<?php
// generate_hash.php

// 1. Masukkan password yang ingin kamu buat di sini
$password_to_hash = "123456"; 

// 2. Generate Hash
$hashed_password = password_hash($password_to_hash, PASSWORD_DEFAULT);

// 3. Tampilkan Hasil
echo "<h3>Password Generator</h3>";
echo "Password Asli: <b>" . $password_to_hash . "</b><br><br>";
echo "Copy kode Hash di bawah ini dan masukkan ke kolom 'password' di tabel 'users':<br>";
echo "<textarea rows='4' cols='50'>" . $hashed_password . "</textarea>";
?>