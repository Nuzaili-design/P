-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2025 at 11:38 PM
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
  `id` int(10) NOT NULL,
  `vnumber` varchar(255) NOT NULL,
  `codeval` varchar(255) DEFAULT NULL,
  `uid` int(45) DEFAULT NULL,
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
  `uname` varchar(255) NOT NULL,
  `uid` int(45) NOT NULL,
  `pdate` date NOT NULL,
  `stime` time NOT NULL,
  `phrs` varchar(45) NOT NULL,
  `umail` varchar(45) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `slot_name` varchar(255) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `endtime` time NOT NULL,
  `pcost` varchar(45) NOT NULL,
  `image_data` longblob DEFAULT NULL,
  `codeval` varchar(255) DEFAULT NULL,
  `ustatus` varchar(45) NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `slot_booking`
--

INSERT INTO `slot_booking` (`id`, `uname`, `uid`, `pdate`, `stime`, `phrs`, `umail`, `slot_name`, `time`, `endtime`, `pcost`, `image_data`, `codeval`, `ustatus`) VALUES
(114, 'eee', 19, '2025-02-26', '12:00:00', '1', 'eee@gmail.com', 'Slot 1', '0000-00-00 00:00:00', '13:00:00', '50', '', '', 'No'),
(115, 'eee', 19, '2025-02-26', '12:00:00', '1', 'eee@gmail.com', 'Slot 2', '0000-00-00 00:00:00', '13:00:00', '50', '', '', 'No'),
(116, 'bob', 14, '2025-02-26', '12:00:00', '1', 'bob@gmail.com', 'Slot 3', '0000-00-00 00:00:00', '13:00:00', '50', '', '', 'No'),
(117, 'bob', 14, '2025-02-26', '13:00:00', '2', 'bob@gmail.com', 'Slot 1', '0000-00-00 00:00:00', '15:00:00', '100', '', '', 'No'),
(118, 'eee', 19, '2025-02-26', '15:00:00', '1', 'eee@gmail.com', 'Slot 1', '0000-00-00 00:00:00', '16:00:00', '50', '', '', 'No'),
(119, 'eee', 19, '2025-03-01', '06:00:00', '2', 'eee@gmail.com', 'Slot 1', '0000-00-00 00:00:00', '08:00:00', '100', '', '', 'No'),
(120, 'bob', 14, '2025-03-01', '11:00:00', '1', 'bob@gmail.com', 'Slot 1', '0000-00-00 00:00:00', '12:00:00', '50', '', '', 'No'),
(121, 'bob', 14, '2025-03-01', '11:00:00', '1', 'bob@gmail.com', 'Slot 2', '0000-00-00 00:00:00', '12:00:00', '50', '', '', 'No'),
(122, 'bob', 14, '2025-03-01', '11:00:00', '1', 'bob@gmail.com', 'Slot 3', '0000-00-00 00:00:00', '12:00:00', '50', '', '', 'No'),
(123, 'bob', 14, '2025-03-01', '11:00:00', '1', 'bob@gmail.com', 'Slot 4', '2025-03-01 19:07:07', '12:00:00', '50', '', '', 'No'),
(124, 'bob', 14, '2025-03-01', '11:00:00', '1', 'bob@gmail.com', 'Slot 5', '2025-03-01 19:10:49', '12:00:00', '50', '', '', 'No'),
(125, 'eee', 19, '2025-03-01', '15:00:00', '2', 'eee@gmail.com', 'Slot 1', '2025-03-01 19:16:09', '17:00:00', '100', '', '', 'No'),
(126, 'faf', 21, '2025-03-01', '11:00:00', '1', 'faf@gmail.com', 'Slot 6', '2025-03-01 19:27:35', '12:00:00', '50', '', '', 'No'),
(127, 'eee', 19, '2025-03-07', '16:00:00', '1', 'eee@gmail.com', 'Slot 1', '2025-03-07 17:33:15', '17:00:00', '50', '', '', 'No'),
(128, 'bob', 14, '2025-03-07', '16:00:00', '1', 'bob@gmail.com', 'Slot 2', '2025-03-07 17:59:32', '17:00:00', '50', 0x89504e470d0a1a0a0000000d4948445200000091000000910103000000f916c86000000006504c5445ffffff00000055c2d37e000000097048597300000ec400000ec401952b0e1b000000d0494441544889ed94310ac3301004175ca4d413f413e7630109f432ff444f70a9c26473273bc4b1db3b70e12d4ed2348b96e5805b175320f9c650db532ecd89656094b3f5e1c40adb184ac518583d59f7f566afe8ca7a7e8938656ac8b40792dfa91b866cfb60c551864c5213c3527ba99d187a6a898f694eb539b1a496c238237a3180933c3931ef22b46583588ad4f7db3573a65a4b3dd089ad7dce621996e8c5d4322c7d594638b16d4fcaf8f97a30b1d43e138e4cc6be07e6ace757287d5efe333564eb9e54df43d7ecd8ad0be9039c85af40f2e1c5500000000049454e44ae426082, '', 'No'),
(129, 'bob', 14, '2025-03-07', '14:00:00', '2', 'bob@gmail.com', 'Slot 1', '2025-03-07 19:50:15', '16:00:00', '100', '', '', 'No'),
(130, 'bob', 14, '2025-03-07', '14:00:00', '1', 'bob@gmail.com', 'Slot 2', '2025-03-07 19:53:58', '15:00:00', '50', '', '', 'No'),
(131, 'bob', 14, '2025-03-07', '06:00:00', '1', 'bob@gmail.com', 'Slot 7', '2025-03-07 20:27:27', '07:00:00', '50', '', '', 'No'),
(132, 'eee', 19, '2025-03-08', '14:00:00', '1', 'eee@gmail.com', 'Slot 1', '2025-03-07 21:39:33', '15:00:00', '50', '', '', 'No'),
(133, 'eee', 19, '2025-03-08', '14:00:00', '1', 'eee@gmail.com', 'Slot 2', '2025-03-07 21:48:07', '15:00:00', '50', NULL, NULL, 'No'),
(134, 'eee', 19, '2025-03-08', '14:00:00', '1', 'eee@gmail.com', 'Slot 3', '2025-03-07 21:56:24', '15:00:00', '50', NULL, NULL, 'No');

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
(11, 'noon', 'noon@gmail.com', '2025-02-12', 'Female', '5380814062', 'dddd', '$2y$10$PL.h4HwJfXGlW/HQDVbfWewN..i9suNLHz7SbZ', 'Yes'),
(12, 'zoz', 'zoz@gmail.com', '2025-02-12', 'Male', '5380814089', 'vov', '$2y$10$rYYLyb9uqCrkidkCZNylXuw1ZEmKaS2bfVLHQYl9RUa4ayFqQOqQ2', 'Yes'),
(13, 'azo', 'azo@gmail.com', '2025-02-12', 'Female', '5380814062', 'vov', '$2y$10$CwNKvuubcQ7/KODJw9bV0eCmQaLojpXPMmDl5bxb98nR1j5i3Dq96', 'Yes'),
(14, 'bob', 'bob@gmail.com', '2025-02-12', 'Male', '5380814062', 'vov', '$2y$10$3QSI/9ygPxGavZy83A8VvuNtY2WZ8xjUVYXnVEhXF.nWwJHQp1EDy', 'Yes'),
(15, 'sos', 'sos@gmail.com', '2025-02-12', 'Female', '5380814062', 'vov', '$2y$10$iRcBpFa/XE9s0a/JFcGLsOFDryeGSSwTBGq11dfnPAC1lr2kOrv.S', 'Yes'),
(16, 'mom', 'mom@gmail.com', '2025-02-12', 'Male', '5380814062', 'Pendik', '$2y$10$Tq/UIenXn4HeQUXdK7WamO/LaO70QTXwUBOGRmz9ZtDVFd2cgQYma', 'Yes'),
(17, 'rat', 'rat@gmail.com', '2025-02-14', 'Other', '5380814062', 'dfg', '$2y$10$t1oq8vFm5FUOiIy5kzIdxO9eomlCARtbhfgmtXYZqHD9eNipbWeqm', 'Yes'),
(18, 'vav', 'vav@gmail.com', '2025-02-15', 'Male', '5380814062', 'Pendik', '$2y$10$7vglMOXAovmhDW9MMOABX.37YCPSuOoNzxbxseFN0k4eMZvR3T6q2', 'Yes'),
(19, 'eee', 'eee@gmail.com', '2025-02-15', 'Male', '5380814062', 'dddddgg', '$2y$10$uR823xDf2e9JvWqkN0P8Cep.76LJu6M1SO5TutxZ6hpDCgoN7WroS', 'Yes'),
(20, 'tat', 'tat@gmail.com', '2025-02-17', 'Male', '5380814062', 'dddd', '$2y$10$hKxlCPqlgE3XitiYVMBfXOvE4M3tGHmuG10xd8CG6y2nbhsgHFYIC', 'Yes'),
(21, 'faf', 'faf@gmail.com', '2025-02-18', 'Female', '5380814062', 'dfg', '$2y$10$/ZumKFA.CITNgygUv/m3m.oVbkIIxM7IuzbffuEFLDE1CRjDgJCSm', 'Yes'),
(22, 'Rayan', 'Rayan@gmail', '2025-02-20', 'Male', '5380814062', 'Pendik', '$2y$10$SU1EzwGnS3CuhYAlKHDD5eGFXoBgouYOvjE1gJ.lFNXjsIV13bINi', 'Yes');

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
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slot_booking`
--
ALTER TABLE `slot_booking`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `user_reg`
--
ALTER TABLE `user_reg`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
