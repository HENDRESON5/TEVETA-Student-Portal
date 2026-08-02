

START TRANSACTION;

-- ------------------------------------------------------------
-- 1. USERNAME-BASED LOGIN (no email)
-- ------------------------------------------------------------

ALTER TABLE `users` ADD COLUMN `username` VARCHAR(50) DEFAULT NULL AFTER `fullname`;

-- Give the existing admin row a real username so it can still log in.
-- Change 'admin' to whatever username you actually want for this account.
UPDATE `users` SET `username` = 'admin' WHERE `id` = 1;

ALTER TABLE `users` MODIFY `username` VARCHAR(50) NOT NULL;
ALTER TABLE `users` ADD UNIQUE KEY `username` (`username`);

ALTER TABLE `users` DROP COLUMN `email`;

-- ------------------------------------------------------------
-- 2. RESULTS — 3 fixed papers, not per-subject

-- ------------------------------------------------------------

DROP TABLE IF EXISTS `results`;
CREATE TABLE IF NOT EXISTS `results` (
  `result_id` int NOT NULL AUTO_INCREMENT,
  `student_id` int DEFAULT NULL,
  `practical_score` int DEFAULT NULL,
  `practical_classification` enum('Pass','Credit','Distinction','Fail') DEFAULT NULL,
  `occupational_score` int DEFAULT NULL,
  `occupational_classification` enum('Pass','Credit','Distinction','Fail') DEFAULT NULL,
  `fundamental_score` int DEFAULT NULL,
  `fundamental_classification` enum('Pass','Credit','Distinction','Fail') DEFAULT NULL,
  `overall_classification` enum('Pass','Credit','Distinction','Fail') DEFAULT NULL,
  `admin_comment` text,
  `academic_year` varchar(20) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`result_id`),
  KEY `student_id` (`student_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ------------------------------------------------------------
-- 3. COLLEGES — replaces departments
-

CREATE TABLE IF NOT EXISTS `colleges` (
  `college_id` int NOT NULL AUTO_INCREMENT,
  `college_name` varchar(100) NOT NULL,
  PRIMARY KEY (`college_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ------------------------------------------------------------
-- 4. STUDENTS — swap department_id for college_id
-- ------------------------------------------------------------

ALTER TABLE `students` ADD COLUMN `college_id` int DEFAULT NULL AFTER `course_id`;
ALTER TABLE `students` ADD KEY `college_id` (`college_id`);

ALTER TABLE `students` DROP KEY `department_id`;
ALTER TABLE `students` DROP COLUMN `department_id`;

-- `semester_id` already maps directly to "Level" (level 1-4) - no change needed there.

-- ------------------------------------------------------------
-- 5. COURSES — no longer tied to a department
-- ------------------------------------------------------------

ALTER TABLE `courses` DROP KEY `department_id`;
ALTER TABLE `courses` DROP COLUMN `department_id`;


-- ------------------------------------------------------------
-- 6. DEPARTMENTS — no longer used, safe to drop last
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `departments`;

COMMIT;