<?php
    // session dimulai
    session_start();
    //cek session untuk memeriksa user telah login atau belum
    if (!isset($_SESSION['user_id'])) {
        header("Location: index_login.php");
        exit();
    }
?>