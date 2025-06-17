<?php 
include 'functions.php'; 
?>
<!doctype html>
<html>

<head>
    <title>Cetak Laporan</title>
    <style>
        body {
            font-family: Verdana;
            font-size: 13px;
        }

        h1 {
            font-size: 14px;
            border-bottom: 4px double #000;
            padding: 3px 0;
        }

        table {
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        td, th {
            border: 1px solid #000;
            padding: 3px;
        }

        .wrapper {
            margin: 0 auto;
            width: 980px;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="wrapper">
        <?php
        // Pastikan $mod ada sebelum digunakan
        $mod = get('m'); // Sesuaikan dengan parameter di URL

        // Periksa apakah periode tersedia
        if (is_null(get('periode'))) {
            $row = $db->get_row("SELECT * FROM tb_periode ORDER BY tahun DESC LIMIT 1");
            if ($row) {
                redirect_js("cetak.php?m=$mod&periode=$row->tahun");
            } else {
                echo "<h1>Tidak ada data periode</h1>";
                die;
            }
        }

        // Ambil data periode yang dipilih
        $PERIODE = get('periode');
        $row = $db->get_row("SELECT * FROM tb_periode WHERE tahun='$PERIODE'");
        
        if (!$row) {
            echo "<h1>Periode Tidak Ditemukan</h1>";
            die;
        }

        // Pastikan file cetak yang diperlukan ada
        $file_cetak = $mod . '_cetak.php';
        if (is_file($file_cetak)) {
            include $file_cetak;
        } else {
            echo "<h1>File cetak tidak ditemukan: $file_cetak</h1>";
        }
        ?>
    </div>
</body>

</html>
