<?php
// Mengambil data yang diperlukan
$periode_id = get('periode');
$nama_periode = $db->get_var("SELECT nama FROM tb_periode WHERE tahun='$periode_id'");
$kebutuhan = (int) $db->get_var("SELECT kebutuhan FROM tb_periode WHERE tahun='$periode_id'");

$alternatifs = $db->get_results("SELECT * FROM tb_alternatif WHERE tahun='$periode_id' ORDER BY rank");
$kriterias = $db->get_results("SELECT kode_kriteria, nama_kriteria FROM tb_kriteria WHERE tahun = '$periode_id' ORDER BY kode_kriteria");
$nilai_alternatif = TOPSIS_get_hasil_analisa();

// Mengambil data pemain terpilih sesuai kebutuhan
$pemain_terpilih = [];
if ($kebutuhan > 0) {
    $pemain_terpilih = $db->get_results("SELECT * FROM tb_alternatif WHERE tahun='$periode_id' ORDER BY rank ASC LIMIT $kebutuhan");
}
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
        .report-section-title { font-size: 14pt; font-weight: bold; margin-top: 30px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #e8e8e8; font-weight: bold; }
        .rank { font-weight: bold; text-align: center; }
        .signature-wrapper { margin-top: 60px; width: 100%; display: table; }
        .signature-box { display: table-cell; width: 50%; text-align: center; }
        .signature-box .signature-name { margin-top: 70px; font-weight: bold; text-decoration: underline; }
    </style>
</head>

<div class="report-wrapper">
    <div class="report-header">
        <img src="logo.png" alt="Logo" class="logo">
        <div class="title-container">
            <h1>LAPORAN HASIL PERHITUNGAN</h1>
            <h2>SELEKSI PEMAIN SPK INDRAMAYU UNITED</h2>
        </div>
    </div>

    <div class="report-info">
        Lini Posisi: <?= htmlspecialchars($nama_periode) ?><br>
        Tanggal Cetak: <?= date('d M Y') ?>
    </div>

    <div class="report-section-title">1. Rincian Nilai Alternatif Terhadap Kriteria</div>
    <table>
        <thead>
            <tr>
                <th>Nama Pemain</th>
                <?php foreach ($kriterias as $kriteria) : ?>
                    <th><?= htmlspecialchars($kriteria->nama_kriteria) ?></th>
                <?php endforeach ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alternatifs as $alternatif) : ?>
                <tr>
                    <td><?= htmlspecialchars($alternatif->nama_alternatif) ?></td>
                    <?php foreach ($kriterias as $kriteria) : ?>
                        <td><?= isset($nilai_alternatif[$alternatif->kode_alternatif][$kriteria->kode_kriteria]) ? htmlspecialchars($nilai_alternatif[$alternatif->kode_alternatif][$kriteria->kode_kriteria]) : '-'; ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <div class="report-section-title">2. Hasil Akhir Perangkingan (Semua Pemain)</div>
    <table>
        <thead>
            <tr>
                <th style="width:10%;">Peringkat</th>
                <th>Kode</th>
                <th>Nama Pemain</th>
                <th style="width:20%;">Skor Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alternatifs as $alternatif) : ?>
                <tr>
                    <td class="rank"><?= $alternatif->rank ?></td>
                    <td><?= htmlspecialchars($alternatif->kode_alternatif) ?></td>
                    <td><?= htmlspecialchars($alternatif->nama_alternatif) ?></td>
                    <td><?= round($alternatif->total, 4) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="report-section-title">3. Rekomendasi Pemain Terpilih (Kebutuhan: <?= $kebutuhan ?> Orang)</div>
    <table>
        <thead>
            <tr>
                <th style="width:10%;">Peringkat</th>
                <th>Kode</th>
                <th>Nama Pemain</th>
                <th style="width:20%;">Skor Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($pemain_terpilih)): ?>
                <?php foreach ($pemain_terpilih as $pemain) : ?>
                    <tr>
                        <td class="rank"><?= $pemain->rank ?></td>
                        <td><?= htmlspecialchars($pemain->kode_alternatif) ?></td>
                        <td><?= htmlspecialchars($pemain->nama_alternatif) ?></td>
                        <td><?= round($pemain->total, 4) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;">Data pemain terpilih tidak tersedia.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="signature-wrapper">
        <div class="signature-box">
            <br>
            Pelatih Kepala,
            <div class="signature-name">( ........................................... )</div>
        </div>
        <div class="signature-box">
            Indramayu, <?= date('d M Y') ?><br>
            Mengetahui/Menyetujui,
            <div class="signature-name">( ........................................... )</div>
            Manajer Tim
        </div>
    </div>
</div>