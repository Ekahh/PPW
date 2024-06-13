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

    // Mendapatkan daftar semua tim
    $sql_all_teams = "SELECT * FROM teams";
    $result_all_teams = mysqli_query($koneksi, $sql_all_teams);
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
  <body>

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
        <a href="index_invitation.php"
          ><i class="fas fa-chalkboard-user"></i><span>Invitation</span></a
        >
      </nav>
    </div>

    <section class="courses">
        <h1 class="heading">
            Team List
            <!-- <a href="teams.php" class="inline-option-btn">&gt;</a> -->
        </h1>

        <div class="box-container">

            <?php
            $num_rows = mysqli_num_rows($result_all_teams);
            if ($num_rows > 0) {
                while ($row = mysqli_fetch_assoc($result_all_teams)) {
                    $is_leader = ($_SESSION['user_id'] == $row['leader_id']);
                    echo "<div class='box'>";
                    echo "<div class='tutor'>";
                    echo "<div class='info'>";
                    echo "<h3>" . $row['team_name'] . "</h3>";
                    echo "<span>" . $row['member_now'] . "/" . $row['member_max'] . "</span>";
                    echo "</div>";
                    echo "</div>";
                    echo "<h3 class='title'>" . $row['description'] . "</h3>";
                    if ($is_leader) {
                      echo "<a href='index_manage_members.php?team_id=" . $row['team_id'] . "' class='inline-btn'>manage members</a>";
                    } else {
                      echo "<a href='index_members.php?team_id=" . $row['team_id'] . "' class='inline-btn'>view members</a>";
                    }
                    echo "</div>";
                }
                
            } else {
                echo "<h1 class='heading'>There are no teams available.</h1>";
            }
            

            echo "<div class='more-btn'>";

            // if ($num_rows !== 1) {
            //   echo "<a href='teams.php' class='inline-option-btn'>view all teams</a>";
            //   echo "</div>";
            // }
            ?>
        </div>
        <?php
        if ($num_rows === 1) {
            echo "<div class='more-btn'>";
            // echo "<a href='teams.php' class='inline-option-btn'>view all teams</a>";
            echo "</div>";
        }
        ?>
    </section>

    <!-- custom js file link  -->
    <script src="js/script.js"></script>
  </body>
</html>
