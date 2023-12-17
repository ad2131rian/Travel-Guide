-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2023 at 09:32 PM
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
-- Database: `travel_guide`
--

-- --------------------------------------------------------

--
-- Table structure for table `attractions`
--

CREATE TABLE `attractions` (
  `id` int(11) NOT NULL,
  `likes` int(11) NOT NULL DEFAULT 0,
  `name` text NOT NULL,
  `description` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `category` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attractions`
--

INSERT INTO `attractions` (`id`, `likes`, `name`, `description`, `image_path`, `category`) VALUES
(1, 6, 'Malta', 'The Maltese archipelago lies virtually at the centre of the Mediterranean, 93 km south of Sicily and 288 km north of Africa.', 'Malta.jpg', 'Nature'),
(2, 6, 'Tunisia', 'Tunisia is a country in Northern Africa bordering the Mediterranean Sea. Neighboring countries include Algeria and Libya. The geography of Tunisia is varied and consists of mountains in the north and a semiarid south that merges into the Sahara.', 'Tunisia.webp', 'Nature'),
(3, 3, 'Los Angeles', 'Los Angeles is the city with the second biggest population in the United States after New York, overtaking Chicago in the 1970s.', 'LA.jpg', 'City'),
(4, 0, 'Boston', 'Boston (US: /ˈbɔːstən/[8]), officially the City of Boston, is the capital and most populous city in the Commonwealth of Massachusetts, and is the cultural & financial center of New England in the Northeastern United States, with an area of 48.4 sq mi (125', 'Boston.jpg', 'City'),
(5, 18, 'Berlin', 'Berlin (/bɜːrˈlɪn/ bur-LIN, German: [bɛʁˈliːn] ⓘ) is the capital and largest city of Germany by both area and population.[8] Its more than 3.85 million inhabitants[9] make it the European Union\'s most populous city, according to population within city lim', 'Berlin.jpg', 'City'),
(6, 50, 'Vienna Woods', 'The Vienna Woods[1] (German: Wienerwald, pronounced [ˈviːnɐˌvalt] ⓘ) are forested highlands that form the northeastern foothills of the Northern Limestone Alps in the states of Lower Austria and Vienna. The 45-kilometre-long (28 mi) and 20–30-kilometre-wi', 'Vienna Woods.jpg', 'Nature'),
(7, 27, 'Stonehenge', 'Stonehenge is a prehistoric monument on Salisbury Plain in Wiltshire, England, two miles (3 km) west of Amesbury. It consists of an outer ring of vertical sarsen standing stones, each around 13 feet (4.0 m) high, seven feet (2.1 m) wide, and weighing arou', 'Stonehenge.jpg', 'History'),
(8, 56, 'Taj Mahal', 'The Taj Mahal (/ˌtɑːdʒ məˈhɑːl, ˌtɑːʒ-/; lit. \'Crown of the Palace\')[4][5][6] is an ivory-white marble mausoleum on the right bank of the river Yamuna in Agra, Uttar Pradesh, India. It was commissioned in 1631 by the fifth Mughal emperor, Shah Jahan (r. 1', 'Taj Mahal.jpg', 'History'),
(9, 43, 'Florence', 'Florence (/ˈflɒrəns/ FLORR-ənss; Italian: Firenze [fiˈrɛntse] ⓘ)[a] is the capital city of the Italian region of Tuscany. It is also the most populated city in Tuscany, with 360,930 inhabitants in 2023, and 984,991 in its metropolitan area.[4]\r\n\r\n', 'Florence.jpg', 'Art'),
(10, 78, 'Athens', 'Athens (/ˈæθɪnz/ ATH-inz;[5] Greek: Αθήνα, romanized: Athína, pronounced [aˈθina] ⓘ; Ancient Greek: Ἀθῆναι, romanized: Athênai, pronounced [atʰɛ̂ːnai̯]) is a major coastal urban area in the Mediterranean, and it is both the capital and the largest city of', 'Athens.jpg', 'Art'),
(11, 75, 'Rome', 'Rome (Italian and Latin: Roma [ˈroːma] ⓘ) is the capital city of Italy. It is also the capital of the Lazio region, the centre of the Metropolitan City of Rome Capital, and a special comune named Comune di Roma Capitale. With 2,860,009 residents in 1,285 ', 'Rome.jpg', 'Art'),
(12, 32, 'Paris', 'Paris is the capital and most populous city of France. With an official estimated population of 2,102,650 residents as of 1 January 2023[2] in an area of more than 105 km2 (41 sq mi),[5] Paris is the fifth-most populated city in the European Union and the', 'Paris.jpg', 'Art');

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `user_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`user_id`, `id`, `name`, `description`) VALUES
(41, 5, 'LA', ''),
(41, 6, 'Tunisia', ''),
(41, 7, 'Malta', ''),
(41, 8, 'UK', ''),
(55, 13, 'America', 'The land of the free, can\'t wait to go there and see all the great places ');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `attraction_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `attraction_id`) VALUES
(15, 45, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstName` varchar(30) NOT NULL,
  `lastName` varchar(55) NOT NULL,
  `email` varchar(55) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `country` varchar(15) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `email`, `username`, `password`, `country`, `creation_date`) VALUES
