<div class="page-header">
    <h1>Lini</h1>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
    <form class="form-inline">
        <input type="hidden" name="m" value="periode" />
        <div class="form-group">
            <input class="form-control" type="search" placeholder="Pencarian. . ." name="q" value="<?= get('q') ?>" />
        </div>
        <div class="form-group">
            <button class="btn btn-info"><span class="glyphicon glyphicon-refresh"></span> Refresh</button>
        </div>
        <?php if ($_SESSION['level'] != '2') : ?>
        <div class="form-group">
            <a class="btn btn-success" href="?m=periode_tambah"><span class="glyphicon glyphicon-plus"></span> Tambah</a>
        </div>
        <?php endif; ?>
        <div class="form-group">
            <a class="btn btn-default" target="_blank" href="cetak.php?m=periode"><span class="glyphicon glyphicon-print"></span> Cetak</a>
        </div>
    </form>
</div>
<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Lini Posisi</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <?php
        $q = esc_field(get('q'));
        $rows = $db->get_results("SELECT * FROM tb_periode WHERE (nama LIKE '%$q%' or tahun LIKE '%$q%' ) ORDER BY tahun");
        $no = 0;
        foreach ($rows as $row) : ?>
            <tr>
                <td><?= $row->tahun ?></td>
                <td><?= $row->nama ?></td>
                <td><?= $row->keterangan ?></td>
                <td>
                    <?php if ($_SESSION['level'] != '2') : ?>
                    <a class="btn btn-xs btn-warning" href="?m=periode_ubah&ID=<?= $row->tahun ?>"><span class="glyphicon glyphicon-edit"></span> Ubah</a>
                    <a class="btn btn-xs btn-danger" href="aksi.php?act=periode_hapus&ID=<?= $row->tahun ?>" onclick="return confirm('Hapus data?')"><span class="glyphicon glyphicon-trash"></span> Hapus</a>
                    <?php else: ?>
                    -
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</div>