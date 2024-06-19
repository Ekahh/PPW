<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index_login.php");
    exit();
}

include 'config.php';

// Pastikan data yang diperlukan tersedia
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['invite_id'])) {
    $invite_id = $_POST['invite_id'];

    // Hapus data dari tabel invitations berdasarkan invite_id
    $sql_delete_invite = "DELETE FROM invitations WHERE invite_id = '$invite_id'";
    if (mysqli_query($koneksi, $sql_delete_invite)) {
        $_SESSION['invite_message'] = "Invitation cancelled successfully.";
    } else {
        $_SESSION['invite_message'] = "Error cancelling invitation: " . mysqli_error($koneksi);
    }
} else {
    $_SESSION['invite_message'] = "Invalid request.";
}

// Redirect kembali ke halaman index_invite_member.php
header("Location: index_invite_member.php");
exit();
?>
