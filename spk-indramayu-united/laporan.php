<div class="page-header">
    <h1>Cetak Laporan</h1>
</div>
<p>Silakan pilih laporan lini posisi yang ingin Anda cetak.</p>

<div class="panel panel-default">
    <div class="panel-heading">
        <button class="btn btn-primary" onclick="cetakTerpilih()"><span class="glyphicon glyphicon-print"></span> Cetak yang Dipilih</button>
        <button class="btn btn-success" onclick="checkAll()"><span class="glyphicon glyphicon-check"></span> Pilih Semua</button>
        <button class="btn btn-danger" onclick="uncheckAll()"><span class="glyphicon glyphicon-unchecked"></span> Batal Pilih Semua</button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">Pilih</th>
                    <th>Nama Lini Posisi</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $periodes = $db->get_results("SELECT * FROM tb_periode ORDER BY tahun");
            foreach ($periodes as $periode):
            ?>
                <tr>
                    <td class="text-center">
                        <input type="checkbox" class="cb-periode" value="<?= $periode->tahun ?>">
                    </td>
                    <td><?= htmlspecialchars($periode->nama) ?></td>
                    <td class="text-center">
                        <a class="btn btn-xs btn-info" href="cetak.php?m=hitung&periode=<?= $periode->tahun ?>" target="_blank">
                            <span class="glyphicon glyphicon-print"></span> Cetak Laporan Ini
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function checkAll() {
    document.querySelectorAll('.cb-periode').forEach(cb => cb.checked = true);
}

function uncheckAll() {
    document.querySelectorAll('.cb-periode').forEach(cb => cb.checked = false);
}

function cetakTerpilih() {
    const terpilih = document.querySelectorAll('.cb-periode:checked');
    if (terpilih.length === 0) {
        alert('Silakan pilih minimal satu lini untuk dicetak.');
        return;
    }
    
    alert('Sistem akan membuka beberapa tab baru untuk dicetak. Pastikan browser Anda mengizinkan pop-up dari situs ini.');

    terpilih.forEach(cb => {
        const periodeId = cb.value;
        const url = `cetak.php?m=hitung&periode=${periodeId}`;
        window.open(url, '_blank');
    });
}
</script>