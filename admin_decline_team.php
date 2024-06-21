<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index_login.php");
    exit();
}

include 'config.php';

if (isset($_GET['team_id'])) {
    $team_id = $_GET['team_id'];
    $update_sql = "UPDATE teams SET status = 'Not Approved' WHERE team_id = '$team_id'";
    
    if (mysqli_query($koneksi, $update_sql)) {
        header("Location: dashboard_admin.php");
        exit();
    } else {
        echo "Failed to decline the team.";
    }
} else {
    echo "No team ID provided.";
}
?>
