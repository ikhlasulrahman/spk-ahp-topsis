<div class="page-header">
    <h1>Cetak Laporan Perhitungan</h1>
</div>
<p>Silakan pilih lini posisi dan sesi penilaian yang ingin Anda cetak laporannya.</p>

<div class="panel panel-default">
    <div class="panel-heading">
        <form class="form-inline" action="" method="get">
            <input type="hidden" name="m" value="laporan">
            <div class="form-group">
                <label>Pilih Lini:</label>
                <select class="form-control" name="id_lini" onchange="this.form.submit()">
                    <option value="">-- Semua Lini --</option>
                    <?= get_lini_option(get('id_lini')) ?>
                </select>
            </div>
        </form>
    </div>
    <?php if ($ID_LINI) : ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis Sesi</th>
                        <th>Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sesi_penilaian = $db->get_results("SELECT * FROM tb_penilaian WHERE id_lini = '$ID_LINI' ORDER BY tanggal DESC");
                    if ($sesi_penilaian) :
                        foreach ($sesi_penilaian as $sesi) :
                    ?>
                            <tr>
                                <td><?= date('d M Y', strtotime($sesi->tanggal)) ?></td>
                                <td><?= htmlspecialchars($sesi->jenis) ?></td>
                                <td><?= htmlspecialchars($sesi->keterangan) ?></td>
                                <td class="text-center">
                                    <a class="btn btn-xs btn-info" href="cetak.php?m=hitung&id_lini=<?= $sesi->id_lini ?>&id_penilaian=<?= $sesi->id_penilaian ?>" target="_blank">
                                        <span class="glyphicon glyphicon-print"></span> Cetak Laporan Ini
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach;
                    else : ?>
                        <tr>
                            <td colspan="4" class="text-center">Belum ada sesi penilaian untuk lini ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <div class="panel-body">
            <p class="text-info">Silakan pilih Lini Posisi terlebih dahulu untuk melihat daftar sesi penilaian yang tersedia.</p>
        </div>
    <?php endif; ?>
</div>