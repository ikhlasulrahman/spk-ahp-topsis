<?php
include 'functions.php';
?>
<!doctype html>
<html>
<head>
    <title>Cetak Laporan</title>
    <style>
        body {
            font-family: Verdana, sans-serif;
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
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body onload="window.print()">
    
    <?php
    $mod = get('m');
    $file_cetak = $mod . '_cetak.php';

    if (!is_file($file_cetak)) {
        echo "<div class='wrapper'><h1>Error: File cetak tidak ditemukan ($file_cetak).</h1></div>";
        die;
    }

    // --- MODIFIKASI INTI DIMULAI DI SINI ---
    $ID_LINI = get('id_lini');
    $ID_PENILAIAN = get('id_penilaian'); // Ambil id_penilaian

    if (!$ID_LINI) {
        echo "<div class='wrapper'><h1>Tidak ada Lini yang dipilih untuk dicetak.</h1></div>";
        die;
    }
    
    // Untuk pencetakan laporan perhitungan, id_penilaian wajib ada
    if ($mod == 'hitung' && !$ID_PENILAIAN) {
        echo "<div class='wrapper'><h1>Tidak ada Sesi Penilaian yang dipilih untuk dicetak.</h1></div>";
        die;
    }

    echo '<div class="wrapper">';
    include $file_cetak;
    echo '</div>';
    ?>
    
</body>
</html>