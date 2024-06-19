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

// Mendapatkan nilai dari URL jika tersedia
$team_id = isset($_GET['team_id']) ? $_GET['team_id'] : '';
$new_title = isset($_GET['new_title']) ? $_GET['new_title'] : '';
$new_desc = isset($_GET['new_desc']) ? $_GET['new_desc'] : '';
$new_pkm_type = isset($_GET['new_pkm_type']) ? $_GET['new_pkm_type'] : '';

// Mengambil data lama dari database jika tersedia
if (!empty($team_id)) {
    $sql = "SELECT team_name, description FROM teams WHERE team_id = '$team_id'";
    $result = mysqli_query($koneksi, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $team_name = $row['team_name'];
        $desc = $row['description'];
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <?php
        if(isset($_COOKIE["message"])) {
            echo "<script>alert('" . $_COOKIE["message"] . "')</script>";
            setcookie("message", "", time()-3600); // Hapus cookie pesan
        }
    ?>

    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Update Team</title>

    <!-- font awesome cdn link  -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
    />

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <!-- Header -->
    <header class="header">
        <section class="flex">
            <a href="dashboard.php" class="logo">
                <img src="images/upnvj.png" alt="Logo" /> PKM
            </a>

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
                <!-- <a href="profile.html" class="btn">View Profile</a> -->
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

    <section class="form-container">
      <form action="update_team.php" method="post">
        <h3>Update Team</h3>
        <input type="hidden" name="team_id" value="<?php echo $team_id; ?>">
        <p>Judul <span>*</span></p>
        <input
          type="text"
          name="new_title"
          required
          placeholder="Masukkan Judul"
          value="<?php echo $new_title ? $new_title : $team_name; ?>"
          maxlength="50"
          class="box"
        /><!-- seharusnya ada required di sini -->

        <p>Deskripsi <span>*</span></p>
        <textarea rows="4" cols="50" value="<?php echo $new_desc ? $new_desc : $desc; ?>" placeholder="Masukkan Deskripsi" name="new_desc" class="box"></textarea>
        <!-- seharusnya ada required di sini -->

        <div class="form-group">
          <p>Jenis Peran <span>*</span></p>
          <select id="peran" name="peran" required class="box">
            <option value="PKM-K" <?php if ($new_pkm_type == 'PKM-K') echo 'selected'; ?>>PKM-K</option>
            <option value="PKM-PM" <?php if ($new_pkm_type == 'PKM-PM') echo 'selected'; ?>>PKM-PM</option>
            <option value="PKM-KC" <?php if ($new_pkm_type == 'PKM-KC') echo 'selected'; ?>>PKM-KC</option>
            <option value="PKM-GFK" <?php if ($new_pkm_type == 'PKM-GFK') echo 'selected'; ?>>PKM-GFK</option>
          </select>
        </div>
        <!-- seharusnya ada required di sini -->

        <input type="submit" name="update" class="btn" />
      </form>
    </section>

    <!-- custom js file link  -->
    <script src="js/script.js"></script>
  </body>
</html>
