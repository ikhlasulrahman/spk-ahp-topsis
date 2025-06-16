<?php
// Di awal akun_ubah.php
session_start();
if (isset($_GET['ID'])) {
    $_SESSION['id_akun'] = $_GET['ID'];
}
$id = $_SESSION['id_akun'] ?? null;

print_r($_GET);
// Cek apakah parameter ID tersedia
if (!isset($_GET['ID'])) {
    echo "<div class='alert alert-danger'>ID akun tidak ditemukan.</div>";
    return;
}

$id = (int)$_GET['ID'];
$row = $db->get_row("SELECT * FROM tb_user WHERE id_akun='$id'");

if (!$row) {
    echo "<div class='alert alert-danger'>Data akun tidak ditemukan.</div>";
    return;
}
?>

<div class="page-header">
    <h1>Ubah Akun</h1>
</div>

<form method="post" action="?m=akun_ubah&ID=<?= $_GET['ID'] ?>&periode=2025">

    <div class="form-group">
        <label>Nama *</label>
        <input class="form-control" type="text" name="nama" value="<?= $row->nama ?>" required />
    </div>
    <div class="form-group">
        <label>Username *</label>
        <input class="form-control" type="text" name="user" value="<?= $row->user ?>" required />
    </div>
    <div class="form-group">
        <label>Password *</label>
        <input class="form-control" type="password" name="pass" placeholder="Kosongkan jika tidak ingin mengubah" />
    </div>
    <div class="form-group">
        <label>Level *</label>
        <select class="form-control" name="level" required>
            <option value="">- Pilih Level -</option>
            <option value="1" <?= $row->level == 1 ? 'selected' : '' ?>>Pelatih</option>
            <option value="2" <?= $row->level == 2 ? 'selected' : '' ?>>Manager</option>
            <option value="3" <?= $row->level == 3 ? 'selected' : '' ?>>Admin</option>
        </select>
    </div>
    <div class="form-group">
        <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
        <a class="btn btn-danger" href="?m=akun"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
    </div>
</form>
