<?php
$row = $db->get_row("SELECT * FROM tb_lini WHERE id_lini='" . get('ID') . "'");
?>
<div class="page-header">
    <h1>Ubah Lini</h1>
</div>
<div class="row">
    <div class="col-sm-6">
        <?php if ($_POST) include 'aksi.php' ?>
        <form method="post">
            <div class="form-group">
                <label>Kode</label>
                <input class="form-control" type="text" name="id_lini" readonly="readonly" value="<?= $row->id_lini ?>" />
            </div>
            <div class="form-group">
                <label>Nama Lini Posisi <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="nama" value="<?= $row->nama ?>" />
            </div>
            <div class="form-group">
                <label>Kebutuhan Pemain <span class="text-danger">*</span></label>
                <input class="form-control" type="number" name="kebutuhan" value="<?= $row->kebutuhan ?>" required />
            </div>
            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control"><?= $row->keterangan ?></textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
                <a class="btn btn-info" href="?m=lini"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>