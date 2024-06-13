<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index_login.php");
    exit();
}

include 'config.php';

$user_id = $_SESSION['user_id'];

if (isset($_GET['invite_id'])) {
    $invite_id = $_GET['invite_id'];

    // Menghapus undangan
    $sql_delete_invitation = "DELETE FROM invitations WHERE invite_id = '$invite_id' AND user_id = '$user_id'";
    if (mysqli_query($koneksi, $sql_delete_invitation)) {
        header("Location: index_invitation.php?message=Invitation declined successfully.");
    } else {
        header("Location: index_invitation.php?message=Failed to decline invitation.");
    }
} else {
    header("Location: index_invitation.php?message=No invitation selected.");
}
?>
