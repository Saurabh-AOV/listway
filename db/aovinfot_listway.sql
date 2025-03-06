-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2025 at 01:47 PM
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
-- Database: `aovinfot_listway`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$Z.WU1LOia7bDCNny9F.o5uFtOKSINYlT7P2Wibkz/FwRq3zmLzjSu', '2024-08-06 01:25:35');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `city_village` varchar(255) NOT NULL,
  `education` varchar(255) NOT NULL,
  `occupation` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `user_id`, `name`, `city_village`, `education`, `occupation`, `phone`) VALUES
(2, 1, 'raj', 'delhi', '10', 'job', '8448113418'),
(6, 3, 'Kamlesh Gangwal', 'Ajmer ', '10th', ' Selling paytm Marketing ', '9057877323'),
(7, 3, 'Ramdev ji ', 'Tateya', '8th', 'Marketing ', '8769049753'),
(8, 3, 'Omprakash ji ', 'Deratu', '12th', 'Phone pe work ', '7878223954'),
(9, 3, 'Bana ', 'Gujrwada', '10th', 'Car driving', '6376131470'),
(10, 3, 'Gopal Varma ', 'Goteyana', '8th', 'Civil work', '9001296718'),
(12, 3, 'Radha ', 'Ajmer ', '10', 'Home work ', '1234564899'),
(13, 6, 'Sameer', 'Delhi', 'Bechalor\'s', 'Marketing', '917701954154'),
(14, 6, 'Saurabh', 'Delhi', 'Master\'s', 'CEO', '917217676540'),
(15, 6, 'Testing', 'Delhi', '12th', 'Sales', '919310593936'),
(16, 6, 'AOV Technical', 'Delhi', 'Null', 'Company', '918448113418'),
(17, 6, 'Mamta mam', 'Delhi', 'NA', 'Sales + Manager', '919871047523'),
(18, 8, 'Sameer Khan', 'Delhi', 'Digital Marketing', 'Job', '917701954154'),
(19, 8, 'Technical', 'Delhi', 'Testing', 'Extra', '918368319420');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `user_id`, `item`, `price`, `date`, `reason`) VALUES
(2, 1, 'abc', 100.00, '2024-08-06', 'test'),
(3, 3, 'Ggugt', 222.00, '2024-08-06', 'Wysyhko');

-- --------------------------------------------------------

--
-- Table structure for table `pdf`
--

CREATE TABLE `pdf` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `pdf_location` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pdf`
--

INSERT INTO `pdf` (`id`, `title`, `description`, `pdf_location`, `created_at`) VALUES
(3, 'Testing asdfghjkl', 'Testing anotyher one to upload the pdf qwertyuioasdfghjjxcvbnm,', 'Anantram Yadav_88 (2).pdf', '2025-03-06 12:11:40'),
(4, 'TEsting', '', 'Sumit  Chhipee_241 (1).pdf', '2025-03-06 12:30:06');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `user_id`, `contact_id`, `item_name`, `quantity`, `price`, `date`, `total_price`, `profit`) VALUES
(1, 1, 2, 'abc', 3, 200.00, '2024-06-11', 600.00, 150.00),
(2, 1, 2, 'abc2', 2, 100.00, '2024-11-12', 200.00, 0.00),
(3, 3, 6, 'Kamlesh ', 5, 500.00, '2024-08-06', 2500.00, 2000.00),
(4, 6, 13, 'Mobile', 4, 1000.00, '2025-03-14', 4000.00, 20.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `city_village` varchar(100) NOT NULL,
  `occupation` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `city_village`, `occupation`, `password`, `created_at`) VALUES
(1, 'rahul', '9310593936', 'Delhi', 'Job', '$2y$10$1P7cwQC2OxL3sniEEQrZd.NG2qeHNl3tUqpXgX.YjQn/TnHWwgBCy', '2024-07-24 19:53:13'),
(2, 'Sonu', '8585858585', 'Delhi', 'Job', '$2y$10$eht.234LdeK9fwaMwwuLG.IwE6WRor5UpQqvaYslM3oi.YAE.S1/u', '2024-07-29 12:39:04'),
(3, 'Kamlesh Gangwal', '9057877323', 'Kekri', '10', '$2y$10$/0YEpm1wgf7C6SHJJNfw7.4nVeE4N9vkOW0uMpeTPS8uQ4wYKLnP6', '2024-07-30 05:08:01'),
(4, 'Swati', '9315737093', 'Delhi', 'Urittirrewghd', '$2y$10$DGsLnJy7bg86aeGjSBKeIe9o5C7OQe0k6apHzdCqlxyiMxhtcWH2e', '2024-07-30 05:08:24'),
(5, 'Swati Kumari', '+19315737093', 'Delhi ', 'Dr grd', '$2y$10$gZp.2x7aeNxNFJy4FxEPU.nNBT.HSmHaxoP2zTL54Kj6XVzbKeG12', '2024-12-21 07:59:18'),
(6, 'Saurabh Kumar', '7217676541', 'Delhi', 'Backend Developer', '$2y$10$Jdy8YzuWtIqW1AcHvuDVp.XY9KaNM//eXC9VzQZjUPV.TOd/b0wle', '2025-03-01 10:56:04'),
(8, 'Saurabh Kumar', '7217676540', 'Delhi', 'Job', '$2y$10$vQtF/vbQciglUumfYcwVSusmsoP.e31NkGgPw1QYAxCBjxKfIk8ea', '2025-03-06 09:51:12');

-- --------------------------------------------------------

--
-- Table structure for table `youtube`
--

CREATE TABLE `youtube` (
  `id` int(11) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `youtube`
--

INSERT INTO `youtube` (`id`, `banner`, `title`, `description`, `video_url`, `created_at`) VALUES
(1, 'a22.jpg', 'Tutorial 1', 'best way of tutorial', 'https://youtu.be/RzF4NKsWnOg?si=elrAAwOGJndIKCV0', '2024-08-06 01:38:42'),
(2, 'about-3.jpg', 'tutorial 2', 'best tutorial of yt', 'https://www.youtube.com/watch?v=tBgNpc39FJk', '2024-08-06 01:43:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pdf`
--
ALTER TABLE `pdf`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `contact_id` (`contact_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `youtube`
--
ALTER TABLE `youtube`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pdf`
--
ALTER TABLE `pdf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `youtube`
--
ALTER TABLE `youtube`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
