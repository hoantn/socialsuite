-- SQL script for socialsuite database
-- Ensure that a database named 'socialsuite' exists and is in use.
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `transactions`;
DROP TABLE IF EXISTS `posts`;
DROP TABLE IF EXISTS `pages`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `plans`;

CREATE TABLE `users` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NULL,
    `phone` VARCHAR(255) NULL,
    `is_admin` TINYINT(1) NOT NULL DEFAULT 0,
    `plan_id` BIGINT UNSIGNED NOT NULL DEFAULT 1,
    `plan_expires_at` TIMESTAMP NULL,
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `pages` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `page_id` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NULL,
    `page_token` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `posts` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `page_id` BIGINT UNSIGNED NOT NULL,
    `message` TEXT NULL,
    `scheduled_at` TIMESTAMP NULL,
    `status` VARCHAR(255) NULL,
    `fb_post_id` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `plans` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `price` INT UNSIGNED NOT NULL,
    `features` JSON NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `transactions` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `amount` INT NOT NULL,
    `type` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `cache` (
    `key` VARCHAR(255) PRIMARY KEY,
    `value` MEDIUMTEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `jobs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `queue` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL,
    `reserved_at` INT UNSIGNED NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at` INT UNSIGNED NOT NULL,
    INDEX `idx_queue` (`queue`),
    INDEX `idx_reserved` (`reserved_at`),
    INDEX `idx_queue_reserved` (`queue`, `reserved_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `sessions` (
    `id` VARCHAR(255) PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    INDEX `idx_last_activity` (`last_activity`),
    INDEX `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `plans` (`id`, `name`, `price`, `features`, `created_at`, `updated_at`) VALUES
    (1, 'Free', 0, '{"max_pages":1,"max_scheduled_posts":5,"inbox":false}', NOW(), NOW()),
    (2, 'Premium', 200000, '{"max_pages":10,"max_scheduled_posts":-1,"inbox":true}', NOW(), NOW());

INSERT INTO `users` (`id`, `username`, `password`, `email`, `phone`, `is_admin`, `plan_id`, `plan_expires_at`, `remember_token`, `created_at`, `updated_at`) VALUES
    (1, 'admin', '$2b$12$c7oQB76TjZHB8tR8GZgm7eGDKOzVrBL5i0sQUZwEZGKzUvwVv0iBO', NULL, NULL, 1, 2, NULL, NULL, NOW(), NOW());
