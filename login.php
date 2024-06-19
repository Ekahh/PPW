<?php
include 'config.php'; // Mengimpor file konfigurasi database

session_start(); // Memulai sesi untuk menyimpan data pengguna yang masuk

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Memeriksa apakah permintaan adalah metode POST

    // Mengambil data yang dikirim melalui formulir login
    $nim_email = $_POST['nim_email'];
    $password = $_POST['password'];
    
    // Memeriksa apakah input adalah email atau NIM
    if (filter_var($nim_email, FILTER_VALIDATE_EMAIL)) {
        // Input adalah email
        $sql = "SELECT * FROM users WHERE email = '$nim_email'";
    } else {
        // Input adalah NIM
        $sql = "SELECT * FROM users WHERE nim_nid = '$nim_email'";
    }

    $query = mysqli_query($koneksi, $sql);

    // Memeriksa apakah data pengguna ditemukan
    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $hashed_password = $row['password'];
        $user_id = $row['user_id'];
        $nama = $row['nama'];
        $nim_nid = $row['nim_nid'];
        $tahun = $row['tahun_angkatan'];
        $peran = $row['peran'];
        
        // Memeriksa apakah password sesuai
        if ($password == $hashed_password) {
            // Jika autentikasi berhasil, menyimpan informasi pengguna ke dalam sesi
            $_SESSION['user_id'] = $user_id;
            $_SESSION['nama'] = $nama;
            $_SESSION['nim_nid'] = $nim_nid;
            $_SESSION['tahun_angkatan'] = $tahun;
            $_SESSION['peran'] = $peran;
            
            // Mengarahkan pengguna ke halaman dashboard sesuai peran
            if ($peran == 'admin') {
                header("Location: dashboard_admin.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        } else {
            $_SESSION['message'] = "Password Tidak Sesuai"; // Menyimpan pesan kesalahan ke dalam sesi jika password tidak sesuai
            header("Location: index_login.php");
        }
    } else {
        $_SESSION['message'] = "User Tidak Ditemukan"; // Menyimpan pesan kesalahan ke dalam sesi jika email atau NIM tidak ditemukan
        header("Location: index_login.php");
    }
} else {
    header("Location: index_login.php"); // Mengarahkan kembali ke halaman login jika tidak ada permintaan POST
}

// Menutup koneksi database
mysqli_close($koneksi);
?>
