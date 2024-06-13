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

    // Mendapatkan daftar tim yang dibuat oleh pengguna
    $sql_my_teams = "SELECT * FROM teams WHERE leader_id = '$user_id'";
    $result_my_teams = mysqli_query($koneksi, $sql_my_teams);

    // Mendapatkan daftar semua tim
    $sql_all_teams = "SELECT * FROM teams";
    $result_all_teams = mysqli_query($koneksi, $sql_all_teams);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Buat Tim</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

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
        <a href="teachers.html"
          ><i class="fas fa-chalkboard-user"></i><span>teachers</span></a
        >
      </nav>
    </div>

   <section class="form-container">
      <!-- <?php if (!$has_team) : ?> -->
      <form action="create_team.php" method="post" enctype="multipart/form-data">
         <h3>Buat Tim</h3>
         <p>Judul</p>
         <input type="text" id="team_name" name="team_name" required placeholder="Masukkan Judul" maxlength="100" class="box">
         <p>Deskripsi</p>
         <textarea id="desc" rows="4" cols="50" placeholder="Masukkan Deskripsi" name="desc" class="box"></textarea>
         <p>Jenis PKM<span></span></p>
         <select id="pkm_type" name="pkm_type" required class="box">
               <option value="PKM-K">PKM-K</option>
               <option value="PKM-PM">PKM-PM</option>
               <option value="PKM-KC">PKM-KC</option>
               <option value="PKM-GFK">PKM-GFK</option>
         </select>
         <p>Max Anggota</p>
         <input type="number" id="member_max" name="member_max" required min="1" max="5" placeholder="Masukkan Jumlah Maksimal Anggota" class="box">
         <input type="submit" value="Buat Tim" name="submit" class="btn">
      </form>
      <?php else : ?>
      <p>You already have a team. You cannot create more than one team.</p>
      <?php endif; ?>
   </section>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>
</html>
