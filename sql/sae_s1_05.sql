-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 09 nov. 2022 à 11:15
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sae_s1.05`
--

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `comm` varchar(1000) DEFAULT NULL,
  `note` int(11) NOT NULL,
  `email` varchar(256) NOT NULL,
  `idSerie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `commentaire`
--

INSERT INTO `commentaire` (`comm`, `note`, `email`, `idSerie`) VALUES
('Le plot n\'est pas si intéréssant mais l\'action y est forte et prenante', 3, 'user1@mail.com', 1);

-- --------------------------------------------------------

--
-- Structure de la table `encours`
--

CREATE TABLE `encours` (
  `email` varchar(256) NOT NULL,
  `idSerie` int(11) NOT NULL,
  `dernierEp` int(2) NOT NULL,
  `nbEpisode` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `encoursepisode`
--

CREATE TABLE `encoursepisode` (
  `idSerie` int(3) NOT NULL,
  `idEpisode` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `episode`
--

CREATE TABLE `episode` (
  `id` int(11) NOT NULL,
  `numero` int(11) NOT NULL DEFAULT 1,
  `titre` varchar(128) NOT NULL,
  `resume` text DEFAULT NULL,
  `duree` int(11) NOT NULL DEFAULT 0,
  `file` varchar(256) DEFAULT NULL,
  `serie_id` int(11) DEFAULT NULL,
  `image` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `episode`
--

INSERT INTO `episode` (`id`, `numero`, `titre`, `resume`, `duree`, `file`, `serie_id`, `image`) VALUES
(1, 1, 'Le lac', 'Le lac se révolte ', 8, 'lake.mp4', 1, ''),
(2, 2, 'Le lac : les mystères de l\'eau trouble', 'Un grand mystère, l\'eau du lac est trouble. Jack trouvera-t-il la solution ?', 8, 'lake.mp4', 1, ''),
(3, 3, 'Le lac : les mystères de l\'eau sale', 'Un grand mystère, l\'eau du lac est sale. Jack trouvera-t-il la solution ?', 8, 'lake.mp4', 1, ''),
(4, 3, 'Le lac : les mystères de l\'eau chaude', 'Un grand mystère, l\'eau du lac est chaude. Jack trouvera-t-il la solution ?', 8, 'lake.mp4', 1, ''),
(5, 3, 'Le lac : les mystères de l\'eau froide', 'Un grand mystère, l\'eau du lac est froide. Jack trouvera-t-il la solution ?', 8, 'lake.mp4', 1, ''),
(6, 1, 'Eau calme', 'L\'eau coule tranquillement au fil du temps.', 15, 'water.mp4', 2, ''),
(7, 2, 'Eau calme 2', 'Le temps a passé, l\'eau coule toujours tranquillement.', 15, 'water.mp4', 2, ''),
(8, 3, 'Eau moins calme', 'Le temps des tourments est pour bientôt, l\'eau s\'agite et le temps passe.', 15, 'water.mp4', 2, ''),
(9, 4, 'la tempête', 'C\'est la tempête, l\'eau est en pleine agitation. Le temps passe mais rien n\'y fait. Jack trouvera-t-il la solution ?', 15, 'water.mp4', 2, ''),
(10, 5, 'Le calme après la tempête', 'La tempête est passée, l\'eau retrouve son calme. Le temps passe et Jack part en vacances.', 15, 'water.mp4', 2, ''),
(11, 1, 'les chevaux s\'amusent', 'Les chevaux s\'amusent bien, ils ont apportés les raquettes pour faire un tournoi de badmington.', 7, 'horses.mp4', 3, ''),
(12, 2, 'les chevals fous', '- Oh regarde, des beaux chevals !!\r\n- non, des chevaux, des CHEVAUX !\r\n- oh, bin ça alors, ça ressemble drôlement à des chevals ?!!?', 7, 'horses.mp4', 3, ''),
(13, 3, 'les chevaux de l\'étoile noire', 'Les chevaux de l\'Etoile Noire débrquent sur terre et mangent toute l\'herbe !', 7, 'horses.mp4', 3, ''),
(14, 1, 'Tous à la plage', 'C\'est l\'été, tous à la plage pour profiter du soleil et de la mer.', 18, 'beach.mp4', 4, ''),
(15, 2, 'La plage le soir', 'A la plage le soir, il n\'y a personne, c\'est tout calme', 18, 'beach.mp4', 4, ''),
(16, 3, 'La plage le matin', 'A la plage le matin, il n\'y a personne non plus, c\'est tout calme et le jour se lève.', 18, 'beach.mp4', 4, ''),
(17, 1, 'champion de surf', 'Jack fait du surf le matin, le midi le soir, même la nuit. C\'est un pro.', 11, 'surf.mp4', 5, ''),
(18, 2, 'surf détective', 'Une planche de surf a été volée. Jack mène l\'enquête. Parviendra-t-il à confondre le brigand ?', 11, 'surf.mp4', 5, ''),
(19, 3, 'surf amitié', 'En fait la planche n\'avait pas été volée, c\'est Jim, le meilleur ami de Jack, qui lui avait fait une blague. Les deux amis partagent une menthe à l\'eau pour célébrer leur amitié sans faille.', 11, 'surf.mp4', 5, ''),
(20, 1, 'Ça roule, ça roule', 'Ça roule, ça roule toute la nuit. Jack fonce dans sa camionnette pour rejoindre le spot de surf.', 27, 'cars-by-night.mp4', 6, ''),
(21, 2, 'Ça roule, ça roule toujours', 'Ça roule la nuit, comme chaque nuit. Jim fonce avec son taxi, pour rejoindre Jack à la plage. De l\'eau a coulé sous les ponts. Le mystère du Lac trouve sa solution alors que les chevaux sont de retour après une virée sur l\'Etoile Noire.', 27, 'cars-by-night.mp4', 6, '');

-- --------------------------------------------------------

--
-- Structure de la table `estfini`
--

CREATE TABLE `estfini` (
  `email` varchar(256) NOT NULL,
  `idSerie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `estfini`
--

INSERT INTO `estfini` (`email`, `idSerie`) VALUES
('user1@mail.com', 1);

-- --------------------------------------------------------

--
-- Structure de la table `estvisionne`
--

CREATE TABLE `estvisionne` (
  `email` varchar(256) NOT NULL,
  `idVideo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `estvisionne`
--

INSERT INTO `estvisionne` (`email`, `idVideo`) VALUES
('user1@mail.com', 1),
('user1@mail.com', 2),
('user1@mail.com', 3),
('user1@mail.com', 4),
('user1@mail.com', 5),
('user2@mail.com', 6),
('user2@mail.com', 7),
('user2@mail.com', 8);

-- --------------------------------------------------------

--
-- Structure de la table `favori`
--

CREATE TABLE `favori` (
  `email` varchar(256) NOT NULL,
  `idSerie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `favori`
--

INSERT INTO `favori` (`email`, `idSerie`) VALUES
('user1@mail.com', 1),
('user1@mail.com', 2),
('user1@mail.com', 4),
('user2@mail.com', 3),
('user2@mail.com', 5),
('user3@mail.com', 3);

-- --------------------------------------------------------

--
-- Structure de la table `serie`
--

CREATE TABLE `serie` (
  `id` int(11) NOT NULL,
  `titre` varchar(128) NOT NULL,
  `descriptif` text NOT NULL,
  `img` varchar(256) NOT NULL,
  `genre` varchar(256) NOT NULL,
  `publicVise` varchar(256) NOT NULL,
  `annee` int(11) NOT NULL,
  `date_ajout` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `serie`
--

INSERT INTO `serie` (`id`, `titre`, `descriptif`, `img`, `genre`, `publicVise`, `annee`, `date_ajout`) VALUES
(1, 'Le lac aux mystères', 'C\'est l\'histoire d\'un lac mystérieux et plein de surprises. La série, bluffante et haletante, nous entraine dans un labyrinthe d\'intrigues époustouflantes. A ne rater sous aucun prétexte !', 'lake.jpg', 'Horreur', 'Adulte', 2020, '2022-10-30'),
(2, 'L\'eau a coulé', 'Une série nostalgique qui nous invite à revisiter notre passé et à se remémorer tout ce qui s\'est passé depuis que tant d\'eau a coulé sous les ponts.', 'water.jpg', 'Action', 'Adolescent', 1907, '2022-10-29'),
(3, 'Chevaux fous', 'Une série sur la vie des chevals sauvages en liberté. Décoiffante.', 'horses.jpg', 'Aventure', 'Famille', 2017, '2022-10-31'),
(4, 'A la plage', 'Le succès de l\'été 2021, à regarder sans modération et entre amis.', 'beach.jpg', 'Aventure', 'Famille', 2021, '2022-11-04'),
(5, 'Champion', 'La vie trépidante de deux champions de surf, passionnés dès leur plus jeune age. Ils consacrent leur vie à ce sport. ', 'surf.jpg', 'Sport', 'Famille', 2022, '2022-11-03'),
(6, 'Une ville la nuit', 'C\'est beau une ville la nuit, avec toutes ces voitures qui passent et qui repassent. La série suit un livreur, un chauffeur de taxi, et un insomniaque. Tous parcourent la grande ville une fois la nuit venue, au volant de leur véhicule.', 'carsbynight.jpg', 'Nostalgie', 'Adulte', 2017, '2022-10-31');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `email` varchar(256) NOT NULL,
  `passwd` varchar(256) NOT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`email`, `passwd`, `role`) VALUES
('admin@mail.com', '$2y$12$JtV1W6MOy/kGILbNwGR2lOqBn8PAO3Z6MupGhXpmkeCXUPQ/wzD8a', 100),
('beal.lucas@gmail.com', '$2y$12$MufN.kfbr06ws40gAKhdZOBDT0jPCwBcRZiDqoRjTLl3NnligxL5i', 1),
('romain.alexandre@mail.com', '$2y$12$VNd0gBVa6R28FTxb9YZriO/xcI7zMZ.Tf3ppOc5MDbD34Smt0oOf2', 1),
('user1@mail.com', '$2y$12$e9DCiDKOGpVs9s.9u2ENEOiq7wGvx7sngyhPvKXo2mUbI3ulGWOdC', 1),
('user2@mail.com', '$2y$12$4EuAiwZCaMouBpquSVoiaOnQTQTconCP9rEev6DMiugDmqivxJ3AG', 1),
('user3@mail.com', '$2y$12$5dDqgRbmCN35XzhniJPJ1ejM5GIpBMzRizP730IDEHsSNAu24850S', 1),
('user4@mail.com', '$2y$12$ltC0A0zZkD87pZ8K0e6TYOJPJeN/GcTSkUbpqq0kBvx6XdpFqzzqq', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`email`,`idSerie`);

--
-- Index pour la table `episode`
--
ALTER TABLE `episode`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `estfini`
--
ALTER TABLE `estfini`
  ADD PRIMARY KEY (`email`,`idSerie`);

--
-- Index pour la table `estvisionne`
--
ALTER TABLE `estvisionne`
  ADD PRIMARY KEY (`email`,`idVideo`);

--
-- Index pour la table `favori`
--
ALTER TABLE `favori`
  ADD PRIMARY KEY (`email`,`idSerie`);

--
-- Index pour la table `serie`
--
ALTER TABLE `serie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `episode`
--
ALTER TABLE `episode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `serie`
--
ALTER TABLE `serie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
