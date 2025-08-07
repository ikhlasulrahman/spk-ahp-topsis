<?php
error_reporting(~E_NOTICE);
session_start();

include 'config.php';
include 'includes/db.php';
$db = new DB($config['server'], $config['username'], $config['password'], $config['database_name']);
include 'includes/general.php';
include 'includes/paging.php';

$ID_LINI = get('id_lini');
$ID_PENILAIAN = get('id_penilaian'); // Variabel global baru

/**
 * Fungsi untuk mengatur pesan notifikasi (flash message).
 * @param string $msg Pesan yang akan ditampilkan.
 * @param string $type Jenis notifikasi (success, danger, warning, info).
 */
function set_message($msg, $type = 'success')
{
    $_SESSION['message'] = [
        'text' => $msg,
        'type' => $type,
    ];
}

$mod = get('m');
$act = get('act');

$nRI = array(
    1 => 0,
    2 => 0,
    3 => 0.58,
    4 => 0.9,
    5 => 1.12,
    6 => 1.24,
    7 => 1.32,
    8 => 1.41,
    9 => 1.46,
    10 => 1.49,
    11 => 1.51,
    12 => 1.48,
    13 => 1.56,
    14 => 1.57,
    15 => 1.59
);

$rows = $db->get_results("SELECT kode_alternatif, nama_alternatif FROM tb_alternatif where id_lini = '" . get('id_lini') . "' ORDER BY kode_alternatif");
foreach ($rows as $row) {
    $ALTERNATIF[$row->kode_alternatif] = $row->nama_alternatif;
}

$rows = $db->get_results("SELECT kode_kriteria, nama_kriteria, atribut FROM tb_kriteria where id_lini = '" . get('id_lini') . "' ORDER BY kode_kriteria");
foreach ($rows as $row) {
    $KRITERIA[$row->kode_kriteria] = array(
        'nama_kriteria' => $row->nama_kriteria,
        'atribut' => $row->atribut,
        'bobot' => isset($row->bobot) ? $row->bobot : null
    );
}

function get_lini_option($selected_id)
{
    global $db;
    $rows = $db->get_results("SELECT id_lini, nama FROM tb_lini ORDER BY id_lini");
    $options = '';
    foreach ($rows as $row) {
        $selected = ($row->id_lini == $selected_id) ? 'selected' : '';
        $options .= "<option value='{$row->id_lini}' {$selected}>{$row->nama}</option>";
    }
    return $options;
}

function get_penilaian_option($id_lini, $selected_id)
{
    global $db;
    if (!$id_lini) return '';
    $rows = $db->get_results("SELECT * FROM tb_penilaian WHERE id_lini='$id_lini' ORDER BY tanggal DESC");
    $options = '';
    foreach ($rows as $row) {
        $selected = ($row->id_penilaian == $selected_id) ? 'selected' : '';
        $options .= "<option value='{$row->id_penilaian}' {$selected}>" . date('d-m-Y', strtotime($row->tanggal)) . " - {$row->keterangan}</option>";
    }
    return $options;
}

