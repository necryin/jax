CREATE TABLE `comments` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `text` VARCHAR(10240) NOT NULL,
  `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `left_key` BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
  `right_key` BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
  `parent_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
  `root_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
  `deleted` TINYINT(1) UNSIGNED DEFAULT 0,
  `level` BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
  INDEX tree (left_key, right_key, level),
  INDEX root (root_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
