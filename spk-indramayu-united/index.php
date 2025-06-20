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
  <link rel="icon" href="favicon.ico" />

  <title>SPK INDRAMAYU UNITED</title>
  <link href="assets/css/flatly-bootstrap.min.css" rel="stylesheet" />
  <link href="assets/css/general.css" rel="stylesheet" />
  <link href="assets/css/custom.css" rel="stylesheet" />
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
        <a class="navbar-brand" href="?m=home">Beranda</a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <?php if ($_SESSION['level'] != '3') : // Menu untuk Pelatih (1) dan Manajer (2) ?>
            <li class="<?= isActive(['periode', 'periode_tambah', 'periode_ubah']) ?>"><a href="?m=periode"><span class="glyphicon glyphicon-list-alt"></span> Lini</a></li>
            <li class="<?= isActive(['kriteria', 'rel_kriteria', 'kriteria_tambah', 'kriteria_ubah']) ?>" class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-th-large"></span> Kriteria <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="?m=kriteria&periode=<?= get('periode') ?>"><span class="glyphicon glyphicon-th-large"></span> Data Kriteria</a></li>
                <li><a href="?m=rel_kriteria&periode=<?= get('periode') ?>"><span class="glyphicon glyphicon-th-list"></span> Nilai Bobot Kriteria</a></li>
              </ul>
            </li>
            <li class="<?= isActive(['alternatif', 'rel_alternatif', 'alternatif_tambah', 'alternatif_ubah', 'rel_alternatif_ubah']) ?>" class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-user"></span> Pemain <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="?m=alternatif&periode=<?= get('periode') ?>"><span class="glyphicon glyphicon-user"></span> Data Pemain</a></li>
                <li><a href="?m=rel_alternatif&periode=<?= get('periode') ?>"><span class="glyphicon glyphicon-signal"></span> Nilai Data Pemain</a></li>
              </ul>
            </li>
            <li class="<?= isActive('hitung') ?>"><a href="?m=hitung&periode=<?= get('periode') ?>"><span class="glyphicon glyphicon-calendar"></span> Perhitungan</a></li>
          <?php endif; ?>

          <?php if ($_SESSION['level'] == '3') : // Menu khusus Admin ?>
            <li class="<?= isActive(['akun', 'akun_tambah', 'akun_ubah']) ?>"><a href="?m=akun"><span class="glyphicon glyphicon-user"></span> Kelola Akun</a></li>
          <?php endif; ?>
          
          <li class="<?= isActive('profil') ?>"><a href="?m=profil"><span class="glyphicon glyphicon-edit"></span> Kelola Profil</a></li>
          <li><a href="aksi.php?act=logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">
    <?php
    if (isset($_SESSION['message'])) {
        print_msg($_SESSION['message']['text'], $_SESSION['message']['type']);
        unset($_SESSION['message']);
    }

    $mod = get('m');
    $level = $_SESSION['level'];

    $allowed_modules = [];
    if ($level == '1') { // Pelatih
        $allowed_modules = ['home', 'periode', 'periode_tambah', 'periode_ubah', 'kriteria', 'kriteria_tambah', 'kriteria_ubah', 'rel_kriteria', 'alternatif', 'alternatif_tambah', 'alternatif_ubah', 'rel_alternatif', 'rel_alternatif_ubah', 'hitung', 'profil'];
    } else if ($level == '2') { // Manajer
        $allowed_modules = ['home', 'periode', 'kriteria', 'rel_kriteria', 'alternatif', 'rel_alternatif', 'hitung', 'profil'];
    } else if ($level == '3') { // Admin
        $allowed_modules = ['home', 'akun', 'akun_tambah', 'akun_ubah', 'profil'];
    }

    if ($mod && !in_array($mod, $allowed_modules)) {
        print_msg("Anda tidak memiliki hak akses untuk membuka halaman ini!", "danger");
        $mod = 'home'; 
    }
    
    if ($mod && file_exists($mod . '.php')) {
        // Daftar halaman yang bergantung pada periode
        $periode_related_pages = ['kriteria', 'rel_kriteria', 'kriteria_tambah', 'kriteria_ubah', 'alternatif', 'rel_alternatif', 'alternatif_tambah', 'alternatif_ubah', 'rel_alternatif_ubah', 'hitung'];
        
        if (in_array($mod, $periode_related_pages)) {
            // --- INILAH BAGIAN YANG DIPERBAIKI ---
            // Cek apakah parameter periode kosong atau tidak ada sama sekali
            if (!get('periode')) { 
                $latest_periode = $db->get_row("SELECT * FROM tb_periode ORDER BY tahun DESC LIMIT 1");
                if ($latest_periode) {
                    // Arahkan ke URL yang benar dengan periode terbaru
                    redirect_js("index.php?m=$mod&periode=$latest_periode->tahun");
                    exit; // Hentikan eksekusi untuk menunggu redirect
                } else {
                    // Jika belum ada periode sama sekali
                    print_msg("Belum ada data lini (periode). Silahkan tambahkan terlebih dahulu.", "warning");
                    include 'periode.php';
                    exit; // Hentikan agar tidak terjadi error
                }
            }
            // Tetapkan variabel $PERIODE jika valid
            $PERIODE = get('periode');
        }
        
        include $mod . '.php';
    } else {
        include 'home.php';
    }
    ?>
  </div>
  <footer class="footer bg-primary">
    <div class="container">
      <p>&copy; <?= date('Y') ?> || Indramayu United <em class="pull-right"></em></p>
    </div>
  </footer>
</body>
</html>