SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP TABLE IF EXISTS `demo_category`;
DROP TABLE IF EXISTS `demo_product`;
DROP TABLE IF EXISTS `demo_feature`;
DROP TABLE IF EXISTS `demo_language`;
DROP TABLE IF EXISTS `demo_description`;
DROP TABLE IF EXISTS `demo_feature_has_product`;


-- -----------------------------------------------------
-- Table `demo_category`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `demo_category` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `demo_product`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `demo_product` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL ,
  `category_id` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_product_category1` (`category_id` ASC) ,
  CONSTRAINT `fk_product_category1`
    FOREIGN KEY (`category_id` )
    REFERENCES `demo_category` (`id` )
    ON DELETE SET NULL
    ON UPDATE SET NULL)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `demo_feature`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `demo_feature` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `demo_language`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `demo_language` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `code` VARCHAR(45) NULL ,
  `name` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `demo_description`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `demo_description` (
  `title` VARCHAR(45) NULL ,
  `text` TEXT NULL ,
  `product_id` INT NOT NULL ,
  `language_id` INT NOT NULL ,
  PRIMARY KEY (`product_id`) ,
  INDEX `fk_description_product1` (`product_id` ASC) ,
  INDEX `fk_description_language1` (`language_id` ASC) ,
  CONSTRAINT `fk_description_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `demo_product` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_description_language1`
    FOREIGN KEY (`language_id` )
    REFERENCES `demo_language` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `demo_feature_has_product`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `demo_feature_has_product` (
  `feature_id` INT NOT NULL ,
  `product_id` INT NOT NULL ,
  PRIMARY KEY (`feature_id`, `product_id`) ,
  INDEX `fk_feature_has_product_feature1` (`feature_id` ASC) ,
  INDEX `fk_feature_has_product_product1` (`product_id` ASC) ,
  CONSTRAINT `fk_feature_has_product_feature1`
    FOREIGN KEY (`feature_id` )
    REFERENCES `demo_feature` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_feature_has_product_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `demo_product` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
