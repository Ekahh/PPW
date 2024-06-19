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

    // Mendapatkan daftar anggota tim
    $sql_members = "SELECT * FROM users 
                    JOIN members ON users.user_id = members.user_id 
                    WHERE members.team_id = '$team_id'";
    $result_members = mysqli_query($koneksi, $sql_members);

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>video playlist</title>

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
          <img src="images/upnvj.png" alt="Logo" /> PKM
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
          <!-- <a href="profile.html" class="btn">View Profile</a> -->
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
            <button type="submit"><span>PKM-KC</span></button>
          </form>

          <!-- <div class="thumb">
            <img src="images/thumb-1.png" alt="" />
            <span>4/5</span>
          </div> -->
          <!-- <div class="tutor">
            <img src="images/pic-2.jpg" alt="" />
            <div>
              <h3>nama dosen</h3>
              <span>dosen pembimbing</span>
            </div>
          </div> -->

          <div class="details">
            <h3>judul pkm</h3>
            <p>
              Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illum
              minus reiciendis, error sunt veritatis exercitationem deserunt
              velit doloribus itaque voluptate.
            </p>
            <a href="playlist.html" class="inline-btn">Request to join team</a>
          </div>
        </div>
      </div>
    </section>

    <!-- <section class="playlist-videos">
      <h1 class="heading">Dosen Pemimbing</h1>

      <div class="box-container">
        <div class="box">
          <table class="table">
            <thead>
              <tr>
                <th>NID</th>
                <th>Nama</th>
                <th>Info Dosen</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>0031089303</td>
                <td>Muhammad Panji Muslim</td>
                <td>
                  <a
                    href="https://new-fik.upnvj.ac.id/teams/muhammad-panji-muslim-s-pd-m-kom/"
                    class="inline-btn"
                    >Info lebih lanjut</a
                  >
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section> -->

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
                <!-- <th>Profile</th> -->
              </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result_members) > 0) {
                    while ($member = mysqli_fetch_assoc($result_members)) {
                        echo "<tr>";
                        echo "<td>" . $member['role'] . "</td>";
                        echo "<td>" . $member['nim_nid'] . "</td>";
                        echo "<td>" . $member['nama'] . "</td>";
                        echo "<td>" . $member['tahun_angkatan'] . "</td>";
                        // echo "<td><a href='profile.php?user_id=" . $member['user_id'] . "' class='inline-btn'>View Profile</a></td>";
                        echo "</tr>";
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


    <!-- custom js file link  -->
    <script src="js/script.js"></script>
  </body>
</html>
