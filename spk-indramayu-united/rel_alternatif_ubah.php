<?php
$id_penilaian = get('id_penilaian');
$kode_alternatif = get('ID');
$alternatif = $db->get_row("SELECT * FROM tb_alternatif WHERE kode_alternatif='$kode_alternatif' AND id_lini='$ID_LINI'");
$sesi = $db->get_row("SELECT * FROM tb_penilaian WHERE id_penilaian='$id_penilaian'");
$nama_lini = $db->get_var("SELECT nama FROM tb_lini WHERE id_lini='$ID_LINI'");
?>
<div class="page-header">
    <h1>Ubah Nilai Pemain &raquo; <small><?= $alternatif->nama_alternatif ?></small></h1>
    <p><strong>Lini:</strong> <?= $nama_lini ?> | <strong>Sesi:</strong> <?= date('d M Y', strtotime($sesi->tanggal)) ?> (<?= $sesi->keterangan ?>)</p>
</div>
<div class="row">
    <div class="col-sm-4">
        <?php if ($_POST) include 'aksi.php'; ?>
        <form method="post" action="?m=rel_alternatif_ubah&ID=<?= $kode_alternatif ?>&id_lini=<?= $ID_LINI ?>&id_penilaian=<?= $id_penilaian ?>">
            <?php
            // --- INI BAGIAN YANG DIPERBAIKI ---
            // Query ini sekarang secara eksplisit memfilter berdasarkan id_lini
            $rows = $db->get_results("
                SELECT ra.ID, k.nama_kriteria, ra.nilai 
                FROM tb_rel_alternatif ra 
                INNER JOIN tb_kriteria k ON k.kode_kriteria = ra.kode_kriteria
                WHERE ra.kode_alternatif = '$kode_alternatif' 
                  AND ra.id_penilaian = '$id_penilaian' 
                  AND ra.id_lini = '$ID_LINI' 
                  AND k.id_lini = '$ID_LINI'
                ORDER BY k.kode_kriteria
            ");
            foreach ($rows as $row) : ?>
                <div class="form-group">
                    <label><?= $row->nama_kriteria ?></label>
                    <input class="form-control" type="number" step="any" min="0" name="ID-<?= $row->ID ?>" value="<?= $row->nilai ?>" />
                </div>
            <?php endforeach ?>
            <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
            <a class="btn btn-info" href="?m=rel_alternatif&id_lini=<?= $ID_LINI ?>&id_penilaian=<?= $id_penilaian ?>"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
        </form>
    </div>
</div>