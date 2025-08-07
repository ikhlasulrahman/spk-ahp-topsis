<style>
    /* Menambahkan beberapa gaya untuk visual yang lebih baik */
    .panel-terpilih .panel-heading {
        background-color: #dff0d8; /* Hijau muda */
        color: #3c763d;
        border-color: #d6e9c6;
    }
    .panel-terpilih {
        border-color: #d6e9c6;
    }
    .text-primary {
        font-weight: bold;
    }
</style>
<div class="page-header">
    <h1>Perhitungan Peringkat Pemain</h1>
    <form class="form-inline" action="" method="get">
        <input type="hidden" name="m" value="hitung">
        <div class="form-group">
            <label>Pilih Lini:</label>
            <select class="form-control" name="id_lini" onchange="this.form.submit()">
                <option value="">-- Semua Lini --</option>
                <?= get_lini_option(get('id_lini')) ?>
            </select>
        </div>
        <div class="form-group">
            <label>Pilih Sesi Penilaian:</label>
            <select class="form-control" name="id_penilaian" onchange="this.form.submit()">
                <option value="">-- Pilih Sesi --</option>
                <?= get_penilaian_option(get('id_lini'), get('id_penilaian')) ?>
            </select>
        </div>
    </form>
</div>

<?php
// Ambil ID Penilaian dari URL
$ID_PENILAIAN = get('id_penilaian');

// Hanya jalankan perhitungan jika ID Lini dan ID Penilaian sudah dipilih
if ($ID_LINI && $ID_PENILAIAN) :

    // Mengambil semua data alternatif (termasuk jabatan) untuk lini ini
    $alternatifs_data = array();
    $rows_alternatif = $db->get_results("SELECT kode_alternatif, nama_alternatif, jabatan FROM tb_alternatif WHERE id_lini=$ID_LINI");
    foreach ($rows_alternatif as $row) {
        $alternatifs_data[$row->kode_alternatif] = $row;
    }

    $c = $db->get_results("SELECT * FROM tb_rel_alternatif WHERE nilai>0 AND id_lini=$ID_LINI AND id_penilaian=$ID_PENILAIAN");
    if (!$ALTERNATIF || !$KRITERIA) :
        echo "<div class='alert alert-danger'>Tampaknya anda belum mengatur alternatif dan kriteria untuk Lini ini. Silahkan tambahkan minimal 3 alternatif dan 3 kriteria.</div>";
    elseif (!$c) :
        echo "<div class='alert alert-danger'>Anda belum mengisi nilai pemain untuk sesi penilaian ini. Silahkan atur pada menu <strong>Sesi Penilaian</strong>.</div>";
    else :
