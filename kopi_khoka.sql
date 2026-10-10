-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Okt 2026 pada 04.20
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
-- Database: `kopi_khoka`
--
CREATE DATABASE IF NOT EXISTS `kopi_khoka` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `kopi_khoka`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(30) NOT NULL,
  `nama` varchar(60) NOT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id`, `slug`, `nama`, `urutan`) VALUES
(1, 'kopi', 'Kopi', 1),
(2, 'nonkopi', 'Non-Kopi', 2),
(3, 'makanan', 'Makanan', 3),
(4, 'camilan', 'Camilan', 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id` int(10) UNSIGNED NOT NULL,
  `kategori_id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` varchar(255) NOT NULL DEFAULT '',
  `harga` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `foto` varchar(200) NOT NULL DEFAULT '',
  `tersedia` tinyint(1) NOT NULL DEFAULT 1,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id`, `kategori_id`, `nama`, `deskripsi`, `harga`, `foto`, `tersedia`, `urutan`, `dibuat`) VALUES
(1, 1, 'Khoka Aren Soft', 'Racikan khas Khoka dengan gula aren, rasa kopinya lebih ringan.', 15000, 'images/menu/khoka_aren_soft.png', 1, 1, '2026-10-05 23:35:21'),
(2, 1, 'Khoka Aren Strong', 'Racikan khas Khoka dengan gula aren, rasa kopinya lebih pekat.', 17000, 'images/menu/khoka_aren_strong.png', 1, 2, '2026-10-05 23:35:21'),
(3, 1, 'Kopi Butterscotch', 'Kopi dengan rasa butterscotch yang manis dan wangi.', 18000, 'images/menu/kopi_butterscotch.png', 1, 3, '2026-10-05 23:35:21'),
(4, 1, 'Kopi Pandan', 'Kopi dengan rasa pandan yang harum.', 18000, 'images/menu/kopi_pandan.png', 1, 4, '2026-10-05 23:35:21'),
(5, 1, 'Kopi Banana', 'Kopi dengan rasa pisang yang manis.', 18000, 'images/menu/kopi_banana.png', 1, 5, '2026-10-05 23:35:21'),
(6, 1, 'Kopi Caramel', 'Kopi dengan rasa karamel yang manis.', 18000, 'images/menu/kopi_caramel.png', 1, 6, '2026-10-05 23:35:21'),
(7, 1, 'Kopi Strawberry', 'Kopi dengan rasa stroberi, manis dan segar.', 18000, 'images/menu/kopi_strawberry.png', 1, 7, '2026-10-05 23:35:21'),
(8, 1, 'Americano', 'Espresso yang dicampur air, rasa kopinya bersih dan tegas.', 15000, 'images/menu/americano.png', 1, 8, '2026-10-05 23:35:21'),
(9, 1, 'Latte', 'Espresso dengan susu yang lembut.', 15000, 'images/menu/latte.png', 1, 9, '2026-10-05 23:35:21'),
(10, 1, 'Cappuccino', 'Espresso, susu, dan busa susu yang tebal.', 15000, 'images/menu/cappuccino.png', 1, 10, '2026-10-05 23:35:21'),
(11, 1, 'Kopi Susu Hot', 'Kopi susu yang disajikan hangat.', 15000, 'images/menu/kopi_susu_hot.png', 1, 11, '2026-10-05 23:35:21'),
(12, 2, 'Matcha', 'Minuman matcha yang lembut.', 18000, 'images/menu/matcha_ice.png', 1, 12, '2026-10-05 23:35:21'),
(13, 2, 'Red Velvet', 'Minuman red velvet yang manis dan lembut.', 18000, 'images/menu/red_velvet.png', 1, 13, '2026-10-05 23:35:21'),
(14, 2, 'Teh Tarik', 'Teh susu manis yang ditarik hingga berbusa.', 18000, 'images/menu/teh_tarik.png', 1, 14, '2026-10-05 23:35:21'),
(15, 2, 'Green Tea', 'Teh hijau yang segar.', 15000, 'images/menu/green_tea.png', 1, 15, '2026-10-05 23:35:21'),
(16, 2, 'Thai Tea', 'Teh Thailand yang manis dan creamy.', 15000, 'images/menu/thai_tea.png', 1, 16, '2026-10-05 23:35:21'),
(17, 2, 'Taro', 'Minuman taro yang manis dan creamy.', 15000, 'images/menu/taro_ice.png', 1, 17, '2026-10-05 23:35:21'),
(18, 2, 'Cokelat', 'Minuman cokelat yang manis dan lembut.', 15000, 'images/menu/coklat_ice.png', 1, 18, '2026-10-05 23:35:21'),
(19, 2, 'Milo', 'Minuman Milo, manis dan gurih.', 15000, 'images/menu/milo_ice.png', 1, 19, '2026-10-05 23:35:21'),
(20, 2, 'Lemon Tea', 'Teh dengan perasan lemon yang segar.', 15000, 'images/menu/lemon_tea.png', 1, 20, '2026-10-05 23:35:21'),
(21, 2, 'Leci Tea', 'Teh dengan rasa leci yang manis dan segar.', 15000, 'images/menu/leci_tea.png', 1, 21, '2026-10-05 23:35:21'),
(22, 2, 'Melon Spark', 'Minuman bersoda rasa melon, segar dan bergelembung.', 15000, 'images/menu/melon_spark.png', 1, 22, '2026-10-05 23:35:21'),
(23, 2, 'Mango Fizz', 'Minuman bersoda rasa mangga, segar dan manis.', 15000, 'images/menu/manggo_fizz.png', 1, 23, '2026-10-05 23:35:21'),
(24, 2, 'Strawberry Fizz', 'Minuman bersoda rasa stroberi, segar dan manis.', 15000, 'images/menu/strawberry_fizz.png', 1, 24, '2026-10-05 23:35:21'),
(25, 2, 'Leci Fizz', 'Minuman bersoda rasa leci, segar dan manis.', 15000, 'images/menu/leci_fizz.png', 1, 25, '2026-10-05 23:35:21'),
(26, 2, 'Lemon Tea Fizz', 'Lemon tea yang dipadu soda, segar dan sedikit asam.', 15000, 'images/menu/lemon_tea_fizz.png', 1, 26, '2026-10-05 23:35:21'),
(27, 3, 'Mie Goreng Telur', 'Mie goreng dengan telur ceplok dan irisan cabai.', 12000, 'images/menu/mie_goreng_telur.png', 1, 27, '2026-10-05 23:35:21'),
(28, 3, 'Mie Soto Telur', 'Mie berkuah soto dengan telur.', 12000, 'images/menu/mie_soto_telur.png', 1, 28, '2026-10-05 23:35:21'),
(29, 4, 'Khoka Sweet Toast', 'Roti bakar manis khas Khoka.', 15000, 'images/menu/khoka_sweet_toast.png', 1, 29, '2026-10-05 23:35:21'),
(30, 4, 'Smoky Bills Pizza', 'Pizza dengan cita rasa smoky, disajikan hangat.', 20000, 'images/menu/smoky_bills_pizza.png', 1, 30, '2026-10-05 23:35:21'),
(31, 4, 'Chicken Mushroom Pizza', 'Pizza dengan topping ayam dan jamur.', 20000, 'images/menu/chicken_mushroom_pizza.png', 1, 31, '2026-10-05 23:35:21'),
(32, 4, 'French Fries', 'Kentang goreng yang renyah.', 12000, 'images/menu/french_fries.png', 1, 32, '2026-10-05 23:35:21'),
(33, 4, 'Fried Meatballs', 'Bakso goreng, gurih dan hangat.', 15000, 'images/menu/fried_meatballs.png', 1, 33, '2026-10-05 23:35:21'),
(34, 4, 'Tahu Walik', 'Tahu goreng dengan isian di dalamnya, gurih dan renyah.', 15000, 'images/menu/tahu_walik.png', 1, 34, '2026-10-05 23:35:21'),
(35, 4, 'Banana Fritter', 'Pisang goreng yang renyah, disajikan hangat.', 15000, 'images/menu/banana_fritter.png', 1, 35, '2026-10-05 23:35:21'),
(36, 4, 'Cassava Chips', 'Keripik singkong yang renyah.', 15000, 'images/menu/cassava_chips.png', 1, 36, '2026-10-05 23:35:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesan_kontak`
--

CREATE TABLE `pesan_kontak` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `pesan` text NOT NULL,
  `dibaca` tinyint(1) NOT NULL DEFAULT 0,
  `dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indeks untuk tabel `pesan_kontak`
--
ALTER TABLE `pesan_kontak`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT untuk tabel `pesan_kontak`
--
ALTER TABLE `pesan_kontak`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
