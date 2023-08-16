
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



CREATE DATABASE IF NOT EXISTS `munkaero_nyilvantarto` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `munkaero_nyilvantarto`;

-
DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `DepartmentID` int NOT NULL AUTO_INCREMENT,
  `DepartmentName` varchar(255) NOT NULL,
  PRIMARY KEY (`DepartmentID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `EmployeeID` int NOT NULL AUTO_INCREMENT,
  `FullName` varchar(255) NOT NULL,
  `PositionID` int DEFAULT NULL,
  `DepartmentID` int DEFAULT NULL,
  `GrossWage` int DEFAULT NULL,
  `TaxNumber` varchar(50) NOT NULL,
  `NINumber` varchar(50) NOT NULL,
  `BankAccount` varchar(26) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`EmployeeID`),
  KEY `PositionID` (`PositionID`),
  KEY `DepartmentID` (`DepartmentID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



DROP TABLE IF EXISTS `positions`;
CREATE TABLE IF NOT EXISTS `positions` (
  `PositionID` int NOT NULL AUTO_INCREMENT,
  `PositionName` varchar(255) NOT NULL,
  `PositionDesc` text,
  PRIMARY KEY (`PositionID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

