<?php
$nama_lini = $db->get_var("SELECT nama FROM tb_lini WHERE id_lini='" . get('id_lini') . "'");
?>
<div class="page-header">
    <h1>Tambah Sesi Penilaian</h1>
    <small>Lini: <?= $nama_lini ?></small>
</div>
<div class="row">
    <div class="col-sm-6">
        <?php if ($_POST) include 'aksi.php' ?>
        <form method="post">
            <div class="form-group">
                <label>Tanggal <span class="text-danger">*</span></label>
                <input class="form-control" type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required />
            </div>
            <div class="form-group">
                <label>Jenis Sesi <span class="text-danger">*</span></label>
                <select class="form-control" name="jenis">
                    <option>Latihan</option>
                    <option>Pertandingan</option>
                    <option>Uji Coba</option>
                </select>
            </div>
            <div class="form-group">
                <label>Keterangan <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="keterangan" placeholder="Contoh: Latihan fisik pagi" required />
            </div>
            <div class="form-group">
                <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
                <a class="btn btn-info" href="?m=penilaian&id_lini=<?= get('id_lini') ?>"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>