-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2025 at 06:23 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qrparking`
--

-- --------------------------------------------------------

--
-- Table structure for table `parking_cost`
--

CREATE TABLE `parking_cost` (
  `id` int(10) UNSIGNED NOT NULL,
  `hour` int(10) UNSIGNED NOT NULL,
  `cost` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `parking_cost`
--

INSERT INTO `parking_cost` (`id`, `hour`, `cost`) VALUES
(1, 1, 50),
(2, 2, 50),
(3, 3, 50),
(4, 4, 50),
(5, 5, 50),
(6, 6, 50);

-- --------------------------------------------------------

--
-- Table structure for table `parking_details`
--

CREATE TABLE `parking_details` (
  `id` int(11) NOT NULL,
  `vnumber` varchar(255) NOT NULL,
  `codeval` varchar(255) DEFAULT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `uname` varchar(255) DEFAULT NULL,
  `slot_name` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slot_booking`
--

CREATE TABLE `slot_booking` (
  `id` int(10) UNSIGNED NOT NULL,
  `uname` varchar(45) NOT NULL,
  `uid` varchar(45) NOT NULL,
  `pdate` varchar(45) NOT NULL,
  `stime` varchar(45) NOT NULL,
  `phrs` varchar(45) NOT NULL,
  `umail` varchar(45) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `slot_name` varchar(45) NOT NULL,
  `time` varchar(45) NOT NULL,
  `endtime` varchar(45) NOT NULL,
  `pcost` varchar(45) NOT NULL,
  `image_data` longblob DEFAULT NULL,
  `codeval` varchar(45) DEFAULT NULL,
  `ustatus` varchar(45) NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `slot_booking`
--

INSERT INTO `slot_booking` (`id`, `uname`, `uid`, `pdate`, `stime`, `phrs`, `umail`, `slot_name`, `time`, `endtime`, `pcost`, `image_data`, `codeval`, `ustatus`) VALUES
(49, 'eee', '19', '2025-02-16', '12:00', '2', 'eee@gmail.com', 'Slot 1', '12:00', '14:00', '100', NULL, NULL, 'No'),
(50, 'bob', '14', '2025-02-16', '12:00', '2', 'bob@gmail.com', 'Slot 2', '12:00', '14:00', '100', NULL, NULL, 'No'),
(51, 'bob', '14', '2025-02-16', '15:00', '3', 'bob@gmail.com', 'Slot 1', '15:00', '18:00', '150', NULL, NULL, 'No'),
(52, 'eee', '19', '2025-02-16', '18:00', '1', 'eee@gmail.com', 'Slot 1', '18:00', '19:00', '50', NULL, NULL, 'No');

-- --------------------------------------------------------

--
-- Table structure for table `user_reg`
--

CREATE TABLE `user_reg` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `dob` varchar(45) NOT NULL,
  `gender` varchar(45) NOT NULL,
  `phone` varchar(45) NOT NULL,
  `address` varchar(300) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `ustatus` varchar(45) DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_reg`
--

INSERT INTO `user_reg` (`id`, `name`, `email`, `dob`, `gender`, `phone`, `address`, `password`, `ustatus`) VALUES
(10, 'non', 'non@gmail.com', '2025-02-12', 'Male', '5380814062', 'Pendik', '$2y$10$5ODtT5uxNZ4un1cUXv7Lze2gSZF5fxfbo67wvs', 'Yes'),
(11, 'noon', 'noon@gmail.com', '2025-02-12', 'Female', '5380814062', 'dddd', '$2y$10$PL.h4HwJfXGlW/HQDVbfWewN..i9suNLHz7SbZ', 'No'),
(12, 'zoz', 'zoz@gmail.com', '2025-02-12', 'Male', '5380814089', 'vov', '$2y$10$rYYLyb9uqCrkidkCZNylXuw1ZEmKaS2bfVLHQYl9RUa4ayFqQOqQ2', 'Yes'),
(13, 'azo', 'azo@gmail.com', '2025-02-12', 'Female', '5380814062', 'vov', '$2y$10$CwNKvuubcQ7/KODJw9bV0eCmQaLojpXPMmDl5bxb98nR1j5i3Dq96', 'Yes'),
(14, 'bob', 'bob@gmail.com', '2025-02-12', 'Male', '5380814062', 'vov', '$2y$10$3QSI/9ygPxGavZy83A8VvuNtY2WZ8xjUVYXnVEhXF.nWwJHQp1EDy', 'No'),
(15, 'sos', 'sos@gmail.com', '2025-02-12', 'Female', '5380814062', 'vov', '$2y$10$iRcBpFa/XE9s0a/JFcGLsOFDryeGSSwTBGq11dfnPAC1lr2kOrv.S', 'No'),
(16, 'mom', 'mom@gmail.com', '2025-02-12', 'Male', '5380814062', 'Pendik', '$2y$10$Tq/UIenXn4HeQUXdK7WamO/LaO70QTXwUBOGRmz9ZtDVFd2cgQYma', 'Yes'),
(17, 'rat', 'rat@gmail.com', '2025-02-14', 'Other', '5380814062', 'dfg', '$2y$10$t1oq8vFm5FUOiIy5kzIdxO9eomlCARtbhfgmtXYZqHD9eNipbWeqm', 'Yes'),
(18, 'vav', 'vav@gmail.com', '2025-02-15', 'Male', '5380814062', 'Pendik', '$2y$10$7vglMOXAovmhDW9MMOABX.37YCPSuOoNzxbxseFN0k4eMZvR3T6q2', 'No'),
(19, 'eee', 'eee@gmail.com', '2025-02-15', 'Male', '5380814062', 'dddddgg', '$2y$10$uR823xDf2e9JvWqkN0P8Cep.76LJu6M1SO5TutxZ6hpDCgoN7WroS', 'Yes');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `parking_cost`
--
ALTER TABLE `parking_cost`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parking_details`
--
ALTER TABLE `parking_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slot_booking`
--
ALTER TABLE `slot_booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_reg`
--
ALTER TABLE `user_reg`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `parking_cost`
--
ALTER TABLE `parking_cost`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `parking_details`
--
ALTER TABLE `parking_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slot_booking`
--
ALTER TABLE `slot_booking`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `user_reg`
--
ALTER TABLE `user_reg`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
