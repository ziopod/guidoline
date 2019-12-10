-- MySQL Workbench Synchronization
-- Generated: 2019-12-10 01:01
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Ziopod

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `guidoline`.`members` 
DROP FOREIGN KEY `fk_members_users`;

ALTER TABLE `guidoline`.`forms` 
DROP FOREIGN KEY `fk_forms_currencies`;

ALTER TABLE `guidoline`.`dues` 
DROP FOREIGN KEY `fk_dues_forms`;

ALTER TABLE `guidoline`.`members` 
DROP COLUMN `is_volunteer`,
ADD COLUMN `is_volunteer` INT(1) NOT NULL DEFAULT 0 AFTER `is_active`;

ALTER TABLE `guidoline`.`members` 
ADD CONSTRAINT `fk_members_users`
  FOREIGN KEY (`user_id`)
  REFERENCES `guidoline`.`users` (`id`)
  ON DELETE SET NULL
  ON UPDATE RESTRICT;

ALTER TABLE `guidoline`.`forms` 
ADD CONSTRAINT `fk_forms_currencies`
  FOREIGN KEY (`currency_code`)
  REFERENCES `guidoline`.`currencies` (`code`)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;

ALTER TABLE `guidoline`.`dues` 
DROP FOREIGN KEY `fk_dues_member`;

ALTER TABLE `guidoline`.`dues` ADD CONSTRAINT `fk_dues_member`
  FOREIGN KEY (`member_id`)
  REFERENCES `guidoline`.`members` (`id`)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT,
ADD CONSTRAINT `fk_dues_forms`
  FOREIGN KEY (`form_id`)
  REFERENCES `guidoline`.`forms` (`id`)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
