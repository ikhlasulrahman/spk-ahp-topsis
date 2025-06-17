<?php
$row = $db->get_row("SELECT * FROM tb_alternatif WHERE kode_alternatif='" . get('ID') . "'");
// Ambil nama periode dari variabel $row yang sudah disiapkan oleh index.php
$nama_periode = $db->get_var("SELECT nama FROM tb_periode WHERE tahun='" . get('periode') . "'");
?>
<div class="page-header">
    <h1>Ubah Nilai Bobot &raquo; <small><?= $row->nama_alternatif ?></small></h1>
    <small>Lini <?= $nama_periode ?></small>
</div>
<div class="row">
    <div class="col-sm-4">
        <?php if ($_POST) include 'aksi.php' ?>
        <form method="post">
            <?php
            $rows = $db->get_results("SELECT ra.ID, k.kode_kriteria, k.nama_kriteria, ra.nilai FROM tb_rel_alternatif ra INNER JOIN tb_kriteria k ON k.kode_kriteria=ra.kode_kriteria  WHERE ra.tahun = $PERIODE and kode_alternatif='" . get('ID') . "' ORDER BY kode_kriteria");
            foreach ($rows as $row) : ?>
                <div class="form-group">
                    <label><?= $row->nama_kriteria ?></label>
                    <input class="form-control" type="number" min="1" max="100" name="ID-<?= $row->ID ?>" value="<?= $row->nilai ?>" />
                </div>
            <?php endforeach ?>
            <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
            <a class="btn btn-danger" href="?m=rel_alternatif"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
        </form>
    </div>
</div>