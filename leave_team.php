<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: logout.php");
    exit();
}

include 'config.php';

$user_id = $_SESSION['user_id'];

if (!isset($_GET['team_id'])) {
    echo "Team ID is not specified.";
    exit();
}

$team_id = $_GET['team_id'];

// Menghapus user dari tim
$sql = "DELETE FROM members WHERE user_id = '$user_id' AND team_id = '$team_id'";
if (mysqli_query($koneksi, $sql)) {
    // Kurangi nilai member_now dalam tabel teams
    $sql_update_team = "UPDATE teams SET member_now = member_now - 1 WHERE team_id = '$team_id'";
    if (mysqli_query($koneksi, $sql_update_team)) {
        echo "You have successfully left the team and the team member count has been updated.";
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating team member count: " . mysqli_error($koneksi);
    }
} else {
    echo "Error: " . mysqli_error($koneksi);
}

// Menutup koneksi database
mysqli_close($koneksi);
?>
