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


ALTER TABLE `daily_entries` CHANGE `check_in` `check_in` TIME NULL DEFAULT NULL;

CREATE TABLE `change_pay_type_log` ( `id` INT NOT NULL AUTO_INCREMENT , `sitting_id` INT NULL DEFAULT NULL , `old_pay_type` TINYINT(1) NOT NULL DEFAULT '0' , `new_pay_type` TINYINT(1) NOT NULL DEFAULT '0' , `changed_by` INT NULL DEFAULT NULL , `created_at` TIMESTAMP NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `change_pay_type_log` ADD `date` DATE NULL DEFAULT NULL AFTER `new_pay_type`;

ALTER TABLE `change_pay_type_log` ADD `e_entry_id` INT NULL DEFAULT NULL AFTER `sitting_id`;

ALTER TABLE `sitting_entries` ADD `alert_count` TINYINT(1) NOT NULL DEFAULT '0' AFTER `m_slip`;

ALTER TABLE `users` ADD `is_auto_alert_access` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0=>false, 1=>true' AFTER `last_login`;

ALTER TABLE `users` ADD `org_id` INT NULL DEFAULT NULL AFTER `last_login`;
ALTER TABLE `clients` ADD `org_id` INT NULL DEFAULT NULL AFTER `address`;

ALTER TABLE `clients` ADD `client_name` VARCHAR(255) NULL DEFAULT NULL AFTER `name`;
ALTER TABLE `daily_entry_items` ADD `client_id` INT NULL DEFAULT NULL AFTER `entry_id`;
ALTER TABLE `lockers` ADD `client_id` INT NOT NULL DEFAULT '0' AFTER `locker_no`;
UPDATE lockers SET client_id = 1;

ALTER TABLE `canteen_items` ADD `is_manual` TINYINT NOT NULL DEFAULT '0' AFTER `added_by`;
ALTER TABLE `canteen_items` ADD `barvalue_avail` TINYINT NOT NULL DEFAULT '1' AFTER `is_manual`;
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'CREAM BUN', 30,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'BROWNI', 60,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'CREAM ROLL', 20,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'VEG PATIES', 30,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'PANEER PATIES', 40,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'CUP CAKE', 30,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'PESTRY', 50,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'HOTDOG', 60,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'PIZZA BASKET', 70,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'SWEET PUFF', 20,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'PANEER PAW ROLL', 70,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'ONION RINGS', 30,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'VEG WRAP', 50,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'DONUT', 50,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'BAKED SAMOSA', 25,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'PANEER WRAP', 70, 1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'VEG SANDWICH', 60,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'CHEESE SANDWICH', 70,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'VEG TIKKA SANDWICH', 60, 1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'PANEER TIKKA SANDWICH',0, 80,1);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'SLICE CAKE', 120,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'PIZZA', 100,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'VEG BURGER', 50,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'CHEESE BURGER', 60,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'CHEESE COOKIE', 130,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'SEB COOKIE', 110,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'CHOCO CHIPS COOKIES',0, 130,1);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'JEERA COOKIE', 120,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'VEG MOMO', 60, 1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'CHICKEN MOM', 70, 1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'NORTH INDIAN VEG THALI',0, 140,1);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'NORTH INDIA NON-VEG THALI',0, 180,1);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'EGG CURRY WITH RICE', 100,0, 1);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'CHICKEN CURRY WITH RICE',0, 150, 1);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'RED TEA', 30, 1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'SPECIAL TEA', 20,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'SPECIAL TEA -SUGAR FREE',0, 30,1);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'BLACK TEA', 20,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'GREEN TEA', 30,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'COFFEE', 20,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'COFFEE CAPACHINO', 30,1,0);

CREATE TABLE `total_incomes` ( `id` INT NOT NULL , `date` DATE NULL DEFAULT NULL , `total_amount` INT NOT NULL , `advance_amount` INT NOT NULL , `back_balance` INT NOT NULL , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `created_at` TIMESTAMP NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `total_incomes` ADD `added_by` INT NOT NULL DEFAULT '0' AFTER `back_balance`;
ALTER TABLE `total_incomes` ADD `all_total` INT NOT NULL DEFAULT '0' AFTER `back_balance`;
ALTER TABLE `total_incomes` CHANGE `total_amount` `total_amount` INT(11) NOT NULL DEFAULT '0', CHANGE `back_balance` `back_balance` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `incomes` ADD `income_id` INT NOT NULL DEFAULT '0' AFTER `id`;

ALTER TABLE `client_services` CHANGE `services_id` `services_id` INT(11) NOT NULL DEFAULT '0' COMMENT '1=sittinng,2=cloakroom, 3=canteen,4= Massage, 5=Locker,6=Ledger account';


CREATE TABLE `incomes` ( `id` INT NOT NULL AUTO_INCREMENT , `client_id` INT NOT NULL DEFAULT '0' , `date` DATE NULL DEFAULT NULL , `cash_amount` DOUBLE NOT NULL DEFAULT '0' , `upi_amount` DOUBLE NOT NULL DEFAULT '0' , `total_amount` DOUBLE NOT NULL DEFAULT '0' , `back_balance` DOUBLE NOT NULL DEFAULT '0' , `day_total` DOUBLE NOT NULL DEFAULT '0' , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `created_at` TIMESTAMP NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `income_entries` ( `id` INT NOT NULL AUTO_INCREMENT , `income_id` INT NOT NULL DEFAULT '0' , `client_id` INT NOT NULL DEFAULT '0' , `service_id` INT NOT NULL DEFAULT '0' , `cash_amount` DOUBLE NOT NULL DEFAULT '0' , `upi_amount` DOUBLE NOT NULL DEFAULT '0' , `total_amount` DOUBLE NOT NULL DEFAULT '0' , `date` DATE NULL DEFAULT NULL , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `created_at` TIMESTAMP NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `income_entries` ADD `remarks` VARCHAR(255) NULL DEFAULT NULL AFTER `date`;

ALTER TABLE `income_entries` ADD `source` VARCHAR(255) NULL DEFAULT NULL AFTER `service_id`;

//DIpanshu 29th Sep 2024

CREATE TABLE `nnhp`.`godowns` ( `id` INT NOT NULL , `org_id` INT NOT NULL DEFAULT '0' , `date` DATE NULL DEFAULT NULL , `client_item_id` INT NOT NULL DEFAULT '0' , `stock` INT NOT NULL , `status` TINYINT NOT NULL DEFAULT '0' , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `created_at` TIMESTAMP NULL DEFAULT NULL ) ENGINE = InnoDB;

CREATE TABLE `nnhp`.`transfer_godown_stocks` ( `id` INT NOT NULL , `org_id` INT NOT NULL DEFAULT '0' , `client_id` INT NOT NULL DEFAULT '0' , `canteen_item_id` INT NOT NULL DEFAULT '0' , `date` DATE NULL DEFAULT NULL , `stock` INT NOT NULL DEFAULT '0' , `updated_at` TIMESTAMP NOT 
  
ALTER TABLE `users` ADD `godown_id` INT NOT NULL DEFAULT '0' AFTER `org_id`;
ALTER TABLE `canteen_items` ADD `godown_id` INT NOT NULL DEFAULT '0' AFTER `client_id`;

ALTER TABLE `cloakroom_rate_list` ADD `type` FLOAT NOT NULL DEFAULT '1' AFTER `second_rate`;
ALTER TABLE `clients` ADD `rate_type` INT NOT NULL DEFAULT '1' AFTER `org_id`;

?>