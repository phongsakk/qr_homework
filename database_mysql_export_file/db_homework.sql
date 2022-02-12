-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2022 at 09:34 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_homework`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_class`
--

CREATE TABLE `tb_class` (
  `ClassID` int(3) UNSIGNED ZEROFILL NOT NULL,
  `ClassTitle` varchar(50) NOT NULL,
  `ClassDetail` varchar(255) NOT NULL,
  `OwnerID` int(3) UNSIGNED ZEROFILL NOT NULL DEFAULT 001,
  `Day` enum('อาทิตย์','จันทร์','อังคาร','พุธ','พฤหัสบดี','ศุกร์','เสาร์') NOT NULL,
  `ClassBegin` time NOT NULL DEFAULT '08:00:00',
  `ClassEnd` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_class`
--

INSERT INTO `tb_class` (`ClassID`, `ClassTitle`, `ClassDetail`, `OwnerID`, `Day`, `ClassBegin`, `ClassEnd`) VALUES
(001, 'โครงการ 1', 'ป.ตรี ปี4(เทียบโอน)', 001, 'จันทร์', '08:00:00', '11:00:00'),
(002, 'โครงการ 2', 'ป.ตรี ปี4(เทียบโอน)', 001, 'จันทร์', '13:00:00', '16:00:00'),
(003, 'ภาษาไทย', 'พศ.64', 001, 'อังคาร', '13:15:00', '16:00:00'),
(004, 'ภาษาไทย', 'สารสนเทศ.63', 001, 'พุธ', '08:00:00', '11:00:00'),
(005, 'ทดสอบระบบ', 'สมาชิกชมรม', 001, 'จันทร์', '08:00:00', '11:00:00'),
(006, 'name', 'last', 001, 'จันทร์', '08:00:00', '08:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tb_enroll`
--

CREATE TABLE `tb_enroll` (
  `UserID` int(3) UNSIGNED ZEROFILL NOT NULL,
  `ClassID` int(3) UNSIGNED ZEROFILL NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_enroll`
--

INSERT INTO `tb_enroll` (`UserID`, `ClassID`) VALUES
(002, 001),
(002, 003),
(002, 005),
(002, 006),
(003, 002),
(003, 004),
(003, 005);

-- --------------------------------------------------------

--
-- Table structure for table `tb_exercise`
--

CREATE TABLE `tb_exercise` (
  `ClassID` int(3) UNSIGNED ZEROFILL NOT NULL,
  `ExerciseID` int(3) UNSIGNED ZEROFILL NOT NULL,
  `ExerciseTitle` text NOT NULL,
  `ExerciseStart` datetime NOT NULL DEFAULT current_timestamp(),
  `ExerciseEnd` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_exercise`
--

INSERT INTO `tb_exercise` (`ClassID`, `ExerciseID`, `ExerciseTitle`, `ExerciseStart`, `ExerciseEnd`) VALUES
(005, 001, '<p>จงทำงาน 1-2-3 มาส่ง</p>', '2022-02-01 00:00:00', '2022-02-09 00:00:00'),
(005, 002, '<p>ให้คัดลอกเนื้อหาบทที่ 1 ลงสมุด<p>', '2022-01-30 16:10:00', '2022-01-30 16:10:00'),
(005, 003, '<p>เทส ยังไม่ส่ง อยู่ในช่วงเวลา</p>', '2023-01-01 00:02:00', '2023-01-02 00:02:00'),
(005, 004, '<p>ให้นักเรียนตอบคำถามจากหนังสือ หน้าที่74 ข้อ1. 2. 3.</p><p><span style=\"color: rgb(136, 136, 136);\">(กลุ่ม 5 คน)</span></p>', '2022-02-06 22:56:00', '2022-02-06 22:56:00'),
(005, 005, '<p>ให้ นศ. ทำโปรเจคมาส่ง</p>', '2022-02-07 01:23:00', '2022-09-07 01:23:00');

-- --------------------------------------------------------

--
-- Table structure for table `tb_exercisesend`
--

CREATE TABLE `tb_exercisesend` (
  `ExerciseID` int(3) UNSIGNED ZEROFILL NOT NULL,
  `UserID` int(3) UNSIGNED ZEROFILL NOT NULL,
  `FileName` varchar(20) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_exercisesend`
--

INSERT INTO `tb_exercisesend` (`ExerciseID`, `UserID`, `FileName`, `timestamp`) VALUES
(001, 002, '002005001.pdf', '2022-02-07 01:52:51'),
(001, 003, '003005001.pdf', '2022-02-07 03:32:22'),
(005, 002, '002005005.docx', '2022-02-07 03:26:27');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `UserID` int(3) UNSIGNED ZEROFILL NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Status` enum('ADMIN','USER') NOT NULL DEFAULT 'USER'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`UserID`, `Username`, `Password`, `Name`, `Status`) VALUES
(001, 'admin', 'admin', 'Administrator', 'ADMIN'),
(002, 'user', 'user', 'Firstname Lastname', 'USER'),
(003, 'mii', 'newpass', 'พงษ์ศักดิ์ ยอดเสาดี', 'USER');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_class`
--
ALTER TABLE `tb_class`
  ADD PRIMARY KEY (`ClassID`),
  ADD KEY `OwnerID` (`OwnerID`);

--
-- Indexes for table `tb_enroll`
--
ALTER TABLE `tb_enroll`
  ADD PRIMARY KEY (`UserID`,`ClassID`),
  ADD KEY `ClassID` (`ClassID`);

--
-- Indexes for table `tb_exercise`
--
ALTER TABLE `tb_exercise`
  ADD PRIMARY KEY (`ExerciseID`),
  ADD KEY `ClassID` (`ClassID`);

--
-- Indexes for table `tb_exercisesend`
--
ALTER TABLE `tb_exercisesend`
  ADD PRIMARY KEY (`ExerciseID`,`UserID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_class`
--
ALTER TABLE `tb_class`
  MODIFY `ClassID` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_exercise`
--
ALTER TABLE `tb_exercise`
  MODIFY `ExerciseID` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `UserID` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_class`
--
ALTER TABLE `tb_class`
  ADD CONSTRAINT `tb_class_ibfk_1` FOREIGN KEY (`OwnerID`) REFERENCES `tb_user` (`UserID`);

--
-- Constraints for table `tb_enroll`
--
ALTER TABLE `tb_enroll`
  ADD CONSTRAINT `tb_enroll_ibfk_1` FOREIGN KEY (`ClassID`) REFERENCES `tb_class` (`ClassID`),
  ADD CONSTRAINT `tb_enroll_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `tb_user` (`UserID`);

--
-- Constraints for table `tb_exercise`
--
ALTER TABLE `tb_exercise`
  ADD CONSTRAINT `tb_exercise_ibfk_1` FOREIGN KEY (`ClassID`) REFERENCES `tb_class` (`ClassID`);

--
-- Constraints for table `tb_exercisesend`
--
ALTER TABLE `tb_exercisesend`
  ADD CONSTRAINT `tb_exercisesend_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `tb_user` (`UserID`),
  ADD CONSTRAINT `tb_exercisesend_ibfk_2` FOREIGN KEY (`ExerciseID`) REFERENCES `tb_exercise` (`ExerciseID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
