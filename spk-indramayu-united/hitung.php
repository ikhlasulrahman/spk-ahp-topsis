<style>
    .text-primary {
        font-weight: bold;
    }
</style>
<div class="page-header">
    <h1>Perhitungan</h1>
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

<?php
// --- TAMBAHKAN KODE INI ---
// Mengambil semua data alternatif (termasuk jabatan) untuk lini ini
$alternatifs_data = array();
$rows_alternatif = $db->get_results("SELECT kode_alternatif, nama_alternatif, jabatan FROM tb_alternatif WHERE id_lini=$ID_LINI");
foreach ($rows_alternatif as $row) {
    $alternatifs_data[$row->kode_alternatif] = $row;
}


$c = $db->get_results("SELECT * FROM tb_rel_alternatif WHERE nilai>0 and id_lini=$ID_LINI");
$ALTERNATIF = isset($ALTERNATIF) ? $ALTERNATIF : null;
if (!$ALTERNATIF || !$KRITERIA) :
    echo "Tampaknya anda belum mengatur alternatif dan kriteria. Silahkan tambahkan minimal 3 alternatif dan 3 kriteria.";
elseif (!$c) :
    echo "Tampaknya anda belum mengatur nilai alternatif. Silahkan atur pada menu <strong>Nilai Bobot</strong> > <strong>Nilai Bobot Alternatif</strong>.";
