<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: logout.php");
    exit();
}

include 'config.php';

$nama = $_SESSION['nama'];
$nim_nid = $_SESSION['nim_nid'];
$user_id = $_SESSION['user_id'];
$tahun = $_SESSION['tahun_angkatan'];
$peran = $_SESSION['peran'];

// Memeriksa apakah team_id ada di URL
if (!isset($_GET['team_id'])) {
    echo "Team ID is not specified.";
    exit();
}

$team_id = $_GET['team_id'];

// Mendapatkan detail tim
$sql_team = "SELECT * FROM teams WHERE team_id = '$team_id'";
$result_team = mysqli_query($koneksi, $sql_team);

if (mysqli_num_rows($result_team) == 0) {
    echo "Team not found.";
    exit();
}

$team = mysqli_fetch_assoc($result_team);

// Mendapatkan detail member
$sql_member = "SELECT * FROM members WHERE team_id = '$team_id' AND user_id = '$user_id'";
$result_member = mysqli_query($koneksi, $sql_member);

$member = mysqli_fetch_assoc($result_member);

// Mendapatkan detail apakah user sudah memiliki tim
$sql_in_team = "SELECT * FROM members WHERE user_id = '$user_id'";
$result_in_team = mysqli_query($koneksi, $sql_in_team);

$in_team = mysqli_fetch_assoc($result_in_team);

// Mendapatkan daftar anggota tim
$sql_my_members = "SELECT * FROM users 
                JOIN members ON users.user_id = members.user_id 
                WHERE members.team_id = '$team_id'";
$result_my_members = mysqli_query($koneksi, $sql_my_members);

$is_leader = ($_SESSION['user_id'] == $team['leader_id']);
$is_in_team = ($_SESSION['user_id'] == $in_team['user_id']);
$is_member = ($member && $_SESSION['user_id'] == $member['user_id']);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>video playlist</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css" />
</head>
<body class="home-container">
    <!-- ini navbar dibiarkan saja -->
    <header class="header">
        <section class="flex">
            <a href="dashboard.php" class="logo">
                <img src="images/upnvj.png" alt="Logo" /> Pekaem
            </a>

            <form action="search.html" method="post" class="search-form">
                <input
                    type="text"
                    name="search_box"
                    required
                    placeholder="search courses..."
                    maxlength="100"
                />
                <button type="submit" class="fas fa-search"></button>
            </form>

            <div class="icons">
                <div id="menu-btn" class="fas fa-bars"></div>
                <div id="search-btn" class="fas fa-search"></div>
                <div id="user-btn" class="fas fa-user"></div>
                <div id="toggle-btn" class="fas fa-sun"></div>
            </div>

            <div class="profile">
                <img src="images/pic-1.jpg" class="image" alt="" />
                <h3 class="name"><?php echo $nama; ?></h3>
                <p class="role"><?php echo $nim_nid; ?></p>
                <a href="profile.html" class="btn">view profile</a>
                <div class="flex-btn">
                    <a href="logout.php" class="option-btn">Logout</a>
                </div>
            </div>
        </section>
    </header>

    <!-- ini sidebar dibiarkan saja -->
    <div class="side-bar">
        <div id="close-btn">
            <i class="fas fa-times"></i>
        </div>

        <div class="profile">
            <img src="images/pic-1.jpg" class="image" alt="" />
            <h3 class="name"><?php echo $nama; ?></h3>
            <p class="role"><?php echo $nim_nid; ?></p>
            <!-- <a href="profile.html" class="btn">view profile</a> -->
        </div>

        <nav class="navbar">
            <a href="dashboard.php"><i class="fas fa-home"></i><span>home</span></a>
            <a href="teams.php"
                ><i class="fas fa-graduation-cap"></i><span>Teams</span></a
            >
            <a href="teachers.html"
                ><i class="fas fa-chalkboard-user"></i><span>teachers</span></a
            >
        </nav>
    </div>

    <section class="playlist-details">
        <h1 class="heading">Team Details</h1>

        <div class="row">
            <div class="column">
            <form action="" method="post" class="save-playlist">
                <button type="submit"><span><?php echo $team['pkm_type']; ?></span></button>
            </form>

                <div class="details">
                    <h3><?php echo $team['team_name']; ?></h3>
                    <p><?php echo $team['description']; ?></p>
                    <?php
                      // Perbarui bagian HTML yang relevan untuk menambahkan tautan "Leave Team"
                      if ($is_leader) {
                          echo '<a href="index_update_team.php?team_id=' . $team['team_id'] . '" class="inline-btn" style="margin-right: 10px;">Update</a>';
                          echo '<a class="inline-delete-btn" onclick="togglePopup()" style="margin-right: 10px;">Delete</a>';
                          echo '<a href="index_invite_member.php?team_id=' . $team_id . '" class="inline-option-btn" style="margin-right: 10px;">Invite</a>';
                      } else if ($is_member) {
                          echo '<a href="leave_team.php?team_id=' . $team_id . '" class="inline-delete-btn">Leave Team</a>';
                      } else if ($is_in_team) {
                         echo '';
                      }
                      else {
                          echo '<a href="#" class="inline-btn">Request to join team</a>';
                      }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <section class="playlist-videos">
        <h1 class="heading">Team Members</h1>

        <div class="box-container">
            <div class="box">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Angkatan</th>
                            <?php
                            if ($is_leader) {
                                echo "<th>Manage</th>";
                            }
                            ?>
                            <!-- <th>Profile</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result_my_members) > 0) {
                            while ($my_member = mysqli_fetch_assoc($result_my_members)) {
                                echo "<tr style='height: 70px;'>";
                                echo "<td style='width: 15%;'>" . $my_member['role'] . "</td>";
                                echo "<td style='width: 25%;'>" . $my_member['nim_nid'] . "</td>";
                                echo "<td style='width: 25%;'>" . $my_member['nama'] . "</td>";
                                echo "<td style='width: 20%;'>" . $my_member['tahun_angkatan'] . "</td>";
                                if ($is_leader) {
                                    // Cek jika peran bukan ketua, tampilkan tombol hapus member
                                    if ($my_member['role'] !== 'ketua') {
                                      echo "<td style='width: 15%;'>";
                                      echo "<a href='delete_member.php?team_id={$team_id}&member_id={$my_member['user_id']}' class='delete-btn inline-delete-btn'>Hapus</a>";
                                      echo "</td>";
                                    } else {
                                      echo "<td style='width: 15%;'></td>"; 
                                    }
                                }                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No members found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Pop Up -->
    <div class="popup" id="popup-1">
        <div class="overlay"></div>
        <div class="content">
            <div class="close-btn-popup" onclick="togglePopup()">&times;</div>
            <h1 class="heading">Delete Team</h1>
            <p>Apakah kamu yakin ingin menghapus kelompok?</p>
            <div class="btn-group">
                <form action="delete_team.php" method="post" >
                    <input type="hidden" name="team_id" value="<?php echo $team['team_id']; ?>">
                    <button type="submit" class="delete-btn" name="delete">Iya</button>
                </form>
                <form method="post">
                    <a href="#" class="btn" onclick="togglePopup()">Tidak</a>
                </form>
            </div>
        </div>
    </div>

    <!-- custom js file link  -->
    <script src="js/script.js"></script>
</body>
</html>
<?php
// Menutup koneksi database
mysqli_close($koneksi);
?>
