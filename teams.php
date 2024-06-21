<?php
session_start();
include 'config.php';

$is_logged_in = isset($_SESSION['user_id']);

if ($is_logged_in) {
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

    // Memeriksa apakah pengguna belum memiliki tim
    $has_team = (mysqli_num_rows($result_my_teams) > 0) || (mysqli_num_rows($result_member_teams) > 0);
}

// Mendapatkan daftar semua tim
$sql_all_teams = "SELECT * FROM teams";
if (!empty($_POST['search_box'])) {
    $search_query = $_POST['search_box'];
    $sql_all_teams = "SELECT * FROM teams WHERE team_name LIKE '%$search_query%' OR description LIKE '%$search_query%' OR pkm_type LIKE '%$search_query%'";
}
$result_all_teams = mysqli_query($koneksi, $sql_all_teams);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teams</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="home-container">

<!-- Header -->
<header class="header">
        <section class="flex">
            <a href="dashboard.php" class="logo">
                <img src="images/upnvj.png" alt="Logo" /> PKM
            </a>

            <!-- Search Form -->
            <form action="dashboard.php" method="post" class="search-form">
                <input type="text" name="search_box" placeholder="Cari judul atau deskripsi tim..." maxlength="100" />
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
            <?php if ($is_logged_in): ?>
            <div class="profile">
                <img src="images/pic-1.jpg" class="image" alt="" />
                <h3 class="name"><?php echo $nama; ?></h3>
                <p class="role"><?php echo $nim_nid; ?></p>
                <!-- <a href="profile.html" class="btn">View Profile</a> -->
                <div class="flex-btn">
                    <a href="logout.php" class="option-btn">Logout</a>
                </div>
            </div>
            <?php else: ?>
            <div class="profile">
            <div class="flex-btn">
                <a href="login.php" class="option-btn">login</a>
                <a href="index_register.php" class="option-btn">register</a>
            </div>
            <?php endif; ?>
        </div>
        </section>
    </header>

    <!-- Sidebar -->
    <div class="side-bar">
        <div id="close-btn">
            <i class="fas fa-times"></i>
        </div>

        <?php if ($is_logged_in): ?>
        <div class="profile">
            <img src="images/pic-1.jpg" class="image" alt="" />
            <h3 class="name"><?php echo $nama; ?></h3>
            <p class="role"><?php echo $nim_nid; ?></p>
        </div>
        <?php endif; ?>

        <nav class="navbar">
            <a href="dashboard.php"><i class="fas fa-home"></i><span>Home</span></a>
            <a href="teams.php"><i class="fas fa-graduation-cap"></i><span>Teams</span></a>
            <?php if ($is_logged_in): ?>
            <a href="index_invitation.php"><i class="fas fa-person-circle-plus"></i><span>Invitation</span></a>
            <?php endif; ?>
        </nav>
    </div>
    
    <!-- Team List -->
    <section class="courses">
        <h1 class="heading">Team List</h1>
        <div class="box-container">
            <?php if (mysqli_num_rows($result_all_teams) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result_all_teams)): ?>
                    <?php
                    $is_leader = ($is_logged_in && $_SESSION['user_id'] == $row['leader_id']);
                    ?>
                    <div class='box'>
                        <div class='tutor'>
                            <div class='info'>
                                <span><?php echo $row['pkm_type']; ?></span>
                                <h3>
                                    <?= $row['team_name'] ?> 
                                    <?php if ($row['status'] == 'Approved') : ?>
                                        <i class="fa fa-check-circle" aria-hidden="true" style="color: green;"></i>
                                    <?php elseif ($row['status'] == 'Not Approved') : ?>
                                        <i class="fa fa-times-circle" aria-hidden="true" style="color: red;"></i>
                                    <?php endif; ?>
                                </h3>
                                <span><?php echo $row['member_now'] . "/" . $row['member_max']; ?></span>
                                <br>
                                <br>
                                <h4 class='title'><?php echo $row['description']; ?></h4>
                            </div>
                        </div>
                        <?php if ($is_leader): ?>
                            <a href='index_manage_members.php?team_id=<?php echo $row['team_id']; ?>' class='inline-btn'>Manage Team</a>
                        <?php else: ?>
                            <a href='index_manage_members.php?team_id=<?php echo $row['team_id']; ?>' class='inline-btn'>View Team</a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <h1 class='heading'>Tidak ada tim tersedia.</h1>
            <?php endif; ?>
        </div>
    </section>

<!-- Custom JS file link -->
<script src="js/script.js"></script>
</body>
</html>

<?php
// Menutup koneksi database
mysqli_close($koneksi);
?>
