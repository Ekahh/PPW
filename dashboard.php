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

        // query tim yang dibuat user
        $sql_my_teams = "
            SELECT * 
            FROM teams 
            WHERE leader_id = '$user_id'
        ";
        $result_my_teams = mysqli_query($koneksi, $sql_my_teams);

        // query tim yang ada user sebagai anggota
        $sql_member_teams = "
            SELECT t.* 
            FROM teams t 
            JOIN members m 
            ON t.team_id = m.team_id 
            WHERE m.user_id = '$user_id'
        ";
        $result_member_teams = mysqli_query($koneksi, $sql_member_teams);

        // query request buat user yang bikin tim
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

        // query invitation buat user
        $sql_invitations = "
            SELECT 
                invitations.invite_id, 
                teams.team_id, 
                teams.team_name, 
                teams.member_now, 
                teams.member_max, 
                teams.description 
            FROM 
                invitations 
            JOIN 
                teams 
            ON 
                invitations.team_id = teams.team_id 
            WHERE 
                invitations.user_id = '$user_id'
        ";
        $result_invitations = mysqli_query($koneksi, $sql_invitations);

        // cek user ada tim atau tidak
        $has_team = (mysqli_num_rows($result_my_teams) > 0) || (mysqli_num_rows($result_member_teams) > 0);
    }

    // g dipake
    // query team list (semua tim)
    $sql_all_teams = "SELECT * FROM teams";
    $result_all_teams = mysqli_query($koneksi, $sql_all_teams);

    // query team list (kecuali tim sendiri)
    if ($is_logged_in) {
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
    } else {
        $sql_not_my_teams = "SELECT * FROM teams";
    }
    $result_not_my_teams = mysqli_query($koneksi, $sql_not_my_teams);
    
    // query search
    // query search jika ada
    $search_query = isset($_POST['search_box']) ? $_POST['search_box'] : '';

    // Memodifikasi query berdasarkan input search (kecuali tim sendiri)
    if (!empty($search_query)) {
        if ($is_logged_in) {
            $sql_not_my_teams = "
                SELECT * 
                FROM teams 
                WHERE (team_name LIKE '%$search_query%' OR description LIKE '%$search_query%' OR pkm_type LIKE '%$search_query%') 
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
                WHERE (team_name LIKE '%$search_query%' OR description LIKE '%$search_query%' OR pkm_type LIKE '%$search_query%')
            ";
        }
        $result_not_my_teams = mysqli_query($koneksi, $sql_not_my_teams);
    }
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

        
        <div class="profile">
            <img src="images/pic-1.jpg" class="image" alt="" />
            <?php if ($is_logged_in): ?>
                <h3 class="name"><?php echo $nama; ?></h3>
                <p class="role"><?php echo $nim_nid; ?></p>
            <?php endif; ?>
        </div>
        

        <nav class="navbar">
            <a href="dashboard.php"><i class="fas fa-home"></i><span>Home</span></a>
            <a href="teams.php"><i class="fas fa-graduation-cap"></i><span>Teams</span></a>
            <?php if ($is_logged_in): ?>
                <a href="index_invitation.php"><i class="fas fa-envelope-open"></i><span>Invitation</span></a>
            <?php endif; ?>
        </nav>
    </div>

    <!-- Home Grid -->
    <section class="home-grid">
        <h1 class="heading">Quick Options</h1>

        <div class="box-container">
            <?php if ($is_logged_in): ?>
                <!-- Request Box -->
                <?php if (mysqli_num_rows($result_requests) > 0): ?>
                    <div class='box'>
                        <h3 class='title'>Request List</h3>
                        <div class='flex'>
                            <a href='#'><span><?= mysqli_num_rows($result_requests) ?></span></a>
                        </div>
                        <br><a href='index_request.php' class='inline-btn'>View List</a>
                    </div>
                <?php endif; ?>

                <!-- Invitation Box -->
                <?php if (mysqli_num_rows($result_invitations) > 0): ?>
                    <div class='box'>
                        <h3 class='title'>Invitation List</h3>
                        <div class='flex'>
                            <a href='#'><span><?= mysqli_num_rows($result_invitations) ?></span></a>
                        </div>
                        <br><a href='index_invitation.php' class='inline-btn'>View List</a>
                    </div>
                <?php endif; ?>

                <!-- Create Team, Manage Team, atau My Team Box -->
                 <!-- Manage Team Box jika ketua -->
                <?php if (mysqli_num_rows($result_my_teams) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result_my_teams)): ?>
                        <div class='box'>
                            <h3 class='title'>
                                <?= $row['team_name'] ?>
                                <?php if ($row['status'] == 'Approved') : ?>
                                    <i class="fa fa-check-circle" aria-hidden="true" style="color: green;"></i>
                                <?php elseif ($row['status'] == 'Not Approved') : ?>
                                    <i class="fa fa-times-circle" aria-hidden="true" style="color: red;"></i>
                                <?php endif; ?>
                            </h3>
                            <h3 class='tutor'>[<?= $row['member_now'] ?>/<?= $row['member_max'] ?>]</h3>
                            <a href='index_manage_members.php?team_id=<?= $row['team_id'] ?>' class='inline-btn'>Manage Team</a>
                        </div>
                    <?php endwhile; ?>

                <!-- My Team Box jika anggota -->
                <?php elseif (mysqli_num_rows($result_member_teams) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result_member_teams)): ?>
                        <div class='box'>
                            <h3 class='title'>
                                <?= $row['team_name'] ?>
                                <?php if ($row['status'] == 'Approved') : ?>
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                <?php endif; ?>
                            </h3>
                            <h3 class='tutor'>[<?= $row['member_now'] ?>/<?= $row['member_max'] ?>]</h3>
                            <a href='index_manage_members.php?team_id=<?= $row['team_id'] ?>' class='inline-btn'>View Team</a>
                        </div>
                    <?php endwhile; ?>

                <!-- Create Team Box jika belum ada tim -->
                <?php else: ?>
                    <div class='box'>
                        <h3 class='title'>Create Team</h3>
                        <p class='tutor'>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <a href='index_create_team.php' class='inline-btn'>Create New Team</a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class='box'>
                    <h3 class='title'>Silahkan <a href='index_login.php' style="color:green;">login</a> untuk akses lebih banyak fitur</h3>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Team List -->
    <section class="courses">
        <h1 class="heading">Team List</h1>
        <div class="box-container">
            <?php
            $team_count = 0; // Variable to count the number of teams displayed
            if (mysqli_num_rows($result_not_my_teams) > 0):
                while ($row = mysqli_fetch_assoc($result_not_my_teams)):
                    if ($team_count >= 3) break; // Stop the loop if 3 teams have been displayed
            ?>
                    <div class='box'>
                        <div class='tutor'>
                            <div class='info'>
                                <span><?= $row['pkm_type'] ?></span>
                                <h3>
                                    <?= $row['team_name'] ?> 
                                    <?php if ($row['status'] == 'Approved') : ?>
                                        <i class="fa fa-check-circle" aria-hidden="true" style="color: green;"></i>
                                    <?php elseif ($row['status'] == 'Not Approved') : ?>
                                        <i class="fa fa-times-circle" aria-hidden="true" style="color: red;"></i>
                                    <?php endif; ?>
                                </h3>
                                <span><?= $row['member_now'] ?>/<?= $row['member_max'] ?></span>
                                <br><br>
                                <h4 class='title'><?= $row['description'] ?></h4>
                            </div>
                        </div>
                        <a href='index_manage_members.php?team_id=<?= $row['team_id'] ?>' class='inline-btn'>View Team</a>
                    </div>
            <?php
                    $team_count++; // Increment the count
                endwhile;
            else:
            ?>
                <h1 class='heading'>Tidak ada tim tersedia.</h1>
            <?php endif; ?>
        </div>
        <?php if (mysqli_num_rows($result_not_my_teams) === 1): ?>
            <div class='more-btn'></div>
        <?php endif; ?>
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
