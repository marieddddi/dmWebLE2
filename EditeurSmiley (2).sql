-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 22, 2023 at 03:10 PM
-- Server version: 10.3.38-MariaDB-0+deb10u1
-- PHP Version: 7.3.31-1~deb10u3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `EditeurSmiley`
--

-- --------------------------------------------------------

--
-- Table structure for table `palette`
--

CREATE TABLE `palette` (
  `id` int(12) NOT NULL,
  `couleur` varchar(122) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `palette`
--

INSERT INTO `palette` (`id`, `couleur`) VALUES
(2, '#FF0000,#00FF00,#0000FF,#FFFF00,#FF00FF,#00FFFF');

-- --------------------------------------------------------

--
-- Table structure for table `smiley`
--

CREATE TABLE `smiley` (
  `id` int(12) NOT NULL,
  `chaine` varchar(122) NOT NULL,
  `id_user` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `smiley`
--

INSERT INTO `smiley` (`id`, `chaine`, `id_user`) VALUES
(1, '2,#FF000,#FFFFF,#00FFF,#ABCDE', 2);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(12) NOT NULL,
  `nom` varchar(122) NOT NULL,
  `prenom` varchar(122) NOT NULL,
  `pseudo` varchar(122) NOT NULL,
  `mdp` varchar(122) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `pseudo`, `mdp`) VALUES
(2, 'DUPONT', 'Jean', 'jdupont', '1234'),
(3, 'aa', 'aa', 'aa', 'aa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `palette`
--
ALTER TABLE `palette`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `couleur` (`couleur`);

--
-- Indexes for table `smiley`
--
ALTER TABLE `smiley`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `smiley`
--
ALTER TABLE `smiley`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `palette`
--
ALTER TABLE `palette`
  ADD CONSTRAINT `palette_ibfk_1` FOREIGN KEY (`id`) REFERENCES `utilisateur` (`id`);

--
-- Constraints for table `smiley`
--
ALTER TABLE `smiley`
  ADD CONSTRAINT `smiley_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
