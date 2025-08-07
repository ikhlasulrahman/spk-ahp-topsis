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

$ID_LINI = get('id_lini');

// ----- LOGIN & LOGOUT -----
if ($act == 'login') {
    $user = esc_field($_POST['user']);
    $pass = esc_field($_POST['pass']);

    $row = $db->get_row("SELECT * FROM tb_user WHERE user='$user'");
    
    // Verifikasi password menggunakan password_verify()
    if ($row && password_verify($pass, $row->pass)) {
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
    $pass1 = $_POST['pass1']; // Password Lama
    $pass2 = $_POST['pass2']; // Password Baru
    $pass3 = $_POST['pass3']; // Konfirmasi Password Baru

    // Cek nama tidak boleh kosong
    if ($nama == '') {
        print_msg("Nama tidak boleh kosong!", "danger");
    } else {
        // Update nama
        $db->query("UPDATE tb_user SET nama='$nama' WHERE user='$_SESSION[login]'");
        
        // Blok ini hanya berjalan jika pengguna ingin mengubah password
        if ($pass1 != '' || $pass2 != '' || $pass3 != '') {
        $row = $db->get_row("SELECT * FROM tb_user WHERE user='$_SESSION[login]'");

        // Verifikasi password lama
        if (!password_verify($pass1, $row->pass)) {
            print_msg('Password lama salah.', 'danger');
        } elseif ($pass2 != $pass3) {
            print_msg('Password baru dan konfirmasinya tidak sama.', 'danger');
        } elseif ($pass2 == '') {
            print_msg('Password baru tidak boleh kosong.', 'danger');
        } else {
            // Hash password baru sebelum disimpan
            $hashed_password = password_hash($pass2, PASSWORD_DEFAULT);
            $db->query("UPDATE tb_user SET pass='$hashed_password' WHERE user='$_SESSION[login]'");
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
        // Hash password sebelum dimasukkan ke database
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
        $db->query("INSERT INTO tb_user (nama, user, pass, level) VALUES ('$nama', '$user', '$hashed_password', '$level')");
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
            $row_admin = $db->get_row("SELECT * FROM tb_user WHERE user='$_SESSION[login]'");
        if (!$row_admin || !password_verify($pass_admin, $row_admin->pass)) {
            print_msg("Password admin yang Anda masukkan salah. Perubahan dibatalkan.", 'danger');
            return;
        }
        
        // Jika semua verifikasi password berhasil, hash password baru
        $hashed_password = password_hash($pass_baru, PASSWORD_DEFAULT);
        $sql_pass = ", pass='$hashed_password'";
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

// ===== CRUD Sesi Penilaian =====
elseif ($mod == 'penilaian_tambah') {
    $tanggal = $_POST['tanggal'];
    $jenis = $_POST['jenis'];
    $keterangan = $_POST['keterangan'];

    if ($tanggal == '' || $keterangan == '')
        print_msg("Field bertanda * tidak boleh kosong!", "danger");
    else {
        $db->query("INSERT INTO tb_penilaian (id_lini, tanggal, jenis, keterangan) VALUES ('$ID_LINI', '$tanggal', '$jenis', '$keterangan')");
        $id_penilaian_baru = $db->insert_id;

        // Untuk setiap pemain di lini ini, tambahkan nilai default (0) ke sesi baru
        $db->query("INSERT INTO tb_rel_alternatif (id_penilaian, id_lini, kode_alternatif, kode_kriteria, nilai) 
                    SELECT '$id_penilaian_baru', a.id_lini, a.kode_alternatif, k.kode_kriteria, 0 
                    FROM tb_alternatif a, tb_kriteria k 
                    WHERE a.id_lini = '$ID_LINI' AND k.id_lini = '$ID_LINI'");
        set_message('Sesi penilaian berhasil ditambah!', 'success');
        redirect_js("index.php?m=penilaian&id_lini=$ID_LINI");
    }
} elseif ($mod == 'penilaian_ubah') {
    $id_penilaian = get('id_penilaian');
    $tanggal = $_POST['tanggal'];
    $jenis = $_POST['jenis'];
    $keterangan = $_POST['keterangan'];

    $db->query("UPDATE tb_penilaian SET tanggal='$tanggal', jenis='$jenis', keterangan='$keterangan' WHERE id_penilaian='$id_penilaian'");
    set_message('Sesi penilaian berhasil diubah!', 'success');
    redirect_js("index.php?m=penilaian&id_lini=" . get('id_lini'));
} elseif ($act == 'penilaian_hapus') {
    $db->query("DELETE FROM tb_penilaian WHERE id_penilaian='" . get('id_penilaian') . "'");
    $db->query("DELETE FROM tb_rel_alternatif WHERE id_penilaian='" . get('id_penilaian') . "'"); // Hapus juga nilai terkait
    set_message('Sesi penilaian berhasil dihapus!', 'success');
    header("location:index.php?m=penilaian&id_lini=$ID_LINI");
}

// ===== CRUD LAINNYA (KRITERIA, ALTERNATIF, DLL) =====
/** ALTERNATIF */
elseif ($mod == 'alternatif_tambah') {
    // Ambil semua data dari form
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $tinggi_badan = $_POST['tinggi_badan'];
    $berat_badan = $_POST['berat_badan'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $asal_klub = $_POST['asal_klub'];
    $kaki_dominan = $_POST['kaki_dominan'];
    $catatan = $_POST['catatan'];

    if ($kode == '' || $nama == '')
        print_msg("Field yang bertanda * tidak boleh kosong!", 'danger');
    elseif ($db->get_results("SELECT * FROM tb_alternatif WHERE kode_alternatif='$kode' and id_lini = '$ID_LINI'"))
        print_msg("Kode sudah ada!", 'danger');
    else {
        // Query INSERT dengan kolom-kolom baru
        $db->query("INSERT INTO tb_alternatif (id_lini, kode_alternatif, nama_alternatif, jabatan, tinggi_badan, berat_badan, tanggal_lahir, asal_klub, kaki_dominan, catatan) 
                    VALUES ('$ID_LINI', '$kode', '$nama', '$jabatan', '$tinggi_badan', '$berat_badan', '$tanggal_lahir', '$asal_klub', '$kaki_dominan', '$catatan')");
        
        // Untuk setiap sesi penilaian yang ada di lini ini, tambahkan nilai default (0) untuk pemain baru
        $db->query("INSERT INTO tb_rel_alternatif (id_penilaian, id_lini, kode_alternatif, kode_kriteria, nilai) 
                    SELECT p.id_penilaian, '$ID_LINI', '$kode', k.kode_kriteria, 0 
                    FROM tb_penilaian p, tb_kriteria k 
                    WHERE p.id_lini = '$ID_LINI' AND k.id_lini = '$ID_LINI'");
        set_message('Pemain baru berhasil ditambah. Nilai awal telah di-generate untuk semua sesi penilaian yang ada.', 'success');
        redirect_js("index.php?m=alternatif&id_lini=$ID_LINI");
    }
} else if ($mod == 'alternatif_ubah') {
    // Ambil semua data dari form
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $tinggi_badan = $_POST['tinggi_badan'];
    $berat_badan = $_POST['berat_badan'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $asal_klub = $_POST['asal_klub'];
    $kaki_dominan = $_POST['kaki_dominan'];
    $catatan = $_POST['catatan'];

    if ($kode == '' || $nama == '')
        print_msg("Field yang bertanda * tidak boleh kosong!", 'danger');
    else {
        // Query UPDATE dengan kolom-kolom baru
        $db->query("UPDATE tb_alternatif SET 
            nama_alternatif='$nama', 
            jabatan='$jabatan',
            tinggi_badan='$tinggi_badan',
            berat_badan='$berat_badan',
            tanggal_lahir='$tanggal_lahir',
            asal_klub='$asal_klub',
            kaki_dominan='$kaki_dominan',
            catatan='$catatan'
        WHERE id_lini = '$ID_LINI' and kode_alternatif='" . get('ID') . "'");
        
        set_message('Data pemain berhasil diubah!', 'success');
        redirect_js("index.php?m=alternatif&id_lini=$ID_LINI");
    }
}

/** KRITERIA */
elseif ($mod == 'kriteria_tambah') {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $atribut = $_POST['atribut'];

    if ($kode == '' || $nama == '' || $atribut == '')
        print_msg("Field bertanda * tidak boleh kosong!", 'danger');
    elseif ($db->get_results("SELECT * FROM tb_kriteria WHERE kode_kriteria='$kode' and id_lini = '$ID_LINI'"))
        print_msg("Kode sudah ada!", 'danger');
    else {
        $db->query("INSERT INTO tb_kriteria (id_lini, kode_kriteria, nama_kriteria, atribut) VALUES ('$ID_LINI', '$kode', '$nama', '$atribut')");
        $db->query("INSERT INTO tb_rel_kriteria(id_lini, ID1, ID2, nilai) SELECT '$ID_LINI', '$kode', kode_kriteria, 1 FROM tb_kriteria where id_lini = '$ID_LINI'");
        $db->query("INSERT INTO tb_rel_kriteria(id_lini, ID1, ID2, nilai) SELECT '$ID_LINI', kode_kriteria, '$kode', 1 FROM tb_kriteria WHERE kode_kriteria<>'$kode' and id_lini = '$ID_LINI'");
        $db->query("INSERT INTO tb_rel_alternatif (id_penilaian, id_lini, kode_alternatif, kode_kriteria, nilai)
+                    SELECT p.id_penilaian, a.id_lini, a.kode_alternatif, '$kode', 0
+                    FROM tb_alternatif a, tb_penilaian p
+                    WHERE a.id_lini = '$ID_LINI' AND p.id_lini = '$ID_LINI'");
        set_message('Data kriteria berhasil ditambah!', 'success');
        redirect_js("index.php?m=kriteria&id_lini=$ID_LINI");
    }
} else if ($mod == 'kriteria_ubah') {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $atribut = $_POST['atribut'];

    if ($kode == '' || $nama == '' || $atribut == '')
        print_msg("Field bertanda * tidak boleh kosong!", 'danger');
    elseif ($db->get_results("SELECT * FROM tb_kriteria WHERE kode_kriteria='$kode' and id_lini = '$ID_LINI' AND kode_kriteria<>'" . get('ID') . "'"))
        print_msg("Kode sudah ada!", 'danger');
    else {
        $db->query("UPDATE tb_kriteria SET kode_kriteria='$kode', nama_kriteria='$nama', atribut='$atribut' WHERE kode_kriteria='" . get('ID') . "' and id_lini = '$ID_LINI'");
        set_message('Data kriteria berhasil diubah!', 'success');
        redirect_js("index.php?m=kriteria&id_lini=$ID_LINI");
    }
} else if ($act == 'kriteria_hapus') {
    $db->query("DELETE FROM tb_kriteria WHERE kode_kriteria='" . get('ID') . "' and id_lini = '$ID_LINI'");
    $db->query("DELETE FROM tb_rel_kriteria WHERE ID1='" . get('ID') . "' OR ID2='" . get('ID') . "' and id_lini = '$ID_LINI'");
    $db->query("DELETE FROM tb_rel_alternatif WHERE kode_kriteria='" . get('ID') . "' and id_lini = '$ID_LINI'");
    set_message('Data kriteria berhasil dihapus!', 'success');
    header("location:index.php?m=kriteria&id_lini=$ID_LINI");
}

/** RELASI ALTERNATIF */
else if ($mod == 'rel_alternatif_ubah') {
    $id_penilaian = get('id_penilaian');
    foreach ($_POST as $key => $value) {
        $ID = str_replace('ID-', '', $key);
        $db->query("UPDATE tb_rel_alternatif SET nilai='$value' WHERE ID='$ID' AND id_penilaian='$id_penilaian'");
    }
    set_message('Nilai bobot alternatif berhasil diubah!', 'success');
    redirect_js("index.php?m=rel_alternatif&id_lini=$ID_LINI&id_penilaian=$id_penilaian");
}

/** RELASI KRITERIA */
elseif ($mod == 'rel_kriteria') {
    $id_lini = get('id_lini');
    if (isset($_POST['nilai'])) {
        foreach ($_POST['nilai'] as $key => $value) {
            // Memecah kunci 'C1-C2' menjadi ['C1', 'C2']
            $k = explode('-', $key);
            $ID1 = $k[0];
            $ID2 = $k[1];
            $nilai = abs(floatval($value));

            // Memastikan nilai tidak 0 untuk mencegah error pembagian
            if ($nilai == 0) $nilai = 1; 

            // Update nilai perbandingan (misal: C1 vs C2)
            $db->query("UPDATE tb_rel_kriteria SET nilai=$nilai WHERE ID1='$ID1' AND ID2='$ID2' AND id_lini='$id_lini'");
            
            // Update nilai kebalikannya (misal: C2 vs C1)
            $db->query("UPDATE tb_rel_kriteria SET nilai=1/$nilai WHERE ID2='$ID1' AND ID1='$ID2' AND id_lini='$id_lini'");
        }
        set_message('Nilai bobot kriteria berhasil diperbarui.', 'success');
        redirect_js("index.php?m=rel_kriteria&id_lini=$id_lini");
    }
}

/** LINI */
elseif ($mod == 'lini_tambah') {
    $nama = $_POST['nama'];
    $kebutuhan = (int)$_POST['kebutuhan']; // Ambil nilai kebutuhan
    $keterangan = $_POST['keterangan'];

    if ($nama == '' || $kebutuhan < 1)
        print_msg("Field bertanda * tidak boleh kosong dan kebutuhan minimal 1!", "danger");
    else {
        $db->query("INSERT INTO tb_lini (nama, kebutuhan, keterangan) VALUES ('$nama', '$kebutuhan', '$keterangan')");
        set_message('Data lini berhasil ditambah!', 'success');
        redirect_js("index.php?m=lini");
    }
} else if ($mod == 'lini_ubah') {
    $id_lini = $_POST['id_lini'];
    $nama = $_POST['nama'];
    $kebutuhan = (int)$_POST['kebutuhan']; // Ambil nilai kebutuhan
    $keterangan = $_POST['keterangan'];

    if ($id_lini == '' || $nama == '' || $kebutuhan < 1)
        print_msg("Field bertanda * tidak boleh kosong dan kebutuhan minimal 1!", "danger");
    else {
        $db->query("UPDATE tb_lini SET nama='$nama', kebutuhan='$kebutuhan', keterangan='$keterangan' WHERE id_lini='" . get('ID') . "'");
        set_message('Data lini berhasil diubah!', 'success');
        redirect_js("index.php?m=lini");
    }
} else if ($act == 'lini_hapus') {
    $db->query("DELETE FROM tb_lini WHERE id_lini='" . get('ID') . "'");
    set_message('Data lini berhasil dihapus!', 'success');
    header("location:index.php?m=lini");
}

?>