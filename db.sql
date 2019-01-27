SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE TABLE IF NOT EXISTS `sites` (
  `idsites` INT(11) NOT NULL AUTO_INCREMENT,
  `name` TEXT NULL DEFAULT NULL,
  `unifiid` TEXT NULL DEFAULT NULL,
  `usg` TINYINT(1) NULL DEFAULT NULL,
  `wan1` TEXT NULL DEFAULT NULL,
  `wan1interface` TEXT NULL DEFAULT NULL,
  `wan2` TEXT NULL DEFAULT NULL,
  `wan2interface` TEXT NULL DEFAULT NULL,
  `bgp` TINYINT(1) NULL DEFAULT NULL,
  `bgprouterid` TEXT NULL DEFAULT NULL,
  `bgpas` INT(11) NULL DEFAULT NULL,
  `igmpupstream` TINYINT(1) NULL DEFAULT NULL,
  `igmpupstreamaltsubnet` TEXT NULL DEFAULT NULL,
  `dnsredirect` TINYINT(1) NULL DEFAULT NULL,
  `dnsredirectip` TEXT NULL DEFAULT NULL,
  `dnsridirectinterface` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`idsites`),
  UNIQUE INDEX `idsites_UNIQUE` (`idsites` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `vpnsitesother` (
  `idvpnsitesother` INT(11) NOT NULL AUTO_INCREMENT,
  `bgprouterid` TEXT NULL DEFAULT NULL,
  `bgpas` INT(11) NULL DEFAULT NULL,
  `secret` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`idvpnsitesother`),
  UNIQUE INDEX `idvpnsitesother_UNIQUE` (`idvpnsitesother` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `vpnconnectionsunifi` (
  `idvpnconnections` INT(11) NOT NULL AUTO_INCREMENT,
  `site1` INT(11) NOT NULL,
  `site2` INT(11) NOT NULL,
  `secret` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`idvpnconnections`),
  UNIQUE INDEX `idvpnconnections_UNIQUE` (`idvpnconnections` ASC),
  INDEX `fk_vpnconnectionsunifi_sites_idx` (`site1` ASC),
  INDEX `fk_vpnconnectionsunifi_sites1_idx` (`site2` ASC),
  CONSTRAINT `fk_vpnconnectionsunifi_sites`
    FOREIGN KEY (`site1`)
    REFERENCES `sites` (`idsites`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_vpnconnectionsunifi_sites1`
    FOREIGN KEY (`site2`)
    REFERENCES `sites` (`idsites`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `vpnconnectionsother` (
  `idvpnconnectionsother` INT(11) NOT NULL AUTO_INCREMENT,
  `site1` INT(11) NOT NULL,
  `site2` INT(11) NOT NULL,
  PRIMARY KEY (`idvpnconnectionsother`),
  UNIQUE INDEX `idvpnconnectionsother_UNIQUE` (`idvpnconnectionsother` ASC),
  INDEX `fk_vpnconnectionsother_sites1_idx` (`site1` ASC),
  INDEX `fk_vpnconnectionsother_vpnsitesother1_idx` (`site2` ASC),
  CONSTRAINT `fk_vpnconnectionsother_sites1`
    FOREIGN KEY (`site1`)
    REFERENCES `sites` (`idsites`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_vpnconnectionsother_vpnsitesother1`
    FOREIGN KEY (`site2`)
    REFERENCES `vpnsitesother` (`idvpnsitesother`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `sitesubnets` (
  `idsitesubnets` INT(11) NOT NULL AUTO_INCREMENT,
  `subnet` TEXT NULL DEFAULT NULL,
  `inbgp` TINYINT(1) NULL DEFAULT NULL,
  `site` INT(11) NOT NULL,
  `interface` TEXT NULL DEFAULT NULL,
  `igmpupstream` TINYINT(1) NULL DEFAULT NULL,
  `igmpdownstream` TINYINT(1) NULL DEFAULT NULL,
  `dnsredirect` TINYINT(1) NULL DEFAULT NULL,
  `name` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`idsitesubnets`),
  UNIQUE INDEX `idsitesubnets_UNIQUE` (`idsitesubnets` ASC),
  INDEX `fk_sitesubnets_sites1_idx` (`site` ASC),
  CONSTRAINT `fk_sitesubnets_sites1`
    FOREIGN KEY (`site`)
    REFERENCES `sites` (`idsites`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
