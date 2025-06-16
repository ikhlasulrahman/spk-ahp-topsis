-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Jun 2025 pada 12.42
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ahp-topsis`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_alternatif`
--

CREATE TABLE `tb_alternatif` (
  `kode_alternatif` varchar(16) NOT NULL,
  `tahun` int(11) NOT NULL,
  `nama_alternatif` varchar(256) NOT NULL DEFAULT '',
  `jabatan` varchar(256) NOT NULL DEFAULT '',
  `total` double NOT NULL,
  `rank` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_alternatif`
--

INSERT INTO `tb_alternatif` (`kode_alternatif`, `tahun`, `nama_alternatif`, `jabatan`, `total`, `rank`) VALUES
('GK1', 1, 'Imam', '', 0.84806594113985, 1),
('GK2', 1, 'ajisanto', '', 0.14613686773581, 3),
('GK3', 1, 'malik', '', 0.51509373177046, 2),
('CB3', 2, 'Sulaiman', '', 0, 0),
('CB1', 2, 'Hafiz', '', 0, 0),
('CB2', 2, 'Hamzah', '', 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_kriteria`
--

CREATE TABLE `tb_kriteria` (
  `kode_kriteria` varchar(16) NOT NULL,
  `tahun` int(11) NOT NULL,
  `nama_kriteria` varchar(256) NOT NULL,
  `atribut` varchar(256) NOT NULL DEFAULT 'benefit'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_kriteria`
--

INSERT INTO `tb_kriteria` (`kode_kriteria`, `tahun`, `nama_kriteria`, `atribut`) VALUES
('C1', 1, 'passing', 'benefit'),
('C2', 1, 'Shooting', 'benefit'),
('C3', 1, 'Dribbling', 'benefit'),
('C5', 2, 'tekel', 'benefit'),
('C4', 2, 'Intercepting', 'benefit'),
('C6', 2, 'kartu', 'benefit');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_periode`
--

CREATE TABLE `tb_periode` (
  `tahun` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_periode`
--

INSERT INTO `tb_periode` (`tahun`, `nama`, `keterangan`) VALUES
(1, 'Kiper', ''),
(2, 'Bek', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_rel_alternatif`
--

CREATE TABLE `tb_rel_alternatif` (
  `ID` int(11) NOT NULL,
  `tahun` int(11) DEFAULT NULL,
  `kode_alternatif` varchar(16) DEFAULT NULL,
  `kode_kriteria` varchar(16) DEFAULT NULL,
  `nilai` double DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_rel_alternatif`
--

INSERT INTO `tb_rel_alternatif` (`ID`, `tahun`, `kode_alternatif`, `kode_kriteria`, `nilai`) VALUES
(663, 1, 'GK1', 'C1', 13),
(664, 1, 'GK1', 'C2', 7),
(665, 1, 'GK1', 'C3', 8),
(666, 1, 'GK2', 'C1', 5),
(667, 1, 'GK2', 'C2', 10),
(668, 1, 'GK2', 'C3', 9),
(669, 1, 'GK3', 'C1', 9),
(670, 1, 'GK3', 'C2', 10),
(671, 1, 'GK3', 'C3', 10),
(694, 2, 'CB2', 'C5', 0),
(698, 2, 'CB3', 'C6', 0),
(693, 2, 'CB2', 'C4', 0),
(696, 2, 'CB3', 'C4', 0),
(697, 2, 'CB3', 'C5', 0),
(695, 2, 'CB2', 'C6', 0),
(692, 2, 'CB1', 'C6', 0),
(690, 2, 'CB1', 'C4', 0),
(691, 2, 'CB1', 'C5', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_rel_kriteria`
--

CREATE TABLE `tb_rel_kriteria` (
  `ID` int(11) NOT NULL,
  `tahun` int(11) DEFAULT NULL,
  `ID1` varchar(16) DEFAULT NULL,
  `ID2` varchar(16) DEFAULT NULL,
  `nilai` double DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_rel_kriteria`
--

INSERT INTO `tb_rel_kriteria` (`ID`, `tahun`, `ID1`, `ID2`, `nilai`) VALUES
(844, 1, 'C1', 'C1', 1),
(845, 1, 'C2', 'C1', 0.25),
(846, 1, 'C2', 'C2', 1),
(847, 1, 'C1', 'C2', 4),
(848, 1, 'C3', 'C1', 0.333333333),
(849, 1, 'C3', 'C2', 0.333333333),
(850, 1, 'C3', 'C3', 1),
(851, 1, 'C1', 'C3', 3),
(852, 1, 'C2', 'C3', 3),
(853, 2, 'C1', 'C1', 1),
(867, 2, 'C6', 'C5', 1),
(866, 2, 'C6', 'C4', 1),
(865, 2, 'C4', 'C5', 1),
(864, 2, 'C5', 'C5', 1),
(863, 2, 'C5', 'C4', 1),
(862, 2, 'C4', 'C4', 1),
(868, 2, 'C6', 'C6', 1),
(869, 2, 'C4', 'C6', 1),
(870, 2, 'C5', 'C6', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_user`
--

CREATE TABLE `tb_user` (
  `id_akun` int(11) NOT NULL,
  `user` varchar(16) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `pass` varchar(16) DEFAULT NULL,
  `level` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_user`
--

INSERT INTO `tb_user` (`id_akun`, `user`, `nama`, `pass`, `level`) VALUES
(2, 'pelatih', 'Hadi Pamungkas', 'hadipelatih', '1'),
(3, 'akumanajer', 'aku', 'manajer', '2');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_alternatif`
--
ALTER TABLE `tb_alternatif`
  ADD PRIMARY KEY (`kode_alternatif`,`tahun`);

--
-- Indeks untuk tabel `tb_kriteria`
--
ALTER TABLE `tb_kriteria`
  ADD PRIMARY KEY (`kode_kriteria`,`tahun`);

--
-- Indeks untuk tabel `tb_periode`
--
ALTER TABLE `tb_periode`
  ADD PRIMARY KEY (`tahun`);

--
-- Indeks untuk tabel `tb_rel_alternatif`
--
ALTER TABLE `tb_rel_alternatif`
  ADD PRIMARY KEY (`ID`);

--
-- Indeks untuk tabel `tb_rel_kriteria`
--
ALTER TABLE `tb_rel_kriteria`
  ADD PRIMARY KEY (`ID`);

--
-- Indeks untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_akun`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_periode`
--
ALTER TABLE `tb_periode`
  MODIFY `tahun` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tb_rel_alternatif`
--
ALTER TABLE `tb_rel_alternatif`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=699;

--
-- AUTO_INCREMENT untuk tabel `tb_rel_kriteria`
--
ALTER TABLE `tb_rel_kriteria`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=871;

--
-- AUTO_INCREMENT untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_akun` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
