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
  <link rel="icon" href="logo.png" />

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
            <li class="<?= isActive(['lini', 'lini_tambah', 'lini_ubah']) ?>"><a href="?m=lini"><span class="glyphicon glyphicon-flag"></span> Lini</a></li>
            <li class="<?= isActive(['penilaian', 'penilaian_tambah', 'penilaian_ubah']) ?>"><a href="?m=penilaian"><span class="glyphicon glyphicon-calendar"></span> Sesi Penilaian</a></li>
            <li class="<?= isActive(['kriteria', 'rel_kriteria', 'kriteria_tambah', 'kriteria_ubah']) ?>" class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-th-large"></span> Kriteria <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="?m=kriteria&id_lini=<?= get('id_lini') ?>"><span class="glyphicon glyphicon-th-large"></span> Data Kriteria</a></li>
                <li><a href="?m=rel_kriteria&id_lini=<?= get('id_lini') ?>"><span class="glyphicon glyphicon-th-list"></span> Nilai Bobot Kriteria</a></li>
              </ul>
            </li>
            <li class="<?= isActive(['alternatif', 'rel_alternatif', 'alternatif_tambah', 'alternatif_ubah', 'rel_alternatif_ubah']) ?>" class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-user"></span> Pemain <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="?m=alternatif&id_lini=<?= get('id_lini') ?>"><span class="glyphicon glyphicon-user"></span> Data Pemain</a></li>
                <li><a href="?m=rel_alternatif&id_lini=<?= get('id_lini') ?>"><span class="glyphicon glyphicon-signal"></span> Nilai Data Pemain</a></li>
              </ul>
            </li>
            <li class="<?= isActive('hitung') ?>"><a href="?m=hitung&id_lini=<?= get('id_lini') ?>"><span class="glyphicon glyphicon-stats"></span> Skor Akhir</a></li>
            <li class="<?= isActive(['laporan', 'pilih_cetak']) ?>" class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
        <span class="glyphicon glyphicon-print"></span> Laporan & Cetak <span class="caret"></span>
    </a>
    <ul class="dropdown-menu" role="menu">
        <li><a href="?m=laporan"><span class="glyphicon glyphicon-file"></span> Laporan Hasil Perhitungan</a></li>
        <li><a href="?m=pilih_cetak&mod=alternatif"><span class="glyphicon glyphicon-user"></span> Cetak Data Pemain</a></li>
    </ul>
</li>
            <?php endif; ?>

          <?php if ($_SESSION['level'] == '3') : // Menu khusus Admin ?>
            
            <li class="<?= isActive(['akun', 'akun_tambah', 'akun_ubah']) ?>"><a href="?m=akun"><span class="glyphicon glyphicon-user"></span> Kelola Akun</a></li>
          <?php endif; ?>
          
          <li class="<?= isActive('profil') ?>"><a href="?m=profil"><span class="glyphicon glyphicon-edit"></span> Kelola Profil</a></li>
          <li><a href="aksi.php?act=logout" onclick="return confirm('Apakah Anda yakin ingin logout?')"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
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
        $allowed_modules = ['home', 'lini', 'lini_tambah', 'lini_ubah', 'kriteria', 'kriteria_tambah', 'kriteria_ubah', 'rel_kriteria', 'alternatif', 'alternatif_tambah', 'alternatif_ubah', 'rel_alternatif', 'rel_alternatif_ubah', 'hitung', 'laporan', 'pilih_cetak', 'profil', 'penilaian', 'penilaian_tambah', 'penilaian_ubah'];
    } else if ($level == '2') { // Manajer
        $allowed_modules = ['home', 'lini', 'penilaian', 'kriteria', 'rel_kriteria', 'alternatif', 'rel_alternatif', 'hitung', 'laporan', 'pilih_cetak', 'profil'];
    } else if ($level == '3') { // Admin
        $allowed_modules = ['home', 'akun', 'akun_tambah', 'akun_ubah', 'profil'];
    }

    if ($mod && !in_array($mod, $allowed_modules)) {
        print_msg("Anda tidak memiliki hak akses untuk membuka halaman ini!", "danger");
        $mod = 'home'; 
    }
    
    if ($mod && file_exists($mod . '.php')) {
        // Daftar halaman yang bergantung pada lini
        $lini_related_pages = ['penilaian', 'penilaian_tambah', 'penilaian_ubah', 'kriteria', 'rel_kriteria', 'kriteria_tambah', 'kriteria_ubah', 'alternatif', 'rel_alternatif', 'alternatif_tambah', 'alternatif_ubah', 'rel_alternatif_ubah', 'hitung'];
        
        if (in_array($mod, $lini_related_pages)) {
            // --- INILAH BAGIAN YANG DIPERBAIKI ---
            // Cek apakah parameter lini kosong atau tidak ada sama sekali
            if (!get('id_lini')) { 
                $latest_lini = $db->get_row("SELECT * FROM tb_lini ORDER BY id_lini DESC LIMIT 1");
                if ($latest_lini) {
                    // Arahkan ke URL yang benar dengan lini terbaru
                    redirect_js("index.php?m=$mod&id_lini=$latest_lini->id_lini");
                    exit; // Hentikan eksekusi untuk menunggu redirect
                } else {
                    // Jika belum ada lini sama sekali
                    print_msg("Belum ada data lini. Silahkan tambahkan terlebih dahulu.", "warning");
                    include 'lini.php';
                    exit; // Hentikan agar tidak terjadi error
                }
            }
            // Tetapkan variabel $ID_LINI jika valid
            $ID_LINI = get('id_lini');
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