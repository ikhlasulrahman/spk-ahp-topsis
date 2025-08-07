<?php
$row = $db->get_row("SELECT * FROM tb_kriteria WHERE kode_kriteria='" . get('ID') . "' and id_lini = '$ID_LINI'");
// Ambil nama lini dari variabel $row yang sudah disiapkan oleh index.php
$nama_lini = $db->get_var("SELECT nama FROM tb_lini WHERE id_lini='" . get('id_lini') . "'");
?>
<div class="page-header">
    <h1>Ubah Kriteria</h1>
    <small>Lini <?= $nama_lini ?></small>
</div>
<div class="row">
    <div class="col-sm-6">
        <?php if ($_POST) include 'aksi.php' ?>
        <form method="post">
            <div class="form-group">
                <label>Kode <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="kode" readonly="readonly" value="<?= $row->kode_kriteria ?>" />
            </div>
            <div class="form-group">
                <label>Nama kriteria <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="nama" value="<?= $row->nama_kriteria ?>" />
            </div>
            <div class="form-group">
                <label>Atribut <span class="text-danger">*</span></label>
                <select class="form-control" name="atribut">
                    <option></option>
                    <?= get_atribut_option($row->atribut) ?>
                </select>
            </div>
            <div class="form-group">
    <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
    <a class="btn btn-info" href="?m=kriteria&id_lini=<?= get('id_lini') ?>"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
</div>
        </form>
    </div>
</div>