<?php
$id = (int)get('ID');
$row = $db->get_row("SELECT * FROM tb_user WHERE id_akun='$id' AND status=0");

if (!$row) {
    echo "<div class='alert alert-danger'>Pengguna tidak ditemukan atau sudah dikonfirmasi.</div>";
    return;
}
?>

<div class="page-header">
    <h1>Konfirmasi Akun</h1>
</div>
<div class="row">
    <div class="col-sm-6">
        <?php if ($_POST) include 'aksi.php'; ?>
        <form method="post" action="?m=akun_konfirmasi&ID=<?= $row->id_akun ?>">
            <div class="form-group">
                <label>Nama</label>
                <input class="form-control" type="text" name="nama" value="<?= $row->nama ?>" readonly />
            </div>
            <div class="form-group">
                <label>Username</label>
                <input class="form-control" type="text" name="user" value="<?= $row->user ?>" readonly />
            </div>
            <div class="form-group">
                <label>Level <span class="text-danger">*</span></label>
                <select class="form-control" name="level">
                    <option value="">- Pilih Level -</option>
                    <option value="Admin">Admin</option>
                    <option value="Pelatih">Pelatih</option>
                    <option value="Manajer">Manajer</option>
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-success"><span class="glyphicon glyphicon-check"></span> Konfirmasi & Aktifkan</button>
                <a class="btn btn-danger" href="?m=akun"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>