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

// Mendapatkan daftar semua tim kecuali tim sendiri
$sql_not_my_teams = "
    SELECT * 
    FROM teams 
    WHERE team_id NOT IN (
        SELECT team_id 
        FROM members 
        WHERE user_id = '$user_id'
    ) 
    AND leader_id != '$user_id'
";
$result_not_my_teams = mysqli_query($koneksi, $sql_not_my_teams);

// Mendapatkan daftar undangan untuk pengguna saat ini
$sql_requests = "
    SELECT 
        join_requests.join_id, 
        users.user_id, 
        users.nama, 
        users.nim_nid, 
        teams.team_id, 
        teams.team_name, 
        teams.member_now, 
        teams.member_max
    FROM 
        join_requests 
    JOIN 
        teams 
    ON 
        join_requests.team_id = teams.team_id 
    JOIN 
        users 
    ON 
        join_requests.user_id = users.user_id 
    WHERE 
        teams.leader_id = '$user_id'
";
$result_requests = mysqli_query($koneksi, $sql_requests);

// Mendapatkan daftar undangan untuk pengguna saat ini
$sql_invitations = "SELECT invitations.invite_id, teams.team_id, teams.team_name, teams.member_now, teams.member_max, teams.description FROM invitations JOIN teams ON invitations.team_id = teams.team_id WHERE invitations.user_id = '$user_id'";
$result_invitations = mysqli_query($koneksi, $sql_invitations);

// Memeriksa apakah pengguna belum memiliki tim
$has_team = (mysqli_num_rows($result_my_teams) > 0) || (mysqli_num_rows($result_member_teams) > 0);

// Mendapatkan query pencarian jika ada
$search_query = isset($_POST['search_box']) ? $_POST['search_box'] : '';

// Memodifikasi query berdasarkan input pencarian
// if (!empty($search_query)) {
//     $sql_all_teams = "SELECT * FROM teams WHERE team_name LIKE '%$search_query%' OR description LIKE '%$search_query%'";
// } else {
//     $sql_all_teams = "SELECT * FROM teams";
// }
// $result_all_teams = mysqli_query($koneksi, $sql_all_teams);


// Memodifikasi query berdasarkan input pencarian kecuali tim sendiri
if (!empty($search_query)) {
    $sql_not_my_teams = "
        SELECT * 
        FROM teams 
        WHERE (team_name LIKE '%$search_query%' OR description LIKE '%$search_query%') 
        AND team_id NOT IN (
            SELECT team_id 
            FROM members 
            WHERE user_id = '$user_id'
        ) 
        AND leader_id != '$user_id'
    ";
} else {
    $sql_not_my_teams = "
        SELECT * 
        FROM teams 
        WHERE team_id NOT IN (
            SELECT team_id 
            FROM members 
            WHERE user_id = '$user_id'
        ) 
        AND leader_id != '$user_id'
    ";
}

