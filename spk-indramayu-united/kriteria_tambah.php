<?php
// Ambil nama periode dari variabel $row yang sudah disiapkan oleh index.php
$nama_periode = $db->get_var("SELECT nama FROM tb_periode WHERE tahun='" . get('periode') . "'");
?>
<div class="page-header">
    <h1>Tambah Kriteria</h1>
    <small>Lini <?= $nama_periode ?></small>
</div>
<div class="row">
    <div class="col-sm-6">
        <?php if ($_POST) include 'aksi.php' ?>
        <form method="post">
            <div class="form-group">
                <label>Kode <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="kode" value="<?= set_value('kode') ?>" />
            </div>
            <div class="form-group">
                <label>Nama Kriteria <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="nama" value="<?= set_value('nama') ?>" />
            </div>
            <div class="form-group">
                <label>Atribut <span class="text-danger">*</span></label>
                <select class="form-control" name="atribut">
                    <option></option>
                    <?= get_atribut_option(set_value('atribut')) ?>
                </select>
            </div>
            <div class="form-group">
    <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
    <a class="btn btn-info" href="?m=kriteria&periode=<?= get('periode') ?>"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
</div>
        </form>
    </div>
</div>