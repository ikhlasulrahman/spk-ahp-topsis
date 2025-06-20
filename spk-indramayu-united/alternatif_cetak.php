<?php
// Ambil nama periode dari variabel $row yang sudah disiapkan oleh index.php
$nama_periode = $db->get_var("SELECT nama FROM tb_periode WHERE tahun='" . get('periode') . "'");
?>
<h1>Pemain</h1>
<small>Lini <?= $nama_periode ?></small>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Pemain</th>
            <th>Posisi</th>
        </tr>
    </thead>
    <?php
    $q = esc_field(get('q'));
    $rows = $db->get_results("SELECT * FROM tb_alternatif WHERE nama_alternatif LIKE '%$q%' and tahun=$PERIODE ORDER BY kode_alternatif");
    $no = 0;

    foreach ($rows as $row) : ?>
        <tr>
            <td><?= ++$no ?></td>
            <td><?= $row->kode_alternatif ?></td>
            <td><?= $row->nama_alternatif ?></td>
            <td><?= $row->jabatan ?></td>
        </tr>
    <?php endforeach; ?>
</table>