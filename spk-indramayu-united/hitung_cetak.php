<?php
// Ambil nama periode dari variabel $row yang sudah disiapkan oleh index.php
$nama_periode = $db->get_var("SELECT nama FROM tb_periode WHERE tahun='" . get('periode') . "'");
?>
<h1>Hasil Perhitungan</h1>
<small>Lini <?= $nama_periode ?></small>
<table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th>Rank</th>
            <th>Kode</th>
            <th>Nama Alternatif</th>
            <th>Jabatan</th>
            <th>Total</th>
        </tr>
    </thead>
    <?php
    $q = esc_field(get('q'));
    $rows = $db->get_results("SELECT * FROM tb_alternatif WHERE tahun=$PERIODE AND nama_alternatif LIKE '%$q%' ORDER BY total DESC");
    $no = 0;

    foreach ($rows as $row) : ?>
        <tr>
            <td><?= $row->rank ?></td>
            <td><?= $row->kode_alternatif ?></td>
            <td><?= $row->nama_alternatif ?></td>
            <td><?= $row->jabatan ?></td>
            <td><?= $row->total ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</div>