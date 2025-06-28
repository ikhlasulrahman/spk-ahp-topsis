<?php
// Ambil nama periode dari variabel $row yang sudah disiapkan oleh index.php
$nama_periode = $db->get_var("SELECT nama FROM tb_periode WHERE tahun='" . get('periode') . "'");
?>
<h1>Kriteria</h1>
<small>Lini <?= $nama_periode ?></small>
<table>
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama Kriteria</th>
            <th>Atribut</th>
        </tr>
    </thead>
    <?php
    $q = esc_field(get('q'));
    $rows = $db->get_results("SELECT * FROM tb_kriteria WHERE nama_kriteria LIKE '%$q%' and tahun = '$PERIODE' ORDER BY kode_kriteria");
    $no = 0;
    foreach ($rows as $row) : ?>
        <tr>
            <td><?= $row->kode_kriteria ?></td>
            <td><?= $row->nama_kriteria ?></td>
            <td><?= $row->atribut ?></td>
        </tr>
    <?php endforeach; ?>
</table>