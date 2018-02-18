ALTER TABLE `Championnat_Equipes` CHANGE `idClub` `idClub` INT(11)  NULL  DEFAULT NULL;
ALTER TABLE `CoupeCH_Equipes` CHANGE `idClub` `idClub` INT(11)  NULL  DEFAULT NULL;

UPDATE Personne SET idClub = NULL WHERE idClub = 0;
UPDATE Championnat_Equipes SET idClub = NULL WHERE idClub = 0;

ALTER TABLE `Cotisations_Clubs` ADD `montantMembresActifs` INT  NOT NULL  DEFAULT '0'  AFTER `nbMembresActifs`;
ALTER TABLE `Cotisations_Clubs` ADD `montantMembresJuniors` INT  NOT NULL  DEFAULT '0'  AFTER `nbMembresJuniors`;
ALTER TABLE `Cotisations_Clubs` ADD `montantMembresSoutiens` INT  NOT NULL  DEFAULT '0'  AFTER `nbMembresSoutiens`;
ALTER TABLE `Cotisations_Clubs` ADD `montantMembresPassifs` INT  NOT NULL  DEFAULT '0'  AFTER `nbMembresPassifs`;
ALTER TABLE `Cotisations_Clubs` ADD `montantMembresVIP` INT  NOT NULL  DEFAULT '0'  AFTER `nbMembresVIP`;
ALTER TABLE `Cotisations_Clubs` ADD `nbMembresVIPOfferts` INT  NOT NULL  DEFAULT '0'  AFTER `nbMembresVIP`;
-- TODO: Manually update the content of nbMembresVIPOfferts
-- This query can help:
# SELECT id, floor(((nbMembresActifs + nbMembresJuniors) / 20) + 1) AS nbAboMaxOffers, nbMembresVIP, nbMembresVIPOfferts
# FROM Cotisations_Clubs
# WHERE annee >= 2015


UPDATE Cotisations_Clubs SET montantMembresSoutiens=10, montantMembresVIP=10;
UPDATE Cotisations_Clubs SET montantMembresActifs=30, montantMembresJuniors=20 WHERE annee>=2016;
UPDATE Cotisations_Clubs SET montantMembresActifs=25, montantMembresJuniors=15 WHERE annee<2016;


UPDATE Cotisations_Clubs SET idClub = NULL WHERE idClub = 15;
UPDATE Cotisations_Clubs SET idClub = 15 WHERE idClub = 17;
UPDATE Cotisations_Clubs SET idClub = 16 WHERE idClub = 18;
UPDATE Cotisations_Clubs SET idClub = 17 WHERE idClub = 21;
UPDATE Cotisations_Clubs SET idClub = 23 WHERE idClub = 27;
UPDATE Cotisations_Clubs SET idClub = 24 WHERE idClub = 28;
UPDATE Cotisations_Clubs SET idClub = 25 WHERE idClub = 29;
UPDATE Cotisations_Clubs SET idClub = 26 WHERE idClub = 30;
UPDATE Cotisations_Clubs SET idClub = 27 WHERE idClub = 31;
UPDATE Cotisations_Clubs SET idClub = 28 WHERE idClub = 32;
UPDATE Cotisations_Clubs SET idClub = 29 WHERE idClub = 33;
UPDATE Cotisations_Clubs SET idClub = 30 WHERE idClub = 34;
UPDATE Cotisations_Clubs SET idClub = 31 WHERE idClub = 35;
UPDATE Cotisations_Clubs SET idClub = 32 WHERE idClub = 36;
UPDATE Cotisations_Clubs SET idClub = 33 WHERE idClub = 37;
UPDATE Cotisations_Clubs SET idClub = 34 WHERE idClub = 38;
UPDATE Cotisations_Clubs SET idClub = 35 WHERE idClub = 39;

