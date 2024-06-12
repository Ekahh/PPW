<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>login</title>

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
        <a href="dashboard.php" class="logo">
          <img src="images/upnvj.png" alt="Logo" /> Pekaem
        </a>

        <div class="icons">
          <!-- <div id="menu-btn" class="fas fa-bars"></div> -->
          <div id="search-btn" class="fas fa-search"></div>
          <!-- <div id="user-btn" class="fas fa-user"></div> -->
          <div id="toggle-btn" class="fas fa-sun"></div>
        </div>

      </section>
    </header>

    <section class="form-container">
      <form action="login.php" method="POST" enctype="multipart/form-data">
        <h3>login</h3>
        <p for="email">Email <span>*</span></p>
        <input
          type="email"
          id="email"
          name="email"
          required
          placeholder="Masukkan email"
          maxlength="50"
          class="box"
        /><!-- seharusnya ada required di insert email dan password -->

        <p for="email">Password <span>*</span></p>
        <input
          type="password"
          id="password"
          name="password"
          required
          placeholder="Masukkan password"
          maxlength="20"
          class="box"
        /><!-- seharusnya ada required di insert email dan password -->
        <input
          type="submit"
          value="login"
          name="submit"
          class="btn"
        />
      </form>
      <input
          type="submit"
          value="daftar"
          name="submit"
          class="btn"
          formaction="register.php"
        />
    </section>

    <footer class="footer-login">
      &copy;copyright @2024 by <span>Kelompok Ojan</span> | all rights reserved!
    </footer>

    <!-- custom js file link  -->
    <script src="js/script.js"></script>
  </body>
</html>
