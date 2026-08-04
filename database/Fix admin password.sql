-- ============================================================
-- FIX ADMIN PASSWORD
-- The password hash that came with the original database dump was
-- a placeholder, not a real bcrypt hash - password_verify() would
-- never match it. This sets a real, working one.
--
-- Login credentials after running this:
--   username: admin
--   password: Teveta@2026
--
-- Change the password afterwards from Account Management once you
-- can log in, or generate a new hash the same way (see note below).
-- ============================================================

UPDATE `users`
SET `password` = '$2b$10$EkZRElN3z3YZsMTfv6YNpehs3Ad2YkO6orM6bT9X/GppO.UTlVcV6'
WHERE `id` = 1;

-- ------------------------------------------------------------
-- To generate a hash for a DIFFERENT password later, run this
-- in any throwaway .php file on the server and copy the output:
--
--   <?php echo password_hash('your_new_password', PASSWORD_DEFAULT); ?>
--
-- Then: UPDATE users SET password = '<paste output>' WHERE id = 1;
-- ------------------------------------------------------------