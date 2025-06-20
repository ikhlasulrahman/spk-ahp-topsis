<?php
require_once 'functions.php';

// ===== KONTROL AKSES =====
if (isset($_SESSION['level'])) {
    $level = $_SESSION['level'];
    $mod = get('m');
    $act = get('act');

    // Manajer (level 2) tidak boleh melakukan aksi apa pun kecuali logout dan ganti password
    if ($level == '2' && !in_array($mod, ['profil']) && !in_array($act, ['login', 'logout'])) {
        set_message('Manajer tidak memiliki hak untuk mengubah data.', 'danger');
        redirect_js('index.php?m=' . str_replace(array('_tambah', '_ubah', '_hapus'), '', $mod));
        exit;
    }
    
    // Pelatih (level 1) tidak boleh mengelola akun
    $disallowed_for_pelatih = ['akun', 'akun_tambah', 'akun_ubah', 'akun_hapus'];
    if ($level == '1' && (in_array($mod, $disallowed_for_pelatih) || $act == 'akun_hapus')) {
        set_message('Pelatih tidak memiliki hak akses untuk mengelola akun.', 'danger');
        redirect_js('index.php?m=home');
        exit;
    }
}
// ==========================

$PERIODE = get('periode');

// ----- LOGIN & LOGOUT -----
if ($act == 'login') {
    $user = esc_field($_POST['user']);
    $pass = esc_field($_POST['pass']);

    $row = $db->get_row("SELECT * FROM tb_user WHERE user='$user' AND pass='$pass'");
    if ($row) {
        $_SESSION['login'] = $row->user;
        $_SESSION['level'] = strtolower($row->level);
        set_message("Selamat datang, $row->nama!", "info");
        redirect_js("index.php");
    } else {
        set_message("Kombinasi username dan password salah.", "danger");
        redirect_js("login.php");
    }
} elseif ($act == 'logout') {
    unset($_SESSION['login'], $_SESSION['level']);
    set_message('Anda telah berhasil logout', 'info');
    header("location:login.php");
} 
// ----- UBAH PASSWORD -----
// ----- UBAH PROFIL & PASSWORD PENGGUNA -----
elseif ($mod == 'profil') {
    $nama = $_POST['nama'];
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    $pass3 = $_POST['pass3'];

    // Cek nama tidak boleh kosong
    if ($nama == '') {
        print_msg("Nama tidak boleh kosong!", "danger");
    } else {
        // Update nama
        $db->query("UPDATE tb_user SET nama='$nama' WHERE user='$_SESSION[login]'");
        
        // Blok ini hanya berjalan jika pengguna ingin mengubah password
        if ($pass1 != '' || $pass2 != '' || $pass3 != '') {
            $row = $db->get_row("SELECT * FROM tb_user WHERE user='$_SESSION[login]' AND pass='$pass1'");

            if (!$row) {
                print_msg('Password lama salah.', 'danger');
            } elseif ($pass2 != $pass3) {
                print_msg('Password baru dan konfirmasinya tidak sama.', 'danger');
            } else {
                // Update password jika validasi berhasil
                $db->query("UPDATE tb_user SET pass='$pass2' WHERE user='$_SESSION[login]'");
                set_message('Profil dan password berhasil diubah.', 'success');
                redirect_js("index.php?m=profil");
            }
        } else {
            // Jika hanya mengubah nama
            set_message('Profil berhasil diubah.', 'success');
            redirect_js("index.php?m=profil");
        }
    }
}

