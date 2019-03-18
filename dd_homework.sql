-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2019 m. Kov 17 d. 13:23
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dd_homework`
--

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL,
  `fk_userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sukurta duomenų kopija lentelei `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `date`, `fk_userId`) VALUES
(5, 'Run with doggies', 'Run with good boys for charity purpose', '2019-03-28', 1),
(9, 'What is Lorem Ipsum?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2019-03-17', 1),
(10, 'This is event title', 'This is an event description', '2019-03-20', 2),
(11, 'Paws on the run', 'Paws on the run event description here', '2019-03-31', 3);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Slaptažodis atitinka vardą iki ''@'' simbolio'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sukurta duomenų kopija lentelei `users`
--

INSERT INTO `users` (`id`, `email`, `password`) VALUES
(1, 'antanas@e.mail', '$2y$10$7pfJZfCXkbvml6IncCLgRenrLzlgU.O2LMmdu/eDQ2lCmNzAXqPd6'),
(2, 'klikas@e.mail', '$2y$10$IzMIavmxa2CKMCSg6a4PQ.WJX1OasfFKftBUmWatmAN03uexXykKK'),
(3, 'mikas@e.mail', '$2y$10$/dnwoKkQE76CMWu71lzNa.vw/.WZ18grNwOeGofU179EyznYlmuMG'),
(4, 'tomas@e.mail', '$2y$10$qVy7Yv6I8PjqdKbX.hXFgeCwpHqQrfRkfuR0aUcB2J7qMCph45zha'),
(5, 'renas@e.mail', '$2y$10$nWAD0QJyAOtVGPqL9onn8uR2boFcV64KE.iMy3FwlDQcRIVxek2h.'),
(6, 'ridas@e.mail', '$2y$10$/xuYhd8pJzhJBN3iEsKQGO8kJKw1WBlLEgBTC7uSxElC7.3SbmNa6');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created` (`fk_userId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- Apribojimai eksportuotom lentelėm
--

--
-- Apribojimai lentelei `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `created` FOREIGN KEY (`fk_userId`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
