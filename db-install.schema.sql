-- MySQL Workbench Synchronization
-- Generated: 2019-12-10 18:09
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Ziopod

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE TABLE IF NOT EXISTS `guidoline-export`.`members` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idm` INT(11) NOT NULL,
  `user_id` INT(11) UNSIGNED NULL DEFAULT NULL,
  `gender` ENUM('m','f') NULL DEFAULT 'm',
  `firstname` VARCHAR(45) NOT NULL,
  `lastname` VARCHAR(45) NOT NULL,
  `email` VARCHAR(128) NULL DEFAULT NULL,
  `phone` VARCHAR(19) NULL DEFAULT NULL,
  `street` VARCHAR(45) NULL DEFAULT NULL,
  `zipcode` VARCHAR(5) NULL DEFAULT NULL,
  `city` VARCHAR(45) NULL DEFAULT NULL,
  `country` VARCHAR(2) NULL DEFAULT 'FR',
  `birthdate` DATE NULL DEFAULT NULL,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` INT(1) NOT NULL DEFAULT 1,
  `is_volunteer` INT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idm_UNIQUE` (`idm` ASC),
  INDEX `fk_members_users_idx` (`user_id` ASC),
  CONSTRAINT `fk_members_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `guidoline-export`.`users` (`id`)
    ON DELETE SET NULL
    ON UPDATE RESTRICT)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `guidoline-export`.`roles` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(32) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uniq_name` (`name` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `guidoline-export`.`roles_users` (
  `user_id` INT(10) UNSIGNED NOT NULL,
  `role_id` INT(10) UNSIGNED UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`, `role_id`),
  INDEX `fk_role_id` (`role_id` ASC),
  CONSTRAINT `fk_roles_uses_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `guidoline-export`.`users` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_roles_users_role`
    FOREIGN KEY (`role_id`)
    REFERENCES `guidoline-export`.`roles` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `guidoline-export`.`forms` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(35) NOT NULL,
  `heading` VARCHAR(70) NOT NULL,
  `slug` VARCHAR(70) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `price` FLOAT(5,2) NOT NULL DEFAULT 0.00,
  `currency_code` VARCHAR(3) NOT NULL DEFAULT 'EUR',
  `free_price` INT(1) NOT NULL DEFAULT 1,
  `date_start` DATE NULL DEFAULT NULL,
  `duration` VARCHAR(10) NOT NULL DEFAULT '1 year',
  `start_at_due` INT(1) NOT NULL DEFAULT 1,
  `is_active` INT(1) NOT NULL DEFAULT 1,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`, `currency_code`),
  INDEX `fk_forms_currency_id` (`currency_code` ASC),
  CONSTRAINT `fk_forms_currencies`
    FOREIGN KEY (`currency_code`)
    REFERENCES `guidoline-export`.`currencies` (`code`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `guidoline-export`.`dues` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` INT(11) UNSIGNED NOT NULL,
  `form_id` INT(11) UNSIGNED NOT NULL,
  `title` VARCHAR(35) NOT NULL,
  `to_name` VARCHAR(80) NOT NULL,
  `to_address` TINYTEXT NOT NULL,
  `to_contact` VARCHAR(128) NOT NULL,
  `heading` VARCHAR(70) NOT NULL,
  `date_start` DATE NULL DEFAULT NULL,
  `date_end` DATE NULL DEFAULT NULL,
  `amount` FLOAT(5,2) NOT NULL DEFAULT 0.00,
  `currency` VARCHAR(1) NOT NULL DEFAULT '€',
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`, `member_id`, `form_id`),
  INDEX `fk_dues_form_id` (`form_id` ASC),
  INDEX `fk_dues_member_idx` (`member_id` ASC),
  CONSTRAINT `fk_dues_member`
    FOREIGN KEY (`member_id`)
    REFERENCES `guidoline-export`.`members` (`id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `fk_dues_forms`
    FOREIGN KEY (`form_id`)
    REFERENCES `guidoline-export`.`forms` (`id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `guidoline-export`.`user_tokens` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `user_agent` VARCHAR(40) NOT NULL,
  `token` VARCHAR(40) NOT NULL,
  `created` INT(10) UNSIGNED NOT NULL,
  `expires` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uniq_token` (`token` ASC),
  INDEX `fk_user_id` (`user_id` ASC),
  INDEX `expires` (`expires` ASC),
  CONSTRAINT `fk_user_token`
    FOREIGN KEY (`user_id`)
    REFERENCES `guidoline-export`.`users` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `guidoline-export`.`users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(254) NOT NULL,
  `username` VARCHAR(32) NOT NULL,
  `password` VARCHAR(64) NOT NULL,
  `logins` INT(10) UNSIGNED NOT NULL DEFAULT 0,
  `last_login` INT(10) UNSIGNED NULL DEFAULT NULL,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uniq_username` (`username` ASC),
  UNIQUE INDEX `uniq_email` (`email` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `guidoline-export`.`currencies` (
  `code` VARCHAR(3) NOT NULL,
  `name` VARCHAR(16) NOT NULL,
  `entity` VARCHAR(1) NOT NULL,
  PRIMARY KEY (`code`),
  UNIQUE INDEX `id_UNIQUE` (`code` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `guidoline-export`.`skills` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `guidoline-export`.`members_skills` (
  `member_id` INT(11) UNSIGNED NOT NULL,
  `skill_id` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`member_id`, `skill_id`),
  INDEX `fk_members_skills_skill_id` (`skill_id` ASC),
  INDEX `fk_members_skills_member_id` (`member_id` ASC),
  CONSTRAINT `fk_members_skills_members`
    FOREIGN KEY (`member_id`)
    REFERENCES `guidoline-export`.`members` (`id`)
    ON DELETE CASCADE
    ON UPDATE RESTRICT,
  CONSTRAINT `fk_members_skils_skills`
    FOREIGN KEY (`skill_id`)
    REFERENCES `guidoline-export`.`skills` (`id`)
    ON DELETE CASCADE
    ON UPDATE RESTRICT)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
