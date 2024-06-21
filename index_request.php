<?php
session_start();

if (!isset($_SESSION['user_id'])) :
    header("Location: index_login.php");
    exit();
endif;

include 'config.php';

// Mendapatkan data dari session
$nama = $_SESSION['nama'];
$nim_nid = $_SESSION['nim_nid'];

// Mendapatkan daftar undangan untuk pengguna saat ini
$user_id = $_SESSION['user_id'];
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

// Tambahkan pengecekan apakah query berhasil dijalankan
if ($result_requests === false) :
    echo "Error: " . mysqli_error($koneksi);
    exit();
endif;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Join Request List</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>

<header class="header">
    <section class="flex">
        <a href="dashboard.php" class="logo">
            <img src="images/upnvj.png" alt="Logo" /> PKM
        </a>

        <form action="search.html" method="post" class="search-form">
            <input type="text" name="search_box" required placeholder="search courses..." maxlength="100" />
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
            <!-- <a href="profile.html" class="btn">view profile</a> -->
            <div class="flex-btn">
                <a href="logout.php" class="option-btn">Logout</a>
            </div>
        </div>
    </section>
</header>

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
        <a href="dashboard.php"><i class="fas fa-home"></i><span>home</span></a>
        <a href="teams.php"><i class="fas fa-graduation-cap"></i><span>Teams</span></a>
        <a href="teachers.html"><i class="fas fa-chalkboard-user"></i><span>teachers</span></a>
    </nav>
</div>

<section class="courses">
    <h1 class="heading">requests</h1>

    <div class="box-container">
        <?php if (mysqli_num_rows($result_requests) > 0) : ?>
            <?php while ($row = mysqli_fetch_assoc($result_requests)) : ?>
                <div class="box">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 70%;">
                                <div class="tutor">
                                    <div class="info">
                                        <h3><?php echo $row['nama']; ?></h3>
                                        <span>[<?php echo $row['nim_nid']; ?>]</span>
                                    </div>
                                </div>
                            </td>
                            <td style="width: 30%; text-align: right;">
                                <a href="accept_request.php?join_id=<?php echo $row['join_id']; ?>" class="inline-btn" style="width:20rem;">Accept Request</a>
                                <a href="decline_request.php?join_id=<?php echo $row['join_id']; ?>" class="inline-btn" style="width:20rem;">Decline Request</a>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <h1 class="heading">No requests found.</h1>
        <?php endif; ?>
    </div>

    <?php if (mysqli_num_rows($result_requests) === 1) : ?>
        <div class="more-btn">
            <!-- <a href="teams.php" class="inline-option-btn">view all teams</a> -->
        </div>
    <?php endif; ?>
</section>

<!-- Custom JavaScript -->
<script src="js/script.js"></script>

</body>
</html>
<?php
// Menutup koneksi database
mysqli_close($koneksi);
?>
