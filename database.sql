-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 11 Kwi 2012, 15:14
-- Wersja serwera: 5.5.16
-- Wersja PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza danych: `template`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
  `content` text COLLATE utf8_polish_ci,
  `author` int(11) DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `reads` int(11) DEFAULT NULL,
  `allow_comment` smallint(2) DEFAULT NULL,
  `allow_rating` smallint(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `atom`
--

CREATE TABLE IF NOT EXISTS `atom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `atom_url` varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `blacklist`
--

CREATE TABLE IF NOT EXISTS `blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ban_userid` int(11) DEFAULT NULL,
  `ban_ip` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `ban_reason` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `ban_start` datetime DEFAULT NULL,
  `ban_end` datetime DEFAULT NULL,
  `adds` int(11) DEFAULT NULL,
  `adds_ip` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `calendar`
--

CREATE TABLE IF NOT EXISTS `calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `content` text COLLATE utf8_polish_ci,
  `href` varchar(150) COLLATE utf8_polish_ci DEFAULT NULL,
  `type` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `item_type` varchar(2) COLLATE utf8_polish_ci DEFAULT NULL,
  `message` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `ip` varchar(30) COLLATE utf8_polish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `faq`
--

CREATE TABLE IF NOT EXISTS `faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text COLLATE utf8_polish_ci,
  `answer` text COLLATE utf8_polish_ci,
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `desc` text COLLATE utf8_polish_ci,
  `url` varchar(150) COLLATE utf8_polish_ci DEFAULT NULL,
  `accepted` int(1) DEFAULT NULL,
  `size` varchar(30) COLLATE utf8_polish_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `adds` int(11) DEFAULT NULL,
  `cat` int(11) DEFAULT NULL,
  `direct` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `files_cat`
--

CREATE TABLE IF NOT EXISTS `files_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `order` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `lea_challanges`
--

CREATE TABLE IF NOT EXISTS `lea_challanges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player1` int(11) DEFAULT NULL,
  `player2` int(11) DEFAULT NULL,
  `season` varchar(20) COLLATE utf8_polish_ci DEFAULT NULL,
  `actived` int(11) DEFAULT '0',
  `nations` varchar(20) COLLATE utf8_polish_ci DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `winner` int(11) DEFAULT NULL,
  `score` int(1) DEFAULT '0',
  `score_set` int(11) DEFAULT NULL,
  `accepted` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `lea_games`
--

CREATE TABLE IF NOT EXISTS `lea_games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player1` int(11) DEFAULT NULL,
  `player2` int(11) DEFAULT NULL,
  `season` varchar(20) COLLATE utf8_polish_ci DEFAULT NULL,
  `nations` varchar(20) COLLATE utf8_polish_ci DEFAULT NULL,
  `winner` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT '0',
  `score_accept` int(11) DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `scored` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `lea_msg`
--

CREATE TABLE IF NOT EXISTS `lea_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text COLLATE utf8_polish_ci,
  `author` int(11) DEFAULT NULL,
  `challange_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `ip` varchar(30) COLLATE utf8_polish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `lea_players`
--

CREATE TABLE IF NOT EXISTS `lea_players` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `active` int(1) DEFAULT NULL,
  `free` int(1) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `score_stats` int(11) DEFAULT NULL,
  `last_stats_score` int(11) DEFAULT NULL,
  `last_stats_position` int(11) DEFAULT NULL,
  `wins` int(11) DEFAULT NULL,
  `lost` int(11) DEFAULT NULL,
  `medals` text COLLATE utf8_polish_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `lea_settings`
--

CREATE TABLE IF NOT EXISTS `lea_settings` (
  `last_stats_date` date DEFAULT NULL,
  `rules` text COLLATE utf8_polish_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `url` varchar(150) COLLATE utf8_polish_ci DEFAULT NULL,
  `order` int(5) DEFAULT NULL,
  `cat` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `links_cat`
--

CREATE TABLE IF NOT EXISTS `links_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `order` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `text` text COLLATE utf8_polish_ci,
  `text_ext` text COLLATE utf8_polish_ci,
  `author` int(11) DEFAULT NULL,
  `languages` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `allow_comment` int(1) DEFAULT NULL,
  `allow_rating` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `panels`
--

CREATE TABLE IF NOT EXISTS `panels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8_polish_ci DEFAULT NULL,
  `content` text COLLATE utf8_polish_ci,
  `display` int(1) DEFAULT NULL,
  `order` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=3 ;

--
-- Zrzut danych tabeli `panels`
--

INSERT INTO `panels` (`id`, `name`, `content`, `display`, `order`) VALUES
(1, '{CLAN SECTION}', '<ul><li><a href=''clan-members.php''>{TEAM}</a></li></ul>', 1, 1),
(2, '{LEAGUE SECTION}', '<ul><li><a href=''lea.table.php''>{TABLE}</a></li><li><a href=''lea.stats.php''>{STATS}</a></li><li><a href=''lea.account.php''>{SYSTEM}</a></li></ul>', 1, 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `ratings`
--

CREATE TABLE IF NOT EXISTS `ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `item_type` varchar(2) COLLATE utf8_polish_ci DEFAULT NULL,
  `vote` smallint(2) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `ip` varchar(30) COLLATE utf8_polish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `reads`
--

CREATE TABLE IF NOT EXISTS `reads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `item_type` varchar(2) COLLATE utf8_polish_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `reg_countries`
--

CREATE TABLE IF NOT EXISTS `reg_countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `lang` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `code` varchar(10) COLLATE utf8_polish_ci DEFAULT NULL,
  `set` int(1) DEFAULT NULL,
  `charset` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=10 ;

--
-- Zrzut danych tabeli `reg_countries`
--

INSERT INTO `reg_countries` (`id`, `name`, `lang`, `code`, `set`, `charset`) VALUES
(1, 'Polska', 'Polski', 'pol', 1, 'ISO-8859-2'),
(2, 'England', 'English', 'eng', 0, 'ISO-8859-1'),
(3, 'Russia', 'Russian', 'rus', 0, 'ISO-8859-1'),
(4, 'Germany', 'German', 'ger', 0, 'ISO-8859-1'),
(5, 'Ukraine', 'Ukrainian', 'ukr', 0, 'ISO-8859-1'),
(9, 'Hungary', 'Hungarian', 'hun', 0, 'ISO-8859-1');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `reg_emots`
--

CREATE TABLE IF NOT EXISTS `reg_emots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) COLLATE utf8_polish_ci DEFAULT NULL,
  `image` varchar(30) COLLATE utf8_polish_ci DEFAULT NULL,
  `display` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=10 ;

--
-- Zrzut danych tabeli `reg_emots`
--

INSERT INTO `reg_emots` (`id`, `code`, `image`, `display`) VALUES
(1, ':)', '1.gif', 1),
(2, ';)', '2.gif', 1),
(3, ':D', '3.gif', 1),
(4, ';D', '3.gif', 1),
(5, '=]', '4.gif', 1),
(6, ';]', '5.gif', 1),
(7, ':]', '5.gif', 1),
(8, ':x', '6.gif', 1),
(9, ';x', '6.gif', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `mainpage` varchar(50) COLLATE utf8_polish_ci DEFAULT 'news.php',
  `clanname` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `headadmin` int(11) DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `title` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `header_text_main` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `header_text_sub` varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
  `footer` varchar(200) COLLATE utf8_polish_ci DEFAULT NULL,
  `logoimage` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `fav_ico` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `desc` varchar(200) COLLATE utf8_polish_ci DEFAULT NULL,
  `keys` varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
  `author` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `log_sys` int(1) DEFAULT '1',
  `forum_sys` int(1) DEFAULT '0',
  `forum_link` varchar(100) COLLATE utf8_polish_ci DEFAULT '#',
  `banner_sys` int(1) DEFAULT '0',
  `antyflood` int(3) DEFAULT '30',
  `admin_note` text COLLATE utf8_polish_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `settings`
--

INSERT INTO `settings` (`mainpage`, `clanname`, `headadmin`, `email`, `title`, `header_text_main`, `header_text_sub`, `footer`, `logoimage`, `fav_ico`, `desc`, `keys`, `author`, `log_sys`, `forum_sys`, `forum_link`, `banner_sys`, `antyflood`, `admin_note`) VALUES
('news.php', '{CLAN NAME}', 1, 'admin@host.domain', '{TITLE}', '{HEADER}', '{HEADER-SUB}', '{FOOTER}', '', 'images/favico.ico', 'description', 'keywords', 'Daniel Gutkowski', 1, 0, '#', 0, 30, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `shoutbox`
--

CREATE TABLE IF NOT EXISTS `shoutbox` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `text` varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
  `author` int(11) DEFAULT NULL,
  `show` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `shoutbox_set`
--

CREATE TABLE IF NOT EXISTS `shoutbox_set` (
  `display` int(1) DEFAULT NULL,
  `rows` int(2) DEFAULT NULL,
  `max_lenght` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `shoutbox_set`
--

INSERT INTO `shoutbox_set` (`display`, `rows`, `max_lenght`) VALUES
(0, 3, 50);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `password` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `mail` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `register_date` datetime DEFAULT NULL,
  `register_ip` varchar(30) COLLATE utf8_polish_ci DEFAULT NULL,
  `last_date` datetime DEFAULT NULL,
  `last_ip` varchar(30) COLLATE utf8_polish_ci DEFAULT NULL,
  `active` int(1) DEFAULT NULL,
  `level` int(4) DEFAULT NULL,
  `rights` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `avatar` varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
  `gg` varchar(20) COLLATE utf8_polish_ci DEFAULT NULL,
  `icq` varchar(20) COLLATE utf8_polish_ci DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `intrest` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `location` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `born` date DEFAULT NULL,
  `gender` int(1) DEFAULT NULL,
  `country` varchar(10) COLLATE utf8_polish_ci DEFAULT NULL,
  `gamenick` varchar(30) COLLATE utf8_polish_ci DEFAULT NULL,
  `clan` varchar(20) COLLATE utf8_polish_ci DEFAULT NULL,
  `favrules` varchar(50) COLLATE utf8_polish_ci DEFAULT NULL,
  `usertitle` varchar(30) COLLATE utf8_polish_ci DEFAULT NULL,
  `siteclan_member` int(1) DEFAULT NULL,
  `siteclan_date` date DEFAULT NULL,
  `display_mail` int(1) DEFAULT NULL,
  `display_online` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=5 ;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `mail`, `register_date`, `register_ip`, `last_date`, `last_ip`, `active`, `level`, `rights`, `avatar`, `gg`, `icq`, `name`, `intrest`, `location`, `born`, `gender`, `country`, `gamenick`, `clan`, `favrules`, `usertitle`, `siteclan_member`, `siteclan_date`, `display_mail`, `display_online`) VALUES
(1, 'admin', '74b87337454200d4d33f80c4663dc5e5', 'admin@host.domain', '2012-04-10 00:00:00', NULL, '0000-00-00 00:00:00', NULL, 1, 101, 'M.A.LA.SA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'pol', 'gamenick', 'clan', NULL, NULL, 1, '0000-00-00', 1, 1),
(2, 'SYSTEM', '0', '0', '0000-00-00 00:00:00', '0', '0000-00-00 00:00:00', '0', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(3, 'Robot #1', '0', '0', '0000-00-00 00:00:00', '0', '0000-00-00 00:00:00', '0', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(4, 'Robot #2', '0', '0', '0000-00-00 00:00:00', '0', '0000-00-00 00:00:00', '0', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(5, 'Guest', '0', '0', '0000-00-00 00:00:00', '0', '0000-00-00 00:00:00', '0', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
