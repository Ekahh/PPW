<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: logout.php");
    exit();
}

include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $team_id = $_POST['team_id'];
    $new_title = $_POST['new_title'];
    $new_desc = $_POST['new_desc'];
    $new_pkm_type = $_POST['new_pkm_type'];

    // Lakukan validasi data di sini jika diperlukan

    // Buat string kueri SQL untuk UPDATE
    $update_sql = "UPDATE teams SET ";

    // Tambahkan bagian kueri SQL yang sesuai berdasarkan input yang diberikan
    if (!empty($new_title)) {
        $update_sql .= "team_name = '$new_title', ";
    }
    if (!empty($new_desc)) {
        $update_sql .= "description = '$new_desc', ";
    }
    if (!empty($new_pkm_type)) {
        $update_sql .= "pkm_type = '$new_pkm_type', ";
    }

    // Hapus koma terakhir dari string kueri SQL
    $update_sql = rtrim($update_sql, ", ");

    // Tambahkan bagian WHERE untuk menyatakan tim mana yang ingin diupdate
    $update_sql .= " WHERE team_id = '$team_id'";

    // Jalankan kueri SQL UPDATE
    $update_query = mysqli_query($koneksi, $update_sql);

    if ($update_query) {
        // Jika pembaruan berhasil, arahkan kembali ke halaman update dengan data yang baru
        header("Location: index_manage_members.php?team_id=$team_id");
        exit();
    } else {
        echo "Failed to update team.";
    }
}
?>
