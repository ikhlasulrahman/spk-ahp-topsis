<?php
// Mengambil data pengguna yang sedang login
$user_login = $_SESSION['login'];
$row = $db->get_row("SELECT * FROM tb_user WHERE user='$user_login'");
?>
<div class="page-header">
    <h1>Kelola Profil</h1>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php if ($_POST) include 'aksi.php'; ?>
        <form method="post" action="?m=profil">
            
            <h4>Ubah Data Profil</h4>
            <hr>
            <div class="form-group">
                <label>Nama Lengkap <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="nama" value="<?= htmlspecialchars($row->nama) ?>" required />
            </div>
            <div class="form-group">
                <label>Username</label>
                <input class="form-control" type="text" name="user" value="<?= htmlspecialchars($row->user) ?>" readonly />
                <p class="help-block">Username tidak dapat diubah.</p>
            </div>
            
            <br>
            
            <h4>Ubah Password</h4>
            <hr>
            <p class="text-info"><i>Kosongkan semua kolom password jika Anda tidak ingin mengubahnya.</i></p>
            <div class="form-group">
                <label>Password Lama <span class="text-danger">*</span></label>
                <input class="form-control" type="password" name="pass1"/>
            </div>
            <div class="form-group">
                <label>Password Baru <span class="text-danger">*</span></label>
                <input class="form-control" type="password" name="pass2"/>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password Baru <span class="text-danger">*</span></label>
                <input class="form-control" type="password" name="pass3"/>
            </div>
            
            <div class="form-group">
                <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>