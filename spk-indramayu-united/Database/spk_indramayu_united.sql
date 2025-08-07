-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 07 Agu 2025 pada 06.17
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
-- Database: `spk_indramayu_united`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_alternatif`
--

CREATE TABLE `tb_alternatif` (
  `kode_alternatif` varchar(16) NOT NULL,
  `id_lini` int(11) NOT NULL,
  `nama_alternatif` varchar(256) NOT NULL DEFAULT '',
  `jabatan` varchar(256) NOT NULL DEFAULT '',
  `total` double NOT NULL,
  `rank` int(11) NOT NULL DEFAULT 0,
  `tinggi_badan` int(11) DEFAULT NULL COMMENT 'dalam cm',
  `berat_badan` int(11) DEFAULT NULL COMMENT 'dalam kg',
  `tanggal_lahir` date DEFAULT NULL,
  `asal_klub` varchar(100) DEFAULT NULL,
  `kaki_dominan` varchar(20) DEFAULT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_alternatif`
--

INSERT INTO `tb_alternatif` (`kode_alternatif`, `id_lini`, `nama_alternatif`, `jabatan`, `total`, `rank`, `tinggi_badan`, `berat_badan`, `tanggal_lahir`, `asal_klub`, `kaki_dominan`, `catatan`) VALUES
('GK1', 1, 'Saputra', 'Kiper', 0.90620414642073, 1, 165, 50, '2009-12-31', '-', 'Kanan', '-'),
('GK2', 1, 'Kevin', 'Kiper', 0.057095014815282, 3, NULL, NULL, NULL, NULL, NULL, NULL),
('GK3', 1, 'Rizki', 'Kiper', 0.71880217706965, 2, NULL, NULL, NULL, NULL, NULL, NULL),
('B3', 2, 'Adriansah', 'Bek Tengah', 0.84002152988491, 2, NULL, NULL, NULL, NULL, NULL, NULL),
('B2', 2, 'April', 'Bek Tengah', 0.85189860154714, 1, NULL, NULL, NULL, NULL, NULL, NULL),
('B1', 2, 'Dimas', 'Bek Tengah', 0.43930181515324, 6, NULL, NULL, NULL, NULL, NULL, NULL),
('B4', 2, 'Agung', 'Bek Tengah', 0.53590812285279, 5, NULL, NULL, NULL, NULL, NULL, NULL),
('B5', 2, 'Fajar', 'Bek Kanan', 0.18429782033936, 7, NULL, NULL, NULL, NULL, NULL, NULL),
('B6', 2, 'Adnan', 'Bek Kanan', 0.62934261328845, 3, NULL, NULL, NULL, NULL, NULL, NULL),
('B7', 2, 'Rizky', 'Bek Kiri', 0.14407588013955, 8, NULL, NULL, NULL, NULL, NULL, NULL),
('B8', 2, 'Arif', 'Bek Kiri', 0.54160381586172, 4, NULL, NULL, NULL, NULL, NULL, NULL),
('G1', 3, 'Satria', 'Gelandang Bertahan', 0.4111643261417, 4, NULL, NULL, NULL, NULL, NULL, NULL),
('G2', 3, 'Taufiqurohman', 'Gelandang Bertahan', 0.90431747144716, 1, NULL, NULL, NULL, NULL, NULL, NULL),
('G3', 3, 'Bima', 'Gelandang Tengah', 0.15779436221329, 6, NULL, NULL, NULL, NULL, NULL, NULL),
('G4', 3, 'Zidan', 'Gelandang Tengah', 0.25123861649225, 5, NULL, NULL, NULL, NULL, NULL, NULL),
('G5', 3, 'Deri', 'Gelandang Tengah', 0.79828082828111, 2, NULL, NULL, NULL, NULL, NULL, NULL),
('G6', 3, 'Fazri', 'Gelandang Serang', 0.71258079119881, 3, NULL, NULL, NULL, NULL, NULL, NULL),
('P1', 4, 'Aldi', 'Penyerang Tengah', 0.32565210356454, 4, NULL, NULL, NULL, NULL, NULL, NULL),
('P2', 4, 'Rapa', 'Penyerang Tengah', 0.88439061431767, 1, NULL, NULL, NULL, NULL, NULL, NULL),
('P3', 4, 'Rehan', 'Penyerang Sayap Kiri', 0.82901805814512, 2, NULL, NULL, NULL, NULL, NULL, NULL),
('P4', 4, 'Galang', 'Penyerang Sayap Kiri', 0.24506095350834, 5, NULL, NULL, NULL, NULL, NULL, NULL),
('P5', 4, 'Ilham', 'Penyerang Sayap Kanan', 0.69281864031367, 3, NULL, NULL, NULL, NULL, NULL, NULL),
('P6', 4, 'Sultan', 'Penyerang Sayap Kanan', 0.20965847796054, 6, NULL, NULL, NULL, NULL, NULL, NULL),
('SS1', 6, 'Ajisanto', 'Striker', 0.46823835324363, 2, NULL, NULL, NULL, NULL, NULL, NULL),
('SS2', 6, 'Galang', 'Striker', 0.67331325797959, 1, NULL, NULL, NULL, NULL, NULL, NULL),
('SS3', 6, 'Julfan', 'Striker', 0.13543846307049, 3, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_kriteria`
--

CREATE TABLE `tb_kriteria` (
  `kode_kriteria` varchar(16) NOT NULL,
  `id_lini` int(11) NOT NULL,
  `nama_kriteria` varchar(256) NOT NULL,
  `atribut` varchar(256) NOT NULL DEFAULT 'benefit'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_kriteria`
--

INSERT INTO `tb_kriteria` (`kode_kriteria`, `id_lini`, `nama_kriteria`, `atribut`) VALUES
('C1', 1, 'Disiplin', 'benefit'),
('C2', 1, 'Tangkapan', 'benefit'),
('C3', 1, 'Refleks', 'benefit'),
('C4', 1, 'Distribusi', 'benefit'),
('C5', 1, 'Tangkapan/tepisan Gagal', 'cost'),
('C1', 2, 'Disipli', 'benefit'),
('C6', 2, 'Tekel/intersep', 'benefit'),
('C7', 2, 'Duel Udara', 'benefit'),
('C8', 2, 'Kehilangan Posisi', 'cost'),
('C9', 2, 'Passing', 'benefit'),
('C1', 3, 'Disiplin', 'benefit'),
('C9', 3, 'Passing', 'benefit'),
('C10', 3, 'Dribbling', 'benefit'),
('C11', 3, 'Kontrol Bola', 'benefit'),
('C12', 3, 'Kehilangan Bola', 'cost'),
('C13', 3, 'Visi Bermain', 'benefit'),
('C1', 4, 'Disiplin', 'benefit'),
('C9', 4, 'Passing', 'benefit'),
('C10', 4, 'Dribbling', 'benefit'),
('C11', 4, 'Kontrol Bola', 'benefit'),
('C12', 4, 'Kehilangan Bola', 'cost'),
('C14', 4, 'Finishing', 'benefit'),
('z1', 5, 'umpan', 'benefit'),
('z2', 5, 'tekel', 'benefit'),
('z3', 5, 'tembakan', 'benefit'),
('z10', 5, 'uaysgd', 'benefit'),
('z01', 5, 'asuyg', 'benefit'),
('K1', 6, 'Tembakan', 'benefit'),
('K2', 6, 'passing', 'benefit'),
('K3', 6, 'dribbling', 'benefit'),
('sk1', 8, 'passsing', 'benefit');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_lini`
--

CREATE TABLE `tb_lini` (
  `id_lini` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `kebutuhan` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_lini`
--

INSERT INTO `tb_lini` (`id_lini`, `nama`, `keterangan`, `kebutuhan`) VALUES
(1, 'Kiper', '', 1),
(2, 'Bek', '', 4),
(3, 'Gelandang', '', 3),
(4, 'Penyerang', '', 3),
(5, 'Midfielder', '', 1),
(6, 'Striker', '', 3),
(8, 'SayapKiri', '', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_rel_alternatif`
--

CREATE TABLE `tb_rel_alternatif` (
  `ID` int(11) NOT NULL,
  `id_lini` int(11) DEFAULT NULL,
  `kode_alternatif` varchar(16) DEFAULT NULL,
  `kode_kriteria` varchar(16) DEFAULT NULL,
  `nilai` double DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_rel_alternatif`
--

INSERT INTO `tb_rel_alternatif` (`ID`, `id_lini`, `kode_alternatif`, `kode_kriteria`, `nilai`) VALUES
(663, 1, 'GK1', 'C1', 8),
(664, 1, 'GK1', 'C2', 18),
(665, 1, 'GK1', 'C3', 14),
(666, 1, 'GK2', 'C1', 8),
(667, 1, 'GK2', 'C2', 15),
(668, 1, 'GK2', 'C3', 8),
(669, 1, 'GK3', 'C1', 7),
(670, 1, 'GK3', 'C2', 16),
(671, 1, 'GK3', 'C3', 15),
(704, 1, 'GK3', 'C5', 9),
(701, 1, 'GK3', 'C4', 3),
(703, 1, 'GK2', 'C5', 17),
(700, 1, 'GK2', 'C4', 2),
(699, 1, 'GK1', 'C4', 6),
(702, 1, 'GK1', 'C5', 8),
(724, 2, 'B1', 'C9', 9),
(723, 2, 'B1', 'C8', 4),
(722, 2, 'B1', 'C7', 5),
(721, 2, 'B1', 'C6', 9),
(720, 2, 'B1', 'C1', 7),
(725, 2, 'B2', 'C1', 10),
(726, 2, 'B2', 'C6', 14),
(727, 2, 'B2', 'C7', 15),
(728, 2, 'B2', 'C8', 3),
(729, 2, 'B2', 'C9', 14),
(730, 2, 'B3', 'C1', 9),
(731, 2, 'B3', 'C6', 15),
(732, 2, 'B3', 'C7', 9),
(733, 2, 'B3', 'C8', 2),
(734, 2, 'B3', 'C9', 10),
(735, 2, 'B4', 'C1', 8),
(736, 2, 'B4', 'C6', 11),
(737, 2, 'B4', 'C7', 7),
(738, 2, 'B4', 'C8', 4),
(739, 2, 'B4', 'C9', 9),
(740, 2, 'B5', 'C1', 9),
(741, 2, 'B5', 'C6', 10),
(742, 2, 'B5', 'C7', 6),
(743, 2, 'B5', 'C8', 8),
(744, 2, 'B5', 'C9', 12),
(745, 2, 'B6', 'C1', 8),
(746, 2, 'B6', 'C6', 13),
(747, 2, 'B6', 'C7', 7),
(748, 2, 'B6', 'C8', 4),
(749, 2, 'B6', 'C9', 12),
(750, 2, 'B7', 'C1', 7),
(751, 2, 'B7', 'C6', 8),
(752, 2, 'B7', 'C7', 8),
(753, 2, 'B7', 'C8', 7),
(754, 2, 'B7', 'C9', 8),
(755, 2, 'B8', 'C1', 8),
(756, 2, 'B8', 'C6', 15),
(757, 2, 'B8', 'C7', 8),
(758, 2, 'B8', 'C8', 6),
(759, 2, 'B8', 'C9', 11),
(760, 3, 'G1', 'C1', 9),
(761, 3, 'G1', 'C10', 12),
(762, 3, 'G1', 'C11', 16),
(763, 3, 'G1', 'C12', 6),
(764, 3, 'G1', 'C13', 6),
(765, 3, 'G1', 'C9', 14),
(766, 3, 'G2', 'C1', 8),
(767, 3, 'G2', 'C10', 14),
(768, 3, 'G2', 'C11', 17),
(769, 3, 'G2', 'C12', 4),
(770, 3, 'G2', 'C13', 9),
(771, 3, 'G2', 'C9', 18),
(772, 3, 'G3', 'C1', 6),
(773, 3, 'G3', 'C10', 18),
(774, 3, 'G3', 'C11', 15),
(775, 3, 'G3', 'C12', 10),
(776, 3, 'G3', 'C13', 6),
(777, 3, 'G3', 'C9', 12),
(778, 3, 'G4', 'C1', 7),
(779, 3, 'G4', 'C10', 16),
(780, 3, 'G4', 'C11', 16),
(781, 3, 'G4', 'C12', 12),
(782, 3, 'G4', 'C13', 7),
(783, 3, 'G4', 'C9', 15),
(784, 3, 'G5', 'C1', 9),
(785, 3, 'G5', 'C10', 13),
(786, 3, 'G5', 'C11', 18),
(787, 3, 'G5', 'C12', 3),
(788, 3, 'G5', 'C13', 8),
(789, 3, 'G5', 'C9', 17),
(790, 3, 'G6', 'C1', 7),
(791, 3, 'G6', 'C10', 17),
(792, 3, 'G6', 'C11', 17),
(793, 3, 'G6', 'C12', 5),
(794, 3, 'G6', 'C13', 8),
(795, 3, 'G6', 'C9', 16),
(796, 4, 'P1', 'C1', 7),
(797, 4, 'P1', 'C10', 17),
(798, 4, 'P1', 'C11', 17),
(799, 4, 'P1', 'C12', 8),
(800, 4, 'P1', 'C14', 10),
(801, 4, 'P1', 'C9', 12),
(802, 4, 'P2', 'C1', 8),
(803, 4, 'P2', 'C10', 15),
(804, 4, 'P2', 'C11', 17),
(805, 4, 'P2', 'C12', 5),
(806, 4, 'P2', 'C14', 15),
(807, 4, 'P2', 'C9', 13),
(808, 4, 'P3', 'C1', 9),
(809, 4, 'P3', 'C10', 16),
(810, 4, 'P3', 'C11', 16),
(811, 4, 'P3', 'C12', 4),
(812, 4, 'P3', 'C14', 14),
(813, 4, 'P3', 'C9', 15),
(814, 4, 'P4', 'C1', 9),
(815, 4, 'P4', 'C10', 11),
(816, 4, 'P4', 'C11', 14),
(817, 4, 'P4', 'C12', 7),
(818, 4, 'P4', 'C14', 9),
(819, 4, 'P4', 'C9', 16),
(820, 4, 'P5', 'C1', 7),
(821, 4, 'P5', 'C10', 17),
(822, 4, 'P5', 'C11', 18),
(823, 4, 'P5', 'C12', 6),
(824, 4, 'P5', 'C14', 13),
(825, 4, 'P5', 'C9', 14),
(826, 4, 'P6', 'C1', 8),
(827, 4, 'P6', 'C10', 16),
(828, 4, 'P6', 'C11', 15),
(829, 4, 'P6', 'C12', 11),
(830, 4, 'P6', 'C14', 10),
(831, 4, 'P6', 'C9', 13),
(849, 6, 'SS2', 'K2', 18),
(848, 6, 'SS2', 'K1', 15),
(847, 6, 'SS1', 'K3', 10),
(846, 6, 'SS1', 'K2', 16),
(845, 6, 'SS1', 'K1', 18),
(850, 6, 'SS2', 'K3', 14),
(851, 6, 'SS3', 'K1', 16),
(852, 6, 'SS3', 'K2', 17),
(853, 6, 'SS3', 'K3', 8);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_rel_kriteria`
--

CREATE TABLE `tb_rel_kriteria` (
  `ID` int(11) NOT NULL,
  `id_lini` int(11) DEFAULT NULL,
  `ID1` varchar(16) DEFAULT NULL,
  `ID2` varchar(16) DEFAULT NULL,
  `nilai` double DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_rel_kriteria`
--

INSERT INTO `tb_rel_kriteria` (`ID`, `id_lini`, `ID1`, `ID2`, `nilai`) VALUES
(844, 1, 'C1', 'C1', 1),
(845, 1, 'C2', 'C1', 4),
(846, 1, 'C2', 'C2', 1),
(847, 1, 'C1', 'C2', 0.25),
(848, 1, 'C3', 'C1', 3.000003000003),
(849, 1, 'C3', 'C2', 0.5),
(850, 1, 'C3', 'C3', 1),
(851, 1, 'C1', 'C3', 0.333333),
(852, 1, 'C2', 'C3', 2),
(853, 2, 'C1', 'C1', 1),
(874, 1, 'C4', 'C4', 1),
(876, 1, 'C2', 'C4', 5),
(873, 1, 'C4', 'C3', 0.25),
(875, 1, 'C1', 'C4', 2),
(871, 1, 'C4', 'C1', 0.5),
(872, 1, 'C4', 'C2', 0.2),
(877, 1, 'C3', 'C4', 4),
(878, 1, 'C5', 'C1', 2),
(879, 1, 'C5', 'C2', 0.333333333),
(880, 1, 'C5', 'C3', 0.5),
(881, 1, 'C5', 'C4', 3.000003000003),
(882, 1, 'C5', 'C5', 1),
(883, 1, 'C1', 'C5', 0.5),
(884, 1, 'C2', 'C5', 3),
(885, 1, 'C3', 'C5', 2),
(886, 1, 'C4', 'C5', 0.333333),
(887, 2, 'C1', 'C1', 1),
(888, 2, 'C6', 'C1', 3.000003000003),
(889, 2, 'C6', 'C6', 1),
(890, 2, 'C1', 'C6', 0.333333),
(891, 2, 'C7', 'C1', 0.5),
(892, 2, 'C7', 'C6', 0.25),
(893, 2, 'C7', 'C7', 1),
(894, 2, 'C1', 'C7', 2),
(895, 2, 'C6', 'C7', 4),
(896, 2, 'C8', 'C1', 2),
(897, 2, 'C8', 'C6', 0.5),
(898, 2, 'C8', 'C7', 3.000003000003),
(899, 2, 'C8', 'C8', 1),
(900, 2, 'C1', 'C8', 0.5),
(901, 2, 'C6', 'C8', 2),
(902, 2, 'C7', 'C8', 0.333333),
(903, 2, 'C9', 'C1', 0.333333333),
(904, 2, 'C9', 'C6', 0.2),
(905, 2, 'C9', 'C7', 0.5),
(906, 2, 'C9', 'C8', 0.25),
(907, 2, 'C9', 'C9', 1),
(908, 2, 'C1', 'C9', 3),
(909, 2, 'C6', 'C9', 5),
(910, 2, 'C7', 'C9', 2),
(911, 2, 'C8', 'C9', 4),
(912, 3, 'C1', 'C1', 1),
(913, 3, 'C9', 'C1', 4),
(914, 3, 'C9', 'C9', 1),
(915, 3, 'C1', 'C9', 0.25),
(916, 3, 'C10', 'C1', 0.5),
(917, 3, 'C10', 'C10', 1),
(918, 3, 'C10', 'C9', 0.2),
(919, 3, 'C1', 'C10', 2),
(920, 3, 'C9', 'C10', 5),
(921, 3, 'C11', 'C1', 2),
(922, 3, 'C11', 'C10', 3),
(923, 3, 'C11', 'C11', 1),
(924, 3, 'C11', 'C9', 0.5),
(925, 3, 'C1', 'C11', 0.5),
(926, 3, 'C10', 'C11', 0.333333333),
(927, 3, 'C9', 'C11', 2),
(928, 3, 'C12', 'C1', 3),
(929, 3, 'C12', 'C10', 4),
(930, 3, 'C12', 'C11', 1),
(931, 3, 'C12', 'C12', 1),
(932, 3, 'C12', 'C9', 0.5),
(933, 3, 'C1', 'C12', 0.333333333),
(934, 3, 'C10', 'C12', 0.25),
(935, 3, 'C11', 'C12', 1),
(936, 3, 'C9', 'C12', 2),
(937, 3, 'C13', 'C1', 5),
(938, 3, 'C13', 'C10', 6),
(939, 3, 'C13', 'C11', 4),
(940, 3, 'C13', 'C12', 3),
(941, 3, 'C13', 'C13', 1),
(942, 3, 'C13', 'C9', 2),
(943, 3, 'C1', 'C13', 0.2),
(944, 3, 'C10', 'C13', 0.166666666),
(945, 3, 'C11', 'C13', 0.25),
(946, 3, 'C12', 'C13', 0.333333333),
(947, 3, 'C9', 'C13', 0.5),
(948, 4, 'C1', 'C1', 1),
(949, 4, 'C9', 'C1', 0.5),
(950, 4, 'C9', 'C9', 1),
(951, 4, 'C1', 'C9', 2),
(952, 4, 'C10', 'C1', 2),
(953, 4, 'C10', 'C10', 1),
(954, 4, 'C10', 'C9', 3),
(955, 4, 'C1', 'C10', 0.5),
(956, 4, 'C9', 'C10', 0.333333333),
(957, 4, 'C11', 'C1', 3),
(958, 4, 'C11', 'C10', 2),
(959, 4, 'C11', 'C11', 1),
(960, 4, 'C11', 'C9', 4),
(961, 4, 'C1', 'C11', 0.333333333),
(962, 4, 'C10', 'C11', 0.5),
(963, 4, 'C9', 'C11', 0.25),
(964, 4, 'C12', 'C1', 2),
(965, 4, 'C12', 'C10', 1),
(966, 4, 'C12', 'C11', 0.5),
(967, 4, 'C12', 'C12', 1),
(968, 4, 'C12', 'C9', 3),
(969, 4, 'C1', 'C12', 0.5),
(970, 4, 'C10', 'C12', 1),
(971, 4, 'C11', 'C12', 2),
(972, 4, 'C9', 'C12', 0.333333333),
(973, 4, 'C14', 'C1', 6),
(974, 4, 'C14', 'C10', 3),
(975, 4, 'C14', 'C11', 2),
(976, 4, 'C14', 'C12', 4),
(977, 4, 'C14', 'C14', 1),
(978, 4, 'C14', 'C9', 7),
(979, 4, 'C1', 'C14', 0.166666666),
(980, 4, 'C10', 'C14', 0.333333333),
(981, 4, 'C11', 'C14', 0.5),
(982, 4, 'C12', 'C14', 0.25),
(983, 4, 'C9', 'C14', 0.142857142),
(984, 5, 'z1', 'z1', 1),
(985, 5, 'z2', 'z1', 1),
(986, 5, 'z2', 'z2', 1),
(987, 5, 'z1', 'z2', 1),
(988, 5, 'z3', 'z1', 1),
(989, 5, 'z3', 'z2', 3.000003000003),
(990, 5, 'z3', 'z3', 1),
(991, 5, 'z1', 'z3', 1),
(992, 5, 'z2', 'z3', 0.333333),
(993, 5, 'z10', 'z1', 1),
(994, 5, 'z10', 'z10', 1),
(995, 5, 'z10', 'z2', 1),
(996, 5, 'z10', 'z3', 1),
(997, 5, 'z1', 'z10', 1),
(998, 5, 'z2', 'z10', 1),
(999, 5, 'z3', 'z10', 1),
(1000, 5, 'z01', 'z01', 1),
(1001, 5, 'z01', 'z1', 1),
(1002, 5, 'z01', 'z10', 1),
(1003, 5, 'z01', 'z2', 1),
(1004, 5, 'z01', 'z3', 1),
(1005, 5, 'z1', 'z01', 1),
(1006, 5, 'z10', 'z01', 1),
(1007, 5, 'z2', 'z01', 1),
(1008, 5, 'z3', 'z01', 1),
(1009, 6, 'K1', 'K1', 1),
(1029, 8, 'sk1', 'sk1', 1),
(1028, 6, 'K2', 'K3', 0.2),
(1027, 6, 'K1', 'K3', 2),
(1026, 6, 'K3', 'K3', 1),
(1025, 6, 'K3', 'K2', 5),
(1024, 6, 'K3', 'K1', 0.5),
(1023, 6, 'K1', 'K2', 4),
(1022, 6, 'K2', 'K2', 1),
(1021, 6, 'K2', 'K1', 0.25);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_user`
--

CREATE TABLE `tb_user` (
  `id_akun` int(11) NOT NULL,
  `user` varchar(16) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `pass` varchar(255) DEFAULT NULL,
  `level` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_user`
--

INSERT INTO `tb_user` (`id_akun`, `user`, `nama`, `pass`, `level`) VALUES
(2, 'pelatih', 'Hadi Pamungkas', '$2y$10$lA3HSZXlbdUNjBmreZWPvehOqWhwiVh9jxrONNAmpTo9t4djkpbDO', '1'),
(3, 'manajer1', 'Masdirah', '$2y$10$Uv4W0SHq.lTdgICD.rx8uOSqOgyfo/rV.ECSp.kBQEXvJbOMdWJ26', '2'),
(6, 'admin', 'admin', '$2y$10$Sydvat0c85U/gTLOBQqbheJ4pWaX04Qtl8duYWgt15Gg5TOK9/pK6', '3'),
(8, 'admin1', 'Ade', '$2y$10$UC08yv.ECvR885gZSMzgseSqA9G4ob6ybyVjsIYSWSOv2WuZKNc9a', '3');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_alternatif`
--
ALTER TABLE `tb_alternatif`
  ADD PRIMARY KEY (`kode_alternatif`,`id_lini`);

--
-- Indeks untuk tabel `tb_kriteria`
--
ALTER TABLE `tb_kriteria`
  ADD PRIMARY KEY (`kode_kriteria`,`id_lini`);

--
-- Indeks untuk tabel `tb_lini`
--
ALTER TABLE `tb_lini`
  ADD PRIMARY KEY (`id_lini`);

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
-- AUTO_INCREMENT untuk tabel `tb_lini`
--
ALTER TABLE `tb_lini`
  MODIFY `id_lini` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `tb_rel_alternatif`
--
ALTER TABLE `tb_rel_alternatif`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=854;

--
-- AUTO_INCREMENT untuk tabel `tb_rel_kriteria`
--
ALTER TABLE `tb_rel_kriteria`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1030;

--
-- AUTO_INCREMENT untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_akun` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
