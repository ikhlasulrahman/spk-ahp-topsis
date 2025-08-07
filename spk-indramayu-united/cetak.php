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
    
    $lini_ids = [];
    // PERUBAHAN: Cek parameter 'id_linis' (untuk multi-cetak)
    if (get('id_linis')) {
        $lini_ids = explode(',', get('id_linis'));
    } 
    // PERUBAHAN: Cek parameter 'id_lini' (untuk cetak tunggal)
    elseif (get('id_lini')) {
        $lini_ids = [get('id_lini')];
    }

    if (empty($lini_ids)) {
        echo "<div class='wrapper'><h1>Tidak ada Lini yang dipilih untuk dicetak.</h1></div>";
        die;
    }
    
    $total_linis = count($lini_ids);
    $counter = 0;

    foreach ($lini_ids as $lini_id) {
        $counter++;
        
        // PERUBAHAN: Atur variabel global $ID_LINI
        $ID_LINI = $lini_id;

        // PERUBAHAN: Query ke tb_lini menggunakan id_lini
        $row = $db->get_row("SELECT * FROM tb_lini WHERE id_lini='$ID_LINI'");
        
        if ($row) {
            echo '<div class="wrapper">';
            include $file_cetak;
            echo '</div>';

            if ($counter < $total_linis) {
                echo '<div class="page-break"></div>';
            }
        } else {
            // PERUBAHAN: Pesan error yang lebih sesuai
            echo "<div class='wrapper'><h1>Data untuk Lini ID $ID_LINI tidak ditemukan.</h1></div>";
            if ($counter < $total_linis) {
                echo '<div class="page-break"></div>';
            }
        }
    }
    ?>
    
</body>
</html>