-- ============================================================
-- PASSWORD RESET REQUESTS TABLE
-- Supports the manual admin-reset flow (no email in this system).
-- Run this in phpMyAdmin the same way as the other migration files.
-- ============================================================

CREATE TABLE IF NOT EXISTS `password_reset_requests` (
  `request_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `requested_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Pending','Resolved') DEFAULT 'Pending',
  `resolved_by` int DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`request_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