(41, 'testt', 'test', 'test@gmail.com', 'testt', '$2y$10$mU3hhqr8HJvT1OOEiMgdYub1qi4jBHEW6RCtLnccYWa', 'USA', '2023-12-12 15:03:21'),
(42, 'Adrian', 'Mercieca', 'adada@gmail.com', 'demo', '$2y$10$XUxyTYHgj4yJpoMeJJm.PeW0y3aMFfh2Cu2JrItige7', 'USA', '2023-12-12 18:01:11'),
(43, 'Adrian', 'Mercieca', 'dadqaeqewewe@gmail.com', '22ee2e', '$2y$10$JXLkDbqymgzF8QqcbDSi6.HuyucFY9r7l7liMbIqKnk', 'USA', '2023-12-13 20:36:18'),
(44, 'Jesus', 'Christ', '6217836@gmail.com', 'sdadada', '$2y$10$yuv9Haanv4U92EnZS814SOCRX1S2p0m/aqAcEfzTYeV', 'USA', '2023-12-14 22:16:00'),
(45, 'Adrian', 'Mercieca', 'ad2131rian@gmail.com', 'J_Van_Gough666', '$2y$10$TunoMojSYP5BuAV23xvpcODfGRLImKqNZnF7H/PuSRM', 'USA', '2023-12-14 22:21:57'),
(46, 'Vicnet', 'Vicne', 'nadjnadjna@gmail.com', 'Vincent22', '$2y$10$1C5LU4LTM1zfhF8il05iteMi51PZDHK5q71iFKDP/74', 'USA', '2023-12-16 23:21:01'),
(47, 'Adrian', 'Mercieca', 'assasa@gmail.com', 'Vincenzo', '$2y$10$4Z4RRilj2/tWQPtsNygb5udjfHUo8SX6owfMDbttnSM', 'USA', '2023-12-16 23:55:16'),
(48, 'Adrian', 'Mercieca', 'a1ssasa@gmail.com', 'Vincenzo1', '$2y$10$hTu1HhDAAol3xEaCxF1EyuJ8t30kxTJcQGk9cfRbTzq', 'USA', '2023-12-17 00:05:15'),
(49, 'Adrian', 'Mercieca', 'ad23112131rian@gmail.com', 'Hesus', '2990db4545e121a56646a3622b7a0a16', 'USA', '2023-12-17 00:36:21'),
(50, 'Adrian', 'Mercieca', 'ad2131wwewrian@gmail.com', 'Hesus1', '$2y$10$ejtDxgEMmlKmuGfUrEZICu8PVB6hldMPIlyGI8Nj7Tw', 'USA', '2023-12-17 01:01:25'),
(51, 'Adrian', 'Mercieca', 'ad2131adadrian@gmail.com', 'Vincent23', '$2y$10$g25dFrp4FEZAHgiv17T.Fuw/1zN4p6P2dPncD7zMfPJ', 'USA', '2023-12-17 01:12:53'),
(52, 'Adrian', 'Mercieca', 'ad2112131rian@gmail.com', 'Vincent27', '$2y$10$Soge0oIdMlCw3AYywk54beu3hH6.MORHYErtGqI0fu8', 'USA', '2023-12-17 01:22:08'),
(53, 'Adrian', 'Mercieca', 'adadada@gmail.com', 'Michael', '7f930db68a8cea9320181f5a2543fbb9', 'USA', '2023-12-17 12:55:59'),
(54, 'Adrian', 'Mercieca', 'dadada@ymail.com', 'Christian', '48080de72bc8d230ee80c83e9624b122', 'USA', '2023-12-17 13:28:55'),
(55, 'Steve', 'Wilkinson', '2536467@gmail.com', 'Ray_m', '727048ad41265e44875c07d0cd07a16a', 'Germany', '2023-12-17 20:14:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attractions`
--
ALTER TABLE `attractions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `attraction_id` (`attraction_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attractions`
--
ALTER TABLE `attractions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `destinations`
--
ALTER TABLE `destinations`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`attraction_id`) REFERENCES `attractions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
