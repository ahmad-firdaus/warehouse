-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Waktu pembuatan: 08 Jun 2026 pada 14.18
-- Versi server: 8.0.35
-- Versi PHP: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_gudang`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `incoming_stock`
--

CREATE TABLE `incoming_stock` (
  `id` int NOT NULL,
  `product_code` varchar(50) DEFAULT NULL,
  `supplier_id` int DEFAULT NULL,
  `incoming_date` date NOT NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `incoming_stock`
--

INSERT INTO `incoming_stock` (`id`, `product_code`, `supplier_id`, `incoming_date`, `quantity`) VALUES
(1, '123', 2, '2000-02-12', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `outgoing_stock`
--

CREATE TABLE `outgoing_stock` (
  `id` int NOT NULL,
  `product_code` varchar(50) DEFAULT NULL,
  `outgoing_date` date NOT NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `outgoing_stock`
--

INSERT INTO `outgoing_stock` (`id`, `product_code`, `outgoing_date`, `quantity`) VALUES
(1, '123', '2022-12-12', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `product_code` varchar(50) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_category` varchar(50) NOT NULL,
  `product_stock` int DEFAULT '0',
  `product_price` int NOT NULL,
  `product_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`product_code`, `product_name`, `product_category`, `product_stock`, `product_price`, `product_image`) VALUES
('123', 'egg', 'food', 0, 20, 'assets/uploads/1780904262_egg.jpeg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int NOT NULL,
  `supplier_name` varchar(100) NOT NULL,
  `supplier_address` text NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `supplier_name`, `supplier_address`, `phone_number`, `email`) VALUES
(2, 'dadang', 'jalan kedung', '08782738732', 'dadangtelur@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('staff','manager') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'staff', 'staff123', 'staff'),
(2, 'manager', 'manager123', 'manager');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `incoming_stock`
--
ALTER TABLE `incoming_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_code` (`product_code`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indeks untuk tabel `outgoing_stock`
--
ALTER TABLE `outgoing_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_code` (`product_code`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_code`);

--
-- Indeks untuk tabel `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `incoming_stock`
--
ALTER TABLE `incoming_stock`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `outgoing_stock`
--
ALTER TABLE `outgoing_stock`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `incoming_stock`
--
ALTER TABLE `incoming_stock`
  ADD CONSTRAINT `incoming_stock_ibfk_1` FOREIGN KEY (`product_code`) REFERENCES `products` (`product_code`) ON DELETE CASCADE,
  ADD CONSTRAINT `incoming_stock_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `outgoing_stock`
--
ALTER TABLE `outgoing_stock`
  ADD CONSTRAINT `outgoing_stock_ibfk_1` FOREIGN KEY (`product_code`) REFERENCES `products` (`product_code`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
