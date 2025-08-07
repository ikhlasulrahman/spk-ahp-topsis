<div class="page-header">
    <h1>Sesi Penilaian</h1>
    <form class="form-inline" action="" method="get">
        <?php
        $linis = $db->get_results("SELECT * FROM tb_lini ORDER BY id_lini");
        ?>
        <input type="hidden" name="m" value="<?= get('m') ?>">
        <div class="form-group">
            <select class="form-control" name="id_lini" onchange="this.form.submit()">
                <?php foreach ($linis as $lini) : ?>
                    <option value="<?= $lini->id_lini ?>" <?= $lini->id_lini == get('id_lini') ? 'selected' : '' ?>><?= $lini->nama ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="form-group">
            <a class="btn btn-success" href="?m=penilaian_tambah&id_lini=<?= get('id_lini') ?>"><span class="glyphicon glyphicon-plus"></span> Tambah Sesi</a>
        </div>
    </form>
</div>
<div class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rows = $db->get_results("SELECT * FROM tb_penilaian WHERE id_lini = '$ID_LINI' ORDER BY tanggal DESC");
                $no = 0;
                foreach ($rows as $row) : ?>
                    <tr>
                        <td><?= ++$no ?></td>
                        <td><?= date('d M Y', strtotime($row->tanggal)) ?></td>
                        <td><?= $row->jenis ?></td>
                        <td><?= $row->keterangan ?></td>
                        <td>
                            <a class="btn btn-xs btn-primary" href="?m=rel_alternatif&id_lini=<?= $row->id_lini ?>&id_penilaian=<?= $row->id_penilaian ?>"><span class="glyphicon glyphicon-edit"></span> Isi/Ubah Nilai</a>
                            <a class="btn btn-xs btn-warning" href="?m=penilaian_ubah&id_penilaian=<?= $row->id_penilaian ?>"><span class="glyphicon glyphicon-pencil"></span> Ubah</a>
                            <a class="btn btn-xs btn-danger" href="aksi.php?act=penilaian_hapus&id_penilaian=<?= $row->id_penilaian ?>&id_lini=<?= $row->id_lini ?>" onclick="return confirm('Menghapus sesi akan menghapus semua data nilai pemain di dalamnya. Yakin?')"><span class="glyphicon glyphicon-trash"></span> Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>