<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index_login.php");
    exit();
}

include 'config.php';

// Mendapatkan data dari session
$nama = $_SESSION['nama'];
$nim_nid = $_SESSION['nim_nid'];

// Mendapatkan daftar undangan untuk pengguna saat ini
$user_id = $_SESSION['user_id'];
$sql_invitations = "SELECT invitations.invite_id, teams.team_id, teams.team_name, teams.member_now, teams.member_max, teams.description FROM invitations JOIN teams ON invitations.team_id = teams.team_id WHERE invitations.user_id = '$user_id'";
$result_invitations = mysqli_query($koneksi, $sql_invitations);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invitations</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>

<header class="header">
    <section class="flex">
        <a href="dashboard.php" class="logo">
            <img src="images/upnvj.png" alt="Logo" /> Pekaem
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
            <a href="profile.html" class="btn">view profile</a>
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
    <h1 class="heading">Invitations</h1>

    <div class="box-container">
        <?php
          if (mysqli_num_rows($result_invitations) > 0) {
            while ($row = mysqli_fetch_assoc($result_invitations)) {
                echo "<div class='box'>";
                echo "<table style='width: 100%;'>";
                echo "<tr>";
                echo "<td style='width: 70%;'>";
                echo "<div class='tutor'>";
                echo "<div class='info'>";
                echo "<h3>" . $row['team_name'] . "</h3>";
                echo "<span>" . $row['member_now'] . "/" . $row['member_max'] . "</span>";
                echo "<h3 class='title'>" . $row['description'] . "</h3>";
                echo "</div>";
                echo "</div>";
                echo "</td>";
                echo "<td style='width: 30%; text-align: right;'>";
                echo "<a href='accept_invite.php?invite_id=" . $row['invite_id'] . "' class='inline-btn'>Accept Invitation</a>";
                echo "<a href='decline_invite.php?invite_id=" . $row['invite_id'] . "' class='inline-btn'>Decline Invitation</a>";
                echo "<a href='view_members.php?team_id=" . $row['team_id'] . "' class='inline-btn'>View Members</a>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
                echo "</div>";
            }
        } else {
            echo "<h1 class='heading'>No invitations found.</h1>";
        }
      ?>
    </div>
</section>

<!-- Custom JavaScript -->
<script src="js/script.js"></script>

</body>
</html>
