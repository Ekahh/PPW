<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index_login.php");
    exit();
}

include 'config.php';

if (isset($_GET['join_id'])) {
    $join_id = $_GET['join_id'];

    // Update status permintaan di tabel joinrequests menjadi 'rejected'
    // $sql_update_request = "UPDATE join_requests SET status = 'rejected' WHERE join_id = '$join_id'";
    $sql_update_request = "DELETE FROM join_requests WHERE join_id = '$join_id'";
    $result_update = mysqli_query($koneksi, $sql_update_request);

    if ($result_update === false) {
        echo "Error: " . mysqli_error($koneksi);
        exit();
    }

    // Redirect kembali ke halaman daftar permintaan
    header("Location: index_request.php");
    exit();
} else {
    echo "Invalid request.";
    exit();
}
?>