$result_not_my_teams = mysqli_query($koneksi, $sql_not_my_teams);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css" />
</head>
<body class= "home-container">

    <!-- Header -->
    <header class="header">
        <section class="flex">
            <a href="dashboard.php" class="logo">
                <img src="images/upnvj.png" alt="Logo" /> Pekaem
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
            <div class="profile">
                <img src="images/pic-1.jpg" class="image" alt="" />
                <h3 class="name"><?php echo $nama; ?></h3>
                <p class="role"><?php echo $nim_nid; ?></p>
                <a href="profile.html" class="btn">View Profile</a>
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

        <div class="profile">
            <img src="images/pic-1.jpg" class="image" alt="" />
            <h3 class="name"><?php echo $nama; ?></h3>
            <p class="role"><?php echo $nim_nid; ?></p>
        </div>

        <nav class="navbar">
            <a href="dashboard.php"><i class="fas fa-home"></i><span>Home</span></a>
            <a href="teams.php"><i class="fas fa-graduation-cap"></i><span>Teams</span></a>
            <a href="index_invitation.php"><i class="fas fa-chalkboard-user"></i><span>Invitation</span></a>
        </nav>
    </div>

    <!-- Home Grid -->
    <section class="home-grid">
        <h1 class="heading">Quick Options</h1>

        <div class="box-container">
            <!-- <div class="box">
                <h3 class="title">Jenis PKM</h3>
                <div class="flex">
                    <a href="#"><span>PKM-RE</span></a>
                    <a href="#"><span>PKM-RSH</span></a>
                    <a href="#"><span>PKM-K</span></a>
                    <a href="#"><span>PKM-PM</span></a>
                    <a href="#"><span>PKM-KC</span></a>
                    <a href="#"><span>PKM-AI</span></a>
                </div>
            </div> -->

            <!-- Request Box -->
            <?php
            if (mysqli_num_rows($result_requests) > 0) {
                echo "<div class='box'>";
                echo "<h3 class='title'>Request List</h3>";
                echo "<div class='flex'>";
                echo "<a href='#'><span>" . mysqli_num_rows($result_requests) . "</span></a>";
                echo "</div>";
                // echo "<p class='tutor'>Jumlah Request = " . mysqli_num_rows($result_requests) . "</p>";
                echo "<br><a href='index_request.php' class='inline-btn'>View List</a>";
                echo "</div>";
            }
            ?>

            <!-- Invitation Box -->
            <?php
            if (mysqli_num_rows($result_invitations) > 0) {
                echo "<div class='box'>";
                echo "<h3 class='title'>Invitation List</h3>";
                echo "<div class='flex'>";
                echo "<a href='#'><span>" . mysqli_num_rows($result_invitations) . "</span></a>";
                echo "</div>";
                // echo "<p class='tutor'>Jumlah Request = " . mysqli_num_rows($result_invitations) . "</p>";
                echo "<br><a href='index_invitation.php' class='inline-btn'>View List</a>";
                echo "</div>";
            }
            ?>

            <!-- Create Team atau My Team box -->
            <?php
            if (mysqli_num_rows($result_my_teams) > 0) {
                while ($row = mysqli_fetch_assoc($result_my_teams)) {
                    echo "<div class='box'>";
                    echo "<h3 class='title'>" . $row['team_name'] . "</h3>";
                    echo "<h3 class='tutor'>" . " [" . $row['member_now'] . "/" . $row['member_max'] . "]" .  "</h3>";
                    echo "<a href='index_manage_members.php?team_id=" . $row['team_id'] . "' class='inline-btn'>Manage Team</a>";
                    echo "</div>";
                }
            } elseif (mysqli_num_rows($result_member_teams) > 0) {
                while ($row = mysqli_fetch_assoc($result_member_teams)) {
                    echo "<div class='box'>";
                    echo "<h3 class='title'>" . $row['team_name'] . " [" . $row['member_now'] . "/" . $row['member_max'] . "]" . "</h3>";
                    echo "<h3 class='tutor'>" . $row['description'] . "</h3>";
                    echo "<div class='box'>";
                    echo "<a href='index_manage_members.php?team_id=" . $row['team_id'] . "' class='inline-btn'>View Team</a>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<div class='box'>";
                echo "<h3 class='title'>Create Team</h3>";
                echo "<p class='tutor'>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>";
                echo "<a href='index_create_team.php' class='inline-btn'>Create New Team</a>";
                echo "</div>";
            }
            ?>
        </div>
    </section>

    <!-- Team List -->
    <section class="courses">
        <h1 class="heading">Team List</h1>
        <div class="box-container">
            <?php
            if (mysqli_num_rows($result_not_my_teams) > 0) {
                while ($row = mysqli_fetch_assoc($result_not_my_teams)) {
                    echo "<div class='box'>";
                    echo "<div class='tutor'>";
                    echo "<div class='info'>";
                    echo "<h3>" . $row['pkm_type'] . "</h3>";
                    echo "<h3>" . $row['team_name'] . "</h3>";
                    echo "<span>" . $row['member_now'] . "/" . $row['member_max'] . "</span>";
                    echo "<br>";
                    echo "<br>";
                    echo "<h3 class='title'>" . $row['description'] . "</h3>";
                    echo "</div>";
                    echo "</div>";
                    echo "<a href='index_manage_members.php?team_id=" . $row['team_id'] . "' class='inline-btn'>View Team</a>";
                    echo "</div>";
                }
            } else {
                echo "<h1 class='heading'>Tidak ada tim tersedia.</h1>";
            }
            echo "<div class='more-btn'>";
            ?>
        </div>
        <?php
        
        if (mysqli_num_rows($result_not_my_teams) === 1) {
            echo "<div class='more-btn'>";
            echo "</div>";
        }
        ?>
    </section>


    <section class="courses">
        
      <div class="box-container">
        <div class='more-btn'>
        <a href='teams.php' class='inline-option-btn'>view all teams</a>
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
