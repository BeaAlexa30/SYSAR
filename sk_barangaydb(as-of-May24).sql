-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2025 at 10:19 AM
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
-- Database: `sk_barangaydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `accepted_for_assistance`
--

CREATE TABLE `accepted_for_assistance` (
  `id` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  `year_level` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accepted_for_assistance`
--

INSERT INTO `accepted_for_assistance` (`id`, `res_id`, `year_level`) VALUES
(7, 2025001, '3rd Year College');

-- --------------------------------------------------------

--
-- Table structure for table `accepted_members`
--

CREATE TABLE `accepted_members` (
  `id` int(11) NOT NULL,
  `members_id` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  `status` tinyint(4) DEFAULT 1,
  `archive` enum('Yes','No') DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accepted_members`
--

INSERT INTO `accepted_members` (`id`, `members_id`, `res_id`, `status`, `archive`) VALUES
(1, 1, 2025001, 1, 'No'),
(2, 1, 2025002, 1, 'No'),
(3, 1, 2025003, 1, 'No'),
(4, 1, 2025004, 1, 'No'),
(5, 2, 2025005, 1, 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `assistance_req`
--

CREATE TABLE `assistance_req` (
  `id` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  `year_level` varchar(100) NOT NULL,
  `ccog_filename` varchar(100) NOT NULL,
  `cor_filename` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assistance_req`
--

INSERT INTO `assistance_req` (`id`, `res_id`, `year_level`, `ccog_filename`, `cor_filename`) VALUES
(1, 2025001, '3rd Year College', 'uploads/Bausas, Bea Alexa D._COR.pdf', 'uploads/Bausas, Bea Alexa D._COR.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `completed_doc_requests`
--

CREATE TABLE `completed_doc_requests` (
  `id` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `docs_filename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `completed_doc_requests`
--

INSERT INTO `completed_doc_requests` (`id`, `res_id`, `first_name`, `last_name`, `docs_filename`) VALUES
(1, 2025001, 'Marvin', 'Anasco', 'RDES-Memo-No.-74-s.-2024_ATTENDANCE-IN-THE-FACE-TO-FACE-SEMINAR-WORKSHOP-ON-SELECTED-RESEARCH-TOOLS-AND-WRITING-RESEARCH-PROPOSAL-FOR-YOUNG-LIFTERS-PROGRAM-YLP-APPLICANTS.pdf'),
(2, 2025001, 'Bea Alexa', 'Bausas', 'Bausas, Bea Alexa D._COR.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `docreq_queue`
--

CREATE TABLE `docreq_queue` (
  `id` int(11) NOT NULL,
  `sk_id` int(11) NOT NULL,
  `year_level` varchar(50) NOT NULL,
  `purpose` varchar(100) NOT NULL,
  `docs_filename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `docreq_queue`
--

INSERT INTO `docreq_queue` (`id`, `sk_id`, `year_level`, `purpose`, `docs_filename`) VALUES
(4, 2025001, '3rd Year College', 'n/a', 'Farmers.docx'),
(6, 2025001, '2nd Year College', 'as', 'Bausas, Bea Alexa D._COR.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `skmembers_queue`
--

CREATE TABLE `skmembers_queue` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix` varchar(11) NOT NULL,
  `address` varchar(50) NOT NULL,
  `contact_num1` int(111) NOT NULL,
  `contact_num2` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `gender` varchar(11) NOT NULL,
  `age` int(11) NOT NULL,
  `blood_type` varchar(11) NOT NULL,
  `dob` varchar(50) NOT NULL,
  `religion` varchar(50) NOT NULL,
  `PWD` varchar(11) NOT NULL,
  `nationality` varchar(11) NOT NULL,
  `father_fullname` varchar(100) NOT NULL,
  `mother_fullname` varchar(100) NOT NULL,
  `contact_person` varchar(100) NOT NULL,
  `cp_relationship` varchar(50) NOT NULL,
  `cp_contactnum` int(11) NOT NULL,
  `cp_telephonenum` int(11) NOT NULL,
  `cp_address` varchar(50) NOT NULL,
  `filename` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skmembers_queue`
--

INSERT INTO `skmembers_queue` (`id`, `first_name`, `middle_name`, `last_name`, `suffix`, `address`, `contact_num1`, `contact_num2`, `email`, `gender`, `age`, `blood_type`, `dob`, `religion`, `PWD`, `nationality`, `father_fullname`, `mother_fullname`, `contact_person`, `cp_relationship`, `cp_contactnum`, `cp_telephonenum`, `cp_address`, `filename`, `status`) VALUES
(1, 'Marvin', 'Anasco', 'Tagle', '', '#157 Jasmin BOTANICAL', 1234567890, 0, 'email@gmail.com', 'male', 21, '', '2004-01-12', '', 'no', 'FILIPINO', 'SECRET', 'HULAAN', 'HULAAN', 'MOTHER', 987654321, 0, 'DITO LANG', '', 1),
(2, 'NENE', 'S.', 'FABS', '', 'DITO LANG STREET', 1234567890, 0, 'andesnatasha71@gmail.com', 'male', 31, '', '1994-01-12', '', 'no', 'FILIPINO', 'NELS', 'MOTHER', 'MOTHER', 'NANAY', 987654321, 0, 'HAHAH', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'superadmin', '12345');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accepted_for_assistance`
--
ALTER TABLE `accepted_for_assistance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `accepted_members` (`res_id`);

--
-- Indexes for table `accepted_members`
--
ALTER TABLE `accepted_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `skmembers_queue` (`members_id`);

--
-- Indexes for table `assistance_req`
--
ALTER TABLE `assistance_req`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `accepted_members` (`res_id`);

--
-- Indexes for table `completed_doc_requests`
--
ALTER TABLE `completed_doc_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `docreq_queue`
--
ALTER TABLE `docreq_queue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accepted_members` (`sk_id`);

--
-- Indexes for table `skmembers_queue`
--
ALTER TABLE `skmembers_queue`
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
-- AUTO_INCREMENT for table `accepted_for_assistance`
--
ALTER TABLE `accepted_for_assistance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `accepted_members`
--
ALTER TABLE `accepted_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `assistance_req`
--
ALTER TABLE `assistance_req`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `completed_doc_requests`
--
ALTER TABLE `completed_doc_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `docreq_queue`
--
ALTER TABLE `docreq_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `skmembers_queue`
--
ALTER TABLE `skmembers_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
