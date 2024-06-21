<?php
session_start();

if (!isset($_SESSION['user_id'])) :
    header("Location: logout.php");
    exit();
endif;

include 'config.php';

$nama = $_SESSION['nama'];
$nim_nid = $_SESSION['nim_nid'];
$user_id = $_SESSION['user_id'];
$tahun = $_SESSION['tahun_angkatan'];
$peran = $_SESSION['peran'];

// Memeriksa apakah team_id ada di URL
if (!isset($_GET['team_id'])) :
    echo "Team ID is not specified.";
    exit();
endif;

$team_id = $_GET['team_id'];

// Mendapatkan detail tim
$sql_team = "SELECT * FROM teams WHERE team_id = '$team_id'";
$result_team = mysqli_query($koneksi, $sql_team);

if (mysqli_num_rows($result_team) == 0) :
    echo "Team not found.";
    exit();
endif;

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
$is_in_team = ($in_team && $_SESSION['user_id'] == $in_team['user_id']);
$is_member = ($member && $_SESSION['user_id'] == $member['user_id']);

// Ambil pesan undangan dari session (jika ada)
$invite_message = isset($_SESSION['invite_message']) ? $_SESSION['invite_message'] : "";
unset($_SESSION['invite_message']); // Hapus pesan dari session setelah ditampilkan

// Ambil pesan permintaan bergabung dari session (jika ada)
$join_message = isset($_SESSION['join_message']) ? $_SESSION['join_message'] : "";
unset($_SESSION['join_message']); // Hapus pesan dari session setelah ditampilkan

// Mendapatkan daftar undangan untuk pengguna saat ini
$sql_invitations = "SELECT invitations.invite_id, teams.team_id, teams.team_name, teams.member_now, teams.member_max, teams.description FROM invitations JOIN teams ON invitations.team_id = teams.team_id WHERE invitations.user_id = '$user_id'";
$result_invitations = mysqli_query($koneksi, $sql_invitations);

$is_invited = mysqli_fetch_assoc($result_invitations);
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

    <!-- Header -->
    <header class="header">
        <section class="flex">
            <a href="dashboard_admin.php" class="logo">
                <img src="images/upnvj.png" alt="Logo" /> PKM
            </a>

            <!-- Icons -->
            <div class="icons">
                <div id="search-btn" class="fas fa-search"></div>
                <div id="user-btn" class="fas fa-user"></div>
                <div id="toggle-btn" class="fas fa-sun"></div>
            </div>

            <!-- Profile -->
            <div class="profile">
                <img src="images/pic-1.jpg" class="image" alt="" />
                <h3 class="name"><?php echo $nama; ?></h3>
                <p class="role"><?php echo $nim_nid; ?></p>
                <!-- <a href="profile.html" class="btn">View Profile</a> -->
                <div class="flex-btn">
                    <a href="logout.php" class="option-btn">Logout</a>
                </div>
            </div>
        </section>
    </header>

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
                    <a href="admin_accept_team.php?team_id=<?php echo $team['team_id']; ?>" class="inline-btn" style="margin-right: 10px;">Accept Team</a>
                    <a href="admin_decline_team.php?team_id=<?php echo $team['team_id']; ?>" class="inline-delete-btn" onclick="togglePopupDelete()" style="margin-right: 10px;">Decline Team</a>
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
                            <?php if ($is_leader) : ?>
                                <th>Manage</th>
                            <?php endif; ?>
                            <!-- <th>Profile</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result_my_members) > 0) : ?>
                            <?php while ($my_member = mysqli_fetch_assoc($result_my_members)) : ?>
                                <tr style='height: 70px;'>
                                    <td style='width: 15%;'><?php echo $my_member['role']; ?></td>
                                    <td style='width: 25%;'><?php echo $my_member['nim_nid']; ?></td>
                                    <td style='width: 25%;'><?php echo $my_member['nama']; ?></td>
                                    <td style='width: 20%;'><?php echo $my_member['tahun_angkatan']; ?></td>
                                    <?php if ($is_leader) : ?>
                                        <?php
                                        // Cek jika peran bukan ketua, tampilkan tombol hapus member
                                        if ($my_member['role'] !== 'ketua') :
                                        ?>
                                            <td style='width: 15%;'>
                                                <a href='delete_member.php?team_id=<?php echo $team_id; ?>&member_id=<?php echo $my_member['user_id']; ?>' class='inline-delete-btn'>Hapus</a><br>
                                            </td>
                                        <?php else : ?>
                                            <td style='width: 15%;'></td>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr><td colspan='5'>No members found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Tampilkan popup jika ada pesan undangan -->
        <?php if (!empty($invite_message) || !empty($join_message)) : ?>
            <div class="popup" id="popup-join" style="display: block;">
                <div class="overlay"></div>
                <div class="content">
                    <div class="close-btn-popup" onclick="togglePopupjoin()">&times;</div>
                    <h1 class="heading">Status</h1>
                    <p><?php echo !empty($invite_message) ? $invite_message : $join_message; ?></p>
                </div>
            </div>
        <?php endif; ?>

    </section>

    <!-- custom js file link  -->
    <script src="js/script.js"></script>

    <!-- script popup -->
    <script>
        <?php if (!empty($invite_message)) : ?>
            document.addEventListener('DOMContentLoaded', function () {
                var popup = document.getElementById("popup-join");
                popup.style.display = "block";
            });
        <?php endif; ?>

        function togglePopupjoin() {
            var popup = document.getElementById("popup-join");
            popup.style.display = "none";
        }

    </script>
</body>

</html>
<?php
// Menutup koneksi database
mysqli_close($koneksi);
?>