// ----- CRUD AKUN (KHUSUS ADMIN) -----
elseif ($mod == 'akun_tambah') {
    $nama = $_POST['nama'];
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $level = $_POST['level'];

    if ($nama == '' || $user == '' || $pass == '' || $level == '')
        print_msg("Semua field bertanda * wajib diisi!", 'danger');
    elseif ($db->get_results("SELECT * FROM tb_user WHERE user='$user'"))
        print_msg("Username sudah ada!", 'danger');
    else {
        $db->query("INSERT INTO tb_user (nama, user, pass, level) VALUES ('$nama', '$user', '$pass', '$level')");
        set_message('Akun berhasil ditambah!', 'success');
        redirect_js("index.php?m=akun");
    }
} elseif ($mod == 'akun_ubah') {
    $id = get('ID');
    $nama = $_POST['nama'];
    $user = $_POST['user'];
    $level = $_POST['level'];
    
    // Variabel untuk password
    $pass_baru = $_POST['pass_baru'];
    $konfirmasi_pass = $_POST['konfirmasi_pass'];
    $pass_admin = $_POST['pass_admin'];

    // Validasi dasar
    if ($nama == '' || $user == '' || $level == '') {
        print_msg("Field Nama, Username, dan Level tidak boleh kosong!", 'danger');
    } elseif ($db->get_var("SELECT COUNT(*) FROM tb_user WHERE user='$user' AND id_akun<>'$id'") > 0) {
        print_msg("Username '$user' sudah digunakan oleh akun lain!", 'danger');
    } else {
        $sql_pass = '';

        // Blok ini hanya berjalan jika admin mengisi password baru
        if ($pass_baru != '') {
            if ($pass_baru != $konfirmasi_pass) {
                print_msg("Password baru dan konfirmasinya tidak cocok!", 'danger');
                return; // Hentikan eksekusi jika tidak cocok
            }
            if ($pass_admin == '') {
                print_msg("Untuk mengubah password, Anda harus memasukkan password admin Anda!", 'danger');
                return; // Hentikan eksekusi
            }

            // Verifikasi password admin yang login
            $row_admin = $db->get_row("SELECT * FROM tb_user WHERE user='$_SESSION[login]' AND pass='$pass_admin'");
            if (!$row_admin) {
                print_msg("Password admin yang Anda masukkan salah. Perubahan dibatalkan.", 'danger');
                return; // Hentikan eksekusi
            }
            
            // Jika semua verifikasi password berhasil
            $sql_pass = ", pass='$pass_baru'";
        }
        
        // Eksekusi query ke database
        $db->query("UPDATE tb_user SET nama='$nama', user='$user', level='$level' $sql_pass WHERE id_akun='$id'");
        
        set_message('Akun berhasil diubah!', 'success');
        redirect_js("index.php?m=akun");
    }
} elseif ($act == 'akun_hapus') {
    $id = get('ID');
    $db->query("DELETE FROM tb_user WHERE id_akun='$id'");
    set_message('Akun berhasil dihapus!', 'success');
    header("location:index.php?m=akun");
}

// ===== CRUD LAINNYA (KRITERIA, ALTERNATIF, DLL) =====
/** ALTERNATIF */
elseif ($mod == 'alternatif_tambah') {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    if ($kode == '' || $nama == '')
        print_msg("Field yang bertanda * tidak boleh kosong!", 'danger');
    elseif ($db->get_results("SELECT * FROM tb_alternatif WHERE kode_alternatif='$kode' and tahun = '$PERIODE'"))
        print_msg("Kode sudah ada!", 'danger');
    else {
        $db->query("INSERT INTO tb_alternatif (tahun, kode_alternatif, nama_alternatif, jabatan) VALUES ('$PERIODE', '$kode', '$nama', '$jabatan')");
        $db->query("INSERT INTO tb_rel_alternatif(tahun, kode_alternatif, kode_kriteria, nilai) SELECT '$PERIODE', '$kode', kode_kriteria, 0 FROM tb_kriteria where tahun = '$PERIODE'");
        set_message('Data pemain berhasil ditambah!', 'success');
        redirect_js("index.php?m=alternatif&periode=$PERIODE");
    }
} else if ($mod == 'alternatif_ubah') {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    if ($kode == '' || $nama == '')
        print_msg("Field yang bertanda * tidak boleh kosong!", 'danger');
    else {
        $db->query("UPDATE tb_alternatif SET nama_alternatif='$nama', jabatan='$jabatan' WHERE tahun = '$PERIODE' and kode_alternatif='" . get('ID') . "'");
        set_message('Data pemain berhasil diubah!', 'success');
        redirect_js("index.php?m=alternatif&periode=$PERIODE");
    }
} else if ($act == 'alternatif_hapus') {
    $db->query("DELETE FROM tb_alternatif WHERE tahun = '$PERIODE' and kode_alternatif='" . get('ID') . "'");
    $db->query("DELETE FROM tb_rel_alternatif WHERE tahun = '$PERIODE' and kode_alternatif='" . get('ID') . "'");
    set_message('Data pemain berhasil dihapus!', 'success');
    header("location:index.php?m=alternatif&periode=$PERIODE");
}

