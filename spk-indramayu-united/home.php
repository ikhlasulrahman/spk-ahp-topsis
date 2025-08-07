<?php
// Mengambil data pengguna yang sedang login
$user_login = $_SESSION['login'];
$user_data = $db->get_row("SELECT * FROM tb_user WHERE user='$user_login'");

$level_map = [
    '1' => 'Pelatih',
    '2' => 'Manajer',
    '3' => 'Admin'
];
$user_level_name = $level_map[$user_data->level] ?? 'Tidak Diketahui';

// Mengambil data statistik
$total_lini = $db->get_var("SELECT COUNT(*) FROM tb_lini");
$total_pemain = $db->get_var("SELECT COUNT(*) FROM tb_alternatif");
?>

<style>
    .welcome-banner {
        background-color: #2c3e50;
        color: #fff;
        padding: 30px 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .welcome-banner h1 {
        margin: 0;
        font-size: 2.5em;
        font-weight: 700;
    }
    .welcome-banner p {
        font-size: 1.2em;
        margin-top: 5px;
        color: #ecf0f1;
    }
    .stat-card {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        border-left: 5px solid #18bc9c;
    }
    .stat-card .stat-icon {
        font-size: 3em;
        color: #18bc9c;
        margin-bottom: 10px;
    }
    .stat-card .stat-number {
        font-size: 2.5em;
        font-weight: 700;
        color: #2c3e50;
    }
    .stat-card .stat-title {
        font-size: 1.1em;
        color: #7b8a8b;
    }
    .user-info-card {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 5px solid #3498db;
    }
     .user-info-card h3 {
        margin-top: 0;
        color: #2c3e50;
        border-bottom: 1px solid #ecf0f1;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }
     .user-info-card p {
        font-size: 1.1em;
        margin-bottom: 10px;
    }
    .user-info-card .glyphicon{
        margin-right: 8px;
        color: #3498db;
    }
</style>

<div class="welcome-banner">
    <h1>Selamat Datang, <?= htmlspecialchars($user_data->nama) ?>!</h1>
    <p>Anda login sebagai <?= htmlspecialchars($user_level_name) ?> di Sistem Pendukung Keputusan Indramayu United.</p>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon">
                <span class="glyphicon glyphicon-list-alt"></span>
            </div>
            <div class="stat-number"><?= $total_lini ?></div>
            <div class="stat-title">Total Lini Posisi</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon">
                <span class="glyphicon glyphicon-user"></span>
            </div>
            <div class="stat-number"><?= $total_pemain ?></div>
            <div class="stat-title">Total Pemain Terdaftar</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="user-info-card">
            <h3><span class="glyphicon glyphicon-info-sign"></span> Informasi Akun</h3>
            <p><span class="glyphicon glyphicon-user"></span><strong>Nama:</strong> <?= htmlspecialchars($user_data->nama) ?></p>
            <p><span class="glyphicon glyphicon-tag"></span><strong>Username:</strong> <?= htmlspecialchars($user_data->user) ?></p>
            <p><span class="glyphicon glyphicon-briefcase"></span><strong>Level:</strong> <?= htmlspecialchars($user_level_name) ?></p>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><span class="glyphicon glyphicon-star"></span> Akses Cepat</h3>
    </div>
    <div class="panel-body">
        <p>Gunakan menu navigasi di atas untuk mulai mengelola data:</p>
        <ul>
            <li><b>Lini:</b> Untuk mengelola lini posisi yang akan diseleksi (misal: Kiper, Bek).</li>
            <li><b>Kriteria:</b> Untuk mengatur kriteria penilaian pada setiap lini.</li>
            <li><b>Pemain:</b> Untuk mengelola data pemain yang menjadi alternatif seleksi.</li>
            <li><b>Skor Akhir:</b> Untuk melihat hasil perhitungan dan perankingan pemain.</li>
            <li><b>Laporan:</b> Untuk mencetak hasil akhir seleksi.</li>
        </ul>
    </div>
</div>