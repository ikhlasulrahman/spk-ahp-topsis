<h1>Kategori Posisi</h1>
<small>Kategori <?= get('periode') ?></small>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Kategori Posisi</th>
            <th>Keterangan</th>
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
        </tr>
    <?php endforeach; ?>
</table>