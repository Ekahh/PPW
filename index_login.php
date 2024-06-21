<?php
    session_start();
    include 'config.php'; // Mengimpor file konfigurasi database

    // Ambil pesan undangan dari session (jika ada)
    $message = isset($_SESSION['message']) ? $_SESSION['message'] : "";
    unset($_SESSION['message']); // Hapus pesan dari session setelah ditampilkan

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Menghapus semua variabel sesi
        $_SESSION = array();
        // Menghancurkan sesi
        session_destroy();

        // Mengambil data yang dikirim melalui formulir login
        $nim_email = $_POST['nim_email'];
        $password = $_POST['password'];

        // Memeriksa apakah input adalah email atau NIM
        if (filter_var($nim_email, FILTER_VALIDATE_EMAIL)) {
            // Input adalah email
            $sql = "SELECT * FROM users WHERE email = '$nim_email'";
        } else {
            // Input adalah NIM
            $sql = "SELECT * FROM users WHERE nim_nid = '$nim_email'";
        }

        $query = mysqli_query($koneksi, $sql);
    }

    // Menutup koneksi database
    mysqli_close($koneksi);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <section class="flex">
            <a href="dashboard.php" class="logo">
                <img src="images/upnvj.png" alt="Logo"/> PKM
            </a>

            <div class="icons">
                <div id="search-btn" class="fas fa-search"></div>
                <div id="user-btn" class="fas fa-user"></div>
                <div id="toggle-btn" class="fas fa-sun"></div>
            </div>

            <div class="profile">
                <div class="flex-btn">
                    <a href="login.php" class="option-btn">Login</a>
                    <a href="index_register.php" class="option-btn">Register</a>
                </div>
            </div>
        </section>
    </header>

    <section class="form-container">
        <form action="login.php" method="POST" enctype="multipart/form-data">
            <h3>Login</h3>
            <p for="nim_email">NIM atau Email <span>*</span></p>
            <input
                type="text"
                id="nim_email"
                name="nim_email"
                required
                placeholder="Masukkan NIM atau Email"
                maxlength="50"
                class="box"
            />

            <p for="password">Password <span>*</span></p>
            <input
                type="password"
                id="password"
                name="password"
                required
                placeholder="Masukkan password"
                maxlength="20"
                class="box"
            />
            <input
                type="submit"
                value="Login"
                name="submit"
                class="btn"
            />
        </form>
    </section>

    <footer class="footer-login">
        &copy; 2024 by <span>Kelompok 3</span> | All rights reserved!
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
