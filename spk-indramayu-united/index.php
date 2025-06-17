<?php
include 'functions.php';
if (empty($_SESSION['login']))
  header("location:login.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Sistem Pendukung Keputusan (SPK) Metode Analytical Hierarchy Proccess(AHP) dan Technique For Others Reference by Similarity to Ideal Solution (TOPSIS) berbasis web dengan PHP dan MySQL." />
  <meta name="keywords" content="Sistem Pendukung Keputusan, Decision Support System, Analytical Hierarchy Proccess, AHP, Technique For Others Reference by Similarity to Ideal Solution, TOPSIS, Seleksi Pemain" />
  <link rel="icon" href="favicon.ico" />

  <title> SPK INDRAMAYU UNITED</title>
  <link href="assets/css/flatly-bootstrap.min.css" rel="stylesheet" />
  <link href="assets/css/general.css" rel="stylesheet" />
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
</head>

<body>
  <nav class="navbar navbar-default navbar-static-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="?m=home&home=<?= get('home') ?>"><span class="glyphicon glyphicon-list-alt"></span>Beranda</a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
  <?php if ($_SESSION['level'] != '3') : // Menu untuk Pelatih dan Manajer ?>
    <li class="<?= isActive(['periode', 'periode_tambah', 'periode_ubah']) ?>"><a href="?m=periode"><span class="glyphicon glyphicon-list-alt"></span> Lini</a></li>
    <li class="<?= isActive(['kriteria', 'rel_kriteria', 'kriteria_tambah', 'kriteria_ubah']) ?>" class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-th-large"></span> Kriteria <span class="caret"></span></a>
      <ul class="dropdown-menu" role="menu">
        <li><a href="?m=kriteria&periode=<?= get('periode') ?>"><span class="glyphicon glyphicon-th-large"></span> Kriteria</a></li>
        <li><a href="?m=rel_kriteria&periode=<?= get('periode') ?>"><span class="glyphicon glyphicon-th-list"></span> Nilai bobot kriteria</a></li>
      </ul>
    </li>
    <li class="<?= isActive(['alternatif', 'rel_alternatif', 'alternatif_tambah', 'alternatif_ubah', 'rel_alternatif_ubah']) ?>" class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-user"></span> Pemain <span class="caret"></span></a>
      <ul class="dropdown-menu" role="menu">
        <li><a href="?m=alternatif&periode=<?= get('periode') ?>"><span class="glyphicon glyphicon-user"></span> Alternatif Pemain</a></li>
        <li><a href="?m=rel_alternatif&periode=<?= get('periode') ?>"><span class="glyphicon glyphicon-signal"></span> Nilai bobot alternatif</a></li>
      </ul>
    </li>
    <li class="<?= isActive('hitung') ?>"><a href="?m=hitung&periode=<?= get('periode') ?>"><span class="glyphicon glyphicon-calendar"></span> Perhitungan</a></li>
  <?php endif; ?>

  <?php if ($_SESSION['level'] == '3') : // Menu khusus Admin ?>
    <li class="<?= isActive(['akun', 'akun_tambah', 'akun_ubah']) ?>"><a href="?m=akun"><span class="glyphicon glyphicon-user"></span> Akun</a></li>
  <?php endif; ?>
  
  <li class="<?= isActive('password') ?>"><a href="?m=password"><span class="glyphicon glyphicon-lock"></span> Password</a></li>
  <li><a href="aksi.php?act=logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
</ul>
        <div class="navbar-text"></div>
      </div></div>
  </nav>

  <div class="container">
    <?php
    // --- TAMBAHKAN KODE INI ---
    if (isset($_SESSION['message'])) {
        print_msg($_SESSION['message']['text'], $_SESSION['message']['type']); //
        unset($_SESSION['message']);
    }
    // -------------------------

    // ===== AWAL KODE KONTROL AKSES HALAMAN =====
$allowed_modules = [];
$mod = get('m');
$level = $_SESSION['level'];

// Default redirect ke home jika akses ditolak
$redirect_page = 'index.php?m=home';

if ($level == '1') { // Pelatih
    $allowed_modules = ['home', 'periode', 'periode_tambah', 'periode_ubah', 'kriteria', 'kriteria_tambah', 'kriteria_ubah', 'rel_kriteria', 'alternatif', 'alternatif_tambah', 'alternatif_ubah', 'rel_alternatif', 'rel_alternatif_ubah', 'hitung', 'password'];
    if($mod == 'akun' || $mod == 'akun_tambah' || $mod == 'akun_ubah'){
        print_msg('Anda tidak memiliki hak akses ke halaman ini.', 'danger');
        $mod = 'home'; // alihkan ke halaman home
    }
} else if ($level == '2') { // Manajer
    $allowed_modules = ['home', 'periode', 'kriteria', 'rel_kriteria', 'alternatif', 'rel_alternatif', 'hitung', 'password'];
     if($mod == 'akun' || $mod == 'akun_tambah' || $mod == 'akun_ubah'){
        print_msg('Anda tidak memiliki hak akses ke halaman ini.', 'danger');
        $mod = 'home'; // alihkan ke halaman home
    }
} else if ($level == '3') { // Admin
    $allowed_modules = ['home', 'akun', 'akun_tambah', 'akun_ubah', 'password'];
    if($mod && !in_array($mod, $allowed_modules)){
         print_msg('Anda tidak memiliki hak akses ke halaman ini.', 'danger');
         $mod = 'home'; // alihkan ke halaman home
    }
    $redirect_page = 'index.php?m=akun'; // admin default ke akun
}

if ($mod && !in_array($mod, $allowed_modules) && $mod != 'home') {
    print_msg('Anda tidak memiliki hak akses ke halaman ini!', 'danger');
    include 'home.php';
} else if (file_exists($mod . '.php')) {
      if (!in_array($mod, ['periode', 'periode_cetak', 'periode_tambah', 'periode_ubah'])) {
        // cek periode
        if (is_null(get('periode'))) {
          $row = $db->get_row("SELECT * FROM tb_periode order by tahun desc limit 1");
          if (is_null($row)) {
            // jika periode belum ada
            redirect_js("index.php?m=periode");
          } else {
            // lempar jika periode tidak valid ke periode terbaru
            redirect_js("index.php?m=$mod&periode=$row->tahun");
          }
          die;
        }

        // jika parameter periode ada
        $row = $db->get_row("SELECT * FROM tb_periode WHERE tahun='" . get('periode') . "'");
        if (is_null($row)) {
          // jika periode tidak valid
          $row = $db->get_row("SELECT * FROM tb_periode order by tahun desc limit 1");

          if (is_null($row)) {
            // jika periode belum ada
            redirect_js("index.php?m=periode");
          } else {
            // lempar jika periode tidak valid ke periode terbaru
            redirect_js("index.php?m=$mod&periode=$row->tahun");
          }
        }
      }

      $PERIODE = get('periode');
      include $mod . '.php';
    } else {
      include 'home.php';
    }
    ?>
  </div>
  <footer class="footer bg-primary">
    <div class="container">
      <p>&copy; <?= date('Y') ?> || Indramayu United</a> <em class="pull-right"></em></p>
    </div>
  </footer>
</body>

</html>