<?php 

// Devendra 02Nov2023

ALTER TABLE `sitting_entries` ADD `checkin_date` TIMESTAMP NULL DEFAULT NULL AFTER `check_in`;
ALTER TABLE `sitting_entries` ADD `show_amount` INT NULL DEFAULT '0' AFTER `paid_amount`;

CREATE TABLE `railway_master`.`checks` (`id` INT NOT NULL AUTO_INCREMENT , `entry_id` INT NOT NULL DEFAULT '0' , `slip_id` INT NOT NULL DEFAULT '0' , `time` TIMESTAMP NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `sitting_rate_list` ADD `adult_rate_sec` INT NOT NULL DEFAULT '0' AFTER `child_rate`;
ALTER TABLE `sitting_rate_list` ADD `child_rate_sec` INT NOT NULL DEFAULT '0' AFTER `adult_rate_sec`;

ALTER TABLE `massage_entries_backup` ADD `is_backup` TINYINT NOT NULL DEFAULT '0' AFTER `added_by`;

ALTER TABLE `locker_entries_backup` ADD `is_backup` TINYINT NOT NULL DEFAULT '0' AFTER `added_by`;

ALTER TABLE `massage_entries` ADD `is_backup` TINYINT NOT NULL DEFAULT '0' AFTER `added_by`;
ALTER TABLE `locker_entries` ADD `is_backup` TINYINT NOT NULL DEFAULT '0' AFTER `added_by`;
ALTER TABLE `locker_penalty` ADD `is_backup` TINYINT NOT NULL DEFAULT '0' AFTER `added_by`;
ALTER TABLE `locker_penalty_backup` ADD `is_backup` TINYINT NOT NULL DEFAULT '0' AFTER `added_by`;
ALTER TABLE `locker_penalty` ADD `is_backup` INT NOT NULL DEFAULT '0' AFTER `added_by`;
ALTER TABLE `sitting_entries_backup` ADD `is_backup` TINYINT NOT NULL DEFAULT '0' AFTER `added_by`;
ALTER TABLE `sitting_entries` ADD `is_backup` TINYINT NOT NULL DEFAULT '0' AFTER `added_by`;
ALTER TABLE `sitting_rate_list` ADD `adult_rate_sec` INT NOT NULL DEFAULT '0' AFTER `child_rate`, ADD `child_rate_sec` INT NOT NULL DEFAULT '0' AFTER `adult_rate_sec`;
ALTER TABLE `client_services` CHANGE `services_id` `services_id` INT(11) NOT NULL DEFAULT '0' COMMENT '1=sittinng,2=cloakroom, 3=canteen,4= Massage, 5=Locker';
CREATE TABLE `railway_master`.`check_status` (`id` INT NOT NULL AUTO_INCREMENT , `check_date_time` TIMESTAMP NULL DEFAULT NULL , `checked_by` INT NOT NULL DEFAULT '0' , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `massage_entries` ADD `slip_id` INT NOT NULL DEFAULT '0' AFTER `client_id`;




CREATE TABLE `locker_rate_list` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `first_rate` int(11) NOT NULL COMMENT 'perDay',
  `second_rate` int(11) NOT NULL COMMENT 'perDay'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `locker_rate_list`
--
ALTER TABLE `locker_rate_list`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `locker_rate_list`
--
ALTER TABLE `locker_rate_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
?>