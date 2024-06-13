<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: logout.php");
    exit();
}

include 'config.php';

$nama = $_SESSION['nama'];
$nim_nid = $_SESSION['nim_nid'];
$user_id = $_SESSION['user_id'];
$tahun = $_SESSION['tahun_angkatan'];
$peran = $_SESSION['peran'];

// Memeriksa apakah team_id dan member_id ada di URL
if (!isset($_GET['team_id']) || !isset($_GET['member_id'])) {
    echo "Team ID or Member ID is not specified.";
    exit();
}

$team_id = $_GET['team_id'];
$member_id = $_GET['member_id'];

// Mendapatkan detail tim
$sql_team = "SELECT * FROM teams WHERE team_id = '$team_id'";
$result_team = mysqli_query($koneksi, $sql_team);

if (mysqli_num_rows($result_team) == 0) {
    echo "Team not found.";
    exit();
}

$team = mysqli_fetch_assoc($result_team);

// Hanya pemimpin tim yang bisa menghapus anggota
if ($_SESSION['user_id'] != $team['leader_id']) {
    echo "You do not have permission to delete members.";
    exit();
}

// Menghapus anggota dari tim
$sql_delete_member = "DELETE FROM members WHERE team_id = '$team_id' AND user_id = '$member_id'";
if (mysqli_query($koneksi, $sql_delete_member)) {
    // Update nilai member_now di tabel teams
    $sql_update_team = "UPDATE teams SET member_now = member_now - 1 WHERE team_id = '$team_id'";
    if (mysqli_query($koneksi, $sql_update_team)) {
        echo "Member has been successfully deleted and team member count has been updated.";
    } else {
        echo "Error updating team member count: " . mysqli_error($koneksi);
    }
} else {
    echo "Error deleting member: " . mysqli_error($koneksi);
}

// Menutup koneksi database
mysqli_close($koneksi);

header("Location: dashboard.php");
exit();
?>