ALTER TABLE `DBDPersonne` CHANGE `idClub` `idClub` INT(11)  NULL  DEFAULT NULL;
UPDATE DBDPersonne SET idClub = NULL WHERE idClub = 15;
UPDATE DBDPersonne SET idClub = 15 WHERE idClub = 17;
UPDATE DBDPersonne SET idClub = 16 WHERE idClub = 18;
UPDATE DBDPersonne SET idClub = 17 WHERE idClub = 21;
UPDATE DBDPersonne SET idClub = 23 WHERE idClub = 27;
UPDATE DBDPersonne SET idClub = 24 WHERE idClub = 28;
UPDATE DBDPersonne SET idClub = 25 WHERE idClub = 29;
UPDATE DBDPersonne SET idClub = 26 WHERE idClub = 30;
UPDATE DBDPersonne SET idClub = 27 WHERE idClub = 31;
UPDATE DBDPersonne SET idClub = 28 WHERE idClub = 32;
UPDATE DBDPersonne SET idClub = 29 WHERE idClub = 33;
UPDATE DBDPersonne SET idClub = 30 WHERE idClub = 34;
UPDATE DBDPersonne SET idClub = 31 WHERE idClub = 35;
UPDATE DBDPersonne SET idClub = 32 WHERE idClub = 36;
UPDATE DBDPersonne SET idClub = 33 WHERE idClub = 37;
UPDATE DBDPersonne SET idClub = 34 WHERE idClub = 38;
UPDATE DBDPersonne SET idClub = 35 WHERE idClub = 39;

ALTER TABLE `DBDRequetesCHangementClub` CHANGE `from_clubID` `from_clubID` INT(11)  NULL  DEFAULT NULL;
UPDATE DBDRequetesCHangementClub SET from_clubID = NULL WHERE from_clubID = 15;
UPDATE DBDRequetesCHangementClub SET from_clubID = 15 WHERE from_clubID = 17;
UPDATE DBDRequetesCHangementClub SET from_clubID = 16 WHERE from_clubID = 18;
UPDATE DBDRequetesCHangementClub SET from_clubID = 17 WHERE from_clubID = 21;
UPDATE DBDRequetesCHangementClub SET from_clubID = 23 WHERE from_clubID = 27;
UPDATE DBDRequetesCHangementClub SET from_clubID = 24 WHERE from_clubID = 28;
UPDATE DBDRequetesCHangementClub SET from_clubID = 25 WHERE from_clubID = 29;
UPDATE DBDRequetesCHangementClub SET from_clubID = 26 WHERE from_clubID = 30;
UPDATE DBDRequetesCHangementClub SET from_clubID = 27 WHERE from_clubID = 31;
UPDATE DBDRequetesCHangementClub SET from_clubID = 28 WHERE from_clubID = 32;
UPDATE DBDRequetesCHangementClub SET from_clubID = 29 WHERE from_clubID = 33;
UPDATE DBDRequetesCHangementClub SET from_clubID = 30 WHERE from_clubID = 34;
UPDATE DBDRequetesCHangementClub SET from_clubID = 31 WHERE from_clubID = 35;
UPDATE DBDRequetesCHangementClub SET from_clubID = 32 WHERE from_clubID = 36;
UPDATE DBDRequetesCHangementClub SET from_clubID = 33 WHERE from_clubID = 37;
UPDATE DBDRequetesCHangementClub SET from_clubID = 34 WHERE from_clubID = 38;
UPDATE DBDRequetesCHangementClub SET from_clubID = 35 WHERE from_clubID = 39;

ALTER TABLE `DBDRequetesCHangementClub` CHANGE `to_clubID` `to_clubID` INT(11)  NULL  DEFAULT NULL;
UPDATE DBDRequetesCHangementClub SET to_clubID = NULL WHERE to_clubID = 15;
UPDATE DBDRequetesCHangementClub SET to_clubID = 15 WHERE to_clubID = 17;
UPDATE DBDRequetesCHangementClub SET to_clubID = 16 WHERE to_clubID = 18;
UPDATE DBDRequetesCHangementClub SET to_clubID = 17 WHERE to_clubID = 21;
UPDATE DBDRequetesCHangementClub SET to_clubID = 23 WHERE to_clubID = 27;
UPDATE DBDRequetesCHangementClub SET to_clubID = 24 WHERE to_clubID = 28;
UPDATE DBDRequetesCHangementClub SET to_clubID = 25 WHERE to_clubID = 29;
UPDATE DBDRequetesCHangementClub SET to_clubID = 26 WHERE to_clubID = 30;
UPDATE DBDRequetesCHangementClub SET to_clubID = 27 WHERE to_clubID = 31;
UPDATE DBDRequetesCHangementClub SET to_clubID = 28 WHERE to_clubID = 32;
UPDATE DBDRequetesCHangementClub SET to_clubID = 29 WHERE to_clubID = 33;
UPDATE DBDRequetesCHangementClub SET to_clubID = 30 WHERE to_clubID = 34;
UPDATE DBDRequetesCHangementClub SET to_clubID = 31 WHERE to_clubID = 35;
UPDATE DBDRequetesCHangementClub SET to_clubID = 32 WHERE to_clubID = 36;
UPDATE DBDRequetesCHangementClub SET to_clubID = 33 WHERE to_clubID = 37;
UPDATE DBDRequetesCHangementClub SET to_clubID = 34 WHERE to_clubID = 38;
UPDATE DBDRequetesCHangementClub SET to_clubID = 35 WHERE to_clubID = 39;