?>
    <div class="panel panel-primary">
        <div class="panel-heading"><strong>Mengukur Konsistensi Kriteria (AHP)</strong></div>
        <div class="panel-body">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a role="button" data-toggle="collapse" href="#c11">
                            Matriks Perbandingan Kriteria
                        </a>
                    </h3>
                </div>
                <div class="panel-body collapse" id="c11">
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
                        <a role="button" data-toggle="collapse" href="#c12">
                            Matriks Bobot Prioritas Kriteria
                        </a>
                    </h3>
                </div>
                <div class="panel-body collapse" id="c12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <?php
                            $normal = AHP_normalize($matriks, $total);
                            $rata = AHP_get_rata($normal);

                            echo "<thead><tr><th></th>";
                            foreach ($normal as $key => $value) {
                                echo "<th class='nw'>$key</th>";
                            }
                            echo "<th class='nw'>Bobot Prioritas</th></tr></thead>";
                            foreach ($normal as $key => $value) {
                                echo "<tr>";
                                echo "<th class='nw'>$key</th>";
                                foreach ($value as $k => $v) {
                                    echo "<td>" . round($v, 3) . "</td>";
                                }
                                echo "<td class='text-primary'>" . round($rata[$key], 3) . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a role="button" data-toggle="collapse" href="#c13">
                           Ukur Konsistensi
                        </a>
                    </h3>
                </div>
                <div class="panel-body collapse" id="c13">
                     <?php
                    $CI = ((array_sum(AHP_consistency_measure($matriks, $rata)) / count($matriks)) - count($matriks)) / (count($matriks) - 1);
                    $RI = $nRI[count($matriks)];
                    $CR = $CI / $RI;
                    echo "<p>Consistency Index: " . round($CI, 4) . "<br />";
                    echo "Ratio Index: " . round($RI, 4) . "<br />";
                    echo "Consistency Ratio: " . round($CR, 4);
                    if ($CR > 0.10) {
                        echo " (Tidak Konsisten)<br />";
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
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Hasil Analisa Awal</strong></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <?= TOPSIS_hasil_analisa(); ?>
                    </table>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><strong>Normalisasi</strong></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <?php
                        $normal = TOPSIS_nomalize(TOPSIS_get_hasil_analisa(false));
                        echo "<tr><th></th>";
                        foreach ($normal[key($normal)] as $key => $value) {
                            echo "<th>$key</th>";
                        }
                        echo "</tr>";
                        foreach ($normal as $key => $value) {
                            echo "<tr><th>$key</th>";
                            foreach ($value as $k => $v) {
                                echo "<td>" . round($v, 4) . "</td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><strong>Normalisasi Terbobot</strong></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                         <?php
                        $terbobot = TOPSIS_nomal_terbobot($normal, $rata);
                        echo "<tr><th></th>";
                        foreach ($terbobot[key($terbobot)] as $key => $value) {
                            echo "<th>$key</th>";
                        }
                        echo "</tr>";
                        foreach ($terbobot as $key => $value) {
                            echo "<tr><th>$key</th>";
                            foreach ($value as $k => $v) {
                                echo "<td>" . round($v, 4) . "</td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><strong>Matriks Solusi Ideal</strong></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <?php
                        $ideal = TOPSIS_solusi_ideal($terbobot);
                        echo "<tr><th></th>";
                        foreach ($ideal[key($ideal)] as $key => $value) {
                            echo "<th>$key</th>";
                        }
                        echo "</tr>";
                        foreach ($ideal as $key => $value) {
                            echo "<tr><th>" . ($key == 'positif' ? 'Positif (A+)' : 'Negatif (A-)') . "</th>";
                            foreach ($value as $k => $v) {
                                echo "<td>" . round($v, 4) . "</td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><strong>Jarak Solusi &amp; Nilai Preferensi</strong></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <tr>
                            <th>Alternatif</th>
                            <th>Jarak Positif (D+)</th>
                            <th>Jarak Negatif (D-)</th>
                            <th>Nilai Preferensi (V)</th>
                        </tr>
                        <?php
                        $jarak = TOPSIS_jarak_solusi($terbobot, $ideal);
                        $pref = TOPSIS_preferensi($jarak);

                        foreach ($normal as $key => $value) {
                            echo "<tr>";
                            echo "<th>$ALTERNATIF[$key]</th>";
                            echo "<td>" . round($jarak[$key]['positif'], 4) . "</td>";
                            echo "<td>" . round($jarak[$key]['negatif'], 4) . "</td>";
                            echo "<td>" . round($pref[$key], 4) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading"><strong>Hasil Akhir Perangkingan</strong></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Peringkat</th>
                                <th>Kode</th>
                                <th>Nama Pemain</th>
                                <th>Posisi</th>
                                <th>Nilai Preferensi (V)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            arsort($pref);
                            $no = 1;
                            foreach ($pref as $key => $value) :
                                // Pindahkan query update ke sini jika Anda ingin menyimpan rank per sesi
                                // $db->query("UPDATE tb_suatu_tabel_rank SET total='$value', rank='$no' WHERE dst...");
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $key ?></td>
                                    <td><?= $alternatifs_data[$key]->nama_alternatif ?></td>
                                    <td><?= $alternatifs_data[$key]->jabatan ?></td>
                                    <td class='text-primary'><?= round($value, 4) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php
            $kebutuhan = (int) $db->get_var("SELECT kebutuhan FROM tb_lini WHERE id_lini='$ID_LINI'");
            if ($kebutuhan > 0) :
                $terpilih = array_slice($pref, 0, $kebutuhan, true);
            ?>
                <div class="panel panel-terpilih">
                    <div class="panel-heading"><strong>Rekomendasi Pemain Terpilih (Kebutuhan: <?= $kebutuhan ?> Pemain)</strong></div>
                    <div class="panel-body">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Peringkat</th>
                                    <th>Nama Pemain</th>
                                    <th>Posisi</th>
                                    <th>Nilai Preferensi (V)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($terpilih as $key => $value) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $alternatifs_data[$key]->nama_alternatif ?></td>
                                        <td><?= $alternatifs_data[$key]->jabatan ?></td>
                                        <td><?= round($value, 4) ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
<?php endif; // Akhir dari blok if-else pengecekan data
else : // Tampilkan ini jika belum ada sesi yang dipilih ?>
    <div class="alert alert-info">
        <p>Silakan pilih <strong>Lini Posisi</strong> dan <strong>Sesi Penilaian</strong> terlebih dahulu untuk menampilkan hasil perhitungan.</p>
    </div>
<?php endif; // Akhir dari blok if ($ID_LINI && $ID_PENILAIAN) 
?>