else :
?>
    <div class="panel panel-primary">
        <div class="panel-heading"><strong>Mengukur Konsistensi Kriteria (AHP)</strong></div>
        <div class="panel-body">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#c11" aria-expanded="false" aria-controls="c11">
                            Matriks Perbandingan Kriteria
                        </a>
                    </h3>
                </div>
                <div class="panel-body collapse" id="c11">
                    <p>Pertama-tama menyusun hirarki dimana diawali dengan tujuan, kriteria dan alternatif-alternatif lokasi pada tingkat paling bawah.
                        Selanjutnya menetapkan perbandingan berpasangan antara kriteria-kriteria dalam bentuk matrik.
                        Nilai diagonal matrik untuk perbandingan suatu elemen dengan elemen itu sendiri diisi dengan bilangan (1) sedangkan isi nilai perbandingan antara (1) sampai dengan (9) kebalikannya, kemudian dijumlahkan perkolom.
                        Data matrik tersebut seperti terlihat pada tabel berikut.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <?php
                            $matriks = AHP_get_relkriteria();
                            $total = AHP_get_total_kolom($matriks);

                            echo "<thead><tr><th></th>";
                            foreach ($matriks as $key => $value) {
                                echo "<th class='nw'>$key</th>";
                            }
                            echo "<tr></thead>";
                            foreach ($matriks as $key => $value) {
                                echo "<tr><th class='nw'>$key</th>";
                                foreach ($value as $k => $v) {
                                    echo "<td>" . round($v, 3) . "</td>";
                                }
                                echo "</tr>";
                            }
                            echo "<tfoot><tr><th class='nw'>Total</th>";
                            foreach ($total as $key => $value) {
                                echo "<td class='text-primary'>" . round($total[$key], 3) . "</td>";
                            }
                            echo "</tr></tfoot>";
                            ?>
                        </table>
                    </div>
                </div>

            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#c12" aria-expanded="false" aria-controls="c12">
                            Matriks Bobot Prioritas Kriteria
                        </a>
                    </h3>
                </div>
                <div class="panel-body collapse" id="c12">
                    <p>Setelah terbentuk matrik perbandingan maka dilihat bobot prioritas untuk perbandingan kriteria.
                        Dengan cara membagi isi matriks perbandingan dengan jumlah kolom yang bersesuaian, kemudian menjumlahkan perbaris setelah itu hasil penjumlahan dibagi dengan banyaknya kriteria sehingga ditemukan bobot prioritas seperti terlihat pada berikut.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <?php
                            $normal = AHP_normalize($matriks, $total);
                            $rata = AHP_get_rata($normal);

                            echo "<thead><tr><th></th>";
                            $no = 1;
                            foreach ($normal as $key => $value) {
                                echo "<th class='nw'>$key</th>";
                                $no++;
                            }
                            echo "<th class='nw'>Bobot Prioritas</th></tr></thead>";
                            $no = 1;
                            foreach ($normal as $key => $value) {
                                echo "<tr>";
                                echo "<th class='nw'>$key</th>";
                                foreach ($value as $k => $v) {
                                    echo "<td>" . round($v, 3) . "</td>";
                                }
                                echo "<td class='text-primary'>" . round($rata[$key], 3) . "</td>";
                                echo "</tr>";
                                $no++;
                            }
                            echo "</tr>";
                            ?>
                        </table>
                    </div>
                </div>

            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#c13" aria-expanded="false" aria-controls="c13">
                            Matriks Konsistensi Kriteria
                        </a>
                    </h3>
                </div>
                <div class="panel-body collapse" id="c13">
                    <p>Untuk mengetahui konsisten matriks perbandingan dilakukan perkalian seluruh isi kolom matriks A perbandingan dengan bobot prioritas kriteria A, isi kolom B matriks perbandingan dengan bobot prioritas kriteria B dan seterusnya. Kemudian dijumlahkan setiap barisnya dan dibagi penjumlahan baris dengan bobot prioritas bersesuaian seperti terlihat pada tabel berikut.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <?php
                            $cm = AHP_consistency_measure($matriks, $rata);

                            echo "<thead><tr><th></th>";
                            $no = 1;
                            foreach ($normal as $key => $value) {
                                echo "<th class='nw'>$key</th>";
                                $no++;
                            }
                            echo "<th>Bobot</th></tr></thead>";
                            $no = 1;
                            foreach ($normal as $key => $value) {
                                echo "<tr>";
                                echo "<th class='nw'>$key</th>";
                                foreach ($value as $k => $v) {
                                    echo "<td>" . round($v, 3) . "</td>";
                                }
                                echo "<td class='text-primary'>" . round($cm[$key], 3) . "</td>";
                                echo "</tr>";
                                $no++;
                            }
                            echo "</tr>";
                            ?>
                        </table>
                    </div>
                    <p>Berikut tabel ratio index berdasarkan ordo matriks.</p>

                    <table class="table table-bordered">
                        <tr>
                            <th>Ordo matriks</th>
                            <?php
                            foreach ($nRI as $key => $value) {
                                if (count($matriks) == $key)
                                    echo "<td class='text-primary'>$key</td>";
                                else
                                    echo "<td>$key</td>";
                            }
                            ?>
                        </tr>
                        <tr>
                            <th>Ratio index</th>
                            <?php
                            foreach ($nRI as $key => $value) {
                                if (count($matriks) == $key)
                                    echo "<td class='text-primary'>$value</td>";
                                else
                                    echo "<td>$value</td>";
                            }
                            ?>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">
                    <?php
                    $CI = ((array_sum($cm) / count($cm)) - count($cm)) / (count($cm) - 1);
                    $RI = $nRI[count($matriks)];
                    $CR = $CI / $RI;
                    echo "<p>Consistency Index: " . round($CI, 3) . "<br />";
                    echo "Ratio Index: " . round($RI, 3) . "<br />";
                    echo "Consistency Ratio: " . round($CR, 3);
                    if ($CR > 0.10) {
                        echo " (Tidak konsisten)<br />";
                    } else {
                        echo " (Konsisten)<br />";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading"><strong>Perhitungan TOPSIS</strong></div>
        <div class="panel-body">
            <div class="panel panel-primary">
                <div class="panel-heading"><strong>Hasil Analisa</strong></div>
                <div class="panel-body oxa">
                    <table class="table table-bordered table-striped table-hover">
                        <?php
                        echo TOPSIS_hasil_analisa();
                        ?>
                    </table>
                </div>
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading"><strong>Normalisasi</strong></div>
                <div class="panel-body oxa">
                    <table class="table table-bordered table-striped table-hover">
                        <?php

                        $normal = TOPSIS_nomalize(TOPSIS_get_hasil_analisa(false));
                        $r = "";
                        $r .= "<tr><th></th>";
                        $no = 1;
                        foreach ($normal[key($normal)] as $key => $value) {
                            $r .= "<th>$key</th>";
                            $no++;
                        }

                        $no = 1;
                        foreach ($normal as $key => $value) {
                            $r .= "<tr>";
                            $r .= "<th>A" . $no . "</th>";
                            foreach ($value as $k => $v) {
                                $r .= "<td>" . round($v, 5) . "</td>";
                            }
                            $r .= "</tr>";
                            $no++;
                        }
                        $r .= "</tr>";
                        echo  $r;
                        ?>
                    </table>
                </div>
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading"><strong>Normalisasi Terbobot</strong></div>
                <div class="panel-body oxa">
                    <table class="table table-bordered table-striped table-hover">
                        <?php
                        $r = "";
                        $terbobot = TOPSIS_nomal_terbobot($normal, $rata);

                        $r .= "<tr><th></th>";
                        $no = 1;
                        foreach ($terbobot[key($terbobot)] as $key => $value) {
                            $r .= "<th>$key</th>";
                            $no++;
                        }

                        $no = 1;
                        foreach ($terbobot as $key => $value) {
                            $r .= "<tr>";
                            $r .= "<th>$key</th>";
                            foreach ($value as $k => $v) {
                                $r .= "<td>" . round($v, 5) . "</td>";
                            }
                            $r .= "</tr>";
                            $no++;
                        }
                        $r .= "</tr>";
                        echo  $r;
                        ?>
                    </table>
                </div>
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading"><strong>Matriks Solusi Ideal</strong></div>
                <div class="panel-body oxa">
                    <table class="table table-bordered table-striped table-hover">
                        <?php
                        $r = "";
                        $ideal = TOPSIS_solusi_ideal($terbobot);

                        $r .= "<tr><th></th>";
                        $no = 1;
                        foreach ($ideal[key($ideal)] as $key => $value) {
                            $r .= "<th>" . $key . "</th>";
                            $no++;
                        }

                        $no = 1;
                        foreach ($ideal as $key => $value) {
                            $r .= "<tr>";
                            $r .= "<th>" . $key . "</th>";
                            foreach ($value as $k => $v) {
                                $r .= "<td>" . round($v, 5) . "</td>";
                            }
                            $r .= "</tr>";
                            $no++;
                        }
                        $r .= "</tr>";
                        echo  $r;
                        ?>
                    </table>
                </div>
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading"><strong>Jarak Solusi &amp; Nilai Preferensi</strong></div>
                <div class="panel-body oxa">
                    <table class="table table-bordered table-striped table-hover">
                        <tr>
                            <th></th>
                            <th>Positif</th>
                            <th>Negatif</th>
                            <th>Preferensi</th>
                        </tr>
                        <?php
                        $jarak = TOPSIS_jarak_solusi($terbobot, $ideal);
                        $pref = TOPSIS_preferensi($jarak);

                        foreach ($normal as $key => $value) {
                            echo "<tr>";
                            echo "<th>$key</th>";
                            echo "<td>" . round($jarak[$key]['positif'], 5) . "</td>";
                            echo "<td>" . round($jarak[$key]['negatif'], 5) . "</td>";
                            echo "<td>" . round($pref[$key], 5) . "</td>";
                            echo "</tr>";
                            $no++;
                        }
                        ?>
                    </table>
                </div>
            </div>

<div class="panel panel-primary">
    <div class="panel-heading"><strong>Perangkingan</strong></div>
    <div class="panel-body oxa">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Pemain</th>
                    <th>Posisi</th>
                    <th>Total</th>
                    <th>Rank</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $rank = get_rank($pref);
            arsort($pref);
            $no = 1;
            foreach ($pref as $key => $value) :
                $db->query("UPDATE tb_alternatif SET total='$value', rank='$no' WHERE kode_alternatif='$key'");
            ?>
                <tr>
                    <td><?= $key ?></td>
                    <td><?= $alternatifs_data[$key]->nama_alternatif ?></td>
                    <td><?= $alternatifs_data[$key]->jabatan ?></td>
                    <td class='text-primary'><?= round($value, 4) ?></td>
                    <td class='text-primary'><?= $no ?></td>
                </tr>
            <?php
                $no++;
            endforeach;
            ?>
            </tbody>
        </table>
    </div>
</div>
    
    <?php
$kebutuhan = (int) $db->get_var("SELECT kebutuhan FROM tb_lini WHERE id_lini='$ID_LINI'");
if($kebutuhan > 0):
    $terpilih = $db->get_results("SELECT * FROM tb_alternatif WHERE id_lini=$ID_LINI ORDER BY rank ASC LIMIT $kebutuhan");
?>
<div class="panel panel-terpilih">
    <div class="panel-heading"><strong>Pemain Terpilih (Kebutuhan: <?= $kebutuhan ?> Pemain)</strong></div>
    <div class="panel-body oxa">
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>Peringkat</th>
                    <th>Kode</th>
                    <th>Nama Pemain</th>
                    <th>Posisi</th>
                    <th>Skor Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($terpilih as $row): ?>
                <tr>
                    <td><?= $row->rank ?></td>
                    <td><?= $row->kode_alternatif ?></td>
                    <td><?= $row->nama_alternatif ?></td>
                    <td><?= $row->jabatan ?></td>
                    <td><?= round($row->total, 4) ?></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

    

<?php endif; ?>