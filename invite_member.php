<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index_login.php");
    exit();
}

include 'config.php';

// Periksa apakah tombol invite telah dikirimkan
if (isset($_POST['invite'])) {
    // Periksa apakah user_id dan team_id telah diterima dari form
    if (isset($_POST['user_id']) && isset($_POST['team_id'])) {
        // Ambil data dari form
        $user_id = $_POST['user_id'];
        $team_id = $_POST['team_id'];

        // Query untuk memeriksa apakah user sudah diundang sebelumnya
        $sql_check_invitation = "SELECT * FROM invitations WHERE user_id = '$user_id' AND team_id = '$team_id'";
        $result_check_invitation = mysqli_query($koneksi, $sql_check_invitation);

        if (mysqli_num_rows($result_check_invitation) > 0) {
            $_SESSION['invite_message'] = "User telah diundang sebelumnya.";
        } else {
            // Query untuk memasukkan data ke dalam tabel invitations
            $sql_invite = "INSERT INTO invitations (team_id, user_id) VALUES ('$team_id', '$user_id')";

            if (mysqli_query($koneksi, $sql_invite)) {
                $_SESSION['invite_message'] = "User berhasil diundang.";
            } else {
                echo "Error: " . $sql_invite . "<br>" . mysqli_error($koneksi);
            }
        }
    } else {
        $_SESSION['invite_message'] = "User ID atau Team ID tidak ditemukan.";
    }

    // Redirect kembali ke index_invite_member.php
    header("Location: index_invite_member.php");
    exit();
}
?>
