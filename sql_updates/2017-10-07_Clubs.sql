RENAME TABLE `ClubsFstb` TO `clubs`;

ALTER TABLE `DBDPersonne` CHANGE `emailFSTB` `emailFederation` VARCHAR(255)  CHARACTER SET latin1  COLLATE latin1_german1_ci  NULL  DEFAULT NULL;

ALTER TABLE `clubs` ADD `committeeComposition` TEXT  NULL  AFTER `url`;

ALTER TABLE `clubs` ADD `coachJSID` INT  NULL  DEFAULT NULL  AFTER `committeeComposition`;

ALTER TABLE `clubs` ADD `emailsOfficialComm` VARCHAR(512)  NULL  DEFAULT NULL  AFTER `email`;

ALTER TABLE `clubs` ADD `emailsTournamentComm` VARCHAR(512)  NULL  DEFAULT NULL  AFTER `emailsOfficialComm`;

CREATE TABLE `coach_js` (
  `personId` int(11) NOT NULL,
  UNIQUE KEY `personId` (`personId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;