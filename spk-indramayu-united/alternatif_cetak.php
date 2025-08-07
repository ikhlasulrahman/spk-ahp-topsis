<?php
// Ambil nama lini berdasarkan variabel global $ID_LINI yang diatur oleh cetak.php
$nama_lini = $db->get_var("SELECT nama FROM tb_lini WHERE id_lini='$ID_LINI'");
?>
<head>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; }
        .report-wrapper { width: 980px; margin: auto; }
        .report-header { text-align: center; border-bottom: 4px double #000; padding-bottom: 10px; margin-bottom: 20px; display: flex; align-items: center; }
        .report-header .logo { width: 80px; height: auto; }
        .report-header .title-container { flex-grow: 1; }
        .report-header h1, .report-header h2 { margin: 0; padding: 0; }
        .report-header h1 { font-size: 18pt; font-weight: bold; }
        .report-header h2 { font-size: 16pt; }
        .report-info { margin-bottom: 20px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 11pt; }
        th { background-color: #e8e8e8; font-weight: bold; }
    </style>
</head>

<div class="report-wrapper">
    <div class="report-header">
        <img src="logo.png" alt="Logo" class="logo">
        <div class="title-container">
            <h1>DATA MASTER PEMAIN</h1>
            <h2>SELEKSI PEMAIN SPK INDRAMAYU UNITED</h2>
        </div>
    </div>

    <div class="report-info">
        Lini Posisi: <?= htmlspecialchars($nama_lini) ?><br>
        Tanggal Cetak: <?= date('d M Y') ?>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Pemain</th>
                <th>Posisi</th>
                <th>Tinggi (cm)</th>
                <th>Berat (kg)</th>
                <th>Tgl Lahir</th>
                <th>Kaki Dominan</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $rows = $db->get_results("SELECT * FROM tb_alternatif WHERE id_lini=$ID_LINI ORDER BY kode_alternatif");
        $no = 0;

        foreach ($rows as $row) : ?>
            <tr>
                <td><?= ++$no ?></td>
                <td><?= $row->kode_alternatif ?></td>
                <td><?= $row->nama_alternatif ?></td>
                <td><?= $row->jabatan ?></td>
                <td><?= $row->tinggi_badan ?></td>
                <td><?= $row->berat_badan ?></td>
                <td><?= $row->tanggal_lahir ?></td>
                <td><?= $row->kaki_dominan ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>