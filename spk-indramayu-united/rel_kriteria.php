<div class="page-header">
    <h1>Nilai Bobot Kriteria</h1>
    <form class="form-inline" action="" method="get">
        <?php
        $linis = $db->get_results("SELECT * FROM tb_lini ORDER BY id_lini");
        ?>
        <input type="hidden" name="m" value="<?= get('m') ?>">
        <div class="form-group">
            <select class="form-control" name="id_lini">
                <?php foreach ($linis as $lini) { ?>
                    <option value="<?= $lini->id_lini ?>" <?= $lini->id_lini == get('id_lini') ? 'selected' : '' ?>><?= htmlspecialchars($lini->nama) ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-list-alt"></span> Set Lini</button>
        </div>
    </form>
</div>

<?php
// Mengambil semua kriteria untuk lini yang aktif
$kriteria = $db->get_results("SELECT kode_kriteria, nama_kriteria FROM tb_kriteria WHERE id_lini = '$ID_LINI' ORDER BY kode_kriteria");

// Mengambil nilai relasi yang sudah ada untuk ditampilkan di form
$relasi = AHP_get_relkriteria();

if (count($kriteria) < 2) {
    echo "<div class='alert alert-danger'>Jumlah kriteria minimal harus 2 untuk dapat melakukan perbandingan.</div>";
} else {
?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Perbandingan Berpasangan</h3>
        </div>
        <div class="panel-body">
            <p>Silakan tentukan tingkat kepentingan untuk setiap pasangan kriteria di bawah ini, lalu klik simpan.</p>
            <form action="aksi.php?m=rel_kriteria&id_lini=<?= get('id_lini') ?>" method="post">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="col-md-3">Kriteria Pertama</th>
                                <th class="col-md-6">Penilaian</th>
                                <th class="col-md-3">Kriteria Kedua</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Membuat form perbandingan untuk setiap pasangan unik
                        for ($i = 0; $i < count($kriteria); $i++) {
                            for ($j = $i + 1; $j < count($kriteria); $j++) {
                                $k1 = $kriteria[$i];
                                $k2 = $kriteria[$j];
                                $nilai_relasi = isset($relasi[$k1->kode_kriteria][$k2->kode_kriteria]) ? $relasi[$k1->kode_kriteria][$k2->kode_kriteria] : 1;
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($k1->nama_kriteria) ?></td>
                                <td>
                                    <select class="form-control" name="nilai[<?= $k1->kode_kriteria ?>-<?= $k2->kode_kriteria ?>]">
                                        <?= AHP_get_nilai_option($nilai_relasi) ?>
                                    </select>
                                </td>
                                <td><?= htmlspecialchars($k2->nama_kriteria) ?></td>
                            </tr>
                        <?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan Semua Perubahan</button>
                </div>
            </form>
        </div>
    </div>
<?php } ?>

<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">Matriks Hasil Perbandingan</h3></div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>Kode</th>
                    <?php
                    foreach ($relasi as $key => $value) {
                        echo "<th>$key</th>";
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($relasi as $key => $value) : ?>
                    <tr>
                        <th class="nw"><?= $key ?></th>
                        <?php
                        foreach ($value as $k => $dt) {
                            $class = ($key == $k) ? 'success' : '';
                            echo "<td class='$class'>" . round($dt, 3) . "</td>";
                        }
                        ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>