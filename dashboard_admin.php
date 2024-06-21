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

// Mendapatkan daftar tim di mana pengguna adalah anggota
$sql_member_teams = "SELECT t.* FROM teams t JOIN members m ON t.team_id = m.team_id WHERE m.user_id = '$user_id'";
$result_member_teams = mysqli_query($koneksi, $sql_member_teams);

// Mendapatkan daftar semua tim
$sql_all_teams = "SELECT * FROM teams";
$result_all_teams = mysqli_query($koneksi, $sql_all_teams);

// Mendapatkan daftar semua tim
$sql_unapproved_teams = "SELECT * FROM teams WHERE status = 'unset'";
$result_unapproved_teams = mysqli_query($koneksi, $sql_unapproved_teams);

// Memeriksa apakah pengguna belum memiliki tim
$has_team = (mysqli_num_rows($result_my_teams) > 0) || (mysqli_num_rows($result_member_teams) > 0);

// Mendapatkan query pencarian jika ada
$search_query = isset($_POST['search_box']) ? $_POST['search_box'] : '';

// Memodifikasi query berdasarkan input pencarian
if (!empty($search_query)) {
    $sql_unapproved_teams = "SELECT * FROM teams WHERE status = 'unset' AND team_name LIKE '%$search_query%' OR description LIKE '%$search_query%'";
} else {
    $sql_unapproved_teams = "SELECT * FROM teams WHERE status = 'unset'";
}
$result_unapproved_teams = mysqli_query($koneksi, $sql_unapproved_teams);

?>

<!doctype html>
<html lang="en">
    <head>
      <meta charset="UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>courses</title>

      <!-- font awesome cdn link  -->
      <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
      />

      <!-- custom css file link  -->
      <link rel="stylesheet" href="css/style.css" />
    </head>
    <body class= "home-container">

        <!-- ini navbar dibiarkan saja -->
        <header class="header">
            <section class="flex">
                <a href="dashboard_admin.php" class="logo">
                    <img src="images/upnvj.png" alt="Logo" /> PKM
                </a>

                <!-- Search Form -->
                <form action="dashboard_admin.php" method="post" class="search-form">
                    <input type="text" name="search_box" placeholder="Cari judul atau deskripsi tim..." maxlength="100" />
                    <button type="submit" class="fas fa-search"></button>
                </form>

                <div class="icons">
                  <!-- <div id="menu-btn" class="fas fa-bars"></div> -->
                  <div id="search-btn" class="fas fa-search"></div>
                  <div id="user-btn" class="fas fa-user"></div>
                  <div id="toggle-btn" class="fas fa-sun"></div>
                </div>

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

        <section class="playlist-videos">
            <h1 class="heading">Team List</h1>

            <div class="box-container">
                <div class="box">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style='width: 15%;'>Jenis PKM</th>
                                <th style='width: 40%;'>Judul</th>
                                <th style='width: 25%;'>Nama Ketua</th>
                                <th style=' width: 20%;'>Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result_unapproved_teams) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result_unapproved_teams)): ?>
                                    <tr style='height: 70px;'>
                                        <td><?php echo $row['pkm_type']; ?></td>
                                        <td><?php echo $row['team_name']; ?></td>
                                        <td>Nama Ketua</td>
                                        <td style='width: 15%;'>
                                            <a href='admin_accept_team.php?team_id=<?php echo $row['team_id']; ?>' class='inline-btn'>Accept Team</a><br>
                                            <a href='admin_decline_team.php?team_id=<?php echo $row['team_id']; ?>' class='inline-delete-btn'>Decline Team</a><br>
                                            <a href='index_admin_team_detail.php?team_id=<?php echo $row['team_id']; ?>' class='inline-option-btn'>View Team</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan='5'>No Teams found.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </section>

        <!-- custom js file link  -->
        <script src="js/script.js"></script>
    </body>
</html>
