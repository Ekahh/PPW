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
    <title>register</title>

    <!-- font awesome cdn link  -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
    />

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <header class="header">
      <section class="flex">
        <a href="home.php" class="logo">
          <img src="images/upnvj.png" alt="Logo" /> Pekaem
        </a>

        <!-- <form action="search.php" method="post" class="search-form">
         <input type="text" name="search_box" required placeholder="search courses..." maxlength="100">
         <button type="submit" class="fas fa-search"></button>
      </form> -->

        <div class="icons">
          <!-- <div id="menu-btn" class="fas fa-bars"></div> -->
          <div id="search-btn" class="fas fa-search"></div>
          <div id="user-btn" class="fas fa-user"></div>
          <div id="toggle-btn" class="fas fa-sun"></div>
        </div>

        <div class="profile">
        <!-- <img src="images/pic-1.jpg" class="image" alt="">
         <h3 class="name">shaikh anas</h3>
         <p class="role">studen</p>
         <a href="profile.php" class="btn">view profile</a> -->
            <div class="flex-btn">
                <a href="login.php" class="option-btn">login</a>
                <a href="index_register.php" class="option-btn">register</a>
            </div>
        </div>
      </section>
    </header>

    <section class="form-container">
      <form action="register.php" method="post" enctype="multipart/form-data">
        <h3>daftar sekarang</h3>
        <p>Nama <span>*</span></p>
        <input
          type="text"
          id="nama"
          name="nama"
          required
          placeholder="Masukkan nama"
          maxlength="50"
          class="box"
        /><!-- seharusnya ada required di sini -->

        <p>NIM/NIDN <span>*</span></p>
        <input
          type="text"
          id="nim_nid"
          name="nim_nid"
          required
          placeholder="Masukkan NIM/NID"
          maxlength="50"
          class="box"
        /><!-- seharusnya ada required di sini -->

        <p>Tahun Angkatan <span>*</span></p>
        <input
          type="number" 
          id="tahun_angkatan" 
          name="tahun_angkatan" 
          required
          placeholder="Masukkan angkatan (contoh: 2022)"
          maxlength="50"
          class="box"
        /><!-- seharusnya ada required di sini -->

        <p>Email <span>*</span></p>
        <input
          type="email" 
          id="email" 
          name="email" 
          required
          placeholder="Masukkan email"
          maxlength="50"
          class="box"
        /><!-- seharusnya ada required di sini -->

        <div class="form-group">
          <p>Peran <span>*</span></p>
          <select id="peran" name="peran" required class="box">
            <option value="mahasiswa">Mahasiswa</option>
          </select>
        </div>
        <!-- seharusnya ada required di sini -->

        <p>Password <span>*</span></p>
        <input
          type="password" 
          id="password" 
          name="password" 
          required
          placeholder="Masukkan password"
          maxlength="20"
          class="box"
        /><!-- seharusnya ada required di sini -->

        <p>Konfirmasi Password <span>*</span></p>
        <input
          type="password"
          name="c_pass"
          placeholder="Konfirmasi password"
          maxlength="20"
          class="box"
        />
        <!-- seharusnya ada required di sini -->

        <!-- <p>select profile <span>*</span></p>
      <input type="file" accept="image/*" required class="box"> -->
        <input type="submit" value="daftar" name="submit" class="btn" />
      </form>
    </section>

    <footer class="footer">
      &copy;copyright @2024 by <span>Kelompok Adnan</span> | all rights reserved!
    </footer>

    <!-- custom js file link  -->
    <script src="js/script.js"></script>
  </body>
</html>
