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
        /* Style untuk memastikan setiap laporan tercetak di halaman baru */
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
        echo "<div class='wrapper'><h1>File cetak tidak ditemukan: $file_cetak</h1></div>";
        die;
    }
    
    $periode_ids = [];
    // Cek jika ada parameter 'periodes' (untuk multi-cetak)
    if (get('periodes')) {
        $periode_ids = explode(',', get('periodes'));
    } 
    // Jika tidak, cek jika ada parameter 'periode' (untuk cetak tunggal)
    elseif (get('periode')) {
        $periode_ids = [get('periode')]; // Jadikan array dengan satu elemen
    }

    // Jika tidak ada ID sama sekali, tampilkan pesan error
    if (empty($periode_ids)) {
        echo "<div class='wrapper'><h1>Tidak ada Lini yang dipilih untuk dicetak.</h1></div>";
        die;
    }
    
    $total_periodes = count($periode_ids);
    $counter = 0;

    // Loop akan berjalan untuk satu atau banyak ID, tergantung input
    foreach ($periode_ids as $periode_id) {
        $counter++;
        
        // Atur variabel global $PERIODE yang dibutuhkan oleh file lain
        $PERIODE = $periode_id;

        $row = $db->get_row("SELECT * FROM tb_periode WHERE tahun='$PERIODE'");
        
        if ($row) {
            echo '<div class="wrapper">';
            // Include file konten laporan
            include $file_cetak;
            echo '</div>';

            // Tambahkan page break jika bukan laporan terakhir
            if ($counter < $total_periodes) {
                echo '<div class="page-break"></div>';
            }
        } else {
            echo "<div class='wrapper'><h1>Data untuk Lini ID $PERIODE tidak ditemukan.</h1></div>";
            if ($counter < $total_periodes) {
                echo '<div class="page-break"></div>';
            }
        }
    }
    ?>
    
</body>
</html>