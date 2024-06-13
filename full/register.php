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
if (($peran == 'mahasiswa' && !strpos($email, '@mahasiswa.upnvj.ac.id')) || 
    ($peran == 'dosen' && !strpos($email, '@dosen.upnvj.ac.id'))) {
    setcookie("message", "Email tidak valid untuk peran yang dipilih", time() + 60);
    header("location: index_register.php"); 
    exit();
}

// Memeriksa apakah data yang dibutuhkan telah diisi
if ($nama != '' && $nim_nid != '' && $tahun_angkatan != '' && $email != '' && $peran != '' && $password != '') {
    // Membuat query SQL untuk memeriksa apakah ada pengguna dengan nama yang sama
    $sql = "SELECT * FROM users WHERE nama='$nama'";
    $query = mysqli_query($koneksi, $sql);

    // Memeriksa apakah ada pengguna dengan nama yang sama
    if (mysqli_num_rows($query) > 0) {
        // Jika ada pengguna dengan nama yang sama, set pesan cookie dan arahkan kembali ke halaman registrasi
        setcookie("message", "Maaf, nama pengguna tidak boleh sama dengan yang lain", time() + 60);
        header("location: index_register.php"); 
    } else {
        // Jika tidak ada pengguna dengan nama yang sama, tambahkan pengguna ke dalam tabel users
        $sql = "INSERT INTO users (nama, nim_nid, tahun_angkatan, email, peran, password) VALUES ('$nama', '$nim_nid', '$tahun_angkatan', '$email', '$peran', '$password')";
        $query = mysqli_query($koneksi, $sql);
        
        // Memeriksa apakah pengguna berhasil ditambahkan
        if ($query) {
            // Jika berhasil, set session pengguna dan arahkan ke halaman login
            $_SESSION['nama'] = $nama;
            header("location: index_login.php");
            exit(); 
        } else {
            // Jika gagal menambahkan pengguna, set pesan cookie dan arahkan kembali ke halaman registrasi
            setcookie("message", "Maaf, akun anda gagal dibuat", time() + 60);
            header("location: index_register.php"); 
        }
    }
} else {
    // Jika data yang dibutuhkan tidak diisi, set pesan cookie dan arahkan kembali ke halaman registrasi
    setcookie("message", "Maaf, semua data harus diisi", time() + 60);
    header("location: index_register.php"); 
}
?>
