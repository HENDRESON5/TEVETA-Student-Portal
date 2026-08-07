-- ============================================================
-- TEVETA STUDENT PORTAL — SCHEMA MIGRATION
-- Run this AFTER your existing teveta_student_portal.sql import.
-- Take a backup first (phpMyAdmin > Export) before running this.
-- ============================================================
-- What this does:
--   1. Adds a username column to `users` for login (removes email
--      as the login method - email column is dropped entirely)
--   2. Rebuilds `results` around 3 fixed papers instead of subjects
--   3. Replaces `departments` with `colleges` (multi-college support)
--   4. Updates `students` to reference a college instead of a department
--   5. Removes the now-unused `departments` table
-- ============================================================

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

-- Email is no longer used anywhere in this system - drop it.
-- (If you'd rather keep it as optional metadata instead of deleting it,
--  skip this line and just stop enforcing it as required/unique instead.)
ALTER TABLE `users` DROP COLUMN `email`;

-- ------------------------------------------------------------
-- 2. RESULTS — 3 fixed papers, not per-subject
--    Field names below match the "Enter/Edit Results" form exactly:
--    practical_score, practical_classification, etc.
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- 3. COLLEGES — replaces departments
--    Names match the College dropdown already in request_results.php
--    and profile.php - keep these in sync if you rename any college.
-- ------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `colleges` (
  `college_id` int NOT NULL AUTO_INCREMENT,
  `college_name` varchar(100) NOT NULL,
  PRIMARY KEY (`college_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Not seeding college data here on purpose - run
-- teveta_colleges_courses_seed.sql right after this file
-- to populate the real list of 27 colleges.

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

-- NOTE: your existing seeded course names (Diploma in ICT, Diploma in
-- Electrical Installation, etc.) don't exactly match the Course dropdown
-- options in request_results.php (ICT, Electrical Installation, Plumbing,
-- Automotive Mechanics, Tailoring). Worth deciding which list is the real
-- one and updating either the `courses` table rows or the dropdown to match -
-- this migration doesn't touch course names, just the structure.

-- ------------------------------------------------------------
-- 6. DEPARTMENTS — no longer used, safe to drop last
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `departments`;

COMMIT;