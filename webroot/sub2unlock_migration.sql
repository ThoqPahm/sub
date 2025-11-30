-- ============================================
-- Sub2Unlock Database Migration
-- Date: 2025-11-30
-- ============================================
-- Instructions:
-- 1. Open phpMyAdmin: http://localhost/phpmyadmin
-- 2. Select database 'xenvn'
-- 3. Go to 'SQL' tab
-- 4. Copy and paste this entire file
-- 5. Click 'Go' button
-- ============================================

-- Create platforms table
CREATE TABLE IF NOT EXISTS `platforms` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'NULL = system platform, NOT NULL = user custom platform',
  `name` varchar(50) NOT NULL COMMENT 'YouTube, Telegram, Facebook, Instagram, etc.',
  `icon` varchar(255) DEFAULT NULL COMMENT 'URL or path to icon',
  `is_system` tinyint(1) DEFAULT 0 COMMENT '1 = system platform (admin), 0 = user custom',
  `is_active` tinyint(1) DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_system` (`is_system`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Insert system platforms
INSERT INTO `platforms` (`id`, `user_id`, `name`, `icon`, `is_system`, `is_active`, `created`, `modified`) VALUES
(1, NULL, 'YouTube', NULL, 1, 1, NOW(), NOW()),
(2, NULL, 'Telegram', NULL, 1, 1, NOW(), NOW()),
(3, NULL, 'Facebook', NULL, 1, 1, NOW(), NOW()),
(4, NULL, 'Instagram', NULL, 1, 1, NOW(), NOW()),
(5, NULL, 'Twitter', NULL, 1, 1, NOW(), NOW());

-- Create platform_actions table
CREATE TABLE IF NOT EXISTS `platform_actions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `platform_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'NULL = system action, NOT NULL = user custom action',
  `name` varchar(100) NOT NULL COMMENT 'Subscribe, Like, Comment, Join Group, etc.',
  `description` text DEFAULT NULL,
  `is_system` tinyint(1) DEFAULT 0 COMMENT '1 = system action, 0 = user custom',
  `is_active` tinyint(1) DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `platform_id` (`platform_id`),
  KEY `user_id` (`user_id`),
  KEY `is_system` (`is_system`),
  FOREIGN KEY (`platform_id`) REFERENCES `platforms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Insert system actions
INSERT INTO `platform_actions` (`id`, `platform_id`, `user_id`, `name`, `description`, `is_system`, `is_active`, `created`, `modified`) VALUES
-- YouTube actions
(1, 1, NULL, 'Subscribe', 'Subscribe to YouTube channel', 1, 1, NOW(), NOW()),
(2, 1, NULL, 'Like', 'Like YouTube video', 1, 1, NOW(), NOW()),
(3, 1, NULL, 'Comment', 'Comment on YouTube video', 1, 1, NOW(), NOW()),
(4, 1, NULL, 'Share', 'Share YouTube video', 1, 1, NOW(), NOW()),
-- Telegram actions
(5, 2, NULL, 'Join Channel', 'Join Telegram channel', 1, 1, NOW(), NOW()),
(6, 2, NULL, 'Join Group', 'Join Telegram group', 1, 1, NOW(), NOW()),
-- Facebook actions
(7, 3, NULL, 'Like Page', 'Like Facebook page', 1, 1, NOW(), NOW()),
(8, 3, NULL, 'Like Post', 'Like Facebook post', 1, 1, NOW(), NOW()),
(9, 3, NULL, 'Share', 'Share Facebook post', 1, 1, NOW(), NOW()),
(10, 3, NULL, 'Comment', 'Comment on Facebook post', 1, 1, NOW(), NOW()),
-- Instagram actions
(11, 4, NULL, 'Follow', 'Follow Instagram account', 1, 1, NOW(), NOW()),
(12, 4, NULL, 'Like', 'Like Instagram post', 1, 1, NOW(), NOW()),
(13, 4, NULL, 'Comment', 'Comment on Instagram post', 1, 1, NOW(), NOW()),
-- Twitter actions
(14, 5, NULL, 'Follow', 'Follow Twitter account', 1, 1, NOW(), NOW()),
(15, 5, NULL, 'Like', 'Like Twitter post', 1, 1, NOW(), NOW()),
(16, 5, NULL, 'Retweet', 'Retweet', 1, 1, NOW(), NOW()),
(17, 5, NULL, 'Comment', 'Comment on Twitter post', 1, 1, NOW(), NOW());

-- Create unlock_tasks table
CREATE TABLE IF NOT EXISTS `unlock_tasks` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `link_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Foreign key to links table',
  `step_order` int(10) NOT NULL DEFAULT 1 COMMENT 'Step order: 1, 2, 3, ...',
  `platform_id` bigint(20) UNSIGNED NOT NULL,
  `platform_action_id` bigint(20) UNSIGNED NOT NULL,
  `platform_url` text NOT NULL COMMENT 'URL of the platform: YouTube video, Telegram group, etc.',
  `is_required` tinyint(1) DEFAULT 1 COMMENT 'Required or optional step',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `link_id` (`link_id`),
  KEY `platform_id` (`platform_id`),
  KEY `platform_action_id` (`platform_action_id`),
  FOREIGN KEY (`link_id`) REFERENCES `links` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`platform_id`) REFERENCES `platforms` (`id`),
  FOREIGN KEY (`platform_action_id`) REFERENCES `platform_actions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Create unlock_logs table
CREATE TABLE IF NOT EXISTS `unlock_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `link_id` bigint(20) UNSIGNED NOT NULL,
  `unlock_task_id` bigint(20) UNSIGNED NOT NULL,
  `visitor_ip` varchar(45) NOT NULL,
  `visitor_fingerprint` varchar(255) DEFAULT NULL COMMENT 'Browser fingerprint',
  `completed` tinyint(1) DEFAULT 0,
  `completed_at` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `link_id` (`link_id`),
  KEY `unlock_task_id` (`unlock_task_id`),
  KEY `visitor_ip` (`visitor_ip`),
  FOREIGN KEY (`link_id`) REFERENCES `links` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`unlock_task_id`) REFERENCES `unlock_tasks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Create api_keys table
CREATE TABLE IF NOT EXISTS `api_keys` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL COMMENT 'WordPress Site A, WordPress Site B',
  `api_key` varchar(64) NOT NULL,
  `api_secret` varchar(64) NOT NULL,
  `whitelisted_domains` text DEFAULT NULL COMMENT 'JSON array of domains',
  `is_active` tinyint(1) DEFAULT 1,
  `last_used` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_key` (`api_key`),
  KEY `user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Modify links table to add Sub2Unlock fields
ALTER TABLE `links` 
  ADD COLUMN IF NOT EXISTS `unlock_mode` tinyint(1) DEFAULT 0 COMMENT '0=direct, 1=sub2unlock',
  ADD COLUMN IF NOT EXISTS `captcha_enabled` tinyint(1) DEFAULT 1 COMMENT 'Enable/disable captcha',
  ADD COLUMN IF NOT EXISTS `redirect_after_unlock` varchar(255) DEFAULT 'tab' COMMENT 'tab, same, popup';

-- Success message
SELECT 'Sub2Unlock tables created successfully!' AS message;