UPDATE DBDStatsClubs SET idClub = NULL WHERE idClub = 15;
UPDATE DBDStatsClubs SET idClub = 15 WHERE idClub = 17;
UPDATE DBDStatsClubs SET idClub = 16 WHERE idClub = 18;
UPDATE DBDStatsClubs SET idClub = 17 WHERE idClub = 21;
UPDATE DBDStatsClubs SET idClub = 23 WHERE idClub = 27;
UPDATE DBDStatsClubs SET idClub = 24 WHERE idClub = 28;
UPDATE DBDStatsClubs SET idClub = 25 WHERE idClub = 29;
UPDATE DBDStatsClubs SET idClub = 26 WHERE idClub = 30;
UPDATE DBDStatsClubs SET idClub = 27 WHERE idClub = 31;
UPDATE DBDStatsClubs SET idClub = 28 WHERE idClub = 32;
UPDATE DBDStatsClubs SET idClub = 29 WHERE idClub = 33;
UPDATE DBDStatsClubs SET idClub = 30 WHERE idClub = 34;
UPDATE DBDStatsClubs SET idClub = 31 WHERE idClub = 35;
UPDATE DBDStatsClubs SET idClub = 32 WHERE idClub = 36;
UPDATE DBDStatsClubs SET idClub = 33 WHERE idClub = 37;
UPDATE DBDStatsClubs SET idClub = 34 WHERE idClub = 38;
UPDATE DBDStatsClubs SET idClub = 35 WHERE idClub = 39;

DELETE FROM clubs WHERE id = 0;
UPDATE clubs SET nbIdClub = 15 WHERE id = 15;
UPDATE clubs SET nbIdClub = 16 WHERE id = 16;
UPDATE clubs SET nbIdClub = 17 WHERE id = 17;
UPDATE clubs SET nbIdClub = 23 WHERE id = 23;
UPDATE clubs SET nbIdClub = 24 WHERE id = 24;
UPDATE clubs SET nbIdClub = 25 WHERE id = 25;
UPDATE clubs SET nbIdClub = 26 WHERE id = 26;
UPDATE clubs SET nbIdClub = 27 WHERE id = 27;
UPDATE clubs SET nbIdClub = 28 WHERE id = 28;
UPDATE clubs SET nbIdClub = 29 WHERE id = 29;
UPDATE clubs SET nbIdClub = 30 WHERE id = 30;
UPDATE clubs SET nbIdClub = 31 WHERE id = 31;
UPDATE clubs SET nbIdClub = 32 WHERE id = 32;
UPDATE clubs SET nbIdClub = 33 WHERE id = 33;
UPDATE clubs SET nbIdClub = 34 WHERE id = 34;
UPDATE clubs SET nbIdClub = 35 WHERE id = 35;

ALTER TABLE `clubs` CHANGE `nbIdClub` `nbIdClub` INT(11)  NOT NULL; -- removes auto_increment from nbIdClub
ALTER TABLE `clubs` CHANGE `id` `id` INT(11)  NOT NULL  AUTO_INCREMENT;
ALTER TABLE `clubs` DROP PRIMARY KEY;
ALTER TABLE `clubs` DROP INDEX `id`;
ALTER TABLE `clubs` ADD PRIMARY KEY (`id`);


