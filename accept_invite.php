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

    // Mendapatkan data undangan
    $sql_invitation = "SELECT team_id FROM invitations WHERE invite_id = '$invite_id' AND user_id = '$user_id'";
    $result_invitation = mysqli_query($koneksi, $sql_invitation);

    if (mysqli_num_rows($result_invitation) > 0) {
        $row = mysqli_fetch_assoc($result_invitation);
        $team_id = $row['team_id'];

        // Menambahkan pengguna ke tabel member
        $sql_add_member = "INSERT INTO members (team_id, user_id) VALUES ('$team_id', '$user_id')";
        if (mysqli_query($koneksi, $sql_add_member)) {
            // Menghapus undangan setelah diterima
            $sql_delete_invitation = "DELETE FROM invitations WHERE invite_id = '$invite_id'";
            mysqli_query($koneksi, $sql_delete_invitation);

            // Mengupdate jumlah member di tabel teams
            $sql_update_team = "UPDATE teams SET member_now = member_now + 1 WHERE team_id = '$team_id'";
            mysqli_query($koneksi, $sql_update_team);

            header("Location: index_invitation.php?message=Invitation accepted successfully.");
        } else {
            header("Location: index_invitation.php?message=Failed to accept invitation.");
        }
    } else {
        header("Location: index_invitation.php?message=Invalid invitation.");
    }
} else {
    header("Location: index_invitation.php?message=No invitation selected.");
}
?>
