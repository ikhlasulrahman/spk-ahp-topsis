<?php
$id_penilaian = get('id_penilaian');

// Jika tidak ada id_penilaian, arahkan ke halaman sesi
if (!$id_penilaian) {
    print_msg('Pilih sesi penilaian terlebih dahulu.', 'warning');
    include 'penilaian.php';
    return;
}

$sesi = $db->get_row("SELECT * FROM tb_penilaian WHERE id_penilaian='$id_penilaian'");
?>
<div class="page-header">
    <h1>Nilai Bobot Pemain</h1>
    <h4>Sesi: <?= date('d M Y', strtotime($sesi->tanggal)) ?> (<?= $sesi->keterangan ?>)</h4>
</div>
<div class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Kandidat</th>
                    <?php
                    // Query ini sudah benar karena memfilter berdasarkan $ID_LINI
                    $kriterias = $db->get_results("SELECT kode_kriteria, nama_kriteria FROM tb_kriteria WHERE id_lini = '$ID_LINI' ORDER BY kode_kriteria");
                    foreach ($kriterias as $k) {
                        echo "<th>$k->nama_kriteria</th>";
                    }
                    ?>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $pemains = $db->get_results("SELECT * FROM tb_alternatif WHERE id_lini = '$ID_LINI' ORDER BY kode_alternatif");
                $data_nilai = [];
                $rows_nilai = $db->get_results("SELECT * FROM tb_rel_alternatif WHERE id_penilaian = '$id_penilaian'");
                foreach ($rows_nilai as $row) {
                    $data_nilai[$row->kode_alternatif][$row->kode_kriteria] = $row->nilai;
                }

                foreach ($pemains as $pemain) : ?>
                    <tr>
                        <td><?= $pemain->kode_alternatif ?></td>
                        <td><?= $pemain->nama_alternatif ?></td>
                        <?php foreach ($kriterias as $k) : ?>
                            <td><?= isset($data_nilai[$pemain->kode_alternatif][$k->kode_kriteria]) ? $data_nilai[$pemain->kode_alternatif][$k->kode_kriteria] : 'N/A' ?></td>
                        <?php endforeach ?>
                        <td>
                            <?php if ($_SESSION['level'] != '2') : ?>
                                <a class="btn btn-xs btn-warning" href="?m=rel_alternatif_ubah&ID=<?= $pemain->kode_alternatif ?>&id_lini=<?= $ID_LINI ?>&id_penilaian=<?= $id_penilaian ?>"><span class="glyphicon glyphicon-edit"></span> Ubah</a>
                            <?php else : ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>