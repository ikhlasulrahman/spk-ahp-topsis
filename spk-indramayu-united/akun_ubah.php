<?php
$id = (int)get('ID');
$row = $db->get_row("SELECT * FROM tb_user WHERE id_akun='$id'");

if (!$row) {
    echo "<div class='alert alert-danger'>Data akun tidak ditemukan.</div>";
    return;
}
?>

<div class="page-header">
    <h1>Ubah Akun</h1>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php if ($_POST) include 'aksi.php'; ?>
        <form method="post" action="?m=akun_ubah&ID=<?= $row->id_akun ?>">
            <div class="form-group">
                <label>Nama Lengkap <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="nama" value="<?= htmlspecialchars($row->nama) ?>" required />
            </div>
            <div class="form-group">
                <label>Username <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="user" value="<?= htmlspecialchars($row->user) ?>" required />
            </div>
            <div class="form-group">
                <label>Level <span class="text-danger">*</span></label>
                <select class="form-control" name="level" required>
                    <option value=""></option>
                    <option value="1" <?= $row->level == 1 ? 'selected' : '' ?>>Pelatih</option>
                    <option value="2" <?= $row->level == 2 ? 'selected' : '' ?>>Manager</option>
                    <option value="3" <?= $row->level == 3 ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>

            <hr>
            
            <p><strong>Ubah Password (Kosongkan jika tidak ingin mengubah)</strong></p>
            <div class="form-group">
                <label>Password Baru</label>
                <input class="form-control" type="password" name="pass_baru" />
            </div>
            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input class="form-control" type="password" name="konfirmasi_pass" />
            </div>
            <div class="form-group">
                <label>Masukkan Password Admin Anda <span class="text-danger">*</span></label>
                <input class="form-control" type="password" name="pass_admin" />
                <span class="help-block">Diperlukan jika Anda mengubah password user ini.</span>
            </div>

            <div class="form-group">
                <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
                <a class="btn btn-info" href="?m=akun"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>