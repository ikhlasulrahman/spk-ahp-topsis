<div class="page-header">
    <h1>Pemain</h1>
    <form class="form-inline" action="" method="get">
        <?php
        $periodes = $db->get_results("SELECT * FROM tb_periode ORDER BY tahun");
        ?>
        <input type="hidden" name="m" value="<?= get('m') ?>">
        <div class="form-group">
            <select class="form-control" name="periode">
                <?php foreach ($periodes as $periode) { ?>
                    <option value="<?= $periode->tahun ?>" <?= $periode->tahun == get('periode') ? 'selected' : '' ?>><?= $periode->nama ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-list-alt"></span> Set Lini</button>
        </div>
    </form>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <form class="form-inline">
            <input type="hidden" name="m" value="alternatif" />
            <input type="hidden" name="periode" value="<?= get('periode') ?>" />
            <div class="form-group">
            <button class="btn btn-info"><span class="glyphicon glyphicon-refresh"></span> Refresh</button>
        </div>
        <?php if ($_SESSION['level'] != '2') : ?>
        <div class="form-group">
            <a class="btn btn-success" href="?m=alternatif_tambah&periode=<?= get('periode') ?>"><span class="glyphicon glyphicon-plus"></span> Tambah</a>
        </div>
        <?php endif; ?>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Pemain</th>
                    <th>Posisi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <?php
            $q = esc_field(get('q'));
            $rows = $db->get_results("SELECT * FROM tb_alternatif WHERE (nama_alternatif LIKE '%$q%' and tahun = $PERIODE) ORDER BY kode_alternatif");
            $no = 0;

            foreach ($rows as $row) : ?>
                <tr>
                    <td><?= ++$no ?></td>
                    <td><?= $row->kode_alternatif ?></td>
                    <td><?= $row->nama_alternatif ?></td>
                    <td><?= $row->jabatan ?></td>
                    <td>
                        <?php if ($_SESSION['level'] != '2') : ?>
                        <a class="btn btn-xs btn-warning" href="?m=alternatif_ubah&ID=<?= $row->kode_alternatif ?>&periode=<?= get('periode') ?>"><span class="glyphicon glyphicon-edit"></span> Ubah</a>
                        <a class="btn btn-xs btn-danger" href="aksi.php?act=alternatif_hapus&ID=<?= $row->kode_alternatif ?>&periode=<?= get('periode') ?>" onclick="return confirm('Hapus data?')"><span class="glyphicon glyphicon-trash"></span> Hapus</a>
                        <?php else: ?>
                        -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>