/** KRITERIA */
elseif ($mod == 'kriteria_tambah') {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $atribut = $_POST['atribut'];

    if ($kode == '' || $nama == '' || $atribut == '')
        print_msg("Field bertanda * tidak boleh kosong!", 'danger');
    elseif ($db->get_results("SELECT * FROM tb_kriteria WHERE kode_kriteria='$kode' and tahun = '$PERIODE'"))
        print_msg("Kode sudah ada!", 'danger');
    else {
        $db->query("INSERT INTO tb_kriteria (tahun, kode_kriteria, nama_kriteria, atribut) VALUES ('$PERIODE', '$kode', '$nama', '$atribut')");
        $db->query("INSERT INTO tb_rel_kriteria(tahun, ID1, ID2, nilai) SELECT '$PERIODE', '$kode', kode_kriteria, 1 FROM tb_kriteria where tahun = '$PERIODE'");
        $db->query("INSERT INTO tb_rel_kriteria(tahun, ID1, ID2, nilai) SELECT '$PERIODE', kode_kriteria, '$kode', 1 FROM tb_kriteria WHERE kode_kriteria<>'$kode' and tahun = '$PERIODE'");
        $db->query("INSERT INTO tb_rel_alternatif(tahun, kode_alternatif, kode_kriteria, nilai) SELECT '$PERIODE', kode_alternatif, '$kode', 0  FROM tb_alternatif where tahun = '$PERIODE'");
        set_message('Data kriteria berhasil ditambah!', 'success');
        redirect_js("index.php?m=kriteria&periode=$PERIODE");
    }
} else if ($mod == 'kriteria_ubah') {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $atribut = $_POST['atribut'];

    if ($kode == '' || $nama == '' || $atribut == '')
        print_msg("Field bertanda * tidak boleh kosong!", 'danger');
    elseif ($db->get_results("SELECT * FROM tb_kriteria WHERE kode_kriteria='$kode' and tahun = '$PERIODE' AND kode_kriteria<>'" . get('ID') . "'"))
        print_msg("Kode sudah ada!", 'danger');
    else {
        $db->query("UPDATE tb_kriteria SET kode_kriteria='$kode', nama_kriteria='$nama', atribut='$atribut' WHERE kode_kriteria='" . get('ID') . "' and tahun = '$PERIODE'");
        set_message('Data kriteria berhasil diubah!', 'success');
        redirect_js("index.php?m=kriteria&periode=$PERIODE");
    }
} else if ($act == 'kriteria_hapus') {
    $db->query("DELETE FROM tb_kriteria WHERE kode_kriteria='" . get('ID') . "' and tahun = '$PERIODE'");
    $db->query("DELETE FROM tb_rel_kriteria WHERE ID1='" . get('ID') . "' OR ID2='" . get('ID') . "' and tahun = '$PERIODE'");
    $db->query("DELETE FROM tb_rel_alternatif WHERE kode_kriteria='" . get('ID') . "' and tahun = '$PERIODE'");
    set_message('Data kriteria berhasil dihapus!', 'success');
    header("location:index.php?m=kriteria&periode=$PERIODE");
}

/** RELASI ALTERNATIF */
else if ($mod == 'rel_alternatif_ubah') {
    foreach ($_POST as $key => $value) {
        $ID = str_replace('ID-', '', $key);
        $db->query("UPDATE tb_rel_alternatif SET nilai='$value' WHERE ID='$ID' and tahun=$PERIODE");
    }
    set_message('Nilai bobot alternatif berhasil diubah!', 'success');
    redirect_js("index.php?m=rel_alternatif&periode=$PERIODE");
}

/** RELASI KRITERIA */
else if ($mod == 'rel_kriteria') {
    $ID1 = $_POST['ID1'];
    $ID2 = $_POST['ID2'];
    $nilai = abs($_POST['nilai']);

    if ($ID1 == $ID2 && $nilai <> 1) {
        print_msg("Kriteria yang sama harus bernilai 1.", 'danger');
    } else {
        $db->query("UPDATE tb_rel_kriteria SET nilai=$nilai WHERE ID1='$ID1' AND ID2='$ID2' and tahun = '$PERIODE'");
        $db->query("UPDATE tb_rel_kriteria SET nilai=1/$nilai WHERE ID2='$ID1' AND ID1='$ID2' and tahun = '$PERIODE'");
        set_message('Nilai kriteria berhasil diubah.', 'success');
        redirect_js("index.php?m=rel_kriteria&periode=$PERIODE");
    }
}

/** PERIODE */
elseif ($mod == 'periode_tambah') {
    $nama = $_POST['nama'];
    $keterangan = $_POST['keterangan'];

    if ($nama == '')
        print_msg("Field bertanda * tidak boleh kosong!", 'danger');
    else {
        $db->query("INSERT INTO tb_periode (nama, keterangan) VALUES ('$nama', '$keterangan')");
        set_message('Data lini berhasil ditambah!', 'success');
        redirect_js("index.php?m=periode");
    }
} else if ($mod == 'periode_ubah') {
    $tahun = $_POST['tahun'];
    $nama = $_POST['nama'];
    $keterangan = $_POST['keterangan'];

    if ($tahun == '' || $nama == '')
        print_msg("Field bertanda * tidak boleh kosong!", 'danger');
    elseif ($db->get_results("SELECT * FROM tb_periode WHERE tahun='$tahun' AND tahun<>'" . get('ID') . "'"))
        print_msg("Tahun sudah ada!", 'danger');
    else {
        $db->query("UPDATE tb_periode SET tahun='$tahun', nama='$nama', keterangan='$keterangan' WHERE tahun='" . get('ID') . "'");
        set_message('Data lini berhasil diubah!', 'success');
        redirect_js("index.php?m=periode");
    }
} else if ($act == 'periode_hapus') {
    $db->query("DELETE FROM tb_periode WHERE tahun='" . get('ID') . "'");
    set_message('Data lini berhasil dihapus!', 'success');
    header("location:index.php?m=periode");
}

?>