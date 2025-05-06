-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Bulan Mei 2025 pada 09.11
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
-- Database: `nailstudio_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT IGNORE INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Almond Nails', '2025-05-06 05:48:37', '2025-05-06 05:48:37'),
(2, 'Oval Nails', '2025-05-06 05:48:37', '2025-05-06 05:48:37'),
(3, 'Lipstick Nails', '2025-05-06 05:48:37', '2025-05-06 05:48:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `newsletter_subscribers`
--

CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subscribed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `newsletter_subscribers`
--

INSERT IGNORE INTO `newsletter_subscribers` (`id`, `email`, `subscribed_at`) VALUES
(1, 'zaharaoyen123@gmail.com', '2025-05-04 14:19:13'),
(2, 'ayesha@gmail.com', '2025-05-04 14:20:59'),
(4, 'egafiandrapratama@gmail.com', '2025-05-05 09:21:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id_product` int(11) NOT NULL,
  `namaproduct` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `status` enum('Low stock','Published','Draft') NOT NULL,
  `added` datetime NOT NULL DEFAULT current_timestamp(),
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `product`
--

INSERT IGNORE INTO `product` (`id_product`, `namaproduct`, `stock`, `price`, `status`, `added`, `foto`) VALUES
(1, 'Bright Red Nail Polish 15ml', 50, 50000, 'Published', '2025-05-01 15:00:43', 'https://storage.googleapis.com/a1aa/image/bc05cc17-cba3-4d14-2a38-f7de1155b469.jpg'),
(2, 'Pink Glitter Nail Polish 12ml', 75, 75000, 'Published', '2025-05-01 15:00:43', 'https://storage.googleapis.com/a1aa/image/8f7d248c-5136-42aa-6351-dc87af882f86.jpg'),
(3, 'Pastel Blue Nail Polish 14ml', 8, 60000, 'Low stock', '2025-05-01 15:00:43', 'https://storage.googleapis.com/a1aa/image/d6b69bab-dd1e-409f-cdd4-704e7a2c5121.jpg'),
(4, 'Deep Purple Nail Polish 16ml', 0, 80000, 'Published', '2025-05-01 15:00:43', 'https://via.placeholder.com/100x100.png?text=Nail+Polish'),
(5, 'Soft Pink Nail Polish 13ml', 120, 45000, 'Published', '2025-05-01 15:00:43', 'https://storage.googleapis.com/a1aa/image/soft-pink-nail-polish.jpg'),
(6, 'Neon Green Nail Polish 15ml', 5, 90000, 'Low stock', '2025-05-01 15:00:43', 'https://storage.googleapis.com/a1aa/image/neon-green-nail-polish.jpg'),
(7, 'Classic Black Nail Polish 14ml', 200, 65000, 'Published', '2025-05-01 15:00:43', 'https://storage.googleapis.com/a1aa/image/classic-black-nail-polish.jpg'),
(8, 'Metallic Gold Nail Polish 13ml', 30, 100000, 'Published', '2025-05-01 15:00:43', 'https://storage.googleapis.com/a1aa/image/metallic-gold-nail-polish.jpg'),
(9, 'Coral Orange Nail Polish 15ml', 60, 55000, 'Published', '2025-05-01 15:00:43', 'https://storage.googleapis.com/a1aa/image/coral-orange-nail-polish.jpg'),
(10, 'Lavender Purple Nail Polish 14ml', 45, 67500, 'Published', '2025-05-01 15:00:43', 'https://storage.googleapis.com/a1aa/image/lavender-purple-nail-polish.jpg'),
(11, 'Sky Blue Nail Polish 15ml', 0, 52500, 'Low stock', '2025-05-01 15:00:43', 'https://storage.googleapis.com/a1aa/image/sky-blue-nail-polish.jpg'),
(12, 'Bright Yellow Nail Polish 14ml', 15, 62500, 'Published', '2025-05-01 15:00:43', 'https://storage.googleapis.com/a1aa/image/bright-yellow-nail-polish.jpg'),
(13, 'Soft Peach Nail Polish 13ml', 9, 47500, 'Low stock', '2025-05-01 15:00:43', 'https://storage.googleapis.com/a1aa/image/soft-peach-nail-polish.jpg'),
(14, 'Matte Top Coat 10ml', 150, 70000, 'Published', '2025-05-01 15:00:43', ''),
(15, 'Cuticle Oil Pen 5ml', 80, 35000, 'Published', '2025-05-01 15:00:43', 'https://via.placeholder.com/100x100.png?text=Cuticle+Oil'),
(16, 'Nail Buffer Block', 250, 15000, 'Published', '2025-05-01 15:00:43', ''),
(17, 'Gel Polish Base Coat 15ml', 55, 85000, 'Draft', '2025-05-01 15:00:43', 'https://via.placeholder.com/100x100.png?text=Base+Coat');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `price` decimal(10,2) NOT NULL,
  `status` enum('draft','published','low_stock') NOT NULL DEFAULT 'draft',
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT IGNORE INTO `products` (`id`, `name`, `category_id`, `stock`, `price`, `status`, `image`, `created_at`, `updated_at`) VALUES
(1, 'PINK PERFECTION', 1, 10, 120000.00, 'low_stock', 'nail-art-1.jpg', '2024-12-29 00:00:00', '2025-05-06 05:48:37'),
(2, 'GREEN ELEGANCE', 2, 30, 130000.00, 'published', 'nail-art-2.jpg', '2024-11-24 00:00:00', '2025-05-06 05:48:37'),
(3, 'TROPICAL BLOOM NAILS', 3, 25, 140000.00, 'draft', 'nail-art-3.jpg', '2024-12-12 00:00:00', '2025-05-06 05:48:37'),
(4, 'ELEGANT RUBY GLAM NAILS', 2, 35, 110000.00, 'draft', 'nail-art-4.jpg', '2024-10-09 00:00:00', '2025-05-06 05:48:37'),
(5, 'BLACK CUTE NAILS', 1, 55, 125000.00, 'draft', 'nail-art-5.jpg', '2024-08-15 00:00:00', '2025-05-06 05:48:37'),
(6, 'GLAMOUR\'S', 3, 15, 165000.00, 'low_stock', 'nail-art-6.jpg', '2024-11-29 00:00:00', '2025-05-06 05:48:37'),
(7, 'FAKE PINK\'S', 1, 45, 200000.00, 'published', 'nail-art-7.jpg', '2024-09-12 00:00:00', '2025-05-06 05:48:37'),
(8, 'COW\'S', 2, 26, 135000.00, 'published', 'nail-art-8.jpg', '2024-10-10 00:00:00', '2025-05-06 05:48:37'),
(9, 'FLOWER\'S', 1, 45, 170000.00, 'low_stock', 'nail-art-9.jpg', '2024-10-25 00:00:00', '2025-05-06 05:48:37'),
(10, 'BLACKIE\'S', 3, 65, 115000.00, 'published', 'nail-art-10.jpg', '2024-10-10 00:00:00', '2025-05-06 05:48:37'),
(11, 'AZURE DREAM', 2, 15, 115000.00, 'draft', 'nail-art-11.jpg', '2024-08-15 00:00:00', '2025-05-06 05:48:37'),
(12, 'CRIMSON NOIR', 3, 67, 105000.00, 'published', 'nail-art-12.jpg', '2024-11-07 00:00:00', '2025-05-06 05:48:37');

--
-- Trigger `products`
--
DELIMITER $$
CREATE TRIGGER `check_stock_level` BEFORE UPDATE ON `products` FOR EACH ROW BEGIN
    IF NEW.stock < 20 AND NEW.stock > 0 THEN
        SET NEW.status = 'low_stock';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','pelanggan') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','pelanggan') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id_product`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `product`
--
ALTER TABLE `product`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;