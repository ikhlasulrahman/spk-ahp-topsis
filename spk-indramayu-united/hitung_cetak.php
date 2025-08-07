<?php
// Variabel $ID_LINI dan $ID_PENILAIAN sudah di-set oleh `cetak.php`

// 1. Mengambil data sesi dan lini
$sesi = $db->get_row("SELECT * FROM tb_penilaian WHERE id_penilaian='$ID_PENILAIAN'");
$nama_lini = $db->get_var("SELECT nama FROM tb_lini WHERE id_lini='$ID_LINI'");
$kebutuhan = (int) $db->get_var("SELECT kebutuhan FROM tb_lini WHERE id_lini='$ID_LINI'");

// 2. Mengambil semua data yang diperlukan untuk perhitungan
$alternatifs_data = array();
$rows_alternatif = $db->get_results("SELECT * FROM tb_alternatif WHERE id_lini=$ID_LINI");
foreach ($rows_alternatif as $row) {
    $alternatifs_data[$row->kode_alternatif] = $row;
}
$kriterias = $db->get_results("SELECT kode_kriteria, nama_kriteria FROM tb_kriteria WHERE id_lini = '$ID_LINI' ORDER BY kode_kriteria");
$nilai_alternatif = TOPSIS_get_hasil_analisa(); // Fungsi ini sudah kita modifikasi untuk menggunakan $ID_PENILAIAN

// 3. Lakukan kembali proses perhitungan AHP-TOPSIS untuk mendapatkan peringkat yang akurat untuk sesi ini
$matriks_kriteria = AHP_get_relkriteria();
$total_kolom = AHP_get_total_kolom($matriks_kriteria);
$normal_kriteria = AHP_normalize($matriks_kriteria, $total_kolom);
$bobot_prioritas = AHP_get_rata($normal_kriteria);

$normal_alternatif = TOPSIS_nomalize($nilai_alternatif);
$terbobot = TOPSIS_nomal_terbobot($normal_alternatif, $bobot_prioritas);
$solusi_ideal = TOPSIS_solusi_ideal($terbobot);
$jarak_solusi = TOPSIS_jarak_solusi($terbobot, $solusi_ideal);
$preferensi = TOPSIS_preferensi($jarak_solusi);

// Urutkan hasil preferensi dari tertinggi ke terendah
arsort($preferensi);

// 4. Mengambil data pemain terpilih sesuai kebutuhan
$pemain_terpilih = [];
if ($kebutuhan > 0) {
    $terpilih_keys = array_slice(array_keys($preferensi), 0, $kebutuhan);
    foreach($terpilih_keys as $key){
        $pemain_terpilih[$key] = $preferensi[$key];
    }
}
?>

<head>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; }
        .report-wrapper { width: 980px; margin: auto; }
        .report-header { text-align: center; border-bottom: 4px double #000; padding-bottom: 10px; margin-bottom: 20px; display: flex; align-items: center; }
        .report-header .logo { width: 80px; height: auto; }
        .report-header .title-container { flex-grow: 1; text-align: center;}
        .report-header h1, .report-header h2 { margin: 0; padding: 0; }
        .report-header h1 { font-size: 18pt; font-weight: bold; }
        .report-header h2 { font-size: 16pt; }
        .report-info { margin-bottom: 20px; font-weight: bold; }
        .report-section-title { font-size: 14pt; font-weight: bold; margin-top: 30px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 11pt; }
        th { background-color: #e8e8e8; font-weight: bold; }
        .rank { font-weight: bold; text-align: center; }
        .signature-wrapper { margin-top: 60px; width: 100%; overflow: hidden; } /* Use overflow to contain floats */
        .signature-box { float: left; width: 50%; text-align: center; }
        .signature-box.right { float: right; }
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
        Lini Posisi: <?= htmlspecialchars($nama_lini) ?><br>
        Sesi Penilaian: <?= htmlspecialchars($sesi->keterangan) ?> (<?= date('d M Y', strtotime($sesi->tanggal)) ?>)<br>
        Tanggal Cetak: <?= date('d M Y') ?>
    </div>

    <div class="report-section-title">1. Hasil Akhir Perangkingan (Semua Pemain)</div>
    <table>
        <thead>
            <tr>
                <th style="width:10%;">Peringkat</th>
                <th>Nama Pemain</th>
                <th>Posisi</th>
                <th style="width:20%;">Skor Total (V)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            foreach ($preferensi as $kode => $nilai) : ?>
                <tr>
                    <td class="rank"><?= $no++ ?></td>
                    <td><?= htmlspecialchars($alternatifs_data[$kode]->nama_alternatif) ?></td>
                    <td><?= htmlspecialchars($alternatifs_data[$kode]->jabatan) ?></td>
                    <td><?= round($nilai, 4) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="report-section-title">2. Rekomendasi Pemain Terpilih (Kebutuhan: <?= $kebutuhan ?> Orang)</div>
    <table>
        <thead>
            <tr>
                <th style="width:10%;">Peringkat</th>
                <th>Nama Pemain</th>
                <th>Posisi</th>
                <th style="width:20%;">Skor Total (V)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($pemain_terpilih)): 
                $no = 1;
                foreach ($pemain_terpilih as $kode => $nilai) : ?>
                    <tr>
                        <td class="rank"><?= $no++ ?></td>
                        <td><?= htmlspecialchars($alternatifs_data[$kode]->nama_alternatif) ?></td>
                        <td><?= htmlspecialchars($alternatifs_data[$kode]->jabatan) ?></td>
                        <td><?= round($nilai, 4) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;">Data pemain terpilih tidak tersedia atau kebutuhan 0.</td>
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
        <div class="signature-box right">
            Indramayu, <?= date('d M Y') ?><br>
            Mengetahui/Menyetujui,
            <div class="signature-name">( ........................................... )</div>
            Manajer Tim
        </div>
    </div>
</div>