<?php
$row = $db->get_row("SELECT * FROM tb_alternatif WHERE kode_alternatif='" . get('ID') . "' AND id_lini='" . get('id_lini') . "'");
$nama_lini = $db->get_var("SELECT nama FROM tb_lini WHERE id_lini='" . get('id_lini') . "'");
?>
<div class="page-header">
    <h1>Ubah Pemain</h1>
    <small>Lini <?= $nama_lini ?></small>
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
                <label>Nama Pemain <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="nama" value="<?= $row->nama_alternatif ?>" />
            </div>
            <div class="form-group">
                <label>Posisi</label>
                 <input class="form-control" type="text" name="jabatan" value="<?= $row->jabatan ?>" />
            </div>
             <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tinggi Badan (cm)</label>
                        <input class="form-control" type="number" name="tinggi_badan" value="<?= $row->tinggi_badan ?>" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Berat Badan (kg)</label>
                        <input class="form-control" type="number" name="berat_badan" value="<?= $row->berat_badan ?>" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input class="form-control" type="date" name="tanggal_lahir" value="<?= $row->tanggal_lahir ?>" />
            </div>
            <div class="form-group">
                <label>Asal Klub / SSB</label>
                <input class="form-control" type="text" name="asal_klub" value="<?= $row->asal_klub ?>" />
            </div>
            <div class="form-group">
                <label>Kaki Dominan</label>
                <select class="form-control" name="kaki_dominan">
                    <option value=""></option>
                    <option value="Kanan" <?= $row->kaki_dominan == 'Kanan' ? 'selected' : '' ?>>Kanan</option>
                    <option value="Kiri" <?= $row->kaki_dominan == 'Kiri' ? 'selected' : '' ?>>Kiri</option>
                    <option value="Keduanya" <?= $row->kaki_dominan == 'Keduanya' ? 'selected' : '' ?>>Keduanya</option>
                </select>
            </div>
            <div class="form-group">
                <label>Catatan Khusus</label>
                <textarea class="form-control" name="catatan"><?= $row->catatan ?></textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
                <a class="btn btn-info" href="?m=alternatif&id_lini=<?= get('id_lini') ?>"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>