<div class="page-header">
    <h1>Tambah Akun</h1>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php if ($_POST) include 'aksi.php' ?>
        <form method="post">
            <div class="form-group">
                <label>Nama <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="nama" value="<?= set_value('nama') ?>" required />
            </div>
            <div class="form-group">
                <label>Username <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="user" value="<?= set_value('user') ?>" required />
            </div>
            <div class="form-group">
                <label>Password <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="pass" value="<?= set_value('pass') ?>" required />
            </div>
            <div class="form-group">
                <label>Level <span class="text-danger">*</span></label>
                <select class="form-control" name="level" required>
                    <option value="">Pilih Level</option>
                    <option value="1" <?= set_value('level') == '1' ? 'selected' : '' ?>>Pelatih</option>
                    <option value="2" <?= set_value('level') == '2' ? 'selected' : '' ?>>Manager</option>
                    <option value="3" <?= set_value('level') == '3' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
                <a class="btn btn-danger" href="?m=akun"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>
