-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-07-13 07:28:36
-- 服务器版本： 10.1.13-MariaDB
-- PHP Version: 7.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lifeistrade`
--

-- --------------------------------------------------------

--
-- 表的结构 `lit_new`
--

CREATE TABLE `lit_new` (
  `nid` int(10) UNSIGNED NOT NULL COMMENT '新闻id',
  `creatusername` char(30) NOT NULL COMMENT '新闻发布人',
  `title` char(255) NOT NULL COMMENT '新闻标题',
  `content` text NOT NULL COMMENT '新闻内容',
  `createtime` int(10) UNSIGNED NOT NULL COMMENT '发布时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `lit_tradeinfo`
--

CREATE TABLE `lit_tradeinfo` (
  `tid` int(10) UNSIGNED NOT NULL COMMENT '交易信息的ID',
  `title` char(255) NOT NULL COMMENT '标题',
  `itemname` char(30) NOT NULL COMMENT '交易的物品',
  `itemnum` int(5) UNSIGNED NOT NULL COMMENT '交易的数量',
  `itemprice` char(20) NOT NULL COMMENT '物品单价',
  `itemquality` int(3) UNSIGNED NOT NULL COMMENT '物品质量',
  `trader` char(30) NOT NULL COMMENT '交易发起者',
  `onlinetime` char(22) NOT NULL COMMENT '在线时间',
  `tradingplace` char(100) NOT NULL COMMENT '交易地点',
  `tradetype` int(1) UNSIGNED NOT NULL COMMENT '交易类型0：出售1：收购',
  `creattime` int(10) UNSIGNED NOT NULL COMMENT '交易创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lit_new`
--
ALTER TABLE `lit_new`
  ADD PRIMARY KEY (`nid`),
  ADD KEY `creatusername` (`creatusername`,`title`);

--
-- Indexes for table `lit_tradeinfo`
--
ALTER TABLE `lit_tradeinfo`
  ADD PRIMARY KEY (`tid`),
  ADD KEY `title` (`title`,`itemname`,`trader`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `lit_new`
--
ALTER TABLE `lit_new`
  MODIFY `nid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '新闻id';
--
-- 使用表AUTO_INCREMENT `lit_tradeinfo`
--
ALTER TABLE `lit_tradeinfo`
  MODIFY `tid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '交易信息的ID';
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
