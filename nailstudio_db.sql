-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2025 at 04:14 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
-- Table structure for table `pengguna`
--

CREATE TABLE `product` (
  `id_pelanggan` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` text NOT NULL,
  `flag` enum('admin','konsumen') NOT NULL DEFAULT 'konsumen'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pelanggan`, `username`, `email`, `password`, `flag`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$G0jeXpAFjduw8nJfS7ao2ep5QP0f2SmptGIaPJcxoyp1ii1m2pHVS', 'konsumen'),
(2, 'daud', 'daud@gmail.com', '$2y$10$K0Csg2ypr7a3dWUk7DNtK.BGPxD8Q4bH2DvLQLHcm87vkveOrBMpK', 'konsumen');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE DATABASE IF NOT EXISTS tazkya_nails;

-- Use the database
USE nailstudio_db;

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    price DECIMAL(10,2) NOT NULL,
    status ENUM('draft', 'published', 'low_stock') NOT NULL DEFAULT 'draft',
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Insert some categories
INSERT INTO categories (name) VALUES 
('Almond Nails'),
('Oval Nails'),
('Lipstick Nails');

-- Insert sample products
INSERT INTO products (name, category_id, stock, price, status, image, created_at) VALUES
('PINK PERFECTION', 1, 10, 120000, 'low_stock', 'nail-art-1.jpg', '2024-12-29'),
('GREEN ELEGANCE', 2, 30, 130000, 'published', 'nail-art-2.jpg', '2024-11-24'),
('TROPICAL BLOOM NAILS', 3, 25, 140000, 'draft', 'nail-art-3.jpg', '2024-12-12'),
('ELEGANT RUBY GLAM NAILS', 2, 35, 110000, 'draft', 'nail-art-4.jpg', '2024-10-09'),
('BLACK CUTE NAILS', 1, 55, 125000, 'draft', 'nail-art-5.jpg', '2024-08-15'),
('GLAMOUR\'S', 3, 15, 165000, 'low_stock', 'nail-art-6.jpg', '2024-11-29'),
('FAKE PINK\'S', 1, 45, 200000, 'published', 'nail-art-7.jpg', '2024-09-12'),
('COW\'S', 2, 26, 135000, 'published', 'nail-art-8.jpg', '2024-10-10'),
('FLOWER\'S', 1, 45, 170000, 'low_stock', 'nail-art-9.jpg', '2024-10-25'),
('BLACKIE\'S', 3, 65, 115000, 'published', 'nail-art-10.jpg', '2024-10-10'),
('AZURE DREAM', 2, 15, 115000, 'draft', 'nail-art-11.jpg', '2024-08-15'),
('CRIMSON NOIR', 3, 67, 105000, 'published', 'nail-art-12.jpg', '2024-11-07');

-- Create trigger to automatically set status to 'low_stock' when stock falls below 20
DELIMITER //
CREATE TRIGGER check_stock_level
BEFORE UPDATE ON products
FOR EACH ROW
BEGIN
    IF NEW.stock < 20 AND NEW.stock > 0 THEN
        SET NEW.status = 'low_stock';
    END IF;
END//
DELIMITER ;