function AHP_get_relkriteria()
{
    global $db;
    global $ID_LINI;
    $data = array();
    $rows = $db->get_results("SELECT k.nama_kriteria, rk.ID1, rk.ID2, nilai 
        FROM tb_rel_kriteria rk INNER JOIN tb_kriteria k ON k.kode_kriteria=rk.ID1
        where rk.id_lini=$ID_LINI 
        ORDER BY ID1, ID2");
    foreach ($rows as $row) {
        $data[$row->ID1][$row->ID2] = $row->nilai;
    }
    return $data;
}

function AHP_get_relalternatif($kriteria = '')
{
    global $db;
    $rows = $db->get_results("SELECT * FROM tb_rel_alternatif WHERE kode_kriteria='$kriteria' ORDER BY kode1, kode2");
    $matriks = array();
    foreach ($rows as $row) {
        $matriks[$row->kode1][$row->kode2] = $row->nilai;
    }
    return $matriks;
}

function get_kriteria_option($selected = 0)
{
    global $KRITERIA;
    $a = "";
    foreach ($KRITERIA as $key => $value) {
        if ($key == $selected)
            $a .= "<option value='$key' selected>$value[nama_kriteria]</option>";
        else
            $a .= "<option value='$key'>$value[nama_kriteria]</option>";
    }
    return $a;
}

function get_atribut_option($selected = '')
{
    $a = "";
    $atribut = array('benefit' => 'Benefit', 'cost' => 'Cost');
    foreach ($atribut as $key => $value) {
        if ($selected == $key)
            $a .= "<option value='$key' selected>$value</option>";
        else
            $a .= "<option value='$key'>$value</option>";
    }
    return $a;
}

function AHP_get_alternatif_option($selected = '')
{
    global $db;
    $a = "";
    $rows = $db->get_results("SELECT kode_alternatif, nama_alternatif FROM tb_alternatif ORDER BY kode_alternatif");
    foreach ($rows as $row) {
        if ($row->kode_alternatif == $selected)
            $a .= "<option value='$row->kode_alternatif' selected>$row->kode_alternatif - $row->nama_alternatif</option>";
        else
            $a .= "<option value='$row->kode_alternatif'>$row->kode_alternatif - $row->nama_alternatif</option>";
    }
    return $a;
}

function AHP_get_nilai_option($selected = 1)
{
    // Menggunakan struktur array yang lebih aman untuk menghindari konflik kunci
    $options = [
        ['value' => 9, 'text' => '9 - Mutlak sangat penting dari'],
        ['value' => 8, 'text' => '8 - Mendekati mutlak dari'],
        ['value' => 7, 'text' => '7 - Sangat penting dari'],
        ['value' => 6, 'text' => '6 - Mendekati sangat penting dari'],
        ['value' => 5, 'text' => '5 - Lebih penting dari'],
        ['value' => 4, 'text' => '4 - Mendekati lebih penting dari'],
        ['value' => 3, 'text' => '3 - Sedikit lebih penting dari'],
        ['value' => 2, 'text' => '2 - Mendekati sedikit lebih penting dari'],
        ['value' => 1, 'text' => '1 - Sama penting dengan'],
        ['value' => round(1/2, 6), 'text' => '1/2 - Mendekati sedikit kurang penting dari'],
        ['value' => round(1/3, 6), 'text' => '1/3 - Sedikit kurang penting dari'],
        ['value' => round(1/4, 6), 'text' => '1/4 - Mendekati kurang penting dari'],
        ['value' => round(1/5, 6), 'text' => '1/5 - Kurang penting dari'],
        ['value' => round(1/6, 6), 'text' => '1/6 - Mendekati sangat kurang penting dari'],
        ['value' => round(1/7, 6), 'text' => '1/7 - Sangat kurang penting dari'],
        ['value' => round(1/8, 6), 'text' => '1/8 - Mendekati mutlak kurang dari'],
        ['value' => round(1/9, 6), 'text' => '1/9 - Mutlak kurang penting dari'],
    ];

    $html = "";
    foreach ($options as $option) {
        $key = $option['value'];
        $value_text = $option['text'];
        
        // Membandingkan nilai dengan toleransi untuk mengatasi masalah angka desimal (float)
        if (abs(floatval($selected) - floatval($key)) < 0.0001) {
            $html .= "<option value='$key' selected>$value_text</option>";
        } else {
            $html .= "<option value='$key'>$value_text</option>";
        }
    }
    return $html;
}

function AHP_get_total_kolom($matriks = array())
{
    $total = array();
    foreach ($matriks as $key => $value) {
        foreach ($value as $k => $v) {
            $total[$k] = isset($total[$k]) ? ($total[$k] + $v) : $v;
        }
    }
    return $total;
}

function AHP_normalize($matriks = array(), $total = array())
{

    foreach ($matriks as $key => $value) {
        foreach ($value as $k => $v) {
            $matriks[$key][$k] = $matriks[$key][$k] / $total[$k];
        }
    }
    return $matriks;
}

function AHP_get_rata($normal)
{
    $rata = array();
    foreach ($normal as $key => $value) {
        $rata[$key] = array_sum($value) / count($value);
    }
    return $rata;
}

function AHP_mmult($matriks = array(), $rata = array())
{
    $data = array();

    $rata = array_values($rata);

    foreach ($matriks as $key => $value) {
        $no = 0;
        foreach ($value as $k => $v) {
            $data[$key] = isset($data[$key]) ? ($data[$key] + ($v * $rata[$no])) : $v * $rata[$no];
            $no++;
        }
    }

    return $data;
}

function AHP_consistency_measure($matriks, $rata)
{
    $matriks = AHP_mmult($matriks, $rata);
    foreach ($matriks as $key => $value) {
        $data[$key] = $value / $rata[$key];
    }
    return $data;
}

function AHP_get_eigen_alternatif($kriteria = array())
{
    $data = array();
    foreach ($kriteria as $key => $value) {
        $kode_kriteria = $key;
        $matriks = AHP_get_relalternatif($kode_kriteria);
        $total = AHP_get_total_kolom($matriks);
        $normal = AHP_normalize($matriks, $total);
        $rata = AHP_get_rata($normal);
        $data[$kode_kriteria] = $rata;
    }
    $new = array();
    foreach ($data as $key => $value) {
        foreach ($value as $k => $v) {
            $new[$k][$key] = $v;
        }
    }
    return $new;
}

function AHP_get_rank($array)
{
    $data = $array;
    arsort($data);
    $no = 1;
    $new = array();
    foreach ($data as $key => $value) {
        $new[$key] = $no++;
    }
    return $new;
}

function TOPSIS_get_hasil_analisa()
{
    global $db, $ID_LINI, $ID_PENILAIAN; // Tambahkan ID_PENILAIAN

    // Pastikan ID_PENILAIAN ada sebelum menjalankan query
    if (!$ID_PENILAIAN) {
        return []; // Kembalikan array kosong jika tidak ada sesi yang dipilih
    }

    $rows = $db->get_results("SELECT a.kode_alternatif, k.kode_kriteria, ra.nilai
        FROM tb_alternatif a 
        	INNER JOIN tb_rel_alternatif ra ON ra.kode_alternatif=a.kode_alternatif
        	INNER JOIN tb_kriteria k ON k.kode_kriteria=ra.kode_kriteria
        WHERE a.id_lini = '$ID_LINI' AND ra.id_penilaian = '$ID_PENILAIAN'
        ORDER BY a.kode_alternatif, k.kode_kriteria");
    $data = array();
    foreach ($rows as $row) {
        $data[$row->kode_alternatif][$row->kode_kriteria] = $row->nilai;
    }
    return $data;
}

function TOPSIS_hasil_analisa($echo = true)
{
    global $db, $ALTERNATIF, $KRITERIA;

    $r = "";
    $data = TOPSIS_get_hasil_analisa();

    if (!$echo)
        return $data;

    $r .= "<tr><th></th>";
    $no = 1;
    foreach ($data[key($data)] as $key => $value) {
        $r .= "<th>" . $KRITERIA[$key]['nama_kriteria'] . "</th>";
        $no++;
    }

    $no = 1;
    foreach ($data as $key => $value) {
        $r .= "<tr>";
        $r .= "<th nowrap>" . $ALTERNATIF[$key] . "</th>";
        foreach ($value as $k => $v) {
            $r .= "<td>" . $v . "</td>";
        }
        $r .= "</tr>";
        $no++;
    }
    $r .= "</tr>";
    return $r;
}

function TOPSIS_nomalize($array, $max = true)
{
    $data = array();
    $kuadrat = array();

    foreach ($array as $key => $value) {
        foreach ($value as $k => $v) {
            // $kuadrat[$k] += ($v * $v);
            $kuadrat[$k] = isset($kuadrat[$k]) ? ($kuadrat[$k] + ($v * $v)) : ($v * $v);
        }
    }

    foreach ($array as $key => $value) {
        foreach ($value as $k => $v) {
            $data[$key][$k] = $v / sqrt($kuadrat[$k]);
        }
    }
    return $data;
}

function TOPSIS_nomal_terbobot($array, $bobot)
{
    $data = array();

    foreach ($array as $key => $value) {
        foreach ($value as $k => $v) {
            $data[$key][$k] = $v * $bobot[$k];
        }
    }

    return $data;
}

function TOPSIS_solusi_ideal($array)
{
    global $KRITERIA;
    $data = array();

    $temp = array();

    foreach ($array as $key => $value) {
        foreach ($value as $k => $v) {
            $temp[$k][] = $v;
        }
    }

    foreach ($temp as $key => $value) {
        $max = max($value);
        $min = min($value);
        if ($KRITERIA[$key]['atribut'] == 'benefit') {
            $data['positif'][$key] = $max;
            $data['negatif'][$key] = $min;
        } else {
            $data['positif'][$key] = $min;
            $data['negatif'][$key] = $max;
        }
    }

    return $data;
}

function TOPSIS_jarak_solusi($array, $ideal)
{
    $temp = array();
    $arr = array();
    foreach ($array as $key => $value) {
        foreach ($value as $k => $v) {
            $arr['positif'][$key][$k] = pow(($v - $ideal['positif'][$k]), 2);
            $arr['negatif'][$key][$k] = pow(($v - $ideal['negatif'][$k]), 2);

            // $temp[$key]['positif'] += pow(($v - $ideal['positif'][$k]), 2);
            // $temp[$key]['negatif'] += pow(($v - $ideal['negatif'][$k]), 2);
            $temp[$key]['positif'] = isset($temp[$key]['positif']) ? ($temp[$key]['positif'] + pow(($v - $ideal['positif'][$k]), 2)) : pow(($v - $ideal['positif'][$k]), 2);
            $temp[$key]['negatif'] = isset($temp[$key]['negatif']) ? ($temp[$key]['negatif'] + pow(($v - $ideal['negatif'][$k]), 2)) : pow(($v - $ideal['negatif'][$k]), 2);
        }
        $temp[$key]['positif'] = sqrt($temp[$key]['positif']);
        $temp[$key]['negatif'] = sqrt($temp[$key]['negatif']);
    }
    return $temp;
}

function TOPSIS_preferensi($array)
{
    global $KRITERIA;

    $temp = array();

    foreach ($array as $key => $value) {
        $temp[$key] = $value['negatif'] / ($value['positif'] + $value['negatif']);
    }

    return $temp;
}

function get_rank($array)
{
    $data = $array;
    arsort($data);
    $no = 1;
    $new = array();
    foreach ($data as $key => $value) {
        $new[$key] = $no++;
    }
    return $new;
}


function get($key)
{
    return isset($_GET[$key]) ? $_GET[$key] : null;
}

function post($key)
{
    return isset($_POST[$key]) ? $_POST[$key] : null;
}


function isActive($name)
{

    if (is_array($name)) {
        $result = false;
        foreach ($name as $m) {
            if ($m == get('m')) {
                $result = true;
            }
        }
        return  $result ?  'active'  : null;
    }

    return get('m') == $name ?  'active'  : null;
}