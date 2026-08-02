-- ============================================================
-- TEVETA STUDENT PORTAL — REAL COLLEGES + COURSES SEED
-- Run this AFTER teveta_schema_migration.sql (colleges table
-- and courses.department_id removal must already be in place).
-- ============================================================

START TRANSACTION;

-- ------------------------------------------------------------
-- Clear out the placeholder data
-- ------------------------------------------------------------

TRUNCATE TABLE `colleges`;
TRUNCATE TABLE `courses`;

-- ------------------------------------------------------------
-- COLLEGES (27 total - technical colleges + community colleges)
-- ------------------------------------------------------------

INSERT INTO `colleges` (`college_name`) VALUES
('Lilongwe Technical College'),
('Livingstonia Technical College'),
('Mzuzu Technical College'),
('Namitete Technical College'),
('Nasawa Technical College'),
('Salima Technical College'),
('Soche Technical College'),
('MACODA-Lilongwe Vocational'),
('Malawi Institute of Tourism'),
('Aida Chilembwe Community College'),
('Chilobwe Community College'),
('Kalinda Community College'),
('Khiwisa Community College'),
('Linthipe Community College'),
('Machinga Community College'),
('Mangochi Community College'),
('Mbandira Community College'),
('Milonga Community College'),
('Mponela Community College'),
('Naminjiwa Community College'),
('Nansomba Community College'),
('Ngara Community College'),
('Nkhata Bay Community College'),
('Nsoni Community College'),
('Ntonda Community College'),
('Sakata Community College'),
('Tengani Community College');

-- ------------------------------------------------------------
-- COURSES (18 total)
-- ------------------------------------------------------------

INSERT INTO `courses` (`course_name`, `duration`) VALUES
('Artisanal and Small-Scale Mining', NULL),
('Administrative Studies', NULL),
('Automobile Mechanics', NULL),
('Bricklaying', NULL),
('Carpentry and Joinery', NULL),
('Electrical Installation and Electronics', NULL),
('Fabrication and Welding', NULL),
('Food Production', NULL),
('General Fitting', NULL),
('Information and Communication Technology', NULL),
('Motor Cycle Mechanics', NULL),
('Painting and Decoration', NULL),
('Plumbing', NULL),
('Refrigeration and Air Conditioning Mechanics', NULL),
('Solar Photovoltaic Installation', NULL),
('Tailoring and Fashion Design', NULL),
('Tour Guide', NULL),
('Vehicle Body Repairing and Refinishing', NULL),
('Woodwork Machining', NULL);

COMMIT;