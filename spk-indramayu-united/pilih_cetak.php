<?php
// Mendapatkan modul apa yang akan dicetak (misal: 'alternatif' untuk pemain)
$mod_cetak = get('mod');
if(!$mod_cetak){
    print_msg('Modul cetak tidak ditemukan!', 'danger');
    return;
}

// Mengganti underscore dengan spasi dan membuat huruf kapital di awal kata
$judul_modul = ucwords(str_replace('_', ' ', $mod_cetak));
?>
<div class="page-header">
    <h1>Cetak Data <?= $judul_modul ?></h1>
</div>
<p>Silakan pilih satu atau beberapa lini posisi di bawah ini, lalu klik tombol "Cetak yang Dipilih".</p>

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
                    <th class="text-center">Aksi Cetak Tunggal</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $linis = $db->get_results("SELECT * FROM tb_lini ORDER BY id_lini");
            foreach ($linis as $lini):
            ?>
                <tr>
                    <td class="text-center">
                        <input type="checkbox" class="cb-lini" value="<?= $lini->id_lini ?>">
                    </td>
                    <td><?= htmlspecialchars($lini->nama) ?></td>
                    <td class="text-center">
                        <a class="btn btn-xs btn-info" href="cetak.php?m=<?= $mod_cetak ?>&id_lini=<?= $lini->id_lini ?>" target="_blank">
                            <span class="glyphicon glyphicon-print"></span> Cetak Lini Ini
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
    $('.cb-lini').prop('checked', true);
}

function uncheckAll() {
    $('.cb-lini').prop('checked', false);
}

function cetakTerpilih() {
    const terpilih = document.querySelectorAll('.cb-lini:checked');
    if (terpilih.length === 0) {
        alert('Silakan pilih minimal satu lini untuk dicetak.');
        return;
    }
    
    const liniIds = [];
    terpilih.forEach(cb => {
        liniIds.push(cb.value);
    });

    const ids_string = liniIds.join(',');
    // URL ini akan meneruskan modul (misal: 'alternatif') dan ID lini yang dipilih
    const url = `cetak.php?m=<?= $mod_cetak ?>&id_linis=${ids_string}`;
    window.open(url, '_blank');
}
</script>