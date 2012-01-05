-- phpMyAdmin SQL Dump
-- version 2.11.3deb1ubuntu1.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 11, 2009 at 10:16 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.4-2ubuntu5.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `digaboard_schema`
--

-- --------------------------------------------------------

--
-- Table structure for table `assign`
--

CREATE TABLE IF NOT EXISTS `assign` (
  `AID` int(9) NOT NULL auto_increment,
  `IID_link` int(9) NOT NULL,
  `UID_link` int(9) NOT NULL,
  PRIMARY KEY  (`AID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=113 ;

--
-- Dumping data for table `assign`
--


-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `CID` int(9) NOT NULL auto_increment,
  `IID_link` int(9) NOT NULL,
  `UID_link` int(9) default NULL,
  `date` datetime NOT NULL,
  `subject` varchar(100) NOT NULL,
  `comment` varchar(500) NOT NULL,
  PRIMARY KEY  (`CID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1203 ;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`CID`, `IID_link`, `UID_link`, `date`, `subject`, `comment`) VALUES
(1202, 268, 1, '2009-09-11 18:37:41', '', 'Item Created!');

-- --------------------------------------------------------

--
-- Table structure for table `date`
--

CREATE TABLE IF NOT EXISTS `date` (
  `DID` int(9) NOT NULL auto_increment,
  `IID_link` int(9) NOT NULL,
  `Slevel` int(3) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY  (`DID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1354385 ;

--
-- Dumping data for table `date`
--

INSERT INTO `date` (`DID`, `IID_link`, `Slevel`, `date`) VALUES
(1354384, 268, 2, '2009-09-11 18:37:41'),
(1354383, 268, 1, '2009-09-11 18:37:41'),
(1354382, 268, 0, '2009-09-11 18:37:41');

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `GID` int(3) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  `state` varchar(10) default NULL,
  PRIMARY KEY  (`GID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `group`
--

INSERT INTO `group` (`GID`, `name`, `state`) VALUES
(1, 'My Team', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `IID` int(3) NOT NULL auto_increment,
  `DID` int(9) default NULL,
  `Slevel` int(3) NOT NULL,
  `Tlevel` int(3) NOT NULL,
  `GID` int(3) NOT NULL,
  `UID` int(3) default NULL,
  `Cdate` datetime NOT NULL,
  `Sdate` datetime default NULL,
  `Edate` datetime default NULL,
  `title` varchar(200) default NULL,
  `description` varchar(1000) default NULL,
  `priority` int(5) default NULL,
  `tag` varchar(12) default NULL,
  `state` varchar(20) NOT NULL default 'active',
  PRIMARY KEY  (`IID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=269 ;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`IID`, `DID`, `Slevel`, `Tlevel`, `GID`, `UID`, `Cdate`, `Sdate`, `Edate`, `title`, `description`, `priority`, `tag`, `state`) VALUES
(1, NULL, 0, 0, 1, 1, '2009-09-12 22:43:52', NULL, NULL, 'My First Project', 'This is the default project added with the scheme. You can delete this project and add your own from the right menu bar.', 1, NULL, 'active'),
(268, NULL, 2, 1, 1, 1, '2009-09-11 18:37:41', NULL, NULL, 'Cool task on the board', 'This is a task pre-loaded with the default schema. It is safe to delete this task and project to create your own.', 0, NULL, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `link`
--

CREATE TABLE IF NOT EXISTS `link` (
  `LID` int(5) NOT NULL auto_increment,
  `IID` int(5) NOT NULL,
  `IID_link` int(5) default NULL,
  PRIMARY KEY  (`LID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=259 ;

--
-- Dumping data for table `link`
--

INSERT INTO `link` (`LID`, `IID`, `IID_link`) VALUES
(258, 268, 1);

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE IF NOT EXISTS `options` (
  `OID` int(12) NOT NULL auto_increment,
  `GID` int(12) NOT NULL,
  `option` varchar(12) NOT NULL,
  `status` varchar(2) default NULL,
  `value` int(4) NOT NULL,
  PRIMARY KEY  (`OID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`OID`, `GID`, `option`, `status`, `value`) VALUES
(1, 0, 'item', '1', 5),
(2, 0, 'item', '2', 3),
(3, 0, 'item', '3', 3),
(4, 0, 'item', '4', 8),
(5, 0, 'date', NULL, 5),
(13, 1, 'item', '2', 3),
(12, 1, 'item', '1', 7),
(14, 1, 'item', '3', 2),
(15, 1, 'item', '4', 10),
(16, 1, 'date', NULL, 5);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `SID` int(3) NOT NULL auto_increment,
  `GID` int(12) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(3) NOT NULL,
  `limit` int(3) NOT NULL,
  PRIMARY KEY  (`SID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=52 ;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`SID`, `GID`, `name`, `level`, `limit`) VALUES
(1, 0, 'Pool', 0, 0),
(2, 0, 'Ready', 1, 6),
(3, 0, 'Doing', 2, 2),
(4, 0, 'Review', 3, 5),
(5, 0, 'Done', 4, 0),
(6, 1, 'Pool', 0, 0),
(7, 1, 'Ready', 1, 4),
(8, 1, 'Doing', 2, 3),
(9, 1, 'Review', 3, 2),
(10, 1, 'Done', 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `TID` int(9) NOT NULL auto_increment,
  `UID_link` int(9) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY  (`TID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tag`
--


-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE IF NOT EXISTS `type` (
  `TID` int(3) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `level` int(3) NOT NULL,
  PRIMARY KEY  (`TID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`TID`, `name`, `level`) VALUES
(1, 'Project', 0),
(2, 'Item', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `UID` int(3) NOT NULL auto_increment,
  `GID` int(3) NOT NULL,
  `name` varchar(100) default NULL,
  `displayname` varchar(12) NOT NULL,
  `email` varchar(100) default NULL,
  `encryptedpw` varchar(20) default NULL,
  `temp` int(1) default NULL,
  PRIMARY KEY  (`UID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UID`, `GID`, `name`, `displayname`, `email`, `encryptedpw`, `temp`) VALUES
(1, 1, 'Your Name', 'not supporte', 'yourname@yourdomain.com', NULL, NULL);
