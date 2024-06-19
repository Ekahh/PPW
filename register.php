<?php
// Memulai sesi
session_start();
// Mengimpor file konfigurasi database
include 'config.php';

// Mengambil data yang dikirim melalui formulir registrasi
$nama = $_POST['nama'];
$nim_nid = $_POST['nim_nid'];
$tahun_angkatan = $_POST['tahun_angkatan'];
$email = $_POST['email'];
$peran = $_POST['peran'];
$password = $_POST['password']; 

// Validasi domain email
if ((!strpos($email, '@mahasiswa.upnvj.ac.id'))) {
    $_SESSION['message'] = "Email tidak valid untuk peran yang dipilih";
    header("location: index_register.php");
    exit();
}

// Memeriksa apakah data yang dibutuhkan telah diisi
if ($nama != '' && $nim_nid != '' && $tahun_angkatan != '' && $email != '' && $password != '') {
    // Membuat query SQL untuk memeriksa apakah ada pengguna dengan NIM atau NID yang sama
    $sql = "SELECT * FROM users WHERE nim_nid='$nim_nid'";
    $query = mysqli_query($koneksi, $sql);

    if (mysqli_num_rows($query) > 0) {
        $_SESSION['message'] = "NIM atau NID sudah digunakan";
        header("location: index_register.php");
        exit();
    } else {
        // Jika tidak ada pengguna dengan NIM atau NID yang sama, tambahkan pengguna ke dalam tabel users
        $sql = "INSERT INTO users (nama, nim_nid, tahun_angkatan, email, peran, password) VALUES ('$nama', '$nim_nid', '$tahun_angkatan', '$email', 'mahasiswa', '$password')";
        $query = mysqli_query($koneksi, $sql);
        
        // Memeriksa apakah pengguna berhasil ditambahkan
        if ($query) {
            // Jika berhasil, set session pengguna dan arahkan ke halaman login
            $_SESSION['nama'] = $nama;
            header("location: index_login.php");
            exit(); 
        } else {
            // Jika gagal menambahkan pengguna, set pesan session dan arahkan kembali ke halaman registrasi
            $_SESSION['message'] = "Maaf, akun anda gagal dibuat";
            header("location: index_register.php");
            exit();
        }
    }
} else {
    // Jika data yang dibutuhkan tidak diisi, set pesan session dan arahkan kembali ke halaman registrasi
    $_SESSION['message'] = "Maaf, semua data harus diisi";
    header("location: index_register.php");
    exit();
}
?>
