<?php
$row = $db->get_row("SELECT * FROM tb_alternatif WHERE kode_alternatif='" . get('ID') . "'");
// Ambil nama periode dari variabel $row yang sudah disiapkan oleh index.php
$nama_periode = $db->get_var("SELECT nama FROM tb_periode WHERE tahun='" . get('periode') . "'");
?>
<div class="page-header">
    <h1>Ubah pemain</h1>
    <small>Lini <?= $nama_periode ?></small>
</div>
<div class="row">
    <div class="col-sm-6">
        <?php if ($_POST) include 'aksi.php' ?>
        <form method="post">
            <div class="form-group">
                <label>Kode <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="kode" readonly="readonly" value="<?= $row->kode_alternatif ?>" />
            </div>
            <div class="form-group">
                <label>Nama pemain <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="nama" value="<?= $row->nama_alternatif ?>" />
            </div>
            <div class="form-group">
                <label>Posisi</label>
                <textarea class="form-control" name="jabatan"><?= $row->jabatan ?></textarea>
            </div>
            <div class="form-group">
    <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
    <a class="btn btn-info" href="?m=alternatif&periode=<?= get('periode') ?>"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
</div>
        </form>
    </div>
</div>