-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 27, 2018 at 09:32 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mtcchain`
--

-- --------------------------------------------------------

--
-- Table structure for table `mtc_blocks`
--

CREATE TABLE `mtc_blocks` (
  `id` int(11) NOT NULL,
  `hash` varchar(256) NOT NULL,
  `previous_block_hash` varchar(256) NOT NULL,
  `hash_salt` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mtc_blocks`
--

INSERT INTO `mtc_blocks` (`id`, `hash`, `previous_block_hash`, `hash_salt`) VALUES
(1, 'mactiencong', '', ''),
(19, 'a188616e77756215b5b523eb45bf19d3', 'mactiencong', '609686227'),
(20, '99b9a2180084e986f86ca07a3937c3f6', 'a188616e77756215b5b523eb45bf19d3', '1206262170'),
(21, '11ccb7303f46227ab90b4cd01f2edffd', '99b9a2180084e986f86ca07a3937c3f6', '1717401591'),
(22, '2fcdc8264564959a0b9af7427f8b419a', '11ccb7303f46227ab90b4cd01f2edffd', '82873708');

-- --------------------------------------------------------

--
-- Table structure for table `mtc_transactions`
--

CREATE TABLE `mtc_transactions` (
  `id` int(11) NOT NULL,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `block_id` int(11) DEFAULT NULL,
  `is_pending` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mtc_transactions`
--

INSERT INTO `mtc_transactions` (`id`, `from`, `to`, `amount`, `time`, `block_id`, `is_pending`) VALUES
(1, 1, 2, 3, '2018-06-27 06:09:48', 19, 0),
(13, 1, 2, 1, '2018-06-27 07:22:20', 20, 0),
(14, 1, 2, 5, '2018-06-27 07:25:48', 21, 0),
(15, 1, 2, 1, '2018-06-27 07:25:48', 21, 0),
(16, 1, 2, 5, '2018-06-27 07:26:49', 21, 0),
(17, 1, 2, 1, '2018-06-27 07:26:49', 22, 0),
(18, 1, 2, 1, '2018-06-27 07:27:40', 22, 0),
(19, 1, 2, 1, '2018-06-27 07:30:31', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mtc_wallet`
--

CREATE TABLE `mtc_wallets` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `mtc_nodes` (
  `id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(256) NOT NULL,
  `wallet_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
--
-- Indexes for dumped tables
--

--
-- Indexes for table `mtc_blocks`
--
ALTER TABLE `mtc_blocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mtc_transactions`
--
ALTER TABLE `mtc_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mtc_wallet`
--
ALTER TABLE `mtc_wallets`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `mtc_nodes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mtc_blocks`
--
ALTER TABLE `mtc_blocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `mtc_transactions`
--
ALTER TABLE `mtc_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `mtc_wallet`
--
ALTER TABLE `mtc_wallets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `mtc_nodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
