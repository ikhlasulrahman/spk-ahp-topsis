<div class="page-header">
    <h1>Kelola Akun Pengguna</h1>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <form class="form-inline">
            <input type="hidden" name="m" value="akun" />
            <div class="form-group">
                <input class="form-control" type="search" placeholder="Cari..." name="q" value="<?= get('q') ?>" />
            </div>
            <div class="form-group">
    <button class="btn btn-info"><span class="glyphicon glyphicon-refresh"></span> Refresh</button>
</div>
<div class="form-group">
    <a class="btn btn-success" href="?m=akun_tambah"><span class="glyphicon glyphicon-plus"></span> Tambah</a>
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
                    <th>Level</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q = esc_field(get('q'));
                $rows = $db->get_results("SELECT * FROM tb_user WHERE nama LIKE '%$q%' OR user LIKE '%$q%' ORDER BY id_akun");
                $no = 0;
                
                $levels = [
                    '1' => 'Pelatih',
                    '2' => 'Manager',
                    '3' => 'Admin'
                ];

                foreach ($rows as $row) : ?>
                    <tr>
                        <td><?= ++$no ?></td>
                        <td><?= $row->nama ?></td>
                        <td><?= $row->user ?></td>
                        <td><?= $levels[$row->level] ?? 'Tidak Diketahui' ?></td>
                        <td>
                            <a class="btn btn-xs btn-warning" href="?m=akun_ubah&ID=<?= $row->id_akun ?>"><span class="glyphicon glyphicon-edit"></span> Ubah</a>
                            <a class="btn btn-xs btn-danger" href="aksi.php?act=akun_hapus&ID=<?= $row->id_akun ?>" onclick="return confirm('Yakin ingin menghapus data ini?')"><span class="glyphicon glyphicon-trash"></span> Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$rows) : ?>
                    <tr>
                        <td colspan="5" class="text-center">Data tidak ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>