-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 28 Lip 2015, 12:34
-- Wersja serwera: 5.5.43-0ubuntu0.14.04.1
-- Wersja PHP: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza danych: `portal`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `announce`
--

CREATE TABLE IF NOT EXISTS `announce` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `content` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id_id` int(11) DEFAULT NULL,
  `category_id_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E6D6DD759D86650F` (`user_id_id`),
  KEY `IDX_E6D6DD759777D11E` (`category_id_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=24 ;

--
-- Zrzut danych tabeli `announce`
--

INSERT INTO `announce` (`id`, `title`, `content`, `address`, `created`, `user_id_id`, `category_id_id`) VALUES
(17, 'test', 'opis1sadadasdsadasdsdfdasdffds dfsdfdsaf dsfadsfdasfd dasfdsafdsaf sadfdsafdsf sdfasdfasfsad fasdf sadf dsaf sdfdsf sadgds sadfsdf sdfaf', 'adres1', '2015-07-27 09:08:03', 1, 3),
(18, 'test', 'opis', 'adres', '2015-07-27 09:08:03', 1, 3),
(19, 'test', 'opis', 'adres1', '2015-07-27 09:08:38', 1, 3),
(20, 'tytul', 'opis', 'adres1', '2015-07-27 09:19:08', 1, 3),
(23, 'lololol', 'lolololo', 'lolololol', '2015-07-27 13:15:09', 2, 4);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Zrzut danych tabeli `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(3, 'Sprzedam'),
(4, 'Kupię');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `fos_user`
--

CREATE TABLE IF NOT EXISTS `fos_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_957A647992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_957A6479A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Zrzut danych tabeli `fos_user`
--

INSERT INTO `fos_user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`) VALUES
(1, 'test', 'test', 'rugala.mr@gmail.com', 'rugala.mr@gmail.com', 1, 'piu3ltcotc00okkcw48sw808844kww4', '$2y$13$piu3ltcotc00okkcw48swudzg3aX4Kx/VrqVqUr8u4Xs2dQEv1yhq', '2015-07-28 09:31:54', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL),
(2, 'marek', 'marek', 'markus663@wp.pl', 'markus663@wp.pl', 1, 'c5kqlt2kxnw4wkcs8gk4gggkc0cwk44', '$2y$13$c5kqlt2kxnw4wkcs8gk4gegtZG/T./lZm4Apa8q0oV/IszgJFsqdC', '2015-07-27 15:14:57', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL);

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `announce`
--
ALTER TABLE `announce`
  ADD CONSTRAINT `FK_E6D6DD759777D11E` FOREIGN KEY (`category_id_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_E6D6DD759D86650F` FOREIGN KEY (`user_id_id`) REFERENCES `fos_user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
