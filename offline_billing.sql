-- Offline billing sync idempotency table.
-- Compatible with MySQL 5.7.32 (the database version in the supplied export).
-- Run this once on the online database before enabling offline sync.

CREATE TABLE IF NOT EXISTS `offline_sync_requests` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `device_uuid` CHAR(36) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `offline_uuid` CHAR(36) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `offline_bill_number` VARCHAR(40) NOT NULL,
  `service` VARCHAR(80) NOT NULL,
  `request_hash` CHAR(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0=pending, 1=processed',
  `response_json` MEDIUMTEXT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `offline_sync_client_uuid_unique` (`client_id`, `offline_uuid`),
  KEY `offline_sync_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `offline_devices` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `device_uuid` CHAR(36) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `device_name` VARCHAR(120) DEFAULT NULL,
  `token_hash` VARCHAR(255) NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1=active, 0=revoked',
  `last_sync_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `offline_device_user_unique` (`client_id`, `user_id`, `device_uuid`),
  KEY `offline_device_status_index` (`client_id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
