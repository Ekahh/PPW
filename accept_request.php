<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index_login.php");
    exit();
}

include 'config.php';

if (isset($_GET['join_id'])) {
    $join_id = $_GET['join_id'];

    // Mendapatkan informasi dari join_requests
    $sql_get_request = "
        SELECT 
            *,
            join_requests.team_id, 
            join_requests.user_id, 
            teams.member_now, 
            teams.member_max 
        FROM 
            join_requests 
        JOIN 
            teams 
        ON 
            join_requests.team_id = teams.team_id 
        WHERE 
            join_requests.join_id = '$join_id'
    ";
    $result_get_request = mysqli_query($koneksi, $sql_get_request);

    if ($result_get_request === false) {
        echo "Error: " . mysqli_error($koneksi);
        exit();
    }

    $row = mysqli_fetch_assoc($result_get_request);
    $team_id = $row['team_id'];
    $user_id = $row['user_id'];
    $member_now = $row['member_now'];
    $member_max = $row['member_max'];

    // Periksa apakah anggota sekarang kurang dari anggota maksimum
    if ($member_now < $member_max) {

        // Mengupdate jumlah member di tabel teams
        $sql_update_team = "UPDATE teams SET member_now = member_now + 1 WHERE team_id = '$team_id'";
        mysqli_query($koneksi, $sql_update_team);

        // Tambahkan anggota ke dalam tabel members dengan status 'accepted'
        $sql_add_member = "INSERT INTO members (team_id, user_id) VALUES ('$team_id', '$user_id')";
        mysqli_query($koneksi, $sql_add_member);

        // Update status permintaan di tabel join_requests menjadi 'accepted'
        $sql_update_request = "DELETE FROM join_requests WHERE join_id = '$join_id'";
        mysqli_query($koneksi, $sql_update_request);

        // Redirect kembali ke halaman daftar permintaan
        header("Location: index_request.php");
        exit();
    } else {
        echo "Team is already full.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}
?>
