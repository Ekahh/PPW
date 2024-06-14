<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index_login.php");
    exit();
}

include 'config.php';

// Periksa apakah tombol request join telah dikirimkan
if (isset($_GET['team_id'])) {
    // Ambil data dari URL
    $team_id = $_GET['team_id'];
    $user_id = $_SESSION['user_id'];

    // Query untuk memeriksa apakah user sudah request menjadi anggota tim ini
    $sql_check_requests = "SELECT * FROM join_requests WHERE user_id = '$user_id' AND team_id = '$team_id'";
    $result_check_requests = mysqli_query($koneksi, $sql_check_requests);

    if (mysqli_num_rows($result_check_requests) > 0) {
        $_SESSION['join_message'] = "Anda telah mengirimkan permintaan bergabung sebelumnya.";
    } else {
        // Query untuk memasukkan data ke dalam tabel request_join
        $sql_request_join = "INSERT INTO join_requests (team_id, user_id) VALUES ('$team_id', '$user_id')";

        if (mysqli_query($koneksi, $sql_request_join)) {
            $_SESSION['join_message'] = "Permintaan bergabung berhasil dikirim.";
        } else {
            echo "Error: " . $sql_request_join . "<br>" . mysqli_error($koneksi);
        }
    }

    // Redirect kembali ke halaman sebelumnya
    header("Location: index_manage_members.php?team_id=$team_id");
    exit();
} else {
    $_SESSION['req_message'] = "Team ID tidak ditemukan.";
    header("Location: index_manage_members.php");
    exit();
}
?>
