-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 12, 2021 at 09:34 AM
-- Server version: 10.5.12-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u298279946_pcon_exam`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `createdOn` varchar(255) NOT NULL DEFAULT current_timestamp(),
  `picture` varchar(255) DEFAULT NULL,
  `verified` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `enrolled_test`
--

CREATE TABLE `enrolled_test` (
  `sl` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `test_id` varchar(50) NOT NULL,
  `starttime` varchar(50) NOT NULL,
  `endtime` varchar(50) NOT NULL,
  `time_left` int(10) DEFAULT NULL COMMENT 'in second',
  `attendance` int(11) NOT NULL DEFAULT 0,
  `ip` varchar(100) DEFAULT NULL,
  `tabchange` int(11) NOT NULL DEFAULT 0,
  `login_attempt` int(11) NOT NULL DEFAULT 0,
  `submitted` int(11) NOT NULL DEFAULT 0,
  `enrolled_on` varchar(50) NOT NULL,
  `total_marks` int(11) DEFAULT NULL,
  `device` varchar(100) DEFAULT NULL,
  `sharingID` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `sl` int(11) NOT NULL,
  `question_id` varchar(50) NOT NULL,
  `test_id` varchar(50) NOT NULL,
  `question` text CHARACTER SET utf8 NOT NULL,
  `image` varchar(200) DEFAULT NULL,
  `option_a` text CHARACTER SET utf8 NOT NULL,
  `option_b` text CHARACTER SET utf8 NOT NULL,
  `option_c` text CHARACTER SET utf8 NOT NULL,
  `option_d` text CHARACTER SET utf8 NOT NULL,
  `answer` varchar(10) NOT NULL,
  `negativeMarking` int(11) NOT NULL DEFAULT 0,
  `section` varchar(50) NOT NULL,
  `positiveMarking` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `sl` int(11) NOT NULL,
  `admin` int(11) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `isPublic` int(11) NOT NULL DEFAULT 0 COMMENT 'if 0 then candidate with link can give test',
  `test_id` varchar(50) NOT NULL,
  `test_name` varchar(50) NOT NULL,
  `sdatetime` varchar(25) NOT NULL,
  `edatetime` varchar(25) NOT NULL,
  `test_duration` int(11) NOT NULL,
  `attempts` int(11) NOT NULL,
  `show_result` int(5) NOT NULL DEFAULT 0 COMMENT '1:Yes; 0:No',
  `created` varchar(50) NOT NULL,
  `isActive` int(11) NOT NULL DEFAULT 0,
  `test_for` varchar(50) DEFAULT NULL,
  `solution` varchar(250) NOT NULL COMMENT 'video solution link',
  `nitOnly` int(11) NOT NULL DEFAULT 0,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `test_response`
--

CREATE TABLE `test_response` (
  `sl` int(11) NOT NULL,
  `timestamp` varchar(25) DEFAULT NULL,
  `test_id` varchar(110) NOT NULL,
  `user_id` varchar(200) NOT NULL,
  `question_id` varchar(110) NOT NULL,
  `response` varchar(20) NOT NULL,
  `status` int(1) NOT NULL,
  `marks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_account`
--

CREATE TABLE `user_account` (
  `id` bigint(20) NOT NULL,
  `createdOn` datetime DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `isActive` int(11) DEFAULT NULL,
  `modifiedOn` datetime DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `branch` int(11) DEFAULT NULL,
  `verified` int(11) NOT NULL DEFAULT 0,
  `picture` varchar(255) DEFAULT NULL,
  `alternateEmail` varchar(255) DEFAULT NULL,
  `roll` varchar(15) DEFAULT NULL,
  `sharingID` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enrolled_test`
--
ALTER TABLE `enrolled_test`
  ADD PRIMARY KEY (`sl`),
  ADD UNIQUE KEY `unique_enrollment` (`test_id`,`user_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`sl`),
  ADD UNIQUE KEY `question_id` (`question_id`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`test_id`),
  ADD UNIQUE KEY `sl` (`sl`);

--
-- Indexes for table `test_response`
--
ALTER TABLE `test_response`
  ADD PRIMARY KEY (`sl`);

--
-- Indexes for table `user_account`
--
ALTER TABLE `user_account`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enrolled_test`
--
ALTER TABLE `enrolled_test`
  MODIFY `sl` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `sl` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `test`
--
ALTER TABLE `test`
  MODIFY `sl` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `test_response`
--
ALTER TABLE `test_response`
  MODIFY `sl` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_account`
--
ALTER TABLE `user_account`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
