<?php
// Memulai sesi
session_start();

// Mengimpor file konfigurasi database
include 'config.php';

// Menampilkan pesan dari cookie jika ada
if (isset($_COOKIE["message"])) {
    echo "<script>alert('" . $_COOKIE["message"] . "')</script>";
    setcookie("message", "", time()-3600); // Hapus cookie pesan setelah ditampilkan
}

// Ambil pesan undangan dari session (jika ada)
$message = isset($_SESSION['message']) ? $_SESSION['message'] : "";
unset($_SESSION['message']); // Hapus pesan dari session setelah ditampilkan
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>register</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
<header class="header">
    <section class="flex">
        <a href="home.php" class="logo">
            <img src="images/upnvj.png" alt="Logo" /> PKM
        </a>

        <div class="icons">
            <div id="search-btn" class="fas fa-search"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
        </div>

        <div class="profile">
            <div class="flex-btn">
                <a href="login.php" class="option-btn">login</a>
                <a href="index_register.php" class="option-btn">register</a>
            </div>
        </div>
    </section>
</header>

<section class="form-container">
    <form action="register.php" method="post" enctype="multipart/form-data">
        <h3>daftar sekarang</h3>
        <p>Nama <span>*</span></p>
        <input
                type="text"
                id="nama"
                name="nama"
                required
                placeholder="Masukkan nama"
                maxlength="50"
                class="box"
        />

        <p>NIM <span>*</span></p>
        <input
                type="text"
                id="nim_nid"
                name="nim_nid"
                required
                placeholder="Masukkan NIM"
                maxlength="50"
                class="box"
        />

        <p>Tahun Angkatan <span>*</span></p>
        <input
                type="number"
                id="tahun_angkatan"
                name="tahun_angkatan"
                required
                placeholder="Masukkan angkatan (contoh: 2022)"
                maxlength="50"
                class="box"
        />

        <p>Email <span>*</span></p>
        <input
                type="email"
                id="email"
                name="email"
                required
                placeholder="Masukkan email"
                maxlength="50"
                class="box"
        />

        <p>Password <span>*</span></p>
        <input
                type="password"
                id="password"
                name="password"
                required
                placeholder="Masukkan password"
                maxlength="20"
                class="box"
        />

        <p>Konfirmasi Password <span>*</span></p>
        <input
                type="password"
                name="c_pass"
                placeholder="Konfirmasi password"
                maxlength="20"
                class="box"
        />

        <input type="submit" value="daftar" name="submit" class="btn" />
    </form>
</section>

<footer class="footer">
    &copy;copyright @2024 by <span>Kelompok Adnan</span> | all rights reserved!
</footer>

<?php if (!empty($message)): ?>
    <section class="playlist-videos">
        <!-- Tampilkan popup jika ada pesan undangan -->
        <div class="popup" id="popup-1" style="display: block;">
            <div class="overlay"></div>
            <div class="content">
                <div class="close-btn-popup" onclick="togglePopup()">&times;</div>
                <h1 class="heading">Message</h1>
                <p><?php echo $message; ?></p>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Custom JavaScript -->
<script src="js/script.js"></script>
<script>
    // Tampilkan popup secara otomatis jika ada pesan undangan
    <?php if (!empty($message)): ?>
    document.addEventListener('DOMContentLoaded', function () {
        var popup = document.getElementById("popup-1");
        popup.style.display = "block";
    });
    <?php endif; ?>

    // Fungsi untuk menutup popup
    function togglePopup() {
        var popup = document.getElementById("popup-1");
        popup.style.display = "none";
    }
</script>
</body>
</html>
