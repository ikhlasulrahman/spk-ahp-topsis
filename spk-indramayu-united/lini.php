<div class="page-header">
    <h1>Lini</h1>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
    <form class="form-inline">
        <input type="hidden" name="m" value="lini" />
        <div class="form-group">
            <input class="form-control" type="search" placeholder="Pencarian. . ." name="q" value="<?= get('q') ?>" />
        </div>
        <div class="form-group">
            <button class="btn btn-info"><span class="glyphicon glyphicon-refresh"></span> Refresh</button>
        </div>
        <?php if ($_SESSION['level'] != '2') : ?>
        <div class="form-group">
            <a class="btn btn-success" href="?m=lini_tambah"><span class="glyphicon glyphicon-plus"></span> Tambah</a>
        </div>
        <?php endif; ?>
    </form>
</div>
<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Lini Posisi</th>
                <th>Kebutuhan</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <?php
        $q = esc_field(get('q'));
        $rows = $db->get_results("SELECT * FROM tb_lini WHERE (nama LIKE '%$q%' or id_lini LIKE '%$q%' ) ORDER BY id_lini");
        $no = 0;
        foreach ($rows as $row) : ?>
            <tr>
                <td><?= $row->id_lini ?></td>
                <td><?= $row->nama ?></td>
                <td><?= $row->kebutuhan ?> Pemain</td>
                <td><?= $row->keterangan ?></td>
                <td>
                    <?php if ($_SESSION['level'] != '2') : ?>
                    <a class="btn btn-xs btn-warning" href="?m=lini_ubah&ID=<?= $row->id_lini ?>"><span class="glyphicon glyphicon-edit"></span> Ubah</a>
                    <a class="btn btn-xs btn-danger" href="aksi.php?act=lini_hapus&ID=<?= $row->id_lini ?>" onclick="return confirm('Hapus data?')"><span class="glyphicon glyphicon-trash"></span> Hapus</a>
                    <?php else: ?>
                    -
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</div>