-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 
-- サーバのバージョン： 10.1.38-MariaDB
-- PHP Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tabi_picture`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `category`
--

CREATE TABLE `category` (
  `categoryid` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0',
  `create_date` datetime NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `dm`
--

CREATE TABLE `dm` (
  `send_from` int(11) NOT NULL COMMENT '送信者（本人のid）',
  `send_to` int(11) NOT NULL COMMENT '送信相手',
  `received_msg` varchar(255) NOT NULL,
  `send_msg` varchar(255) NOT NULL,
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0',
  `create_date` datetime NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `favorite`
--

CREATE TABLE `favorite` (
  `userid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0',
  `create_date` datetime NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `follows`
--

CREATE TABLE `follows` (
  `userid` int(11) NOT NULL COMMENT '対象ユーザー',
  `follow` int(11) NOT NULL COMMENT '対象がフォローしている人のID',
  `follower` int(11) NOT NULL COMMENT '対象をフォローしている人のID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `products`
--

CREATE TABLE `products` (
  `productid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `pic1` varchar(255) NOT NULL,
  `pic2` varchar(255) DEFAULT NULL,
  `pic3` varchar(255) DEFAULT NULL,
  `pic4` varchar(255) DEFAULT NULL,
  `pic5` varchar(255) DEFAULT NULL,
  `pic6` varchar(255) DEFAULT NULL,
  `pic7` varchar(255) DEFAULT NULL,
  `pic8` varchar(255) DEFAULT NULL,
  `pic9` varchar(255) DEFAULT NULL,
  `pic10` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `detail` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` datetime NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `categoryid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `age` int(11) NOT NULL,
  `tel` varchar(255) NOT NULL,
  `zip` int(11) NOT NULL,
  `addr` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0',
  `create_date` datetime NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `header_img` varchar(255) NOT NULL,
  `icon_img` varchar(255) NOT NULL,
  `introduction` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`userid`, `username`, `age`, `tel`, `zip`, `addr`, `email`, `password`, `delete_flg`, `create_date`, `update_date`, `login_time`, `header_img`, `icon_img`, `introduction`) VALUES
(14, 'じゅんじ', 24, '08046890879', 2360023, 'カーサミユキ２　201号室', 'iscii1996@gmail.com', '$2y$10$ONawR6ceLSIVQp9MNC8Oe.3vMEjSZ6rWpoSFT5VdhaPFitM0GPr1e', 0, '2019-08-22 13:37:47', '2019-08-22 11:37:47', '2019-08-22 04:37:47', 'uploads/0f12fae63c716960549370049393ab671a9b9f80.jpeg', 'uploads/3350bd38a6c4a06d1af6aef9803a05f161fc2e87.jpeg', '自転車旅が大好きです。');

-- --------------------------------------------------------

--
-- テーブルの構造 `users_introduction`
--

CREATE TABLE `users_introduction` (
  `userid` int(11) NOT NULL,
  `icon_img` varchar(255) NOT NULL,
  `header_img` varchar(255) NOT NULL,
  `introduction` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`productid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `productid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
