<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: logout.php");
    exit();
}

include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    // Mengambil team_id dari formulir yang dikirimkan
    $team_id = $_POST['team_id'];

    // Menghapus semua entri dari tabel members yang terkait dengan team_id
    $delete_members_sql = "DELETE FROM members WHERE team_id = '$team_id'";
    $delete_members_query = mysqli_query($koneksi, $delete_members_sql);
    echo "data member hapus.";

    if ($delete_members_query) {
        // Jika penghapusan member berhasil, lanjutkan dengan menghapus kelompok dari tabel teams
        $delete_team_sql = "DELETE FROM teams WHERE team_id = '$team_id'";
        $delete_team_query = mysqli_query($koneksi, $delete_team_sql);
        echo "data team hapus.";

        if ($delete_team_query) {
            // Jika penghapusan kelompok berhasil, arahkan kembali ke halaman dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Failed to delete team.";
        }
    } else {
        echo "Failed to delete members.";
    }
}
?>
