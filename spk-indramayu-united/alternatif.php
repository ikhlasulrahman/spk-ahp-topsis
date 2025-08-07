<div class="page-header">
    <h1>Pemain</h1>
    <form class="form-inline" action="" method="get">
        <?php
        $linis = $db->get_results("SELECT * FROM tb_lini ORDER BY id_lini");
        ?>
        <input type="hidden" name="m" value="<?= get('m') ?>">
        <div class="form-group">
            <select class="form-control" name="id_lini">
                <?php foreach ($linis as $lini) { ?>
                    <option value="<?= $lini->id_lini ?>" <?= $lini->id_lini == get('id_lini') ? 'selected' : '' ?>><?= $lini->nama ?></option>
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
            <input type="hidden" name="id_lini" value="<?= get('id_lini') ?>" />
            <div class="form-group">
                <button class="btn btn-info"><span class="glyphicon glyphicon-refresh"></span> Refresh</button>
            </div>
            <?php if ($_SESSION['level'] != '2') : ?>
            <div class="form-group">
                <a class="btn btn-success" href="?m=alternatif_tambah&id_lini=<?= get('id_lini') ?>"><span class="glyphicon glyphicon-plus"></span> Tambah</a>
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
                    <th>Tinggi (cm)</th>
                    <th>Berat (kg)</th>
                    <th>Tgl Lahir</th>
                    <th>Asal Klub</th>
                    <th>Kaki Dominan</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $q = esc_field(get('q'));
            $rows = $db->get_results("SELECT * FROM tb_alternatif WHERE (nama_alternatif LIKE '%$q%' and id_lini = $ID_LINI) ORDER BY kode_alternatif");
            $no = 0;

            foreach ($rows as $row) : ?>
                <tr>
                    <td><?= ++$no ?></td>
                    <td><?= $row->kode_alternatif ?></td>
                    <td><?= $row->nama_alternatif ?></td>
                    <td><?= $row->jabatan ?></td>
                    <td><?= $row->tinggi_badan ?></td>
                    <td><?= $row->berat_badan ?></td>
                    <td><?= $row->tanggal_lahir ?></td>
                    <td><?= $row->asal_klub ?></td>
                    <td><?= $row->kaki_dominan ?></td>
                    <td><?= $row->catatan ?></td>
                    <td>
                        <?php if ($_SESSION['level'] != '2') : ?>
                        <a class="btn btn-xs btn-warning" href="?m=alternatif_ubah&ID=<?= $row->kode_alternatif ?>&id_lini=<?= get('id_lini') ?>"><span class="glyphicon glyphicon-edit"></span> Ubah</a>
                        <a class="btn btn-xs btn-danger" href="aksi.php?act=alternatif_hapus&ID=<?= $row->kode_alternatif ?>&id_lini=<?= get('id_lini') ?>" onclick="return confirm('Hapus data?')"><span class="glyphicon glyphicon-trash"></span> Hapus</a>
                        <?php else: ?>
                        -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>