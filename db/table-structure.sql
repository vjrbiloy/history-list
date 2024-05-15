CREATE DATABASE technical_test;

CREATE TABLE `histories` (
	`history_id` INT(10) NOT NULL AUTO_INCREMENT,
	`user_id` INT(10) NULL DEFAULT NULL,
	`amount` DECIMAL(10,2) NULL DEFAULT NULL,
	`country` VARCHAR(200) NULL DEFAULT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`active` TINYINT(1) NULL DEFAULT '1',
	`datetime` DATETIME NULL DEFAULT (now()),
	PRIMARY KEY (`history_id`) USING BTREE,
	INDEX `index1` (`user_id`) USING BTREE,
	INDEX `index2` (`active`) USING BTREE,
	INDEX `index3` (`country`) USING BTREE,
	INDEX `index4` (`datetime`) USING BTREE,
	INDEX `index5` (`active`, `datetime`) USING BTREE
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB;

CREATE TABLE `users` (
	`user_id` INT(10) NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(200) NULL DEFAULT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`active` TINYINT(1) NULL DEFAULT '1',
	PRIMARY KEY (`user_id`) USING BTREE,
	INDEX `index1` (`active`) USING BTREE
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB;
