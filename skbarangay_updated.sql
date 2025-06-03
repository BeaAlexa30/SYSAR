-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2025 at 05:24 AM
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
-- Database: `skbarangay_updated`
--

-- --------------------------------------------------------

--
-- Table structure for table `accepted_for_assistance`
--

CREATE TABLE `accepted_for_assistance` (
  `id` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  `year_level` varchar(100) NOT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accepted_for_assistance`
--

INSERT INTO `accepted_for_assistance` (`id`, `res_id`, `year_level`, `status`) VALUES
(1, 2025002, 'Grade 8', 1),
(2, 2025001, 'Grade 10', 1),
(3, 2025003, 'Grade 8', 1);

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
(1, 100, 2025001, 1, 'No'),
(2, 99, 2025002, 1, 'No'),
(3, 98, 2025003, 1, 'No'),
(4, 97, 2025004, 1, 'No'),
(5, 96, 2025005, 1, 'No'),
(6, 95, 2025006, 1, 'No'),
(7, 94, 2025007, 1, 'No'),
(8, 93, 2025008, 1, 'No'),
(9, 92, 2025009, 1, 'No'),
(10, 91, 2025010, 1, 'No'),
(11, 90, 2025011, 1, 'No'),
(12, 89, 2025012, 1, 'No'),
(13, 88, 2025013, 1, 'No'),
(14, 87, 2025014, 1, 'No'),
(15, 86, 2025015, 1, 'No'),
(16, 85, 2025016, 1, 'No'),
(17, 84, 2025017, 1, 'No'),
(18, 83, 2025018, 1, 'No'),
(19, 82, 2025019, 1, 'No'),
(22, 80, 2025020, 1, 'No');

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
(3, 2025002, 'Grade 8', 'uploads/Bausas, Bea Alexa D._COR.pdf', 'uploads/Bausas, Bea Alexa D._COR.pdf'),
(6, 2025001, 'Grade 10', 'uploads/Bausas, Bea Alexa D._COR.pdf', 'uploads/Bausas, Bea Alexa D._COR.pdf'),
(7, 2025003, 'Grade 8', 'uploads/Bausas, Bea Alexa D._COR.pdf', 'uploads/Bausas, Bea Alexa D._COR.pdf');

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
(4, 2025003, 'Tracy', 'Brown', 'BAUSAS, Bea Alexa D. - PERFORMANCE TASK 1.pdf'),
(6, 2025002, 'Heather', 'Lewis', 'BAUSAS, Bea Alexa D. - WRITTEN WORK NO 1.pdf');

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
(11, 2025003, 'Grade 7', 'activity', 'Bausas_Script.pdf'),
(12, 2025001, 'Grade 12', 'asf', 'Bausas_Script.pdf');

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
  `address` varchar(100) NOT NULL,
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
  `cp_address` varchar(100) NOT NULL,
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skmembers_queue`
--

INSERT INTO `skmembers_queue` (`id`, `first_name`, `middle_name`, `last_name`, `suffix`, `address`, `contact_num1`, `contact_num2`, `email`, `gender`, `age`, `blood_type`, `dob`, `religion`, `PWD`, `nationality`, `father_fullname`, `mother_fullname`, `contact_person`, `cp_relationship`, `cp_contactnum`, `cp_telephonenum`, `cp_address`, `status`) VALUES
(1, 'Justin', 'Joseph', 'Richards', '', '847 Scorpio Road, Lamut, 8360 Sultan Kudarat', 2147483647, 2147483647, 'angela93@yahoo.com', 'Male', 9, 'B-', '2016-01-16', 'Iglesia ni Cristo', 'Yes', 'Filipino', 'Anthony King', 'Brittany Jones', 'Kari Sharp', 'Relative', 2147483647, 2147483647, '4136 Jade Street, Tudela, 7049 Misamis Oriental', 0),
(2, 'Michael', 'Ryan', 'Walker', 'Jr.', '8013 Juno Road Extension, Howard Village 4, Culaba, 7323 Tawi-Tawi', 2147483647, 2147483647, 'travisharvey@xzfoods.org', 'Male', 10, 'AB-', '2015-04-07', 'Born Again', 'No', 'Filipino', 'Shawn Davis', 'Sandra Ayala', 'Gerald Hobbs', 'Relative', 2147483647, 2147483647, 'Block 18 Lot 85 Hudson Village Phase 7, Uranus Street, Baliuag, 6696 Samar', 0),
(3, 'Stephanie', 'Connie', 'Nichols', 'Jr.', '632 Avocado Street, Comet Subdivision, Guihulngan, 2412 Nueva Ecija', 2147483647, 2147483647, 'mary27@dkgservices.com.ph', 'Female', 16, 'O+', '2009-04-30', 'Islam', 'No', 'Filipino', 'Randy Savage', 'Elizabeth Pratt', 'Joseph Solis', 'Parent', 2147483647, 2147483647, 'Block 05 Lot 08 Evans Street, Campanilla Village, Santa, 5077 Guimaras', 0),
(4, 'Jose', 'Brian', 'Johnson', 'III', 'B16 L59 Palali Boulevard, Zamora Homes, Hadji Mohammad Ajul, 7521 Surigao del Sur', 2147483647, 2147483647, 'lmartinez@gmail.com', 'Male', 15, 'A+', '2009-11-29', 'Islam', 'No', 'Filipino', 'Danny Houston', 'Dawn Gray', 'Christopher Parker', 'Relative', 2147483647, 2147483647, '2534 Hydra Road, Samal, 9395 Agusan del Sur', 0),
(5, 'Ashley', 'Charles', 'Haney', '', 'B07 L99 Oleander Village, Mayon Boulevard, Bagulin, 9850 Agusan del Norte', 2147483647, 2147483647, 'alvin91@gypv.com', 'Female', 10, 'A-', '2014-12-20', 'Roman Catholic', 'No', 'Filipino', 'Norman Vasquez', 'Taylor Lamb', 'Donna Wilcox', 'Guardian', 2147483647, 2147483647, '29F Dean Apartment, 123 Zircon Avenue, T\'Boli, 6069 Northern Samar', 0),
(6, 'Tiffany', 'Wendy', 'Terry', '', '27th Floor Lambert Condominiums Tower 9, 9279 Saturn Street, Roxas, 4698 Apayao', 2147483647, 2147483647, 'robinzavala@hrqy.com.ph', 'Female', 14, 'AB+', '2011-01-19', 'Iglesia ni Cristo', 'No', 'Filipino', 'Timothy Brown', 'Michelle Adams', 'Tammie Reyes', 'Guardian', 2147483647, 2147483647, '1338E Jacaranda Street, Tinglayan, 2295 Quirino', 0),
(7, 'Andre', 'Jessica', 'Burke', '', '17th Floor Azalea Towers, 887 Jupiter Drive, San Esteban, 2081 Cagayan', 2147483647, 2147483647, 'andersonstephanie@pacificsummit.org.ph', 'Male', 20, 'AB-', '2004-10-06', 'Iglesia ni Cristo', 'No', 'Filipino', 'Benjamin Moore', 'Teresa Vasquez', 'Gloria Brooks', 'Sibling', 2147483647, 2147483647, '9411-E 78th Drive, Bantayan, 6575 Eastern Samar', 0),
(8, 'Lori', 'Sandra', 'Dillon', '', '5 Daugherty Extension, Ivana, 2535 Camarines Sur', 2147483647, 2147483647, 'darrellwalker@carterstar.org.ph', 'Female', 12, 'B-', '2012-12-23', 'Iglesia ni Cristo', 'Yes', 'Filipino', 'Alex Williams', 'Lori Mcdonald', 'Stephanie Davis', 'Sibling', 2147483647, 2147483647, 'B06 L04 Rodriguez Estates Phase 8, Agate Drive, Datu Unsay, 8640 Misamis Occidental', 0),
(9, 'Stephen', 'Kenneth', 'Washington', 'Jr.', 'Block 18 Lot 41 Jupiter Road, Nipa Cove Phase 6, San Guillermo, 6696 Cebu', 2147483647, 2147483647, 'colecaitlin@easternstate.com', 'Male', 11, 'O+', '2014-03-14', 'Roman Catholic', 'No', 'Filipino', 'Taylor Knight', 'Lori Levy', 'Laura Mullins', 'Guardian', 2147483647, 2147483647, 'Room 515 Ramirez Towers, 9727 Aquamarine Avenue, Tadian, 4241 Cavite', 0),
(10, 'Caitlin', 'Roy', 'Houston', 'Jr.', '4300E Gumamela Highway, San Juan, 0978 Metro Manila', 2147483647, 2147483647, 'soliscarrie@metrogenesis.com.ph', 'Female', 28, 'O+', '1996-12-25', 'Iglesia ni Cristo', 'Yes', 'Filipino', 'Mark Lowe', 'Ashley Fisher', 'Katherine Garcia', 'Parent', 2147483647, 2147483647, 'Block 22 Lot 04 Melon Drive, Supa Subdivision, Munai, 4488 Catanduanes', 0),
(11, 'David', 'Courtney', 'Jones', 'Sr.', '5781 Malinao Drive, Silang, 4602 Sorsogon', 2147483647, 2147483647, 'nicoletanner@zohomail.com', 'Male', 8, 'O+', '2017-04-29', 'Islam', 'No', 'Filipino', 'Jeffrey Martinez', 'Suzanne Morgan', 'Joseph Henry', 'Sibling', 2147483647, 2147483647, 'B22 L86 Atok Street, Hardin Cove 4, Corella, 8485 South Cotabato', 0),
(12, 'Alicia', 'Christopher', 'Campos', '', '1173D Matumtum Street, Concepcion, 6056 Capiz', 2147483647, 2147483647, 'crawfordcrystal@msgproperties.com', 'Female', 26, 'B-', '1999-05-16', 'Born Again', 'Yes', 'Filipino', 'Steven Russell', 'Amanda Velez', 'Nathaniel Daniel', 'Guardian', 2147483647, 2147483647, '3707 Unit I Collins Street, Gigmoto, 3184 Ifugao', 0),
(13, 'James', 'Kevin', 'Randall', 'III', '6723 Bush Street, Cresta Subdivision, Malapatan, 9830 Compostela Valley', 2147483647, 2147483647, 'nathanielbass@adstrust.net.ph', 'Male', 8, 'A+', '2016-12-19', 'Born Again', 'No', 'Filipino', 'Jeff Scott', 'Lauren Sampson', 'Martin Jones', 'Sibling', 2147483647, 2147483647, 'B20 L67 52nd Road, Contreras Cove 8, Salug, 4602 Quezon', 0),
(14, 'Kathleen', 'Jared', 'Phillips', 'Jr.', 'Unit 715 Baticulin Apartments, 1727 Granada Road, Bataraza, 5546 Tarlac', 2147483647, 2147483647, 'xmontgomery@etg.net.ph', 'Female', 7, 'B+', '2017-07-01', 'Born Again', 'No', 'Filipino', 'Jeffrey Oliver', 'Sandra Case', 'Andrew Keith', 'Sibling', 2147483647, 2147483647, '94 Simmons Road, Aquarius Village Phase 1, Amadeo, 8170 South Cotabato', 0),
(15, 'James', 'Daniel', 'Lewis', '', 'Block 12 Lot 60 Orion Street, Zodiac Grove Phase 6, General Salipada K. Pendatun, 5063 Cebu', 2147483647, 2147483647, 'cguerra@yahoo.com', 'Male', 22, 'O+', '2002-12-19', 'Iglesia ni Cristo', 'No', 'Filipino', 'Gregory Freeman', 'Susan Velez', 'Adam Jackson', 'Parent', 2147483647, 2147483647, '7712 Onyx Road, Hagonoy, 6593 Guimaras', 0),
(16, 'Samuel', 'Austin', 'Mcconnell', '', 'Block 01 Lot 83 11th Road Extension, Brown Village 7, Pantar, 2082 Oriental Mindoro', 2147483647, 2147483647, 'christopher18@metrosummit.net.ph', 'Male', 27, 'AB-', '1998-04-15', 'Roman Catholic', 'No', 'Filipino', 'James Martin', 'Heather Johnson', 'Michael Graves', 'Relative', 2147483647, 2147483647, 'Room 4017 Holt Building Tower 9, 5928 Wheeler Street, Bugallon, 6576 Antique', 0),
(17, 'Veronica', 'Rodney', 'Williams', '', 'Room 530 Ortiz Apartment, 4559 Sagittarius Road, Aritao, 3651 Laguna', 2147483647, 2147483647, 'whiteheadlarry@mkgm.org.ph', 'Female', 6, 'O+', '2018-06-22', 'Baptist', 'Yes', 'Filipino', 'Caleb Stewart', 'Bridget Taylor', 'Laura Brandt', 'Sibling', 2147483647, 2147483647, '5742 H Polaris Avenue, Cuyo, 1881 Nueva Vizcaya', 0),
(18, 'Paul', 'Ann', 'Zimmerman', 'Jr.', '8862 Sardonyx Avenue, Aloguinsan, 3777 Abra', 2147483647, 2147483647, 'paulastewart@risingsilver.org.ph', 'Male', 11, 'A+', '2013-07-01', 'Born Again', 'No', 'Filipino', 'Brad Carson', 'Audrey Smith', 'Kimberly Williams', 'Relative', 2147483647, 2147483647, '9547 Unit H Hibok-Hibok Street, Diadi, 3121 Cavite', 0),
(19, 'Joseph', 'Justin', 'Clark', '', 'Room 901 Keller Residences 8, 5423 Harmon Drive, Bagac, 6302 Capiz', 2147483647, 2147483647, 'moralesamy@bishopstate.ph', 'Male', 16, 'B-', '2008-09-24', 'Islam', 'Yes', 'Filipino', 'Mark Becker', 'Molly Rivera', 'Kelly Hammond', 'Sibling', 2147483647, 2147483647, '6425 Unit G 92nd Boulevard, Kawayan, 8092 Davao Occidental', 0),
(20, 'Jason', 'Megan', 'Edwards', 'III', '8091 Scorpio Street, Orion Village 9, Laguindingan, 9209 Sarangani', 2147483647, 2147483647, 'knelson@lkfinance.com.ph', 'Male', 18, 'O-', '2007-05-20', 'Baptist', 'No', 'Filipino', 'Timothy Ayala', 'Sonia Mason', 'Tracy Taylor', 'Sibling', 2147483647, 2147483647, '2848 Pao Street, Marikina, 1555 Metro Manila', 0),
(21, 'Anna', 'Kim', 'Gonzalez', 'III', 'Room 1101 Palali Building, 7333 25th Boulevard, General Salipada K. Pendatun, 8175 South Cotabato', 2147483647, 2147483647, 'snelson@ndccompany.org', 'Female', 11, 'O-', '2014-04-18', 'Iglesia ni Cristo', 'No', 'Filipino', 'Dustin Hall', 'Nancy Cochran', 'Cynthia Newman', 'Guardian', 2147483647, 2147483647, '4405 Caraballo Highway, Balindong, 2535 Bataan', 0),
(22, 'Emily', 'Jimmy', 'Miller', '', '2237I Garnet Street, Laua-an, 4128 Nueva Vizcaya', 2147483647, 2147483647, 'stephanie21@yahoo.com', 'Female', 16, 'AB-', '2009-04-27', 'Iglesia ni Cristo', 'Yes', 'Filipino', 'Thomas Castro', 'Lisa Schmitt', 'Victor Yates', 'Parent', 2147483647, 2147483647, 'Block 04 Lot 82 Tabayoc Homes Phase 6, Bartlett Expressway, Tagbina, 8031 Agusan del Norte', 0),
(23, 'Donald', 'Michelle', 'Henderson', 'Jr.', '49th Floor Gardner Residences 9, 8142 Makiling Avenue, Tabontabon, 4616 Tarlac', 2147483647, 2147483647, 'palmereric@zohomail.com', 'Male', 12, 'A-', '2013-01-31', 'Iglesia ni Cristo', 'Yes', 'Filipino', 'Joseph Patrick', 'Jennifer Hall', 'Joseph Ochoa', 'Sibling', 2147483647, 2147483647, '8806-B Agate Avenue, Las Piñas, 0899 Metro Manila', 0),
(24, 'John', 'Sara', 'Mueller', '', '5796 8th Road, Jalajala, 7072 Davao del Norte', 2147483647, 2147483647, 'malik92@pacificempire.org.ph', 'Male', 10, 'AB+', '2014-11-20', 'Roman Catholic', 'No', 'Filipino', 'Ronald Woods', 'Nicole Rich', 'Lindsay Roberts', 'Sibling', 2147483647, 2147483647, 'Room 905 Santan Place Tower 6, 7918 Hibok-Hibok Boulevard, Pasuquin, 3667 Tarlac', 0),
(25, 'Stephanie', 'Yvonne', 'Graham', 'III', '5771 11th Road, Norala, 2403 Pangasinan', 2147483647, 2147483647, 'edwin01@zohomail.com', 'Female', 10, 'AB-', '2014-07-14', 'Roman Catholic', 'No', 'Filipino', 'Christopher Gutierrez', 'Kristy Sweeney', 'Tyler Blair', 'Parent', 2147483647, 2147483647, '1184 Kanlaon Road, Pontevedra, 4839 Palawan', 0),
(26, 'Eric', 'Timothy', 'Coleman', '', '2049 Lee Drive, Shariff Aguak, 5163 Bataan', 2147483647, 2147483647, 'david47@yahoo.com', 'Male', 19, 'A+', '2006-02-05', 'Born Again', 'No', 'Filipino', 'Patrick Thompson', 'Haley Myers', 'Anthony Maxwell', 'Sibling', 2147483647, 2147483647, '964-J Onyx Drive Extension, Santol, 1877 Cavite', 0),
(27, 'Frank', 'Susan', 'Weaver', 'III', '1771 H Pao Avenue, Benito Soliven, 9233 Davao del Norte', 2147483647, 2147483647, 'phillip33@perezunion.com.ph', 'Male', 21, 'AB-', '2003-12-15', 'Roman Catholic', 'Yes', 'Filipino', 'Kristopher Gibson', 'Alexis Hill', 'Shannon Payne', 'Parent', 2147483647, 2147483647, '863 Garnet Avenue, Agate Subdivision Phase 5, Antequera, 4567 Zambales', 0),
(28, 'Susan', 'Herbert', 'George', 'III', '3472 Onyx Street, Calumpang Subdivision, Ronda, 4823 Romblon', 2147483647, 2147483647, 'stephaniegibbs@pmgconstruction.org', 'Female', 22, 'O-', '2002-11-25', 'Born Again', 'No', 'Filipino', 'Evan Delacruz', 'Brittney Jones', 'James Goodwin', 'Relative', 2147483647, 2147483647, '18F Robbins Suites, 2766 Palmer Street, Macabebe, 4426 La Union', 0),
(29, 'Eddie', 'Claire', 'Cohen', '', 'Room 710 Olsen Condominiums 5, 7427 Balimbing Avenue, Hilongos, 8607 Surigao del Norte', 2147483647, 2147483647, 'kevinrivera@zohomail.com', 'Male', 28, 'A-', '1996-10-26', 'Roman Catholic', 'No', 'Filipino', 'Brian Webster', 'Leslie Davis', 'James Rojas', 'Relative', 2147483647, 2147483647, 'Block 04 Lot 41 Capricorn Street, Lily Grove, Kalibo, 2777 Romblon', 0),
(30, 'Timothy', 'Lauren', 'Kelly', '', '5983 Unit F Venus Street, Laur, 3866 Bulacan', 2147483647, 2147483647, 'thomas85@yahoo.com', 'Male', 26, 'A+', '1998-09-21', 'Iglesia ni Cristo', 'Yes', 'Filipino', 'Nicholas Kane', 'Casey Johnson', 'Jennifer Randall', 'Parent', 2147483647, 2147483647, '6175I Opal Avenue, Cotabato City, 7257 Davao del Sur', 0),
(31, 'Kristy', 'Brian', 'Bailey', 'Jr.', '30th Floor Smith Place Tower 7, 3587 88th Road, Pilar, 9411 Lanao del Sur', 2147483647, 2147483647, 'tvalentine@liservices.net', 'Female', 22, 'B+', '2003-05-05', 'Roman Catholic', 'No', 'Filipino', 'Jacob Coleman', 'Barbara Williams', 'Shelby Smith', 'Sibling', 2147483647, 2147483647, '9567 A Jade Street, Pateros, 1417 Metro Manila', 0),
(32, 'Ryan', 'Christine', 'Jackson', '', '4908 Unit F Juno Drive, Pasay, 1356 Metro Manila', 2147483647, 2147483647, 'sellersscott@sawyerliberty.org', 'Male', 19, 'AB+', '2006-02-22', 'Iglesia ni Cristo', 'No', 'Filipino', 'Michael Hanson', 'Jenna Bird', 'Tyler Golden', 'Parent', 2147483647, 2147483647, '3903 Chico Avenue, Palm Village, Tubo, 4223 Rizal', 0),
(33, 'Krystal', 'Brenda', 'Zimmerman', '', 'B23 L43 Aranga Grove Phase 8, Caraballo Street, Dueñas, 9189 Surigao del Norte', 2147483647, 2147483647, 'haroldtaylor@yahoo.com', 'Female', 19, 'A-', '2006-05-14', 'Islam', 'No', 'Filipino', 'Barry Jones', 'Ashley Chavez', 'John Cortez', 'Guardian', 2147483647, 2147483647, '4141I 92nd Drive, Quinapondan, 4261 Tarlac', 0),
(34, 'Paul', 'William', 'Pierce', 'Jr.', '9623G 40th Road, Pagayawan, 5238 Ifugao', 2147483647, 2147483647, 'kelly16@ygcl.ph', 'Male', 23, 'B+', '2001-10-04', 'Roman Catholic', 'Yes', 'Filipino', 'Adam Black', 'Marilyn Jacobs', 'Melinda Jackson', 'Parent', 2147483647, 2147483647, '4792 Libra Street, Mercury Village Phase 9, Pura, 6447 Northern Samar', 0),
(35, 'Brandon', 'Courtney', 'Moreno', '', '9540 Caraballo Road, Mahayag, 3087 Laguna', 2147483647, 2147483647, 'andrea35@pmlbank.net.ph', 'Male', 25, 'B-', '2000-03-16', 'Roman Catholic', 'No', 'Filipino', 'Joseph Sparks', 'Amanda Ryan', 'Philip Payne', 'Parent', 2147483647, 2147483647, '5703 Anderson Street, Bacuag, 6323 Aklan', 0),
(36, 'Nancy', 'Emily', 'Werner', '', 'Unit 202 Asparagus Towers 8, 9069 67th Street, Mapun, 2688 Benguet', 2147483647, 2147483647, 'toddpeterson@gmail.com', 'Female', 13, 'A+', '2011-12-12', 'Iglesia ni Cristo', 'No', 'Filipino', 'Kevin Mcmahon', 'Natalie Johnson', 'Phillip Valdez', 'Relative', 2147483647, 2147483647, 'Block 07 Lot 89 Barrett Subdivision Phase 4, Santan Avenue, Matanog, 2568 Occidental Mindoro', 0),
(37, 'Debra', 'Tiffany', 'Chan', 'III', 'Block 14 Lot 09 Dungon Subdivision 3, Fuller Avenue, Limay, 9494 Sultan Kudarat', 2147483647, 2147483647, 'richard02@yahoo.com', 'Female', 25, 'B+', '1999-11-14', 'Baptist', 'Yes', 'Filipino', 'Kyle Rodriguez', 'Kayla Daniel', 'Mary Anderson', 'Guardian', 2147483647, 2147483647, '9434 Mayon Road, Iloilo City, 6330 Leyte', 0),
(38, 'Timothy', 'Bryan', 'Aguilar', '', '3585E Sicaba Drive, Bagamanoc, 8782 Lanao del Sur', 2147483647, 2147483647, 'downsbrian@osed.ph', 'Male', 12, 'B-', '2012-11-10', 'Born Again', 'Yes', 'Filipino', 'Christopher Smith', 'Melissa Velazquez', 'Ashley Nichols', 'Parent', 2147483647, 2147483647, 'Block 09 Lot 56 Cypress Homes, Castaneda Service Road, Malimono, 2850 Quezon', 0),
(39, 'Crystal', 'Edward', 'Silva', 'III', '5563 Gumamela Highway, Ilagan, 7011 Surigao del Norte', 2147483647, 2147483647, 'judyhansen@advancedgenesis.org.ph', 'Female', 8, 'A+', '2016-07-14', 'Iglesia ni Cristo', 'No', 'Filipino', 'Ricardo Lynn', 'Caroline Carrillo', 'Alisha Davis', 'Parent', 2147483647, 2147483647, '5728 Avocado Expressway, Wang Subdivision Phase 4, San Juan, 0985 Metro Manila', 0),
(40, 'Jeffery', 'Austin', 'Vasquez', 'Jr.', '5F Citrine Place, 8924 Amber Street, El Nido, 9177 South Cotabato', 2147483647, 2147483647, 'fhubbard@williamsstar.com', 'Male', 9, 'B-', '2015-07-10', 'Iglesia ni Cristo', 'Yes', 'Filipino', 'Brandon Johnson', 'Jamie Duncan', 'Cheyenne Martin', 'Guardian', 2147483647, 2147483647, 'Block 22 Lot 59 Guijo Road, Martin Homes Phase 4, Labason, 6031 Leyte', 0),
(41, 'Brooke', 'Janice', 'Jacobs', '', '1346-F Canopus Road, Santa, 6755 Cebu', 2147483647, 2147483647, 'jill01@mcuequities.com.ph', 'Female', 7, 'AB+', '2017-12-11', 'Islam', 'No', 'Filipino', 'John Norris', 'Pam Mcmillan', 'Jason Dennis', 'Relative', 2147483647, 2147483647, 'B12 L52 Celery Estates 7, Sunstone Street, Aguilar, 8739 Tawi-Tawi', 0),
(42, 'Jean', 'Michele', 'Gray', 'Sr.', '30th Floor Agoho Condominiums 4, 6574 Sunstone Street, Nueva Era, 5471 Catanduanes', 2147483647, 2147483647, 'estanley@zohomail.com', 'Female', 20, 'B+', '2004-06-13', 'Born Again', 'No', 'Filipino', 'Martin Farrell', 'Mary Robinson', 'Julie Dunn', 'Guardian', 2147483647, 2147483647, '6286 Gonzalez Street, Santiago, 4153 Occidental Mindoro', 0),
(43, 'Matthew', 'Alexandra', 'Gomez', '', '3299 Squash Street, Capricorn Homes, Tayum, 5651 Northern Samar', 2147483647, 2147483647, 'tdodson@gsebanking.org', 'Male', 10, 'A+', '2014-07-23', 'Islam', 'No', 'Filipino', 'Richard Brown', 'Andrea Macias', 'Marcus Russell', 'Parent', 2147483647, 2147483647, 'B21 L25 Banaba Cove Phase 8, Jade Road, Dumalinao, 5455 Apayao', 0),
(44, 'Bob', 'Andrea', 'Lewis', '', 'B15 L92 Johnson Estates 5, Caraballo Drive, President Manuel A. Roxas, 3419 Pampanga', 2147483647, 2147483647, 'danielle49@yahoo.com', 'Male', 9, 'O+', '2016-05-26', 'Roman Catholic', 'No', 'Filipino', 'Scott Marks', 'Danielle Casey', 'Edward Graham', 'Parent', 2147483647, 2147483647, 'B05 L76 Park Cove Phase 5, Cresta Boulevard, Tubay, 3651 Camarines Norte', 0),
(45, 'Caitlin', 'Jamie', 'Wolfe', '', 'B23 L15 Hydra Estates Phase 4, 16th Drive, Sual, 6897 Leyte', 2147483647, 2147483647, 'obrienandrew@psesolutions.ph', 'Female', 17, 'A-', '2007-10-02', 'Iglesia ni Cristo', 'No', 'Filipino', 'Thomas Parker', 'Amy Edwards', 'Katie Lang', 'Guardian', 2147483647, 2147483647, 'Block 23 Lot 40 Atis Cove 5, 53rd Drive, Baliangao, 4068 Masbate', 0),
(46, 'Tiffany', 'Timothy', 'Johnson', 'III', '18 Arayat Street, Guimbal, 9271 Agusan del Norte', 2147483647, 2147483647, 'mjennings@ssctechnologies.com.ph', 'Female', 6, 'AB+', '2018-07-17', 'Roman Catholic', 'No', 'Filipino', 'Joe Carey', 'Stacy Mitchell', 'Molly Hubbard', 'Sibling', 2147483647, 2147483647, '9346 Unit B 66th Avenue, Guimbal, 8552 Maguindanao', 0),
(47, 'Angela', 'Benjamin', 'Taylor', '', '3964 Unit F Lee Drive, Amlan, 2920 Camarines Sur', 2147483647, 2147483647, 'deborah30@sotosummit.net', 'Female', 9, 'B+', '2015-08-13', 'Born Again', 'No', 'Filipino', 'Louis Hall', 'Jessica Pugh', 'Dominic Ewing', 'Guardian', 2147483647, 2147483647, '8035 D Halcon Extension, Manila, 0927 Metro Manila', 0),
(48, 'Jimmy', 'Jamie', 'Williamson', 'III', 'Block 18 Lot 84 Dungon Street, Kamias Estates Phase 3, San Juan, 1820 Metro Manila', 2147483647, 2147483647, 'melissagonzalez@asgventures.net', 'Male', 24, 'O-', '2000-09-16', 'Born Again', 'Yes', 'Filipino', 'Eric Payne', 'Michelle Allen', 'Amy Adams', 'Guardian', 2147483647, 2147483647, '38F Jasmine Residences, 2122 Taurus Extension, Carcar, 2872 Bulacan', 0),
(49, 'Richard', 'Natalie', 'Reynolds', '', '9407 Jupiter Road, Tabuan-Lasa, 9771 Lanao del Sur', 2147483647, 2147483647, 'garnerashley@blz.com.ph', 'Male', 29, 'AB-', '1996-04-17', 'Iglesia ni Cristo', 'No', 'Filipino', 'John Carlson', 'Tracy Maldonado', 'Laura Day', 'Parent', 2147483647, 2147483647, 'Block 11 Lot 03 Myers Extension, Howard Village Phase 2, Pata, 5590 Ilocos Sur', 0),
(50, 'George', 'Jonathan', 'Rodgers', '', 'B21 L17 Gutierrez Estates 2, Kamagong Avenue, Arakan, 6543 Biliran', 2147483647, 2147483647, 'eroach@zohomail.com', 'Male', 8, 'O-', '2016-07-11', 'Born Again', 'Yes', 'Filipino', 'Miguel Beck', 'Brenda Richardson', 'Jonathan Swanson', 'Sibling', 2147483647, 2147483647, '4095 Agate Drive, Camaligan, 8288 Davao Occidental', 0),
(51, 'Matthew', 'Alexandra', 'Young', 'III', 'Block 09 Lot 98 30th Street, Hill Subdivision Phase 2, Sabangan, 6634 Negros Oriental', 2147483647, 2147483647, 'andersenbeth@qudevelopment.ph', 'Male', 18, 'AB-', '2007-01-30', 'Iglesia ni Cristo', 'Yes', 'Filipino', 'John Hood', 'Wendy Smith', 'Gregory Fisher', 'Guardian', 2147483647, 2147483647, '5802 Unit I 80th Street, Balagtas, 1962 Masbate', 0),
(52, 'Anita', 'Jeffrey', 'Ferrell', 'Jr.', 'Block 16 Lot 11 Smith Village, 70th Street, Banguingui, 6293 Samar', 2147483647, 2147483647, 'amanda37@nuventures.ph', 'Female', 24, 'A-', '2001-04-12', 'Iglesia ni Cristo', 'No', 'Filipino', 'Alexander Tucker', 'Deborah Smith', 'Jonathan Franklin', 'Guardian', 2147483647, 2147483647, '918-J Caraballo Avenue Extension, Kidapawan, 5225 Pampanga', 0),
(53, 'Karla', 'Marie', 'Osborne', '', '3434-B Garnet Drive, Lupao, 5704 Capiz', 2147483647, 2147483647, 'obernard@dscsolutions.com.ph', 'Female', 24, 'O+', '2001-05-24', 'Islam', 'Yes', 'Filipino', 'Stephen Walker', 'Sarah Ingram', 'Steven Myers', 'Relative', 2147483647, 2147483647, 'Block 12 Lot 06 Matumtum Avenue, Matthews Homes 8, Sibutu, 7181 Maguindanao', 0),
(54, 'Matthew', 'Lori', 'Brooks', 'III', 'Room 1234 Mayon Suites Tower 1, 6520 Mariveles Street, Cawayan, 2851 Albay', 2147483647, 2147483647, 'donna57@ugwn.org', 'Male', 25, 'B-', '2000-03-15', 'Baptist', 'Yes', 'Filipino', 'Kevin Singh', 'Melissa Morrison', 'Barry Mckinney', 'Sibling', 2147483647, 2147483647, '8732 Iriga Street, Maramag, 9162 Basilan', 0),
(55, 'Joseph', 'Nicole', 'Charles', 'III', 'Block 01 Lot 08 Turquoise Cove 1, 20th Avenue, Leon, 2780 Abra', 2147483647, 2147483647, 'chris88@zohomail.com', 'Male', 24, 'B-', '2001-06-04', 'Iglesia ni Cristo', 'Yes', 'Filipino', 'Jay Mitchell', 'Sarah Manning', 'Sean Glenn', 'Parent', 2147483647, 2147483647, 'B21 L36 Ruby Extension, Saturn Village Phase 1, Simunul, 2739 Ilocos Sur', 0),
(56, 'Chris', 'Ryan', 'Curry', 'Jr.', 'B01 L47 Coconut Avenue, Palm Estates 9, Pualas, 2747 Ilocos Norte', 2147483647, 2147483647, 'taylorhernandez@qzproperties.com', 'Male', 16, 'B+', '2008-06-24', 'Born Again', 'Yes', 'Filipino', 'Bruce Williams', 'Tammy Rangel', 'Zachary Tran', 'Sibling', 2147483647, 2147483647, 'Block 24 Lot 80 Lumbayao Estates 4, Hercules Road, Arteche, 6388 Northern Samar', 0),
(57, 'Mark', 'Justin', 'Scott', 'III', 'B13 L83 Clark Cove Phase 6, Topaz Street, Aurora, 1903 Ilocos Norte', 2147483647, 2147483647, 'kristine83@osgcapital.net', 'Male', 26, 'AB+', '1998-07-28', 'Islam', 'No', 'Filipino', 'Christopher Howell', 'Breanna Bowen', 'Brandi Hernandez', 'Parent', 2147483647, 2147483647, '9283 Underwood Drive, Sara, 3875 Occidental Mindoro', 0),
(58, 'Crystal', 'Angie', 'Haley', 'III', 'B02 L13 Pinatubo Service Road, Moore Estates, Tubajon, 1946 Cavite', 2147483647, 2147483647, 'robin83@sgx.net', 'Female', 19, 'B+', '2005-06-18', 'Roman Catholic', 'Yes', 'Filipino', 'Benjamin Wood', 'Samantha Bennett', 'Michael Lewis', 'Guardian', 2147483647, 2147483647, 'Block 06 Lot 97 Shaw Grove 2, Martin Service Road, Palompon, 8875 Agusan del Norte', 0),
(59, 'Frank', 'Anthony', 'Lee', '', 'B04 L64 Talisay Grove 4, Moonstone Street, Marikina, 1610 Metro Manila', 2147483647, 2147483647, 'murphynathan@holdergenesis.com', 'Male', 5, 'A-', '2020-03-15', 'Baptist', 'No', 'Filipino', 'Brian Elliott', 'Tammy Hayes', 'Clifford Gibson', 'Sibling', 2147483647, 2147483647, 'Block 07 Lot 47 Asparagus Boulevard, Munoz Subdivision Phase 1, Nagtipunan, 4455 Pangasinan', 0),
(60, 'Michael', 'Lisa', 'Mckee', 'III', 'Block 21 Lot 81 Sicaba Grove, Moonstone Extension, Lambunao, 8015 Davao del Norte', 2147483647, 2147483647, 'wrightnicole@connercrown.net.ph', 'Male', 16, 'AB+', '2008-10-08', 'Baptist', 'No', 'Filipino', 'Robert Fleming', 'Donna Stokes', 'Justin Lopez', 'Parent', 2147483647, 2147483647, '529G Mayon Drive, Makati, 1211 Metro Manila', 0),
(61, 'Sarah', 'Benjamin', 'Dalton', 'Jr.', '10F Smith Condominiums 2, 9812 Zodiac Street, Victorias, 6089 Southern Leyte', 2147483647, 2147483647, 'pherrera@zohomail.com', 'Female', 7, 'AB-', '2018-01-08', 'Baptist', 'No', 'Filipino', 'Allen Rodriguez', 'Michelle Beck', 'Jeremy Sanders', 'Parent', 2147483647, 2147483647, '5712 Terrell Road, Gumamela Subdivision 7, Lutayan, 8384 Davao del Sur', 0),
(62, 'Steven', 'Misty', 'Soto', '', 'Block 13 Lot 23 Garnet Road, Leo Grove 3, Medellin, 8387 Zamboanga del Norte', 2147483647, 2147483647, 'bgarcia@zohomail.com', 'Male', 23, 'O+', '2001-07-05', 'Iglesia ni Cristo', 'Yes', 'Filipino', 'Eddie Campos', 'Carrie Haynes', 'Benjamin Hernandez', 'Parent', 2147483647, 2147483647, 'Block 05 Lot 06 Norman Grove Phase 1, 71st Street, Jomalig, 4523 Aurora', 0),
(63, 'Kristen', 'Linda', 'Ryan', 'III', '1317 Gloriosa Road, Jupiter Cove Phase 4, Aloran, 2846 Masbate', 2147483647, 2147483647, 'renee67@skproperties.org.ph', 'Female', 5, 'AB+', '2020-04-05', 'Baptist', 'No', 'Filipino', 'Matthew Booker', 'Lindsey Graves', 'Stephanie Lopez', 'Parent', 2147483647, 2147483647, '19F Duffy Apartment, 5801 71st Road, Hinunangan, 2812 Kalinga', 0),
(64, 'Keith', 'Michael', 'Powers', '', '2164 Anderson Street, Ruby Homes Phase 5, Licuan-Baay, 2511 Abra', 2147483647, 2147483647, 'slamb@zohomail.com', 'Male', 7, 'O+', '2018-02-24', 'Baptist', 'No', 'Filipino', 'Cody Bowers', 'Brenda Jimenez', 'Kenneth Hernandez', 'Parent', 2147483647, 2147483647, '6064 Dapdap Street, Tagbina, 8333 Tawi-Tawi', 0),
(65, 'Lisa', 'Chad', 'Watson', 'Jr.', '9131 F Bautista Drive Extension, Kidapawan, 2116 Masbate', 2147483647, 2147483647, 'coffeyjocelyn@gmail.com', 'Female', 12, 'B+', '2013-01-04', 'Roman Catholic', 'No', 'Filipino', 'Daniel Kelley', 'Alicia Harvey', 'Clayton Welch', 'Guardian', 2147483647, 2147483647, 'B20 L27 Sicaba Subdivision Phase 3, Lapis Lazuli Drive, Taguig, 1043 Metro Manila', 0),
(66, 'Gene', 'James', 'Bradley', 'Jr.', '3108 Pepper Street, Taylor Homes Phase 2, Malabon, 0557 Metro Manila', 2147483647, 2147483647, 'catherine22@yahoo.com', 'Male', 21, 'B-', '2004-04-06', 'Baptist', 'Yes', 'Filipino', 'David Bishop', 'Kayla Perkins', 'Richard Miranda', 'Relative', 2147483647, 2147483647, '4F Hernandez Apartments, 5908 Hercules Street, Quezon City, 0944 Metro Manila', 0),
(67, 'Alexander', 'Thomas', 'Douglas', 'Jr.', 'Block 17 Lot 89 Kaimito Avenue, Piña Homes, Siniloan, 3661 Isabela', 2147483647, 2147483647, 'aaron28@amlventures.ph', 'Male', 13, 'B+', '2011-10-07', 'Islam', 'No', 'Filipino', 'Robert Medina', 'Carrie Lopez', 'Cynthia Dunn', 'Relative', 2147483647, 2147483647, 'B15 L06 Davenport Homes 6, Jade Extension, Impasugong, 7287 Lanao del Sur', 0),
(68, 'Robert', 'James', 'Castaneda', 'III', 'Unit 5629 Citrine Place, 167 Galaxy Extension, Leon, 3810 Rizal', 2147483647, 2147483647, 'christine40@gmail.com', 'Male', 13, 'O-', '2012-05-02', 'Born Again', 'Yes', 'Filipino', 'Samuel Adams', 'Victoria Thomas', 'Kathy Kennedy', 'Sibling', 2147483647, 2147483647, '2099 Kanlaon Avenue, Gill Estates Phase 7, Taft, 7187 Basilan', 0),
(69, 'Christine', 'Steven', 'Ortega', '', '8590E Orion Extension, Pateros, 0754 Metro Manila', 2147483647, 2147483647, 'bdavis@mlcompany.ph', 'Female', 23, 'A-', '2002-02-12', 'Baptist', 'Yes', 'Filipino', 'Justin Mcconnell', 'Stephanie Joseph', 'James Brown', 'Relative', 2147483647, 2147483647, '6739 16th Extension, Lewis Estates Phase 3, Mabitac, 5706 Eastern Samar', 0),
(70, 'Henry', 'Steven', 'Baker', 'III', '759I Turquoise Road, Bayabas, 1914 Occidental Mindoro', 2147483647, 2147483647, 'richardcortez@jwg.com', 'Male', 23, 'B+', '2001-07-01', 'Baptist', 'No', 'Filipino', 'Matthew Smith', 'Erika Morales', 'Neil Jones', 'Sibling', 2147483647, 2147483647, 'Room 409 Miles Residences, 581 Clark Highway, Pagayawan, 6853 Bohol', 0),
(71, 'Pamela', 'Christina', 'Curtis', '', '9986 Halcon Street, Bernard Subdivision, Unisan, 2227 Camarines Sur', 2147483647, 2147483647, 'qmills@awkholdings.org.ph', 'Female', 20, 'AB-', '2004-06-09', 'Iglesia ni Cristo', 'No', 'Filipino', 'Robert Diaz', 'Kelli Jones', 'Edwin Bass', 'Parent', 2147483647, 2147483647, 'Unit 4918 Turquoise Place Tower 5, 4933 Pham Road, Villaviciosa, 6562 Eastern Samar', 0),
(72, 'Elizabeth', 'Kara', 'Maxwell', '', 'Unit 3322 Balete Building, 6177 Anahaw Road Extension, San Jorge, 3869 Abra', 2147483647, 2147483647, 'mariohamilton@weo.com.ph', 'Female', 16, 'A-', '2008-11-03', 'Roman Catholic', 'No', 'Filipino', 'Bradley Trevino', 'Gabrielle Espinoza', 'Debra Huff', 'Parent', 2147483647, 2147483647, 'B14 L26 Talisay Estates, Martinez Street, Valenzuela, 0737 Metro Manila', 0),
(73, 'Nicole', 'George', 'Nelson', '', '9701 Santan Avenue, San Julian, 8626 Sulu', 2147483647, 2147483647, 'fmitchell@evanscity.org.ph', 'Female', 13, 'AB+', '2011-12-01', 'Baptist', 'No', 'Filipino', 'Paul Brown', 'Kaitlyn Arellano', 'Kiara Henry', 'Sibling', 2147483647, 2147483647, '9th Floor Asparagus Suites, 2200 Adams Street, South Ubian, 3507 Batangas', 0),
(74, 'Kimberly', 'Vanessa', 'Aguilar', 'III', 'Room 421 Joseph Residences Tower 6, 1677 Mars Street, Dingalan, 8700 Misamis Oriental', 2147483647, 2147483647, 'omejia@filipinomorning.net', 'Female', 9, 'B+', '2016-02-24', 'Born Again', 'Yes', 'Filipino', 'Anthony Jimenez', 'Amy Campos', 'Deborah Bush', 'Parent', 2147483647, 2147483647, '3604 Banahaw Avenue, Garner Estates Phase 3, Guinsiliban, 3207 Oriental Mindoro', 0),
(75, 'Amy', 'Wendy', 'Hansen', 'Jr.', '16th Floor Amber Residences 1, 9806 Topaz Street, Catanauan, 6677 Leyte', 2147483647, 2147483647, 'jamesstewart@gmail.com', 'Female', 28, 'O-', '1996-10-25', 'Baptist', 'Yes', 'Filipino', 'Ronald Lee', 'Elizabeth Gutierrez', 'Isaac Mcdonald', 'Parent', 2147483647, 2147483647, '3F Simpson Residences Tower 4, 9707 14th Boulevard, Iligan, 4499 Aurora', 0),
(76, 'Sandra', 'Gregory', 'Dillon', '', 'B06 L05 Pepper Village 3, Jade Boulevard, Tampilisan, 6444 Iloilo', 2147483647, 2147483647, 'laneangela@dzwz.net.ph', 'Female', 7, 'B-', '2018-05-15', 'Islam', 'Yes', 'Filipino', 'Chad Schmidt', 'Susan Alvarado', 'Andrea Ruiz', 'Relative', 2147483647, 2147483647, '1316 Neptune Avenue Extension, Flores Subdivision Phase 8, Pagbilao, 4718 Marinduque', 0),
(77, 'Connor', 'Zachary', 'Ryan', '', '9970 Amber Street, Mariveles Estates, Patnongon, 3934 Cagayan', 2147483647, 2147483647, 'hansenkaren@rgfoods.com', 'Male', 20, 'AB-', '2005-03-01', 'Iglesia ni Cristo', 'No', 'Filipino', 'Kenneth Roberts', 'Karen Johnson', 'Jacob Smith', 'Relative', 2147483647, 2147483647, '115 Gumamela Drive, Mushroom Estates Phase 2, Isabel, 8010 Misamis Occidental', 0),
(78, 'Andrea', 'Chelsey', 'Mcfarland', 'III', 'B15 L13 Jacaranda Cove, Andromeda Avenue, Manila, 0429 Metro Manila', 2147483647, 2147483647, 'zgeorge@lac.net', 'Female', 10, 'B+', '2015-03-30', 'Born Again', 'No', 'Filipino', 'Travis Cook', 'Shannon Wilson', 'Amber Rodriguez', 'Relative', 2147483647, 2147483647, '5583 Santol Avenue, Gladiola Estates, Tugaya, 4702 Ifugao', 0),
(79, 'Laura', 'Melissa', 'Mayo', '', 'Block 09 Lot 63 Iriga Road, Scott Subdivision, Paracelis, 3139 Aurora', 2147483647, 2147483647, 'kjenkins@bgmcapital.com.ph', 'Female', 18, 'O-', '2006-07-06', 'Born Again', 'No', 'Filipino', 'Michael Harper', 'Alyssa Garcia', 'Ashley Carlson', 'Relative', 2147483647, 2147483647, '245 J 37th Drive, Garchitorena, 4804 Abra', 0),
(80, 'Christopher', 'Christopher', 'Johnson', '', 'Unit 1118 Earth Residences, 1092 Barker Drive Extension, Boac, 7440 South Cotabato', 2147483647, 2147483647, 'carriebowman@wadegold.org', 'Male', 21, 'AB+', '2003-10-30', 'Iglesia ni Cristo', 'No', 'Filipino', 'Scott Browning', 'Lindsey Calderon', 'Jennifer Stewart', 'Relative', 2147483647, 2147483647, '7824 Camachile Avenue Extension, Perez Subdivision, Dasmariñas, 7044 Misamis Oriental', 1),
(81, 'Natalie', 'Raymond', 'Mcgee', '', 'Unit 717 Uranus Condominiums Tower 7, 8516 Tindalo Road, Banga, 4148 Catanduanes', 2147483647, 2147483647, 'sanderson@rumgroup.com', 'Female', 6, 'O+', '2018-10-23', 'Born Again', 'Yes', 'Filipino', 'Matthew Murray', 'Patricia Rodriguez', 'Gwendolyn Beck', 'Parent', 2147483647, 2147483647, '807 Dita Street, Milky Way Village Phase 5, Cabucgayan, 8742 Basilan', 1),
(82, 'James', 'Amy', 'Marshall', 'III', 'B04 L85 Apo Road, Orion Estates 3, Languyan, 5699 Eastern Samar', 2147483647, 2147483647, 'colemanmichael@yahoo.com', 'Male', 27, 'O-', '1997-11-13', 'Roman Catholic', 'No', 'Filipino', 'Wayne Stevens', 'Terry Rodriguez', 'Michael Weiss', 'Guardian', 2147483647, 2147483647, '6189 I Mariveles Street, Calape, 3282 Ilocos Norte', 1),
(83, 'Alexandra', 'Aaron', 'Mitchell', 'III', '2410 Pinatubo Street, Milflower Grove Phase 4, Ilog, 5045 Aklan', 2147483647, 2147483647, 'duane69@farsun.org.ph', 'Female', 29, 'O+', '1996-05-13', 'Roman Catholic', 'No', 'Filipino', 'Mario Duncan', 'Lisa Chaney', 'Alison Ward', 'Sibling', 2147483647, 2147483647, '20th Floor Kanlaon Tower, 641 68th Drive Extension, Parañaque, 1483 Metro Manila', 1),
(84, 'John', 'Sarah', 'Jones', 'Sr.', 'B21 L80 Granada Avenue, Jones Estates Phase 8, Kabasalan, 3286 Laguna', 2147483647, 2147483647, 'robert93@hlmmanufacturing.com', 'Male', 25, 'B-', '2000-05-03', 'Islam', 'No', 'Filipino', 'Gregory Jones', 'Katherine Hernandez', 'David Lewis', 'Parent', 2147483647, 2147483647, '624 Hall Road, Tigaon, 8139 Zamboanga del Norte', 1),
(85, 'Robert', 'Carrie', 'Wright', 'Jr.', 'B02 L45 Martin Village, Brown Street, Bayawan, 5314 Marinduque', 2147483647, 2147483647, 'carlyjacobs@pscbanking.ph', 'Male', 22, 'A+', '2003-02-19', 'Baptist', 'Yes', 'Filipino', 'Jose West', 'Jackie Hoffman', 'Angela Roberts', 'Guardian', 2147483647, 2147483647, 'Room 3824 Banyan Suites 9, 2359 Bulusan Boulevard, Caluya, 4784 Mountain Province', 1),
(86, 'Douglas', 'Peter', 'Foster', 'III', '2220 Emerald Avenue, Sampaguita Subdivision 8, Maddela, 6898 Capiz', 2147483647, 2147483647, 'kerryramos@westerngenesis.org.ph', 'Male', 14, 'AB+', '2010-07-27', 'Islam', 'Yes', 'Filipino', 'Christopher Schmidt', 'Tracy Lucas', 'Eric Johnston', 'Guardian', 2147483647, 2147483647, 'B21 L53 Matumtum Street, Carrot Grove Phase 7, Bato, 2993 Albay', 1),
(87, 'Matthew', 'Justin', 'Hudson', 'III', '1892 Unit B Uranus Street, Del Carmen, 7352 Agusan del Norte', 2147483647, 2147483647, 'craigamber@salasunion.net', 'Male', 25, 'AB+', '1999-09-10', 'Islam', 'Yes', 'Filipino', 'Geoffrey George', 'Stephanie Cooper', 'James Proctor', 'Sibling', 2147483647, 2147483647, 'B01 L92 Barnett Village Phase 8, Jones Avenue, Bangar, 4119 Isabela', 1),
(88, 'Kaitlyn', 'Chad', 'Peters', 'III', '8007 Tabayoc Avenue, San Pablo, 9723 Sulu', 2147483647, 2147483647, 'andrew11@gckhotel.com.ph', 'Female', 13, 'AB+', '2011-11-10', 'Born Again', 'Yes', 'Filipino', 'Daniel Martin', 'Katherine Robinson', 'Amber Sanchez', 'Sibling', 2147483647, 2147483647, '4499 H Martinez Street, Banga, 2138 Laguna', 1),
(89, 'Jessica', 'Richard', 'Phelps', 'III', '31st Floor Peridot Suites, 9848 Ruby Street, Kapai, 6031 Cebu', 2147483647, 2147483647, 'carolyn03@rdsg.net.ph', 'Female', 8, 'A-', '2017-04-10', 'Born Again', 'No', 'Filipino', 'Charles Martin', 'Sharon Taylor', 'Jerry Dickerson', 'Relative', 2147483647, 2147483647, 'Block 25 Lot 35 Halcon Avenue, Robertson Grove Phase 6, Saguday, 5157 Occidental Mindoro', 1),
(90, 'Jeffrey', 'John', 'George', 'III', '9479 Tabayoc Road, Ruby Village 7, Iligan, 8611 Lanao del Norte', 2147483647, 2147483647, 'tristan90@vnmanufacturing.org.ph', 'Male', 19, 'A+', '2006-04-21', 'Islam', 'No', 'Filipino', 'Wesley Holland', 'Jennifer Tucker', 'Jane Harrison', 'Sibling', 2147483647, 2147483647, 'Room 2935 Griffin Apartment, 4121 Galaxy Drive, Floridablanca, 5476 Pangasinan', 1),
(91, 'David', 'Diana', 'Moore', '', '8910-A Planet Extension, Linamon, 8139 Cotabato', 2147483647, 2147483647, 'burtondarrell@rfbq.net.ph', 'Male', 28, 'O-', '1996-09-30', 'Baptist', 'No', 'Filipino', 'Jordan Mendoza', 'Stephanie Jones', 'Clifford Simpson', 'Relative', 2147483647, 2147483647, 'Block 07 Lot 74 Macopa Street, Polaris Homes 4, Lakewood, 8454 Sulu', 1),
(92, 'Rebecca', 'Jennifer', 'Joseph', '', 'Room 1229 Mayapis Building Tower 5, 8271 Sicaba Street, Sigay, 9739 Sultan Kudarat', 2147483647, 2147483647, 'lawrence02@lptechnologies.com.ph', 'Female', 15, 'B-', '2010-01-22', 'Iglesia ni Cristo', 'No', 'Filipino', 'Jacob Campbell', 'Renee Henry', 'Danielle Watson', 'Guardian', 2147483647, 2147483647, '6782 F Jasper Road, Hadji Muhtamad, 5613 Biliran', 1),
(93, 'Cody', 'Michael', 'Kennedy', '', 'B24 L06 Agate Drive, Mushroom Village Phase 2, Valenzuela, 0934 Metro Manila', 2147483647, 2147483647, 'pennyhart@hkmbank.ph', 'Male', 20, 'AB-', '2004-10-10', 'Baptist', 'Yes', 'Filipino', 'David Johnson', 'Cristina Ramos', 'Amanda Ramos', 'Guardian', 2147483647, 2147483647, 'Block 04 Lot 76 Coleman Grove, Jade Avenue, Guinobatan, 2417 Batanes', 1),
(94, 'Joyce', 'Abigail', 'Green', 'Jr.', 'Block 25 Lot 25 69th Road Extension, Arayat Estates Phase 8, Indang, 3518 Rizal', 2147483647, 2147483647, 'lisa04@nuccompany.net.ph', 'Female', 17, 'B-', '2008-01-08', 'Iglesia ni Cristo', 'Yes', 'Filipino', 'Michael Ayala', 'Brianna Gibson', 'Brandon Baker', 'Relative', 2147483647, 2147483647, '9102 33rd Road, Bakun, 2146 Palawan', 1),
(95, 'Megan', 'David', 'Good', 'III', '3366-E 20th Avenue, Pantukan, 2511 La Union', 2147483647, 2147483647, 'mccarthyfrank@megacity.net.ph', 'Female', 19, 'AB+', '2006-01-14', 'Baptist', 'No', 'Filipino', 'Alexander Myers', 'Kim Robinson', 'Kaitlyn Leonard', 'Sibling', 2147483647, 2147483647, '2954 Arayat Street, Oliva Homes Phase 7, Lutayan, 4167 Tarlac', 1),
(96, 'Bradley', 'Raymond', 'Garcia', '', 'B12 L44 Topaz Cove, Dita Road, Manila, 0997 Metro Manila', 2147483647, 2147483647, 'xpaul@yahoo.com', 'Male', 19, 'AB-', '2005-09-07', 'Roman Catholic', 'Yes', 'Filipino', 'Bryan Mcneil', 'Amy Palmer', 'Stephanie Hall', 'Sibling', 2147483647, 2147483647, '8133 Warren Road, Perez, 8492 Compostela Valley', 1),
(97, 'Robert', 'Sandra', 'Goodman', 'III', '1591 Unit G 74th Street, Malabon, 1283 Metro Manila', 2147483647, 2147483647, 'dennisbates@perezsun.net.ph', 'Male', 22, 'A+', '2003-06-03', 'Born Again', 'No', 'Filipino', 'Bruce Mccann', 'Lindsey Kennedy', 'Clinton Rodriguez', 'Parent', 2147483647, 2147483647, 'B19 L81 Iriga Street, Anderson Estates, Diplahan, 4640 Occidental Mindoro', 1),
(98, 'Tracy', 'Sean', 'Brown', 'III', '4252 Ipil Road, San Juan, 1288 Metro Manila', 2147483647, 2147483647, 'halljoshua@sscventures.org.ph', 'Male', 16, 'A+', '2009-02-25', 'Baptist', 'Yes', 'Filipino', 'Ronald Dean', 'Mary Reilly', 'Sharon White', 'Parent', 2147483647, 2147483647, '3044 Unit H Lopez Drive, Panitan, 2016 Zambales', 1),
(99, 'Heather', 'Andrew', 'Lewis', 'Jr.', 'B07 L69 Juno Drive Extension, Chico Subdivision Phase 1, Kolambugan, 5552 Bataan', 2147483647, 2147483647, 'shawmichael@quadmillennium.org', 'Female', 12, 'A-', '2012-08-18', 'Baptist', 'No', 'Filipino', 'Nicholas Williams', 'Mary Lopez', 'Justin Key', 'Relative', 2147483647, 2147483647, '3500 6th Street, Melendez Homes Phase 7, Samal, 6893 Eastern Samar', 1),
(100, 'Kyle', 'Alexander', 'Bell', '', '3860I Sagittarius Drive, Matanao, 3085 Apayao', 2147483647, 2147483647, 'madisongreen@mugservices.ph', 'Male', 18, 'B+', '2007-02-15', 'Islam', 'Yes', 'Filipino', 'Tyler Johnson', 'Laura Arnold', 'Zachary Mercer', 'Sibling', 2147483647, 2147483647, 'Block 23 Lot 73 Azucena Homes, Acacia Service Road, Sinacaban, 7461 South Cotabato', 1),
(102, 'Bea Aexa', 'Delos Santos', 'Bausas', '', 'sa tabi tabi', 2147483647, 0, 'bea@gmail.com', 'female', 22, '', '2003-05-01', '', 'yes', 'Filipino', 'Rolando Bausas', 'Noemi Bausas', 'Rolando Bausas', 'Father', 2147483647, 0, 'sa tabi tabi', 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `accepted_members`
--
ALTER TABLE `accepted_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `assistance_req`
--
ALTER TABLE `assistance_req`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `completed_doc_requests`
--
ALTER TABLE `completed_doc_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `docreq_queue`
--
ALTER TABLE `docreq_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `skmembers_queue`
--
ALTER TABLE `skmembers_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
