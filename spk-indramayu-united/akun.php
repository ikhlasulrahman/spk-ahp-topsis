<div class="page-header">
    <h1>Akun</h1>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <form class="form-inline">
            <input type="hidden" name="m" value="akun" />
            <div class="form-group">
                <input class="form-control" type="search" placeholder="Pencarian. . ." name="q" value="<?= get('q') ?>" />
            </div>
            <div class="form-group">
                <button class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span> Refresh</button>
            </div>
            <div class="form-group">
                <a class="btn btn-primary" href="?m=akun_tambah"><span class="glyphicon glyphicon-plus"></span> Tambah</a>
            </div>
            <div class="form-group">
                <a class="btn btn-default" target="_blank" href="cetak.php?m=akun"><span class="glyphicon glyphicon-print"></span> Cetak</a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Level</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q = esc_field(get('q'));
                $rows = $db->get_results("SELECT * FROM tb_user WHERE (nama LIKE '%$q%' OR user LIKE '%$q%') ORDER BY id_akun");
                $no = 0;
                foreach ($rows as $row) :
                    // Konversi level ke teks
                    $level_text = [
                        1 => 'Pelatih',
                        2 => 'Manager',
                        3 => 'Admin'
                    ][$row->level] ?? 'Tidak Diketahui';
                ?>
                    <tr>
                        <td><?= ++$no ?></td>
                        <td><?= $row->nama ?></td>
                        <td><?= $row->user ?></td>
                        <td><?= $row->pass ?></td>
                        <td><?= $level_text ?></td>
                        <td>
                            <a class="btn btn-xs btn-warning" href="?m=akun_ubah&ID=<?= $row->id_akun ?>"><span class="glyphicon glyphicon-edit"></span></a>
                            <a class="btn btn-xs btn-danger" href="aksi.php?act=akun_hapus&ID=<?= $row->id_akun ?>" onclick="return confirm('Hapus data?')"><span class="glyphicon glyphicon-trash"></span></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$rows) : ?>
                    <tr>
                        <td colspan="6" class="text-center">Data tidak ditemukan.</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>
