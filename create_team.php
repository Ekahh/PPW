<?php
// Memulai sesi
session_start();

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index_login.php");
    exit();
}

// Mengimpor file konfigurasi database
include 'config.php';

// Memeriksa apakah permintaan adalah metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari formulir pembuatan tim
    $team_name = $_POST['team_name'];
    $description = $_POST['desc']; // Mengubah nama kolom dari 'desc' menjadi 'description'
    $pkm_type = $_POST['pkm_type'];
    $leader_id = $_SESSION['user_id'];
    $member_max = $_POST['member_max']; // Menambahkan pengambilan nilai member_max

    // Memeriksa apakah pengguna sudah memiliki tim
    $check_sql = "SELECT * FROM teams WHERE leader_id = '$leader_id'";
    $check_query = mysqli_query($koneksi, $check_sql);

    if (mysqli_num_rows($check_query) > 0) {
        echo "You already have a team. You cannot create more than one team.";
    } else {
        // Memeriksa apakah data sudah diisi
        if ($team_name != '' && $description != '' && $pkm_type != '' && $member_max != '') {
            // Membuat query untuk memasukkan data tim baru
            $sql = "INSERT INTO teams (team_name, description, leader_id, pkm_type, member_max) VALUES ('$team_name', '$description', '$leader_id', '$pkm_type', '$member_max')";
            $query = mysqli_query($koneksi, $sql);

            // Memeriksa apakah query berhasil dijalankan
            if ($query) {
                // Mendapatkan ID tim yang baru saja dimasukkan
                $team_id = mysqli_insert_id($koneksi);

                // Menambahkan data ke tabel members sebagai ketua
                $add_member_sql = "INSERT INTO members (team_id, user_id, role) VALUES ('$team_id', '$leader_id', 'ketua')";
                $add_member_query = mysqli_query($koneksi, $add_member_sql);

                if ($add_member_query) {
                    echo "Team successfully created and you have been added as the leader.";
                    // Mengarahkan ke halaman lain jika diperlukan
                    header("Location: dashboard.php");
                    exit();
                } else {
                    echo "Team created, but failed to add you as the leader. Error: " . mysqli_error($koneksi);
                }
            } else {
                echo "Failed to create team. Error: " . mysqli_error($koneksi);
            }
        } else {
            echo "All fields are required.";
        }
    }
}

// Menutup koneksi ke database
mysqli_close($koneksi);
?>
