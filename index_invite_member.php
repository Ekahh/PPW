<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index_login.php");
    exit();
}

include 'config.php';

$nama = $_SESSION['nama'];
$nim_nid = $_SESSION['nim_nid'];
$user_id = $_SESSION['user_id'];
$tahun = $_SESSION['tahun_angkatan'];
$peran = $_SESSION['peran'];

// Mendapatkan daftar tim yang dibuat oleh pengguna
$sql_my_teams = "SELECT * FROM teams WHERE leader_id = '$user_id'";
$result_my_teams = mysqli_query($koneksi, $sql_my_teams);

// Inisialisasi $team_id dengan nilai yang sesuai
$team_id = ''; // atau berikan nilai default

if ($result_my_teams && mysqli_num_rows($result_my_teams) > 0) {
    $team = mysqli_fetch_assoc($result_my_teams);
    $team_id = $team['team_id'];
}

// Ambil pesan undangan dari session (jika ada)
$invite_message = isset($_SESSION['invite_message']) ? $_SESSION['invite_message'] : "";
unset($_SESSION['invite_message']); // Hapus pesan dari session setelah ditampilkan

// Mendapatkan daftar mahasiswa yang belum memiliki tim dan belum diundang oleh user (ketua) dalam sesi ini
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_box'])) {
    $search_query = $_POST['search_box'];
    $sql_all_students = "SELECT * FROM users 
                         WHERE peran = 'mahasiswa' 
                         AND user_id != '$user_id'
                         AND user_id NOT IN (SELECT user_id FROM members)
                         AND user_id NOT IN (SELECT user_id FROM invitations WHERE team_id = '$team_id')
                         AND (nama LIKE '%$search_query%' OR nim_nid LIKE '%$search_query%')";
} else {
    $sql_all_students = "SELECT * FROM users 
                         WHERE peran = 'mahasiswa' 
                         AND user_id != '$user_id'
                         AND user_id NOT IN (SELECT user_id FROM members)
                         AND user_id NOT IN (SELECT user_id FROM invitations WHERE team_id = '$team_id')";
}

$result_all_students = mysqli_query($koneksi, $sql_all_students);

// Mendapatkan daftar mahasiswa yang sudah diundang oleh user (ketua) dalam sesi ini
$sql_invited_students = "SELECT users.*, invitations.invite_id FROM users 
                         JOIN invitations ON users.user_id = invitations.user_id 
                         WHERE invitations.team_id = '$team_id'";
$result_invited_students = mysqli_query($koneksi, $sql_invited_students);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite Member</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css" />

    <!-- custom css for popup -->
    <link rel="stylesheet" href="css/popup.css" />
</head>
<body>

    <!-- Navbar -->
    <header class="header">
        <section class="flex">
            <a href="dashboard.php" class="logo">
                <img src="images/upnvj.png" alt="Logo" /> PKM
            </a>

            <!-- Search Form -->
            <form action="index_invite_member.php" method="post" class="search-form">
                <input type="text" name="search_box" placeholder="search students..." maxlength="100" />
                <button type="submit" class="fas fa-search"></button>
            </form>

            <!-- Icons -->
            <div class="icons">
                <div id="menu-btn" class="fas fa-bars"></div>
                <div id="search-btn" class="fas fa-search"></div>
                <div id="user-btn" class="fas fa-user"></div>
                <div id="toggle-btn" class="fas fa-sun"></div>
            </div>

            <!-- Profile -->
            <div class="profile">
                <img src="images/pic-1.jpg" class="image" alt="" />
                <h3 class="name"><?php echo $nama; ?></h3>
                <p class="role"><?php echo $nim_nid; ?></p>
                <!-- <a href="profile.html" class="btn">view profile</a> -->
                <div class="flex-btn">
                    <a href="logout.php" class="option-btn">Logout</a>
                </div>
            </div>
        </section>
    </header>

    <!-- Sidebar -->
    <div class="side-bar">
        <div id="close-btn">
            <i class="fas fa-times"></i>
        </div>

        <!-- Profile -->
        <div class="profile">
            <img src="images/pic-1.jpg" class="image" alt="" />
            <h3 class="name"><?php echo $nama; ?></h3>
            <p class="role"><?php echo $nim_nid; ?></p>
        </div>

        <!-- Navbar -->
        <nav class="navbar">
            <a href="dashboard.php"><i class="fas fa-home"></i><span>Home</span></a>
            <a href="students.php"><i class="fas fa-user-graduate"></i><span>Students</span></a>
            <a href="teachers.html"><i class="fas fa-chalkboard-user"></i><span>Teachers</span></a>
        </nav>
    </div>

    <!-- Content -->
    <section class="playlist-videos">
        <h1 class="heading">
            Invited Student List
        </h1>

        <div class="box-container">
            <div class="box">
                <table class="table">
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Angkatan</th>
                            <th>Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result_invited_students) > 0) {
                            while ($row = mysqli_fetch_assoc($result_invited_students)) {
                                echo "<tr>";
                                echo "<td style='width: 20%;'>" . $row['nim_nid'] . "</td>";
                                echo "<td style='width: 40%;'>" . $row['nama'] . "</td>";
                                echo "<td style='width: 20%;'>" . $row['tahun_angkatan'] . "</td>";
                                echo "<td style='width: 20%;'>";
                                echo "<form method='post' action='cancel_invite.php'>";
                                echo "<input type='hidden' name='invite_id' value='" . $row['invite_id'] . "'>"; 
                                echo "<input type='hidden' name='team_id' value='" . $team_id . "'>"; 
                                echo "<input type='hidden' name='user_id' value='" . $row['user_id'] . "'>";
                                echo "<button type='submit' name='cancel-invite' class='inline-delete-btn'>Cancel Invite</button>";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No students found.</td></tr>";
                        }
                        
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="playlist-videos">
        <h1 class="heading">
            Student List
        </h1>

        <div class="box-container">
            <div class="box">
                <table class="table">
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Angkatan</th>
                            <th>Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result_all_students) > 0) {
                            while ($row = mysqli_fetch_assoc($result_all_students)) {
                                echo "<tr>";
                                echo "<td style='width: 20%;'>" . $row['nim_nid'] . "</td>";
                                echo "<td style='width: 40%;'>" . $row['nama'] . "</td>";
                                echo "<td style='width: 20%;'>" . $row['tahun_angkatan'] . "</td>";
                                echo "<td style='width: 20%;'>";
                                echo "<form method='post' action='invite_member.php'>";
                                echo "<input type='hidden' name='team_id' value='" . $team_id . "'>"; 
                                echo "<input type='hidden' name='user_id' value='" . $row['user_id'] . "'>";
                                echo "<button type='submit' name='invite' class='inline-btn'>Invite</button>";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No students found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="playlist-videos">
            
        <!-- Tampilkan popup jika ada pesan undangan -->
        <?php if (!empty($invite_message)): ?>
        <div class="popup" id="popup-1" style="display: block;">
            <div class="overlay"></div>
            <div class="content">
                <div class="close-btn-popup" onclick="togglePopup()">&times;</div>
                <h1 class="heading">Invite Status</h1>
                <p><?php echo $invite_message; ?></p>
            </div>
        </div>
        <?php endif; ?>
    </section>
    
    <!-- Custom JavaScript -->
    <script src="js/script.js"></script>
    <script>
        // Tampilkan popup secara otomatis jika ada pesan undangan
        <?php if (!empty($invite_message)): ?>
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
