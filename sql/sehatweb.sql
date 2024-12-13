-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2024 at 08:44 PM
-- Server version: 11.3.2-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sehatweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `keuangan`
--

CREATE TABLE `keuangan` (
  `id` int(11) NOT NULL,
  `pemasukan` decimal(15,2) DEFAULT 0.00,
  `pengeluaran` decimal(15,2) DEFAULT 0.00,
  `hasil` decimal(15,2) GENERATED ALWAYS AS (`pemasukan` - `pengeluaran`) STORED,
  `catatan` varchar(255) DEFAULT NULL,
  `tanggal` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keuangan`
--

INSERT INTO `keuangan` (`id`, `pemasukan`, `pengeluaran`, `catatan`, `tanggal`) VALUES
(22345, 10000.00, 5000.00, 'Obat', '2024-12-12'),
(223455, 10000.00, 5000.00, 'Obat', '2024-12-11'),
(223457, 10000.00, 5.00, 'Hasil hari ini', '2024-12-12');

-- --------------------------------------------------------

--
-- Table structure for table `obat`
--

CREATE TABLE `obat` (
  `id_obat` int(11) NOT NULL,
  `nama_obat` varchar(100) NOT NULL,
  `jenis_obat` enum('Tablet','Kapsul','Sirup','Injeksi','Salep') NOT NULL,
  `kategori_obat` varchar(50) DEFAULT NULL,
  `harga_beli` decimal(10,2) NOT NULL,
  `harga_jual` decimal(10,2) NOT NULL,
  `stok` int(11) DEFAULT 0,
  `lokasi_penyimpanan` varchar(50) DEFAULT NULL,
  `tanggal_kadaluwarsa` date DEFAULT NULL,
  `ditambahkan_oleh` varchar(100) DEFAULT NULL,
  `tanggal_ditambahkan` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `obat`
--

INSERT INTO `obat` (`id_obat`, `nama_obat`, `jenis_obat`, `kategori_obat`, `harga_beli`, `harga_jual`, `stok`, `lokasi_penyimpanan`, `tanggal_kadaluwarsa`, `ditambahkan_oleh`, `tanggal_ditambahkan`) VALUES
(1001, 'Mixagrip', 'Tablet', 'Obat Bebas', 10000.00, 5000.00, 9998, 'Gudang A', '2024-12-11', 'Admin', '2024-12-11 10:03:26'),
(1002, 'Neozep', 'Tablet', 'Obat Bebas', 15000.00, 5000.00, 9998, 'Gudang B', '2024-12-10', 'Admin', '2024-12-11 10:36:12'),
(1003, 'Paracetamol', 'Tablet', 'Obat Bebas', 20000.00, 2000.00, 10000, 'Gudang A', '2024-12-09', 'Admin', '2024-12-11 11:37:55'),
(1004, 'Cetirizin', 'Tablet', 'Vitamin', 15000.00, 5000.00, 9, 'Gudang A', '2024-12-11', 'Admin', '2024-12-11 15:39:46'),
(1005, 'Entrostop', 'Tablet', 'Obat Bebas', 10000.00, 2000.00, 1, 'Gudang A', '2024-12-20', 'Admin', '2024-12-11 18:22:16'),
(1006, 'Enfragrow', 'Tablet', 'Obat Resep', 100000.00, 5000.00, 2, 'Gudang C', '2024-12-12', 'Admin', '2024-12-11 19:42:44');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `nomor_pesanan` int(11) NOT NULL,
  `nama_customer` varchar(255) NOT NULL,
  `barang_yang_dibeli` text NOT NULL,
  `harga` decimal(15,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `alamat` text NOT NULL,
  `status_dikirim` enum('Baru','Dikirim','Dibatalkan') NOT NULL DEFAULT 'Baru'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`nomor_pesanan`, `nama_customer`, `barang_yang_dibeli`, `harga`, `qty`, `alamat`, `status_dikirim`) VALUES
(1, 'Astuti', 'Neozep', 50000.00, 1, 'Tabing', 'Dikirim'),
(3, 'Pablo', '1001', 5000.00, 1, 'Tabing', 'Dikirim'),
(4, 'Johny', '1002', 5000.00, 1, 'Tabing', 'Dikirim'),
(5, 'Susi', '1004', 5000.00, 3, 'Tabing', 'Dikirim'),
(6, 'Tio', '1005', 2000.00, 1, 'Tabing', 'Dikirim'),
(7, 'Astuti', '1005', 2000.00, 1, 'Tabing', 'Dikirim'),
(8, 'Sins', '1006', 5000.00, 1, 'Tabing', 'Dikirim'),
(123, 'dio', '1001', 5000.00, 1, 'Air Tawar', 'Dibatalkan');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `stok` int(11) NOT NULL,
  `harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nomor_telepon` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_karyawan` varchar(100) NOT NULL,
  `foto_profil` varchar(255) NOT NULL DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `nomor_telepon`, `password`, `nama_karyawan`, `foto_profil`) VALUES
('22345', 'astuti@gmail.com', '086584531244578', '12345678', 'Astuti Hilawan', 'img/1733940341-download.jpeg'),
('240105', 'ardiherdiana@gmail.com', '089501748186', '$2y$10$wCDX1oRsojw3RYmMPWMsa.N8hS2bIkW9ds074SUmhsPLCxVvS0bcm', 'Ardi Herdiana', 'default.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `keuangan`
--
ALTER TABLE `keuangan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id_obat`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`nomor_pesanan`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `keuangan`
--
ALTER TABLE `keuangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240106;

--
-- AUTO_INCREMENT for table `obat`
--
ALTER TABLE `obat`
  MODIFY `id_obat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1007;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `nomor_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55456789;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
